<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class PaymentController extends Controller
{
    /**
     * Menampilkan daftar jamaah dan ringkasan pembayarannya.
     */
    public function index()
    {
        $jamaahs = Jamaah::with('package')
            ->withSum([
                'payments as total_paid' => function ($query) {
                    $query->where('status', 'paid');
                }
            ], 'amount')
            ->orderBy('name')
            ->paginate(15);

        return view('karyawan.payments.index', compact('jamaahs'));
    }

    /**
     * Membuat transaksi pembayaran melalui Midtrans Snap.
     */
    public function store(
        Request $request,
        Jamaah $jamaah,
        MidtransService $midtrans
    ) {
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'payment_type' => 'required|in:dp,cicilan,pelunasan',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!$jamaah->total_bill || $jamaah->total_bill <= 0) {
            return back()->withErrors([
                'amount' => 'Total tagihan jamaah belum ditentukan.',
            ]);
        }

        $totalPaid = (int) $jamaah->payments()
            ->where('status', 'paid')
            ->sum('amount');

        $remainingAmount = (int) $jamaah->total_bill - $totalPaid;

        if ($remainingAmount <= 0) {
            return back()->withErrors([
                'amount' => 'Tagihan jamaah sudah lunas.',
            ]);
        }

        if ($validated['amount'] > $remainingAmount) {
            return back()->withErrors([
                'amount' => 'Nominal pembayaran melebihi sisa tagihan sebesar Rp'
                    . number_format($remainingAmount, 0, ',', '.') . '.',
            ]);
        }

        // Cegah pembuatan transaksi baru ketika masih ada transaksi pending.
        $hasPendingPayment = $jamaah->payments()
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingPayment) {
            return back()->withErrors([
                'amount' => 'Masih terdapat transaksi pembayaran yang menunggu penyelesaian.',
            ]);
        }

        $orderId = 'ANS-' . $jamaah->id . '-'
            . now()->format('YmdHis') . '-'
            . Str::upper(Str::random(5));

        $payment = Payment::create([
            'jamaah_id' => $jamaah->id,
            'order_id' => $orderId,
            'payment_type' => $validated['payment_type'],
            'amount' => $validated['amount'],
            'currency' => 'IDR',
            'gateway_provider' => 'midtrans',
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        try {
            $customerDetails = [
                'first_name' => $jamaah->name,
            ];

            if ($jamaah->email) {
                $customerDetails['email'] = $jamaah->email;
            }

            if ($jamaah->phone) {
                $customerDetails['phone'] = $jamaah->phone;
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $payment->order_id,
                    'gross_amount' => (int) $payment->amount,
                ],
                'customer_details' => $customerDetails,
                'item_details' => [
                    [
                        'id' => 'PAY-' . $payment->id,
                        'price' => (int) $payment->amount,
                        'quantity' => 1,
                        'name' => Str::limit(
                            'Pembayaran ' . $validated['payment_type']
                                . ' - ' . $jamaah->name,
                            50,
                            ''
                        ),
                    ],
                ],
            ];

            $paymentUrl = $midtrans->createRedirectUrl($params);

            $payment->update([
                'payment_url' => $paymentUrl,
            ]);

            return redirect()->away($paymentUrl);

        } catch (Throwable $exception) {
            Log::error('Gagal membuat transaksi Midtrans.', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'error' => $exception->getMessage(),
            ]);

            $payment->update([
                'status' => 'failed',
            ]);

            return back()->withErrors([
                'amount' => 'Transaksi belum berhasil dibuat. Silakan coba kembali.',
            ]);
        }
    }

    public function history()
    {
        $payments = Payment::with(['jamaah', 'creator'])
            ->latest('created_at')
            ->paginate(15);

        $summary = [
            'total_transactions' => Payment::count(),

            'paid_transactions' => Payment::where('status', 'paid')
                ->count(),

            'pending_transactions' => Payment::where('status', 'pending')
                ->count(),

            'total_paid' => Payment::where('status', 'paid')
                ->sum('amount'),
        ];

        return view('karyawan.payments.history', compact(
            'payments',
            'summary'
        ));
    }


}
