<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Jamaah - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
<div class="flex min-h-screen">
    @include('partials.sidebar')
    <main class="min-w-0 flex-1 p-5 lg:p-8">
        <div class="mx-auto max-w-7xl space-y-6">
            <header>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Jamaah</h1>
                <p class="mt-1 text-sm text-gray-500">Ringkasan informasi pendaftaran dan perjalanan Anda di Ansar Travel.</p>
            </header>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            @if (!$jamaah)
                <section class="rounded-xl border border-amber-200 bg-amber-50 p-6">
                    <h2 class="font-semibold text-amber-900">Data jamaah belum terhubung</h2>
                    <p class="mt-2 text-sm leading-6 text-amber-800">Akun Anda belum terhubung dengan data pendaftaran jamaah. Pastikan email akun sudah diverifikasi dan sama dengan email yang dicatat oleh Karyawan Ansar Travel. Jika masih belum muncul, hubungi Karyawan untuk pemeriksaan data.</p>
                </section>
            @else
                <section class="rounded-xl bg-gray-900 p-6 text-white shadow-sm">
                    <p class="text-sm text-gray-300">Assalamu'alaikum,</p>
                    <h2 class="mt-1 text-2xl font-bold">{{ $jamaah->name }}</h2>
                    <p class="mt-2 text-sm text-gray-300">{{ $jamaah->package?->name ?? 'Paket belum ditentukan' }} · No. Registrasi {{ $jamaah->registration_number ?? '-' }}</p>
                </section>

                <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Status Pendaftaran</p><p class="mt-2 text-xl font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $jamaah->status ?? '-')) }}</p></div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Dokumen Terverifikasi</p><p class="mt-2 text-xl font-semibold text-gray-800">{{ $verifiedDocuments }} / {{ $documentCount }}</p></div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Total Pembayaran Berhasil</p><p class="mt-2 text-xl font-semibold text-green-700">Rp{{ number_format($paidTotal, 0, ',', '.') }}</p></div>
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Sisa Tagihan</p><p class="mt-2 text-xl font-semibold text-orange-700">{{ $billIsSet ? 'Rp' . number_format($remainingBill, 0, ',', '.') : 'Belum diatur' }}</p></div>
                </section>

                <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 px-5 py-4"><h3 class="font-semibold text-gray-800">Informasi Keberangkatan</h3></div>
                        <div class="space-y-3 p-5">
                            @forelse ($groups as $group)
                                <div class="rounded-lg border border-gray-100 p-4">
                                    <p class="font-semibold text-gray-800">{{ $group->name }}</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ $group->schedule?->name ?? 'Jadwal belum tersedia' }}</p>
                                    <p class="mt-1 text-sm text-gray-500">Berangkat: {{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}</p>
                                    <p class="mt-2 text-xs text-gray-500">Muthawif: {{ $group->muthawifs->pluck('name')->join(', ') ?: 'Belum ditentukan' }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Anda belum dimasukkan ke kelompok keberangkatan.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 px-5 py-4"><h3 class="font-semibold text-gray-800">Transaksi Terbaru</h3></div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($recentPayments as $payment)
                                <div class="flex items-center justify-between gap-4 px-5 py-4">
                                    <div><p class="text-sm font-medium text-gray-800">{{ strtoupper(str_replace('_', ' ', $payment->payment_type ?? 'Pembayaran')) }}</p><p class="mt-1 text-xs text-gray-500">{{ $payment->created_at?->format('d M Y, H:i') }}</p></div>
                                    <div class="text-right"><p class="text-sm font-semibold text-gray-800">Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}</p><p class="mt-1 text-xs text-gray-500">{{ ucfirst($payment->status) }}</p></div>
                                </div>
                            @empty
                                <p class="px-5 py-6 text-sm text-gray-500">Belum ada transaksi pembayaran.</p>
                            @endforelse
                        </div>
                        <div class="border-t border-gray-100 px-5 py-3"><a href="{{ route('jamaah.payments') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">Lihat riwayat pembayaran →</a></div>
                    </div>
                </section>
            @endif
        </div>
    </main>
</div>
</body>
</html>
