<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->validate([
            'order_id' => ['required', 'string', 'max:100'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required', 'numeric'],
            'signature_key' => ['required', 'string'],
            'transaction_status' => ['required', 'string'],
            'transaction_id' => ['nullable', 'string'],
            'payment_type' => ['nullable', 'string'],
            'fraud_status' => ['nullable', 'string'],
            'currency' => ['nullable', 'string', 'size:3'],
        ]);

        $signatureKey = config('midtrans.signature_key');

        if (empty($signatureKey)) {
            Log::error('MIDTRANS_SIGNATURE_KEY belum dikonfigurasi.');

            return response()->json([
                'message' => 'Konfigurasi Midtrans belum lengkap.'
            ], 500);
        }

        $grossAmountRaw = (string) $request->input('gross_amount');

        $signatureInput =
            $payload['order_id'] .
            $payload['status_code'] .
            $grossAmountRaw .
            $signatureKey;

        $expectedSignature = hash('sha512', $signatureInput);

        $receivedSignature = strtolower(
            trim($payload['signature_key'])
        );

        $expectedSignature = strtolower($expectedSignature);

        $signatureValid = hash_equals(
            $expectedSignature,
            $receivedSignature
        );

        Log::info('MIDTRANS SIGNATURE DIAGNOSTIC', [
            'order_id' => $payload['order_id'],
            'status_code' => $payload['status_code'],
            'gross_amount' => $grossAmountRaw,
            'gross_amount_type' => gettype(
                $request->input('gross_amount')
            ),
            'gross_amount_length' => strlen($grossAmountRaw),
            'signature_key_length' => strlen($signatureKey),
            'received_signature_length' => strlen(
                $receivedSignature
            ),
            'signature_valid' => $signatureValid,
        ]);

        if (!$signatureValid) {
            Log::warning('Signature notifikasi Midtrans tidak valid.', [
                'order_id' => $payload['order_id'],
            ]);

            return response()->json([
                'message' => 'Signature tidak valid.'
            ], 403);
        }

        $payment = Payment::where('order_id', $payload['order_id'])
            ->where('gateway_provider', 'midtrans')
            ->first();

        if (!$payment) {
            Log::warning('Transaksi Midtrans tidak ditemukan.', [
                'order_id' => $payload['order_id'],
            ]);

            return response()->json([
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        }

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

        if ($expectedAmount !== $receivedAmount) {
            Log::warning('Nominal pembayaran Midtrans tidak sesuai.', [
                'order_id' => $payload['order_id'],
                'expected' => $expectedAmount,
                'received' => $receivedAmount,
            ]);

            return response()->json([
                'message' => 'Nominal pembayaran tidak sesuai.'
            ], 422);
        }

        if (
            isset($payload['currency']) &&
            strtoupper($payload['currency']) !== 'IDR'
        ) {
            return response()->json([
                'message' => 'Mata uang tidak sesuai.'
            ], 422);
        }

        DB::transaction(function () use ($payment, $payload) {

            $locked = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            $transactionStatus = strtolower(
                $payload['transaction_status']
            );

            $fraudStatus = strtolower(
                $payload['fraud_status'] ?? ''
            );

            $statusCode = (string) $payload['status_code'];

            $currentStatus = $locked->status;

            $fraudAccepted = in_array(
                $fraudStatus,
                ['', 'accept'],
                true
            );

            $newStatus = match ($transactionStatus) {

                'settlement', 'capture' =>
                    $statusCode === '200' && $fraudAccepted
                        ? 'paid'
                        : $currentStatus,

                'pending' => 'pending',

                'expire' => 'expired',

                'cancel' => 'cancelled',

                'deny', 'failure' => 'failed',

                'refund' => 'refunded',

                'partial_refund' => 'partially_refunded',

                default => $currentStatus,
            };

            if (
                in_array($currentStatus, [
                    'paid',
                    'refunded',
                    'partially_refunded',
                ], true)
                &&
                in_array($newStatus, [
                    'pending',
                    'failed',
                    'expired',
                    'cancelled',
                ], true)
            ) {
                $newStatus = $currentStatus;
            }

            $update = [
                'status' => $newStatus,
                'gateway_response' => $payload,
            ];

            if (!empty($payload['transaction_id'])) {
                $update['gateway_transaction_id'] =
                    $payload['transaction_id'];
            }

            if (!empty($payload['payment_type'])) {
                $update['payment_method'] =
                    $payload['payment_type'];
            }

            if (
                $newStatus === 'paid' &&
                !$locked->paid_at
            ) {
                $update['paid_at'] = now();
            }

            $locked->update($update);

            Log::info('Notifikasi Midtrans berhasil diproses.', [
                'payment_id' => $locked->id,
                'order_id' => $locked->order_id,
                'old_status' => $currentStatus,
                'new_status' => $newStatus,
                'transaction_status' => $transactionStatus,
                'amount' => $locked->amount,
            ]);
        });

        return response()->json([
            'message' => 'Notifikasi berhasil diproses.'
        ], 200);
    }
}
