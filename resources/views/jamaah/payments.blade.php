<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Saya - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
<div class="flex min-h-screen">
    @include('partials.sidebar')
    <main class="min-w-0 flex-1 p-5 lg:p-8">
        <div class="mx-auto max-w-7xl space-y-5">
            <header>
                <h1 class="text-xl font-bold text-gray-900">Pembayaran Saya</h1>
                <p class="mt-1 text-sm text-gray-500">Pantau tagihan dan lakukan pembayaran perjalanan Anda.</p>
            </header>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Pembayaran belum dapat diproses.</p>
                    <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            @php $pendingPayment = $payments->firstWhere('status', 'pending'); @endphp

            <section class="grid gap-3 sm:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm"><p class="text-xs text-gray-500">Total Tagihan</p><p class="mt-1 text-xl font-semibold text-gray-900">{{ $billIsSet ? 'Rp' . number_format($totalBill, 0, ',', '.') : 'Belum ditetapkan' }}</p></div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm"><p class="text-xs text-gray-500">Sudah Dibayar</p><p class="mt-1 text-xl font-semibold text-green-700">Rp{{ number_format($paidTotal, 0, ',', '.') }}</p></div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="text-xs text-gray-500">Sisa Tagihan</p>
                    <p class="mt-1 text-xl font-semibold {{ $billIsSet && $remainingBill > 0 ? 'text-orange-600' : 'text-gray-900' }}">{{ $billIsSet ? 'Rp' . number_format($remainingBill, 0, ',', '.') : 'Belum ditetapkan' }}</p>
                    @if ($billIsSet && $remainingBill <= 0)<p class="mt-1 text-xs text-green-700">Lunas</p>@elseif (!$billIsSet)<p class="mt-1 text-xs text-amber-700">Menunggu penetapan tagihan dari Karyawan.</p>@endif
                </div>
            </section>

            @if (!$billIsSet)
                <section class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">Tagihan perjalanan Anda belum ditentukan. Silakan hubungi Karyawan Ansar Travel.</section>
            @elseif ($remainingBill > 0 && $pendingPayment)
                <section class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <h2 class="text-sm font-semibold text-amber-900">Ada transaksi yang menunggu pembayaran</h2>
                    <p class="mt-1 text-sm text-amber-800">Selesaikan transaksi yang sedang berjalan sebelum membuat transaksi baru.</p>
                    @if ($pendingPayment->payment_url)<a href="{{ $pendingPayment->payment_url }}" class="mt-3 inline-flex rounded-lg bg-yellow-400 px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-yellow-500">Lanjutkan Pembayaran</a>@endif
                    <p class="mt-2 text-xs text-amber-800">Order ID: {{ $pendingPayment->order_id }} · Rp{{ number_format((float) $pendingPayment->amount, 0, ',', '.') }}</p>
                </section>
            @elseif ($billIsSet && $remainingBill > 0)
                <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="mb-4"><h2 class="text-sm font-semibold text-gray-900">Buat Pembayaran</h2><p class="mt-1 text-xs text-gray-500">Pilih jenis pembayaran dan nominal, kemudian lanjutkan melalui Midtrans.</p></div>
                    <form action="{{ route('jamaah.payments.store') }}" method="POST" class="grid gap-4 md:grid-cols-2">
                        @csrf
                        <div>
                            <label for="payment_type" class="mb-1.5 block text-xs font-medium text-gray-700">Jenis Pembayaran</label>
                            <select id="payment_type" name="payment_type" required class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:ring-yellow-400">
                                <option value="dp">Uang Muka (DP)</option><option value="cicilan">Cicilan</option><option value="pelunasan">Pelunasan</option>
                            </select>
                        </div>
                        <div>
                            <label for="amount" class="mb-1.5 block text-xs font-medium text-gray-700">Nominal Pembayaran (Rp)</label>
                            <input id="amount" type="number" name="amount" min="1" max="{{ (int) $remainingBill }}" step="1" required placeholder="Masukkan nominal" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:ring-yellow-400">
                            <p class="mt-1 text-xs text-gray-400">Maksimal Rp{{ number_format($remainingBill, 0, ',', '.') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label for="notes" class="mb-1.5 block text-xs font-medium text-gray-700">Catatan (Opsional)</label>
                            <input id="notes" type="text" name="notes" maxlength="1000" placeholder="Contoh: Pembayaran DP tahap pertama" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:ring-yellow-400">
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-4 md:col-span-2">
                            <p class="text-xs text-gray-500">Pembayaran diproses secara aman melalui Midtrans.</p>
                            <button type="submit" class="rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-gray-900 hover:bg-yellow-500">Bayar Sekarang</button>
                        </div>
                    </form>
                </section>
            @elseif ($billIsSet && $remainingBill <= 0)
                <section class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">Tagihan perjalanan Anda sudah lunas. Terima kasih telah melakukan pembayaran.</section>
            @endif

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-5 py-4"><h2 class="text-sm font-semibold text-gray-900">Riwayat Transaksi</h2><p class="mt-1 text-xs text-gray-500">Transaksi dari website dan pembayaran yang dicatat Karyawan.</p></div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left text-sm">
                        <thead class="bg-gray-50 text-[11px] font-semibold uppercase text-gray-500"><tr><th class="px-4 py-3">Order ID</th><th class="px-4 py-3">Jenis</th><th class="px-4 py-3">Nominal</th><th class="px-4 py-3">Metode</th><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Status</th></tr></thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse ($payments as $payment)
                            @php
                                $statusClass = match ($payment->status) {'paid' => 'bg-green-50 text-green-700', 'pending' => 'bg-amber-50 text-amber-700', 'failed', 'expired', 'cancelled' => 'bg-red-50 text-red-700', default => 'bg-gray-100 text-gray-600'};
                                $statusLabel = match ($payment->status) {'paid' => 'Berhasil', 'pending' => 'Menunggu', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa', 'cancelled' => 'Dibatalkan', 'refunded' => 'Dikembalikan', 'partially_refunded' => 'Dikembalikan Sebagian', default => ucfirst(str_replace('_', ' ', $payment->status))};
                            @endphp
                            <tr>
                                <td class="px-4 py-3"><p class="font-medium text-gray-800">{{ $payment->order_id }}</p>@if ($payment->status === 'pending' && $payment->payment_url)<a href="{{ $payment->payment_url }}" class="mt-1 inline-block text-xs font-medium text-blue-700 hover:underline">Lanjutkan pembayaran</a>@endif</td>
                                <td class="px-4 py-3 capitalize text-gray-600">{{ $payment->payment_type }}</td>
                                <td class="whitespace-nowrap px-4 py-3 font-semibold text-gray-800">Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : ($payment->gateway_provider === 'midtrans' ? 'Midtrans' : '-') }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-gray-500">{{ $payment->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                                <td class="px-4 py-3"><span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClass }}">{{ $statusLabel }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">Belum ada transaksi pembayaran.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($payments->hasPages())<div class="border-t border-gray-200 px-5 py-4">{{ $payments->links() }}</div>@endif
            </section>
            <p class="text-xs text-gray-500">Status pembayaran online diperbarui setelah notifikasi Midtrans diverifikasi oleh server Ansar Travel. Pembayaran langsung akan dicatat oleh Karyawan.</p>
        </div>
    </main>
</div>
</body>
</html>
