<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /** Menampilkan tagihan dan ringkasan pembayaran setiap jamaah. */
    public function index()
    {
        $jamaahs = Jamaah::with('package')
            ->withSum([
                'payments as total_paid' => function ($query) {
                    $query->where('status', 'paid');
                },
            ], 'amount')
            ->withCount([
                'payments as pending_payments_count' => function ($query) {
                    $query->where('status', 'pending');
                },
            ])
            ->orderBy('name')
            ->paginate(15);

        return view('karyawan.payments.index', compact('jamaahs'));
    }

    /**
     * Mencatat pembayaran langsung yang benar-benar telah diterima Karyawan.
     * Pembayaran online dibuat sendiri oleh Jamaah melalui portal Jamaah.
     */
    public function storeManual(Request $request, Jamaah $jamaah)
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
            'payment_type' => ['required', 'in:dp,cicilan,pelunasan'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($jamaah, $validated) {
            $lockedJamaah = Jamaah::with('package')
                ->whereKey($jamaah->id)
                ->lockForUpdate()
                ->firstOrFail();

            $bill = $this->resolveAndRememberBill($lockedJamaah);

            if ($bill === null) {
                throw ValidationException::withMessages([
                    'amount' => 'Tagihan jamaah belum ditentukan. Tetapkan paket atau total tagihan terlebih dahulu.',
                ]);
            }

            $paid = (int) $lockedJamaah->payments()
                ->where('status', 'paid')
                ->sum('amount');

            $remaining = max($bill - $paid, 0);

            if ($remaining <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Tagihan jamaah sudah lunas.',
                ]);
            }

            if ((int) $validated['amount'] > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal pembayaran melebihi sisa tagihan Rp'
                        . number_format($remaining, 0, ',', '.') . '.',
                ]);
            }

            // Hindari pencatatan tunai ganda ketika pembayaran online masih pending.
            if ($lockedJamaah->payments()->where('status', 'pending')->exists()) {
                throw ValidationException::withMessages([
                    'amount' => 'Masih ada transaksi online yang menunggu konfirmasi. Pastikan transaksi tersebut selesai atau kedaluwarsa sebelum mencatat pembayaran langsung.',
                ]);
            }

            Payment::create([
                'jamaah_id' => $lockedJamaah->id,
                'order_id' => 'CASH-' . $lockedJamaah->id . '-'
                    . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
                'payment_type' => $validated['payment_type'],
                'source' => 'karyawan',
                'amount' => $validated['amount'],
                'currency' => 'IDR',
                'gateway_provider' => null,
                'gateway_transaction_id' => null,
                'payment_method' => 'cash',
                'payment_url' => null,
                'status' => 'paid',
                'paid_at' => now(),
                'notes' => $validated['notes'] ?? 'Pembayaran tunai diterima langsung oleh Karyawan.',
                'gateway_response' => null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('karyawan.payments.index')
            ->with('success', 'Pembayaran langsung berhasil dicatat. Riwayat pembayaran Jamaah telah diperbarui.');
    }

    /** Menampilkan riwayat seluruh transaksi, baik dari Jamaah maupun Karyawan. */
    public function history()
    {
        $payments = Payment::with(['jamaah', 'creator'])
            ->latest('created_at')
            ->paginate(15);

        $summary = [
            'total_transactions' => Payment::count(),
            'paid_transactions' => Payment::where('status', 'paid')->count(),
            'pending_transactions' => Payment::where('status', 'pending')->count(),
            'total_paid' => Payment::where('status', 'paid')->sum('amount'),
        ];

        return view('karyawan.payments.history', compact('payments', 'summary'));
    }

    /** Menentukan tagihan dari snapshot; fallback ke harga paket hanya bila belum disimpan. */
    private function resolveAndRememberBill(Jamaah $jamaah): ?int
    {
        $storedBill = $jamaah->total_bill !== null
            ? (int) $jamaah->total_bill
            : 0;

        if ($storedBill > 0) {
            return $storedBill;
        }

        $packagePrice = $jamaah->package?->price !== null
            ? (int) $jamaah->package->price
            : 0;

        if ($packagePrice <= 0) {
            return null;
        }

        $jamaah->total_bill = $packagePrice;
        $jamaah->save();

        return $packagePrice;
    }
}
