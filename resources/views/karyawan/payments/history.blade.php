
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Riwayat Pembayaran - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 font-sans antialiased">

    <div class="flex min-h-screen bg-gray-50">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content --}}
        <main class="min-w-0 flex-1 p-6 lg:p-8">

            <div class="mx-auto max-w-7xl">

                {{-- Header --}}
                <div class="mb-8 flex flex-wrap items-start justify-between gap-4">

                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            Riwayat Transaksi Pembayaran
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Lihat dan pantau seluruh transaksi pembayaran jamaah.
                        </p>
                    </div>

                    <a
                        href="{{ route('karyawan.payments.index') }}"
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Kembali ke Pembayaran
                    </a>

                </div>

                {{-- Summary Cards --}}
                <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                    {{-- Total Transactions --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <p class="text-sm text-gray-500">
                            Total Transaksi
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-gray-800">
                            {{ number_format($summary['total_transactions'], 0, ',', '.') }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Seluruh transaksi pembayaran
                        </p>

                    </div>

                    {{-- Paid Transactions --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <p class="text-sm text-gray-500">
                            Transaksi Berhasil
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-green-600">
                            {{ number_format($summary['paid_transactions'], 0, ',', '.') }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Transaksi yang sudah dibayar
                        </p>

                    </div>

                    {{-- Pending Transactions --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <p class="text-sm text-gray-500">
                            Menunggu Pembayaran
                        </p>

                        <h2 class="mt-2 text-2xl font-bold text-orange-600">
                            {{ number_format($summary['pending_transactions'], 0, ',', '.') }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Transaksi yang belum selesai
                        </p>

                    </div>

                    {{-- Total Paid --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <p class="text-sm text-gray-500">
                            Total Dana Diterima
                        </p>

                        <h2 class="mt-2 text-xl font-bold text-gray-800">
                            Rp{{ number_format($summary['total_paid'], 0, ',', '.') }}
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Akumulasi transaksi berhasil
                        </p>

                    </div>

                </div>

                {{-- Transaction Table --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    {{-- Table Header --}}
                    <div class="border-b border-gray-200 px-6 py-5">

                        <h2 class="font-semibold text-gray-800">
                            Daftar Riwayat Transaksi
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Riwayat seluruh pembayaran yang tercatat di sistem.
                        </p>

                    </div>

                    {{-- Table Content --}}
                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[1150px] text-left text-sm">

                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">

                                <tr>
                                    <th class="whitespace-nowrap px-6 py-4">
                                        No.
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-4">
                                        Order ID
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-4">
                                        Nama Jamaah
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-4">
                                        Jenis Pembayaran
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-4">
                                        Nominal
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-4">
                                        Metode Pembayaran
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-4">
                                        Tanggal Transaksi
                                    </th>

                                    <th class="whitespace-nowrap px-6 py-4">
                                        Status
                                    </th>
                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse ($payments as $index => $payment)

                                    @php
                                        $statusStyles = [
                                            'paid' => 'bg-green-50 text-green-700',
                                            'pending' => 'bg-orange-50 text-orange-700',
                                            'failed' => 'bg-red-50 text-red-700',
                                            'expired' => 'bg-gray-100 text-gray-600',
                                            'cancelled' => 'bg-gray-100 text-gray-600',
                                        ];

                                        $statusLabels = [
                                            'paid' => 'Berhasil',
                                            'pending' => 'Menunggu',
                                            'failed' => 'Gagal',
                                            'expired' => 'Kedaluwarsa',
                                            'cancelled' => 'Dibatalkan',
                                        ];

                                        $paymentTypes = [
                                            'dp' => 'DP',
                                            'cicilan' => 'Cicilan',
                                            'pelunasan' => 'Pelunasan',
                                        ];

                                        $status = strtolower($payment->status);
                                    @endphp

                                    <tr class="transition hover:bg-gray-50">

                                        {{-- Number --}}
                                        <td class="px-6 py-5 text-gray-500">
                                            {{ $payments->firstItem() + $index }}
                                        </td>

                                        {{-- Order ID --}}
                                        <td class="px-6 py-5">

                                            <p class="font-medium text-gray-800">
                                                {{ $payment->order_id }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-400">
                                                ID Transaksi: #{{ $payment->id }}
                                            </p>

                                        </td>

                                        {{-- Jamaah --}}
                                        <td class="px-6 py-5">

                                            <p class="font-semibold text-gray-800">
                                                {{ $payment->jamaah?->name ?? 'Jamaah tidak ditemukan' }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $payment->jamaah?->phone ?? '-' }}
                                            </p>

                                        </td>

                                        {{-- Payment Type --}}
                                        <td class="px-6 py-5 text-gray-600">
                                            {{ $paymentTypes[$payment->payment_type] ?? ucfirst($payment->payment_type) }}
                                        </td>

                                        {{-- Amount --}}
                                        <td class="whitespace-nowrap px-6 py-5 font-semibold text-gray-800">
                                            Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                                        </td>

                                        {{-- Payment Method --}}
                                        <td class="px-6 py-5 text-gray-600">
                                            {{ $payment->payment_method ? ucwords(str_replace('_', ' ', $payment->payment_method)) : '-' }}
                                        </td>

                                        {{-- Transaction Date --}}
                                        <td class="whitespace-nowrap px-6 py-5 text-gray-600">
                                            {{ $payment->created_at?->format('d M Y, H:i') ?? '-' }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-5">

                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $statusLabels[$status] ?? ucfirst($status) }}
                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="8" class="px-6 py-16 text-center">

                                            <p class="font-semibold text-gray-700">
                                                Belum ada riwayat transaksi.
                                            </p>

                                            <p class="mt-1 text-sm text-gray-400">
                                                Transaksi pembayaran jamaah akan muncul di halaman ini.
                                            </p>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    @if ($payments->hasPages())

                        <div class="border-t border-gray-200 px-6 py-4">
                            {{ $payments->links() }}
                        </div>

                    @endif

                </div>

                {{-- Footer Information --}}
                <p class="mt-5 text-xs text-gray-500">
                    Status pembayaran diperbarui berdasarkan konfirmasi transaksi dari Midtrans.
                </p>

            </div>

        </main>

    </div>

</body>
</html>
