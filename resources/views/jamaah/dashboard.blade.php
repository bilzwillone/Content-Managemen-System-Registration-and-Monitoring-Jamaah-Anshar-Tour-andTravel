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
        <div class="mx-auto max-w-7xl space-y-7">
            <header>
                <p class="text-sm font-semibold text-yellow-700">PORTAL JAMAAH ANSAR TRAVEL</p>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">Dashboard Jamaah</h1>
                <p class="mt-1 text-sm text-gray-500">Ringkasan informasi pendaftaran dan perjalanan Anda.</p>
            </header>

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (!$jamaah)
                <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6">
                    <div class="flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-xl text-amber-700">!</span>
                        <div>
                            <h2 class="font-bold text-amber-900">Data jamaah belum terhubung</h2>
                            <p class="mt-2 text-sm leading-6 text-amber-800">
                                Akun Anda belum terhubung dengan data pendaftaran jamaah. Pastikan email akun sudah diverifikasi
                                dan sama dengan email yang dicatat oleh Karyawan Ansar Travel. Jika masih belum muncul,
                                hubungi Karyawan untuk pemeriksaan data.
                            </p>
                        </div>
                    </div>
                </section>
            @else
                <section class="relative overflow-hidden rounded-2xl bg-[#171717] p-7 text-white shadow-sm">
                    <div class="absolute -right-10 -top-12 h-48 w-48 rounded-full bg-[#FDD52E]/10"></div>
                    <div class="relative">
                        <p class="text-sm text-gray-300">Assalamu'alaikum,</p>
                        <h2 class="mt-2 text-2xl font-bold md:text-3xl">{{ $jamaah->name }}</h2>
                        <p class="mt-3 text-sm text-gray-300">
                            {{ $jamaah->package?->name ?? 'Paket belum ditentukan' }}
                            <span class="mx-1 text-[#FDD52E]">·</span>
                            No. Registrasi {{ $jamaah->registration_number ?? '-' }}
                        </p>
                    </div>
                </section>

                <section>
                    <div class="mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Ringkasan Pendaftaran</h2>
                        <p class="mt-1 text-sm text-gray-500">Pantau status administrasi dan pembayaran Anda.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Status Pendaftaran</p>
                            <p class="mt-4 text-xl font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $jamaah->status ?? '-')) }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Dokumen Terverifikasi</p>
                            <p class="mt-4 text-2xl font-bold text-gray-900">{{ $verifiedDocuments }} <span class="text-base font-medium text-gray-400">/ {{ $documentCount }}</span></p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Total Pembayaran Berhasil</p>
                            <p class="mt-4 text-xl font-bold text-green-700">Rp{{ number_format($paidTotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 border-t-4 border-t-[#FFB22C] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Sisa Tagihan</p>
                            <p class="mt-4 text-xl font-bold text-orange-700">{{ $billIsSet ? 'Rp' . number_format($remainingBill, 0, ',', '.') : 'Belum diatur' }}</p>
                        </div>
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 px-5 py-4">
                            <h3 class="font-bold text-gray-900">Informasi Keberangkatan</h3>
                            <p class="mt-1 text-xs text-gray-500">Kelompok dan Muthawif perjalanan Anda.</p>
                        </div>
                        <div class="space-y-3 p-5">
                            @forelse ($groups as $group)
                                <div class="rounded-xl border border-gray-100 p-4 transition hover:border-yellow-300">
                                    <p class="font-bold text-gray-900">{{ $group->name }}</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ $group->schedule?->name ?? 'Jadwal belum tersedia' }}</p>
                                    <p class="mt-2 text-sm text-gray-600">Berangkat: {{ $group->schedule?->departure_date?->format('d M Y') ?? '-' }}</p>
                                    <p class="mt-2 text-xs text-gray-500">Muthawif: {{ $group->muthawifs->pluck('name')->join(', ') ?: 'Belum ditentukan' }}</p>
                                </div>
                            @empty
                                <p class="rounded-xl bg-gray-50 px-4 py-8 text-center text-sm text-gray-500">Anda belum dimasukkan ke kelompok keberangkatan.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 px-5 py-4">
                            <h3 class="font-bold text-gray-900">Transaksi Terbaru</h3>
                            <p class="mt-1 text-xs text-gray-500">Lima transaksi pembayaran terakhir.</p>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($recentPayments as $payment)
                                <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ strtoupper(str_replace('_', ' ', $payment->payment_type ?? 'Pembayaran')) }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $payment->created_at?->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ ucfirst($payment->status) }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="px-5 py-8 text-center text-sm text-gray-500">Belum ada transaksi pembayaran.</p>
                            @endforelse
                        </div>
                        <div class="border-t border-gray-100 px-5 py-4">
                            <a href="{{ route('jamaah.payments') }}" class="text-sm font-semibold text-gray-800 transition hover:text-yellow-700">
                                Lihat riwayat pembayaran →
                            </a>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </main>
</div>
</body>
</html>
