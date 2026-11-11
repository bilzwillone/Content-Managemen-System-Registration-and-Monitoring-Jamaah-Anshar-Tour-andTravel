
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pembayaran Jamaah - Ansar Travel</title>

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
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Pembayaran Jamaah
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Kelola tagihan dan transaksi pembayaran jamaah.
                    </p>
                </div>

                {{-- Success Notification --}}
                @if (session('success'))
                    <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error Notification --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

                        <p class="font-semibold">
                            Terjadi kesalahan:
                        </p>

                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                {{-- Payment Table --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    {{-- Table Header --}}
                    <div class="border-b border-gray-200 px-6 py-5">

                        <h2 class="font-semibold text-gray-800">
                            Daftar Tagihan Jamaah
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Pilih jamaah untuk membuat transaksi pembayaran.
                        </p>

                    </div>

                    {{-- Table Content --}}
                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[1050px] text-left text-sm">

                            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-6 py-4">No.</th>
                                    <th class="px-6 py-4">Nama Jamaah</th>
                                    <th class="px-6 py-4">Paket</th>
                                    <th class="px-6 py-4">Total Tagihan</th>
                                    <th class="px-6 py-4">Sudah Dibayar</th>
                                    <th class="px-6 py-4">Sisa Tagihan</th>
                                    <th class="px-6 py-4">Buat Pembayaran</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse ($jamaahs as $index => $jamaah)

                                    @php
                                        $totalBill = (float) ($jamaah->total_bill ?? 0);
                                        $totalPaid = (float) ($jamaah->total_paid ?? 0);
                                        $remaining = max($totalBill - $totalPaid, 0);
                                    @endphp

                                    <tr class="align-top transition hover:bg-gray-50">

                                        {{-- Number --}}
                                        <td class="px-6 py-5 text-gray-500">
                                            {{ $jamaahs->firstItem() + $index }}
                                        </td>

                                        {{-- Jamaah --}}
                                        <td class="px-6 py-5">

                                            <p class="font-semibold text-gray-800">
                                                {{ $jamaah->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $jamaah->phone }}
                                            </p>

                                        </td>

                                        {{-- Package --}}
                                        <td class="px-6 py-5 text-gray-600">
                                            {{ $jamaah->package?->name ?? 'Belum ditentukan' }}
                                        </td>

                                        {{-- Total Bill --}}
                                        <td class="whitespace-nowrap px-6 py-5 font-medium text-gray-700">
                                            Rp{{ number_format($totalBill, 0, ',', '.') }}
                                        </td>

                                        {{-- Total Paid --}}
                                        <td class="whitespace-nowrap px-6 py-5 font-semibold text-green-700">
                                            Rp{{ number_format($totalPaid, 0, ',', '.') }}
                                        </td>

                                        {{-- Remaining --}}
                                        <td class="whitespace-nowrap px-6 py-5 font-semibold {{ $remaining > 0 ? 'text-orange-600' : 'text-green-700' }}">

                                            Rp{{ number_format($remaining, 0, ',', '.') }}

                                            @if ($totalBill > 0 && $remaining <= 0)

                                                <p class="mt-1 text-xs font-normal text-green-600">
                                                    Lunas
                                                </p>

                                            @elseif ($totalBill <= 0)

                                                <p class="mt-1 text-xs font-normal text-red-500">
                                                    Tagihan belum diatur
                                                </p>

                                            @endif

                                        </td>

                                        {{-- Create Payment --}}
                                        <td class="px-6 py-5">

                                            @if ($remaining > 0)

                                                <form
                                                    action="{{ route('karyawan.payments.store', $jamaah) }}"
                                                    method="POST"
                                                    class="w-64 space-y-3"
                                                >

                                                    @csrf

                                                    {{-- Payment Type --}}
                                                    <div>

                                                        <label class="mb-1 block text-xs font-medium text-gray-600">
                                                            Jenis Pembayaran
                                                        </label>

                                                        <select
                                                            name="payment_type"
                                                            required
                                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                                        >

                                                            <option value="dp">
                                                                DP
                                                            </option>

                                                            <option value="cicilan">
                                                                Cicilan
                                                            </option>

                                                            <option value="pelunasan">
                                                                Pelunasan
                                                            </option>

                                                        </select>

                                                    </div>

                                                    {{-- Payment Amount --}}
                                                    <div>

                                                        <label class="mb-1 block text-xs font-medium text-gray-600">
                                                            Nominal Pembayaran
                                                        </label>

                                                        <input
                                                            type="number"
                                                            name="amount"
                                                            min="1"
                                                            max="{{ (int) $remaining }}"
                                                            step="1"
                                                            required
                                                            placeholder="Masukkan nominal"
                                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                                        >

                                                        <p class="mt-1 text-xs text-gray-400">
                                                            Maksimal Rp{{ number_format($remaining, 0, ',', '.') }}
                                                        </p>

                                                    </div>

                                                    {{-- Notes --}}
                                                    <div>

                                                        <label class="mb-1 block text-xs font-medium text-gray-600">
                                                            Catatan (Opsional)
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="notes"
                                                            maxlength="1000"
                                                            placeholder="Contoh: Pembayaran tahap pertama"
                                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-400 focus:ring-yellow-400"
                                                        >

                                                    </div>

                                                    {{-- Submit --}}
                                                    <button
                                                        type="submit"
                                                        class="w-full rounded-lg bg-yellow-400 px-4 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500"
                                                    >
                                                        Buat Pembayaran
                                                    </button>

                                                </form>

                                            @else

                                                <span class="inline-flex rounded-lg bg-green-50 px-3 py-2 text-xs font-semibold text-green-700">
                                                    Tagihan Lunas
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">

                                            <p class="font-medium text-gray-600">
                                                Belum ada data jamaah.
                                            </p>

                                            <p class="mt-1 text-sm text-gray-400">
                                                Data jamaah yang sudah terdaftar akan muncul di sini.
                                            </p>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    @if ($jamaahs->hasPages())

                        <div class="border-t border-gray-200 px-6 py-4">
                            {{ $jamaahs->links() }}
                        </div>

                    @endif

                </div>

                {{-- Footer Information --}}
                <p class="mt-4 text-xs text-gray-500">
                    Pembayaran yang berhasil akan memperbarui jumlah pembayaran
                    setelah status transaksi dikonfirmasi oleh Midtrans.
                </p>

            </div>

        </main>

    </div>

</body>
</html>
