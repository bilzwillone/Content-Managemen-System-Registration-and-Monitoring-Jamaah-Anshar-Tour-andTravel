<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Pelaksanaan - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
<div class="flex min-h-screen">
    @include('partials.sidebar')
    <main class="min-w-0 flex-1 px-5 py-7 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-6">
            <header>
                <h1 class="text-xl font-bold text-gray-900">Laporan Pelaksanaan</h1>
                <p class="mt-1 text-sm text-gray-500">Catat kehadiran, pelaksanaan kegiatan, kendala, dan tindak lanjut kelompok yang Anda dampingi.</p>
            </header>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif
            @if (session('info'))
                <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">{{ session('info') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Periksa kembali data laporan.</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <section class="grid grid-cols-2 gap-3 xl:grid-cols-4">
                @foreach ([['Aktivitas Ditugaskan', $summary['activities']], ['Total Laporan', $summary['reports']], ['Draft', $summary['drafts']], ['Terkirim', $summary['submitted']]] as [$label, $value])
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $value }}</p>
                    </div>
                @endforeach
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-gray-900">Aktivitas yang Perlu Dilaporkan</h2>
                    <p class="mt-1 text-xs text-gray-500">Buat satu laporan untuk setiap aktivitas ibadah yang telah dilaksanakan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left text-sm">
                        <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3">Aktivitas</th><th class="px-5 py-3">Kelompok</th><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">Peserta</th><th class="px-5 py-3">Status Laporan</th><th class="px-5 py-3">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse ($activities as $activity)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-900">{{ $activity->activity_name }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ str_replace('_', ' ', $activity->activity_type) }}</p>
                                </td>
                                <td class="px-5 py-4 text-gray-700">{{ $activity->group?->name ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $activity->activity_date?->format('d M Y') ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $activity->participants_count }}</td>
                                <td class="px-5 py-4">
                                    @if ($activity->report)
                                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $activity->report->status === 'submitted' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ $activity->report->status === 'submitted' ? 'Terkirim' : 'Draft' }}</span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">Belum dibuat</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($activity->report)
                                        <a href="{{ route('muthawif.reports.show', $activity->report) }}" class="text-xs font-semibold text-gray-800 hover:text-yellow-700">Lihat laporan</a>
                                    @else
                                        <a href="{{ route('muthawif.reports.create', $activity) }}" class="inline-flex rounded-md bg-yellow-400 px-3 py-2 text-xs font-semibold text-gray-900 hover:bg-yellow-500">Buat laporan</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-12 text-center">
                                <p class="text-sm font-medium text-gray-800">Belum ada aktivitas dari kelompok yang ditugaskan.</p>
                                <p class="mt-1 text-xs text-gray-500">Aktivitas yang dibuat melalui menu Aktivitas Ibadah akan muncul di sini.</p>
                            </td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($activities->hasPages())<div class="border-t border-gray-100 px-5 py-3">{{ $activities->links() }}</div>@endif
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-gray-900">Riwayat Laporan</h2>
                    <p class="mt-1 text-xs text-gray-500">Laporan draft dan laporan yang sudah dikirim.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] text-left text-sm">
                        <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                        <tr><th class="px-5 py-3">Kegiatan</th><th class="px-5 py-3">Kelompok</th><th class="px-5 py-3">Tanggal Kegiatan</th><th class="px-5 py-3">Kehadiran</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-5 py-4 font-medium text-gray-900">{{ $report->activity?->activity_name ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $report->activity?->group?->name ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $report->activity?->activity_date?->format('d M Y') ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $report->present_count ?? '-' }} hadir / {{ $report->absent_count ?? '-' }} tidak hadir</td>
                                <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $report->status === 'submitted' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ $report->status === 'submitted' ? 'Terkirim' : 'Draft' }}</span></td>
                                <td class="px-5 py-4"><a href="{{ route('muthawif.reports.show', $report) }}" class="text-xs font-semibold text-gray-800 hover:text-yellow-700">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500">Belum ada laporan yang dibuat.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($reports->hasPages())<div class="border-t border-gray-100 px-5 py-3">{{ $reports->links() }}</div>@endif
            </section>
        </div>
    </main>
</div>
</body>
</html>
