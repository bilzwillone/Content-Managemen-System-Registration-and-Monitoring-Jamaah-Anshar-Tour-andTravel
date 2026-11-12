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
<div class="flex min-h-screen">
    @include('partials.sidebar')

    <main class="min-w-0 flex-1 p-5 lg:p-8">
        <div class="mx-auto max-w-7xl space-y-5">
            <header>
                <h1 class="text-xl font-bold text-gray-900">Pembayaran Jamaah</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Pantau tagihan dan catat pembayaran langsung yang telah diterima Karyawan.
                </p>
            </header>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Pembayaran belum dapat dicatat.</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-gray-900">Daftar Tagihan Jamaah</h2>
                    <p class="mt-1 text-xs text-gray-500">
                        Pembayaran online dilakukan oleh Jamaah melalui portal. Karyawan hanya mencatat pembayaran langsung yang benar-benar telah diterima.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[980px] text-left text-sm">
                        <thead class="bg-gray-50 text-[11px] font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Nama Jamaah</th>
                            <th class="px-4 py-3">Paket</th>
                            <th class="px-4 py-3">Total Tagihan</th>
                            <th class="px-4 py-3">Sudah Dibayar</th>
                            <th class="px-4 py-3">Sisa Tagihan</th>
                            <th class="px-4 py-3">Catat Pembayaran Langsung</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                        @forelse ($jamaahs as $index => $jamaah)
                            @php
                                $storedBill = $jamaah->total_bill !== null ? (float) $jamaah->total_bill : 0;
                                $packageBill = $jamaah->package?->price !== null ? (float) $jamaah->package->price : 0;
                                $totalBill = $storedBill > 0 ? $storedBill : ($packageBill > 0 ? $packageBill : null);
                                $totalPaid = (float) ($jamaah->total_paid ?? 0);
                                $remaining = $totalBill !== null ? max($totalBill - $totalPaid, 0) : null;
                                $hasPending = (int) ($jamaah->pending_payments_count ?? 0) > 0;
                            @endphp

                            <tr class="align-top hover:bg-gray-50/70">
                                <td class="px-4 py-4 text-gray-500">{{ $jamaahs->firstItem() + $index }}</td>

                                <td class="px-4 py-4">
                                    <p class="font-semibold text-gray-900">{{ $jamaah->name }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $jamaah->phone }}</p>
                                </td>

                                <td class="px-4 py-4 text-gray-600">{{ $jamaah->package?->name ?? 'Belum ditentukan' }}</td>

                                <td class="whitespace-nowrap px-4 py-4 font-medium text-gray-800">
                                    @if ($totalBill !== null)
                                        Rp{{ number_format($totalBill, 0, ',', '.') }}
                                    @else
                                        <span class="text-xs font-medium text-amber-700">Belum ditetapkan</span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 font-semibold text-green-700">
                                    Rp{{ number_format($totalPaid, 0, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-4 font-semibold">
                                    @if ($remaining !== null)
                                        <span class="{{ $remaining > 0 ? 'text-orange-600' : 'text-green-700' }}">
                                            Rp{{ number_format($remaining, 0, ',', '.') }}
                                        </span>
                                        @if ($totalBill > 0 && $remaining <= 0)
                                            <p class="mt-1 text-xs font-normal text-green-700">Lunas</p>
                                        @endif
                                    @else
                                        <span class="text-xs text-amber-700">Belum diatur</span>
                                    @endif
                                </td>

                                <td class="px-4 py-4">
                                    @if ($totalBill === null)
                                        <p class="max-w-xs text-xs leading-5 text-amber-700">
                                            Tagihan belum ditentukan. Tetapkan paket atau total tagihan sebelum menerima pembayaran.
                                        </p>
                                    @elseif ($remaining <= 0)
                                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                                            Tagihan Lunas
                                        </span>
                                    @elseif ($hasPending)
                                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">
                                            Transaksi online menunggu konfirmasi
                                        </span>
                                        <p class="mt-1 max-w-xs text-xs leading-5 text-gray-500">
                                            Pastikan transaksi online selesai atau kedaluwarsa sebelum mencatat pembayaran langsung agar tidak terjadi pembayaran ganda.
                                        </p>
                                    @else
                                        <form
                                            action="{{ route('karyawan.payments.manual', $jamaah) }}"
                                            method="POST"
                                            class="w-full max-w-xs space-y-2 rounded-lg border border-gray-200 p-3"
                                            onsubmit="return confirm('Pastikan uang benar-benar sudah diterima dari jamaah sebelum mencatat pembayaran ini. Lanjutkan?')"
                                        >
                                            @csrf
                                            <p class="text-xs font-semibold text-gray-800">Pembayaran tunai / langsung</p>

                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-600">Jenis Pembayaran</label>
                                                <select name="payment_type" required class="w-full rounded-md border-gray-300 px-3 py-2 text-xs focus:border-yellow-400 focus:ring-yellow-400">
                                                    <option value="dp">DP</option>
                                                    <option value="cicilan">Cicilan</option>
                                                    <option value="pelunasan">Pelunasan</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-600">Nominal Diterima</label>
                                                <input
                                                    type="number"
                                                    name="amount"
                                                    min="1"
                                                    max="{{ (int) $remaining }}"
                                                    step="1"
                                                    required
                                                    placeholder="Masukkan nominal yang diterima"
                                                    class="w-full rounded-md border-gray-300 px-3 py-2 text-xs focus:border-yellow-400 focus:ring-yellow-400"
                                                >
                                                <p class="mt-1 text-[11px] text-gray-400">Maksimal Rp{{ number_format($remaining, 0, ',', '.') }}</p>
                                            </div>

                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-600">Catatan / Bukti (Opsional)</label>
                                                <input
                                                    type="text"
                                                    name="notes"
                                                    maxlength="1000"
                                                    placeholder="Contoh: diterima tunai di kantor"
                                                    class="w-full rounded-md border-gray-300 px-3 py-2 text-xs focus:border-yellow-400 focus:ring-yellow-400"
                                                >
                                            </div>

                                            <button type="submit" class="w-full rounded-md bg-gray-900 px-3 py-2.5 text-xs font-semibold text-white transition hover:bg-gray-700">
                                                Simpan Pembayaran Tunai
                                            </button>
                                        </form>
                                        <p class="mt-2 max-w-xs text-[11px] leading-5 text-gray-500">
                                            Transaksi akan langsung tercatat berhasil, beserta nama petugas dan waktu pencatatan.
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-500">
                                    Belum ada data jamaah.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($jamaahs->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4">
                        {{ $jamaahs->links() }}
                    </div>
                @endif
            </section>

            <p class="text-xs text-gray-500">
                Pembayaran online akan tercatat otomatis setelah dikonfirmasi Midtrans. Pembayaran langsung dicatat Karyawan setelah uang diterima.
            </p>
        </div>
    </main>
</div>
</body>
</html>
