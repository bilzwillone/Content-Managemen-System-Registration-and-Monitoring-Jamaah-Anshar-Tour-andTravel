<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    /**
     * Menerima dan memproses notifikasi dari Midtrans.
     */
    public function handle(Request $request)
    {
        $payload = $request->validate([
            'order_id' => 'required|string',
            'status_code' => 'required|string',
            'gross_amount' => 'required|string',
            'signature_key' => 'required|string',
            'transaction_status' => 'required|string',
            'transaction_id' => 'nullable|string',
            'payment_type' => 'nullable|string',
            'fraud_status' => 'nullable|string',
        ]);

        // Gunakan Signature Secret Key untuk verifikasi notifikasi.
        $signatureKey = config('midtrans.signature_key');

        if (empty($signatureKey)) {
            Log::error('Midtrans Signature Secret Key belum dikonfigurasi.');

            return response()->json([
                'message' => 'Signature Secret Key belum dikonfigurasi.',
            ], 500);
        }

        // Hitung signature berdasarkan data notifikasi.
        $signatureInput =
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $signatureKey;

        $expectedSignature = hash('sha512', $signatureInput);

        // Verifikasi signature.
        if (!hash_equals(
            $expectedSignature,
            strtolower(trim($payload['signature_key']))
        )) {
            Log::warning('Notifikasi Midtrans dengan signature tidak valid.', [
                'order_id' => $payload['order_id'],
            ]);

            return response()->json([
                'message' => 'Signature tidak valid.',
            ], 403);
        }

        // Cari transaksi berdasarkan order ID.
        $payment = Payment::where('order_id', $payload['order_id'])
            ->where('gateway_provider', 'midtrans')
            ->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        // Pastikan nominal pembayaran sesuai.
        $expectedAmount = number_format(
            (float) $payment->amount,
            2,
            '.',
            ''
        );

        $receivedAmount = number_format(
            (float) $payload['gross_amount'],
            2,
            '.',
            ''
        );

        if (
            $expectedAmount !== $receivedAmount ||
            ($request->filled('currency') &&
                $request->input('currency') !== 'IDR')
        ) {
            Log::warning('Nominal atau mata uang notifikasi tidak sesuai.', [
                'order_id' => $payload['order_id'],
            ]);

            return response()->json([
                'message' => 'Nominal atau mata uang tidak sesuai.',
            ], 422);
        }

        // Perbarui status pembayaran secara aman.
        DB::transaction(function () use ($payment, $payload) {
            $lockedPayment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            $transactionStatus = strtolower(
                $payload['transaction_status']
            );

            $fraudStatus = strtolower(
                $payload['fraud_status'] ?? ''
            );

            $newStatus = match ($transactionStatus) {
                'settlement' => (
                    $payload['status_code'] === '200'
                ) ? 'paid' : $lockedPayment->status,

                'capture' => (
                    $payload['status_code'] === '200' &&
                    ($fraudStatus === '' || $fraudStatus === 'accept')
                ) ? 'paid' : (
                    $fraudStatus === 'deny'
                        ? 'failed'
                        : $lockedPayment->status
                ),

                'pending' => 'pending',
                'expire' => 'expired',
                'cancel' => 'cancelled',
                'deny' => 'failed',

                default => $lockedPayment->status,
            };

            // Status paid tidak boleh turun menjadi status lain.
            if (
                $lockedPayment->status === 'paid' &&
                $newStatus !== 'paid'
            ) {
                $newStatus = 'paid';
            }

            $updateData = [
                'status' => $newStatus,
                'gateway_response' => $payload,
            ];

            if (!empty($payload['transaction_id'])) {
                $updateData['gateway_transaction_id'] =
                    $payload['transaction_id'];
            }

            if (!empty($payload['payment_type'])) {
                $updateData['payment_method'] =
                    $payload['payment_type'];
            }

            if (
                $newStatus === 'paid' &&
                !$lockedPayment->paid_at
            ) {
                $updateData['paid_at'] = now();
            }

            $lockedPayment->update($updateData);
        });

        return response()->json([
            'message' => 'Notifikasi berhasil diproses.',
        ], 200);
    }
}
