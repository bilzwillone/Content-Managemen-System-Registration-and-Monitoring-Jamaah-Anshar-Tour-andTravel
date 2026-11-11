<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use RuntimeException;

class MidtransService
{
    public function __construct()
    {
        $serverKey = config('midtrans.server_key');

        if (empty($serverKey)) {
            throw new RuntimeException(
                'MIDTRANS_SERVER_KEY belum dikonfigurasi.'
            );
        }

        Config::$serverKey = $serverKey;
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Membuat URL pembayaran Snap Redirect.
     */
    public function createRedirectUrl(array $params): string
    {
        $transaction = Snap::createTransaction($params);

        if (empty($transaction->redirect_url)) {
            throw new RuntimeException(
                'Midtrans tidak mengembalikan URL pembayaran.'
            );
        }

        return $transaction->redirect_url;
    }

    /**
     * Mengambil status transaksi dari Midtrans.
     */
    public function getTransactionStatus(string $orderId): object
    {
        return Transaction::status($orderId);
    }
}