<?php

namespace App\Http\Controllers\Jamaah;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class JamaahPaymentController extends Controller
{
    /** Memulai transaksi Snap hanya untuk data jamaah milik akun yang sedang login. */
    public function store(Request $request, MidtransService $midtrans)
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
            'payment_type' => ['required', 'in:dp,cicilan,pelunasan'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $payment = DB::transaction(function () use ($validated) {
            $jamaah = Jamaah::with('package')
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->first();

            if (!$jamaah) {
                abort(403, 'Akun Anda belum terhubung dengan data jamaah.');
            }

            $bill = $this->resolveAndRememberBill($jamaah);
            if ($bill === null) {
                throw ValidationException::withMessages(['amount' => 'Tagihan perjalanan belum ditentukan oleh Ansar Travel.']);
            }

            $paid = (int) $jamaah->payments()->where('status', 'paid')->sum('amount');
            $remaining = max($bill - $paid, 0);

            if ($remaining <= 0) {
                throw ValidationException::withMessages(['amount' => 'Tagihan perjalanan Anda sudah lunas.']);
            }
            if ((int) $validated['amount'] > $remaining) {
                throw ValidationException::withMessages(['amount' => 'Nominal melebihi sisa tagihan Rp' . number_format($remaining, 0, ',', '.') . '.']);
            }
            if ($jamaah->payments()->where('status', 'pending')->exists()) {
                throw ValidationException::withMessages(['amount' => 'Masih ada transaksi yang menunggu pembayaran. Selesaikan transaksi tersebut terlebih dahulu.']);
            }

            $orderId = 'ANS-' . $jamaah->id . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

            return Payment::create([
                'jamaah_id' => $jamaah->id,
                'order_id' => $orderId,
                'payment_type' => $validated['payment_type'],
                'source' => 'jamaah_website',
                'amount' => $validated['amount'],
                'currency' => 'IDR',
                'gateway_provider' => 'midtrans',
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        $jamaah = $payment->jamaah()->firstOrFail();

        try {
            $customer = ['first_name' => $jamaah->name];
            if ($jamaah->email) $customer['email'] = $jamaah->email;
            if ($jamaah->phone) $customer['phone'] = $jamaah->phone;

            $url = $midtrans->createRedirectUrl([
                'transaction_details' => [
                    'order_id' => $payment->order_id,
                    'gross_amount' => (int) $payment->amount,
                ],
                'customer_details' => $customer,
                'item_details' => [[
                    'id' => 'PAY-' . $payment->id,
                    'price' => (int) $payment->amount,
                    'quantity' => 1,
                    'name' => Str::limit('Pembayaran ' . $payment->payment_type . ' - ' . $jamaah->name, 50, ''),
                ]],
            ]);

            $payment->update(['payment_url' => $url]);
            return redirect()->away($url);
        } catch (Throwable $exception) {
            Log::error('Gagal membuat transaksi Midtrans dari portal Jamaah.', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'user_id' => auth()->id(),
                'error' => $exception->getMessage(),
            ]);
            $payment->update(['status' => 'failed']);
            return redirect()->route('jamaah.payments')->withErrors(['amount' => 'Transaksi belum berhasil dibuat. Silakan coba kembali.']);
        }
    }

    private function resolveAndRememberBill(Jamaah $jamaah): ?int
    {
        $bill = $jamaah->total_bill !== null ? (int) $jamaah->total_bill : 0;
        if ($bill > 0) {
            return $bill;
        }

        $packagePrice = $jamaah->package?->price !== null ? (int) $jamaah->package->price : 0;
        if ($packagePrice <= 0) {
            return null;
        }

        $jamaah->total_bill = $packagePrice;
        $jamaah->save();
        return $packagePrice;
    }
}
