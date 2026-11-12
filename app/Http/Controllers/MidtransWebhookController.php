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

        $serverKey = config('midtrans.server_key');
        if (!$serverKey) {
            Log::error('MIDTRANS_SERVER_KEY belum dikonfigurasi.');
            return response()->json(['message' => 'Konfigurasi Midtrans belum lengkap.'], 500);
        }

        $grossAmountRaw = (string) $request->input('gross_amount');
        $signatureInput = $payload['order_id'] . $payload['status_code'] . $grossAmountRaw . $serverKey;
        $expectedSignature = hash('sha512', $signatureInput);

        if (!hash_equals(strtolower($expectedSignature), strtolower(trim($payload['signature_key'])))) {
            Log::warning('Signature notifikasi Midtrans tidak valid.', ['order_id' => $payload['order_id']]);
            return response()->json(['message' => 'Signature tidak valid.'], 403);
        }

        $payment = Payment::where('order_id', $payload['order_id'])
            ->where('gateway_provider', 'midtrans')
            ->first();

        if (!$payment) {
            Log::warning('Order ID notifikasi Midtrans tidak ditemukan.', ['order_id' => $payload['order_id']]);
            return response()->json(['message' => 'Transaksi tidak ditemukan.'], 404);
        }

        $expectedAmount = number_format((float) $payment->amount, 2, '.', '');
        $receivedAmount = number_format((float) $payload['gross_amount'], 2, '.', '');
        if ($expectedAmount !== $receivedAmount) {
            Log::warning('Nominal notifikasi Midtrans tidak sesuai.', [
                'order_id' => $payload['order_id'],
                'expected' => $expectedAmount,
                'received' => $receivedAmount,
            ]);
            return response()->json(['message' => 'Nominal tidak sesuai.'], 422);
        }

        if (isset($payload['currency']) && strtoupper($payload['currency']) !== 'IDR') {
            return response()->json(['message' => 'Mata uang tidak sesuai.'], 422);
        }

        DB::transaction(function () use ($payment, $payload) {
            $locked = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            $transactionStatus = strtolower($payload['transaction_status']);
            $fraudStatus = strtolower($payload['fraud_status'] ?? '');
            $statusCode = (string) $payload['status_code'];

            $newStatus = match ($transactionStatus) {
                'settlement' => $statusCode === '200' ? 'paid' : $locked->status,
                'capture' => match (true) {
                    $statusCode === '200' && in_array($fraudStatus, ['', 'accept'], true) => 'paid',
                    $fraudStatus === 'challenge' => 'pending',
                    $fraudStatus === 'deny' => 'failed',
                    default => $locked->status,
                },
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel' => 'cancelled',
                'deny', 'failure' => 'failed',
                'refund' => 'refunded',
                'partial_refund' => 'partially_refunded',
                default => $locked->status,
            };

            // Abaikan notifikasi lama yang datang terlambat setelah status final diterima.
            if (
                in_array($locked->status, ['paid', 'refunded', 'partially_refunded'], true)
                && in_array($newStatus, ['pending', 'failed', 'expired', 'cancelled'], true)
            ) {
                $newStatus = $locked->status;
            }

            $update = [
                'status' => $newStatus,
                'gateway_response' => $payload,
            ];
            if (!empty($payload['transaction_id'])) $update['gateway_transaction_id'] = $payload['transaction_id'];
            if (!empty($payload['payment_type'])) $update['payment_method'] = $payload['payment_type'];
            if ($newStatus === 'paid' && !$locked->paid_at) $update['paid_at'] = now();

            $locked->update($update);
        });

        return response()->json(['message' => 'Notifikasi berhasil diproses.'], 200);
    }
}
