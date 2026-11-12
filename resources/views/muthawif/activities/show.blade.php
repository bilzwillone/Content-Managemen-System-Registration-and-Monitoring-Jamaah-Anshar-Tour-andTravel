<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aktivitas Ibadah - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
<div class="flex min-h-screen">
    @include('partials.sidebar')
    <main class="min-w-0 flex-1 p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <a href="{{ route('muthawif.activities.index') }}" class="mb-4 inline-flex text-sm font-medium text-gray-500 hover:text-gray-800">← Kembali ke Aktivitas Ibadah</a>
            <div class="mb-7">
                <h1 class="text-2xl font-bold text-gray-800">{{ $activity->activity_name }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $activity->group?->name }} · {{ $activity->activity_date?->format('d M Y') }} · {{ $activity->location ?: 'Lokasi belum ditentukan' }}</p>
            </div>

            @if (session('success'))<div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-5 py-3 text-sm text-green-700">{{ session('success') }}</div>@endif
            @if ($errors->any())<div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700"><ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

            <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Total Jamaah</p><p class="mt-2 text-2xl font-bold text-gray-800">{{ $summary['total'] }}</p></div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Belum Dilaksanakan</p><p class="mt-2 text-2xl font-bold text-gray-600">{{ $summary['belum'] }}</p></div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Sedang Berlangsung</p><p class="mt-2 text-2xl font-bold text-orange-600">{{ $summary['proses'] }}</p></div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><p class="text-sm text-gray-500">Selesai</p><p class="mt-2 text-2xl font-bold text-green-600">{{ $summary['selesai'] }}</p></div>
            </div>

            <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold text-gray-800">Informasi Kegiatan</h2>
                <div class="mt-4 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2 lg:grid-cols-3">
                    <div><p class="text-xs text-gray-400">Jenis Kegiatan</p><p class="mt-1 font-medium text-gray-800">{{ str_replace('_', ' ', ucfirst($activity->activity_type)) }}</p></div>
                    <div><p class="text-xs text-gray-400">Waktu</p><p class="mt-1 font-medium text-gray-800">{{ $activity->start_time ? substr($activity->start_time, 0, 5) : '-' }} – {{ $activity->end_time ? substr($activity->end_time, 0, 5) : '-' }}</p></div>
                    <div><p class="text-xs text-gray-400">Jadwal Perjalanan</p><p class="mt-1 font-medium text-gray-800">{{ $activity->group?->schedule?->name ?? '-' }}</p></div>
                    @if ($activity->description)<div class="sm:col-span-2 lg:col-span-3"><p class="text-xs text-gray-400">Keterangan</p><p class="mt-1 text-gray-700">{{ $activity->description }}</p></div>@endif
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-5"><h2 class="font-semibold text-gray-800">Pemantauan Kegiatan Jamaah</h2><p class="mt-1 text-sm text-gray-500">Perbarui status pelaksanaan dan catatan pendampingan untuk setiap jamaah.</p></div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500"><tr><th class="px-6 py-4">No.</th><th class="px-6 py-4">Nama Jamaah</th><th class="px-6 py-4">Nomor HP</th><th class="px-6 py-4">Status Pelaksanaan</th><th class="px-6 py-4">Catatan Pendampingan</th><th class="px-6 py-4">Aksi</th></tr></thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse ($activity->participants as $index => $participant)
                            <tr class="align-top hover:bg-gray-50">
                                <td class="px-6 py-5 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-5"><p class="font-semibold text-gray-800">{{ $participant->jamaah?->name ?? '-' }}</p><p class="mt-1 text-xs text-gray-400">{{ $participant->updater?->name ? 'Diperbarui oleh '.$participant->updater->name : 'Belum diperbarui' }}</p></td>
                                <td class="px-6 py-5 text-gray-600">{{ $participant->jamaah?->phone ?? '-' }}</td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusLabels = ['belum_dilaksanakan' => 'Belum Dilaksanakan', 'sedang_berlangsung' => 'Sedang Berlangsung', 'selesai_dilaksanakan' => 'Selesai Dilaksanakan'];
                                        $statusStyles = ['belum_dilaksanakan' => 'bg-gray-100 text-gray-600', 'sedang_berlangsung' => 'bg-orange-50 text-orange-700', 'selesai_dilaksanakan' => 'bg-green-50 text-green-700'];
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$participant->status] ?? 'bg-gray-100 text-gray-600' }}">{{ $statusLabels[$participant->status] ?? $participant->status }}</span>
                                </td>
                                <td class="px-6 py-5 text-gray-600">{{ $participant->assistance_notes ?: '-' }}</td>
                                <td class="px-6 py-5">
                                    <form action="{{ route('muthawif.activities.participants.update', [$activity, $participant]) }}" method="POST" class="w-64 space-y-2">
                                        @csrf
                                        @method('PATCH')
                                        <label class="block text-xs font-medium text-gray-600">Ubah Status</label>
                                        <select name="status" required class="w-full rounded-lg border-gray-300 text-xs focus:border-yellow-400 focus:ring-yellow-400">
                                            <option value="belum_dilaksanakan" @selected($participant->status === 'belum_dilaksanakan')>Belum Dilaksanakan</option>
                                            <option value="sedang_berlangsung" @selected($participant->status === 'sedang_berlangsung')>Sedang Berlangsung</option>
                                            <option value="selesai_dilaksanakan" @selected($participant->status === 'selesai_dilaksanakan')>Selesai Dilaksanakan</option>
                                        </select>
                                        <textarea name="assistance_notes" rows="2" maxlength="5000" placeholder="Catatan kondisi atau kebutuhan jamaah..." class="w-full rounded-lg border-gray-300 text-xs focus:border-yellow-400 focus:ring-yellow-400">{{ $participant->assistance_notes }}</textarea>
                                        <button type="submit" class="w-full rounded-lg bg-yellow-400 px-3 py-2 text-xs font-semibold text-gray-900 hover:bg-yellow-500">Simpan Status</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada jamaah aktif dalam kelompok ini.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 px-6 py-4"><p class="text-xs text-gray-500">Status dicatat per jamaah. Catatan pendampingan hanya dapat dilihat oleh Muthawif yang ditugaskan pada kelompok ini.</p></div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
