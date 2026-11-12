<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buat Laporan Pelaksanaan - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
<div class="flex min-h-screen">
    @include('partials.sidebar')
    <main class="min-w-0 flex-1 px-5 py-7 lg:px-8">
        <div class="mx-auto max-w-4xl space-y-6">
            <header>
                <a href="{{ route('muthawif.reports.index') }}" class="text-xs font-medium text-gray-500 hover:text-gray-900">← Kembali ke Laporan</a>
                <h1 class="mt-3 text-xl font-bold text-gray-900">Buat Laporan Pelaksanaan</h1>
                <p class="mt-1 text-sm text-gray-500">Catat kehadiran dan hasil pelaksanaan kegiatan kelompok.</p>
            </header>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Periksa kembali data laporan.</p>
                    <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-gray-900">Informasi Kegiatan</h2>
                </div>
                <div class="grid grid-cols-1 gap-4 px-5 py-4 sm:grid-cols-2">
                    <div><p class="text-xs text-gray-500">Nama Kegiatan</p><p class="mt-1 text-sm font-medium text-gray-900">{{ $activity->activity_name }}</p></div>
                    <div><p class="text-xs text-gray-500">Kelompok</p><p class="mt-1 text-sm font-medium text-gray-900">{{ $activity->group?->name ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Tanggal</p><p class="mt-1 text-sm text-gray-800">{{ $activity->activity_date?->format('d M Y') }}</p></div>
                    <div><p class="text-xs text-gray-500">Total Peserta</p><p class="mt-1 text-sm font-semibold text-gray-900">{{ $activity->participants->count() }} jamaah</p></div>
                    <div><p class="text-xs text-gray-500">Waktu</p><p class="mt-1 text-sm text-gray-800">{{ $activity->start_time ? substr($activity->start_time, 0, 5) : '-' }} – {{ $activity->end_time ? substr($activity->end_time, 0, 5) : '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Lokasi</p><p class="mt-1 text-sm text-gray-800">{{ $activity->location ?: '-' }}</p></div>
                </div>
            </section>

            <form action="{{ route('muthawif.reports.store', $activity) }}" method="POST" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                @csrf
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-gray-900">Catatan Pelaksanaan</h2>
                    <p class="mt-1 text-xs text-gray-500">Laporan dapat disimpan sebagai draft terlebih dahulu.</p>
                </div>
                <div class="space-y-5 p-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="present_count" class="mb-1.5 block text-xs font-semibold text-gray-700">Jumlah Hadir <span class="text-red-500">*</span></label>
                            <input id="present_count" type="number" name="present_count" min="0" max="{{ $activity->participants->count() }}" value="{{ old('present_count') }}" placeholder="Masukkan jumlah hadir" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100">
                        </div>
                        <div>
                            <label for="absent_count" class="mb-1.5 block text-xs font-semibold text-gray-700">Jumlah Tidak Hadir <span class="text-red-500">*</span></label>
                            <input id="absent_count" type="number" name="absent_count" min="0" max="{{ $activity->participants->count() }}" value="{{ old('absent_count') }}" placeholder="Masukkan jumlah tidak hadir" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100">
                        </div>
                    </div>
                    <p class="-mt-3 text-xs text-gray-500">Jumlah hadir dan tidak hadir harus sesuai dengan total {{ $activity->participants->count() }} peserta ketika laporan dikirim.</p>

                    <div>
                        <label for="implementation_notes" class="mb-1.5 block text-xs font-semibold text-gray-700">Catatan Pelaksanaan <span class="text-red-500">*</span></label>
                        <textarea id="implementation_notes" name="implementation_notes" rows="4" placeholder="Jelaskan kegiatan yang telah dilaksanakan..." class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100">{{ old('implementation_notes') }}</textarea>
                    </div>
                    <div>
                        <label for="issues" class="mb-1.5 block text-xs font-semibold text-gray-700">Kendala (Opsional)</label>
                        <textarea id="issues" name="issues" rows="3" placeholder="Catat kendala selama kegiatan, jika ada..." class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100">{{ old('issues') }}</textarea>
                    </div>
                    <div>
                        <label for="follow_up" class="mb-1.5 block text-xs font-semibold text-gray-700">Tindak Lanjut (Opsional)</label>
                        <textarea id="follow_up" name="follow_up" rows="3" placeholder="Tuliskan tindak lanjut yang diperlukan..." class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100">{{ old('follow_up') }}</textarea>
                    </div>
                </div>
                <div class="flex flex-wrap justify-end gap-2 border-t border-gray-100 bg-gray-50 px-5 py-4">
                    <a href="{{ route('muthawif.reports.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" name="action" value="draft" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">Simpan Draft</button>
                    <button type="submit" name="action" value="submit" class="rounded-lg bg-yellow-400 px-4 py-2.5 text-xs font-semibold text-gray-900 hover:bg-yellow-500">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
