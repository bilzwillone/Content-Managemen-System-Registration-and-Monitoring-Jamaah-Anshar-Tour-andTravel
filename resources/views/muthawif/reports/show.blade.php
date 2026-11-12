<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Laporan Pelaksanaan - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
<div class="flex min-h-screen">
    @include('partials.sidebar')
    <main class="min-w-0 flex-1 px-5 py-7 lg:px-8">
        <div class="mx-auto max-w-4xl space-y-6">
            <header>
                <a href="{{ route('muthawif.reports.index') }}" class="text-xs font-medium text-gray-500 hover:text-gray-900">← Kembali ke Laporan</a>
                <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Detail Laporan Pelaksanaan</h1>
                        <p class="mt-1 text-sm text-gray-500">{{ $report->activity?->activity_name ?? '-' }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1.5 text-xs font-semibold {{ $report->status === 'submitted' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ $report->status === 'submitted' ? 'Terkirim' : 'Draft' }}</span>
                </div>
            </header>

            @if (session('success'))<div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>@endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><p class="font-semibold">Periksa kembali data laporan.</p><ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4"><h2 class="text-sm font-semibold text-gray-900">Informasi Kegiatan</h2></div>
                <div class="grid grid-cols-1 gap-4 px-5 py-4 sm:grid-cols-2">
                    <div><p class="text-xs text-gray-500">Kelompok</p><p class="mt-1 text-sm font-medium text-gray-900">{{ $report->activity?->group?->name ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Jadwal Perjalanan</p><p class="mt-1 text-sm font-medium text-gray-900">{{ $report->activity?->group?->schedule?->name ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Tanggal Kegiatan</p><p class="mt-1 text-sm text-gray-800">{{ $report->activity?->activity_date?->format('d M Y') ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Lokasi</p><p class="mt-1 text-sm text-gray-800">{{ $report->activity?->location ?: '-' }}</p></div>
                </div>
            </section>

            @if ($report->status === 'draft')
                <form action="{{ route('muthawif.reports.update', $report) }}" method="POST" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    @csrf
                    @method('PUT')
                    <div class="border-b border-gray-100 px-5 py-4"><h2 class="text-sm font-semibold text-gray-900">Lanjutkan Draft Laporan</h2></div>
                    <div class="space-y-5 p-5">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div><label for="present_count" class="mb-1.5 block text-xs font-semibold text-gray-700">Jumlah Hadir</label><input id="present_count" type="number" name="present_count" min="0" max="{{ $report->activity?->participants->count() ?? 0 }}" value="{{ old('present_count', $report->present_count) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100"></div>
                            <div><label for="absent_count" class="mb-1.5 block text-xs font-semibold text-gray-700">Jumlah Tidak Hadir</label><input id="absent_count" type="number" name="absent_count" min="0" max="{{ $report->activity?->participants->count() ?? 0 }}" value="{{ old('absent_count', $report->absent_count) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100"></div>
                        </div>
                        <p class="-mt-3 text-xs text-gray-500">Total peserta aktivitas: {{ $report->activity?->participants->count() ?? 0 }} jamaah. Jumlah hadir dan tidak hadir harus sesuai sebelum dikirim.</p>
                        <div><label for="implementation_notes" class="mb-1.5 block text-xs font-semibold text-gray-700">Catatan Pelaksanaan</label><textarea id="implementation_notes" name="implementation_notes" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100">{{ old('implementation_notes', $report->implementation_notes) }}</textarea></div>
                        <div><label for="issues" class="mb-1.5 block text-xs font-semibold text-gray-700">Kendala</label><textarea id="issues" name="issues" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100">{{ old('issues', $report->issues) }}</textarea></div>
                        <div><label for="follow_up" class="mb-1.5 block text-xs font-semibold text-gray-700">Tindak Lanjut</label><textarea id="follow_up" name="follow_up" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-100">{{ old('follow_up', $report->follow_up) }}</textarea></div>
                    </div>
                    <div class="flex flex-wrap justify-end gap-2 border-t border-gray-100 bg-gray-50 px-5 py-4">
                        <button type="submit" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">Simpan Perubahan</button>
                    </div>
                </form>
                <form action="{{ route('muthawif.reports.submit', $report) }}" method="POST" onsubmit="return confirm('Kirim laporan ini? Laporan yang sudah dikirim tidak dapat diubah.')" class="flex justify-end">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="rounded-lg bg-yellow-400 px-5 py-2.5 text-xs font-semibold text-gray-900 hover:bg-yellow-500">Kirim Laporan</button>
                </form>
            @else
                <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4"><h2 class="text-sm font-semibold text-gray-900">Hasil Pelaksanaan</h2></div>
                    <div class="grid grid-cols-2 gap-3 p-5">
                        <div class="rounded-lg bg-green-50 p-4"><p class="text-xs text-green-700">Hadir</p><p class="mt-1 text-2xl font-semibold text-green-800">{{ $report->present_count }}</p></div>
                        <div class="rounded-lg bg-red-50 p-4"><p class="text-xs text-red-700">Tidak Hadir</p><p class="mt-1 text-2xl font-semibold text-red-800">{{ $report->absent_count }}</p></div>
                    </div>
                    <div class="space-y-5 px-5 pb-5">
                        <div><p class="text-xs font-semibold text-gray-500">Catatan Pelaksanaan</p><p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-800">{{ $report->implementation_notes ?: '-' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500">Kendala</p><p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-800">{{ $report->issues ?: '-' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500">Tindak Lanjut</p><p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-800">{{ $report->follow_up ?: '-' }}</p></div>
                    </div>
                    <div class="border-t border-gray-100 px-5 py-3 text-xs text-gray-500">Dikirim {{ $report->submitted_at?->format('d M Y, H:i') ?? '-' }} · Dibuat oleh {{ $report->creator?->name ?? 'Pengguna' }}</div>
                </section>
            @endif
        </div>
    </main>
</div>
</body>
</html>
