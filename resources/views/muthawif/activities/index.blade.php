<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aktivitas Ibadah - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .activity-form-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            column-gap: 24px;
            row-gap: 16px;
        }
        @media (min-width: 768px) {
            .activity-form-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .activity-field-wide {
            grid-column: 1 / -1;
        }
        .activity-field-label {
            display: block;
            margin-bottom: 7px;
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.4;
        }
        .activity-form-control {
            display: block;
            width: 100%;
            min-height: 42px;
            padding: 10px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #fff;
            color: #1f2937;
            font-size: 13px;
            line-height: 1.45;
            box-shadow: 0 1px 2px rgb(15 23 42 / 5%);
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .activity-form-control::placeholder {
            color: #9ca3af;
            opacity: 1;
        }
        .activity-form-control:focus {
            border-color: #eab308;
            outline: none;
            box-shadow: 0 0 0 3px rgb(234 179 8 / 14%);
        }
        .activity-form-control:disabled {
            cursor: not-allowed;
            background: #f3f4f6;
            color: #9ca3af;
        }
        .activity-form-textarea {
            min-height: 104px;
            resize: vertical;
            padding-top: 11px;
            padding-bottom: 11px;
        }
    </style>
</head>
<body class="min-h-screen bg-[#F7F8FA] font-sans antialiased text-gray-800">
<div class="flex min-h-screen">
    @include('partials.sidebar')

    <main class="min-w-0 flex-1 p-4 sm:p-5 lg:p-6">
        <div class="mx-auto max-w-6xl">

            {{-- Page Header --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-gray-400">Aktivitas Perjalanan</p>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Aktivitas Ibadah Jamaah</h1>
                </div>
            </div>

            {{-- Notifications --}}
            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 shadow-sm">
                    <span class="mt-0.5 font-bold">✓</span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 shadow-sm">
                    <p class="font-semibold">Periksa kembali isian berikut:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Overview --}}
            <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kelompok Ditugaskan</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900">{{ $groups->count() }}</p>
                            <p class="mt-1 text-xs text-gray-400">Kelompok sebagai Muthawif utama atau pendamping</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-yellow-50 text-lg font-bold text-yellow-700">K</div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Jadwal Kegiatan</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900">{{ $activities->total() }}</p>
                            <p class="mt-1 text-xs text-gray-400">Seluruh aktivitas pada kelompok yang ditugaskan</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-lg font-bold text-blue-700">A</div>
                    </div>
                </div>
            </div>

            {{-- Assignment notice --}}
            @if ($groups->isEmpty())
                <div class="mb-6 rounded-lg border-amber-200 bg-amber-50 px-4 py-3">
                    <div class="flex items-start gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-sm font-bold text-amber-700">!</span>
                        <div>
                            <p class="text-sm font-semibold text-amber-900">Belum ada kelompok yang ditugaskan</p>
                            <p class="mt-1 text-sm leading-6 text-amber-800">
                                Formulir pembuatan kegiatan akan aktif setelah Karyawan menetapkan akun Anda sebagai Muthawif utama atau pendamping pada suatu kelompok.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Create Activity --}}
            <section class="mb-5 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-4 py-4 sm:px-5">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Buat Jadwal Kegiatan</h2>
                            <p class="mt-1 text-sm text-gray-500">Peserta akan diambil otomatis dari anggota aktif kelompok yang dipilih.</p>
                        </div> 
                    </div>
                </div>
                <form action="{{ route('muthawif.activities.store') }}" method="POST" class="p-4 sm:p-5">
                    @csrf

                    <div class="mb-5">
                        <h3 class="mb-1 text-sm font-semibold text-gray-800">Informasi Kegiatan</h3>
                        <p class="text-xs text-gray-400">Lengkapi informasi dasar kegiatan yang akan dilaksanakan.</p>
                    </div>

                    <div class="activity-form-grid">
                        {{-- Group --}}
                        <div>
                            <label for="jamaah_group_id" class="activity-field-label">
                                Kelompok <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="jamaah_group_id"
                                name="jamaah_group_id"
                                required
                                @disabled($groups->isEmpty())
                                class="activity-form-control"
                            >
                                <option value="">{{ $groups->isEmpty() ? 'Belum ada kelompok ditugaskan' : 'Pilih kelompok' }}</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}" @selected(old('jamaah_group_id') == $group->id)>
                                        {{ $group->name }} — {{ $group->schedule?->name ?? 'Jadwal belum tersedia' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Activity type --}}
                        <div>
                            <label for="activity_type" class="activity-field-label">
                                Jenis Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <select id="activity_type" name="activity_type" required class="activity-form-control">
                                <option value="">Pilih jenis kegiatan</option>
                                <option value="persiapan_ibadah" @selected(old('activity_type') === 'persiapan_ibadah')>Persiapan Ibadah</option>
                                <option value="bimbingan_ibadah" @selected(old('activity_type') === 'bimbingan_ibadah')>Bimbingan Ibadah</option>
                                <option value="istirahat" @selected(old('activity_type') === 'istirahat')>Waktu Istirahat</option>
                                <option value="evaluasi_kelompok" @selected(old('activity_type') === 'evaluasi_kelompok')>Evaluasi Kelompok</option>
                                <option value="lainnya" @selected(old('activity_type') === 'lainnya')>Lainnya</option>
                            </select>
                        </div>

                        {{-- Activity name --}}
                        <div>
                            <label for="activity_name" class="activity-field-label">
                                Nama Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="activity_name"
                                name="activity_name"
                                value="{{ old('activity_name') }}"
                                required
                                maxlength="180"
                                placeholder="Contoh: Bimbingan pelaksanaan umrah"
                                class="activity-form-control"
                            >
                        </div>

                        {{-- Date --}}
                        <div>
                            <label for="activity_date" class="activity-field-label">
                                Tanggal Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="activity_date"
                                type="date"
                                name="activity_date"
                                value="{{ old('activity_date', today()->format('Y-m-d')) }}"
                                required
                                class="activity-form-control"
                            >
                        </div>

                        {{-- Start time --}}
                        <div>
                            <label for="start_time" class="activity-field-label">Waktu Mulai</label>
                            <input
                                id="start_time"
                                type="time"
                                name="start_time"
                                value="{{ old('start_time') }}"
                                class="activity-form-control"
                            >
                        </div>

                        {{-- End time --}}
                        <div>
                            <label for="end_time" class="activity-field-label">Waktu Selesai</label>
                            <input
                                id="end_time"
                                type="time"
                                name="end_time"
                                value="{{ old('end_time') }}"
                                class="activity-form-control"
                            >
                        </div>

                        {{-- Location --}}
                        <div class="activity-field-wide">
                            <label for="location" class="activity-field-label">Lokasi</label>
                            <input
                                id="location"
                                name="location"
                                value="{{ old('location') }}"
                                maxlength="255"
                                placeholder="Contoh: Masjidil Haram / Hotel"
                                class="activity-form-control"
                            >
                        </div>

                        {{-- Description --}}
                        <div class="activity-field-wide">
                            <label for="description" class="activity-field-label">Keterangan Kegiatan</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                placeholder="Tuliskan petunjuk, susunan kegiatan, atau keterangan yang perlu diketahui anggota kelompok..."
                                class="activity-form-control activity-form-textarea"
                            >{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <button
                            type="submit"
                            @disabled($groups->isEmpty())
                            class="inline-flex items-center justify-center rounded-lg bg-[#FDD52E] px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-300 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            Simpan Jadwal Kegiatan
                        </button>
                    </div>
                </form>
            </section>

            {{-- Activity List --}}
            <section class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-4 py-4 sm:px-5">
                    <h2 class="text-base font-bold text-gray-900">Daftar Aktivitas</h2>
                    <p class="mt-1 text-sm text-gray-500">Aktivitas dari kelompok yang ditugaskan kepada Anda.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left text-sm">
                        <thead class="bg-gray-50 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Kelompok</th>
                            <th class="px-5 py-4">Kegiatan</th>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Jamaah</th>
                            <th class="px-5 py-4">Progres</th>
                            <th class="px-5 py-4">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse ($activities as $index => $activity)
                            @php
                                $participantCount = (int) $activity->participants_count;
                                $completedCount = (int) $activity->completed_count;
                                $inProgressCount = (int) $activity->in_progress_count;
                                $progressPercent = $participantCount > 0
                                    ? min(100, (int) round(($completedCount / $participantCount) * 100))
                                    : 0;
                            @endphp
                            <tr class="align-middle transition hover:bg-gray-50/80">
                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $activities->firstItem() + $index }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-800">{{ $activity->group?->name ?? '-' }}</p>
                                    <p class="mt-1 text-xs text-gray-400">{{ $activity->group?->schedule?->name ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-800">{{ $activity->activity_name }}</p>
                                    <p class="mt-1 text-xs capitalize text-gray-400">
                                        {{ str_replace('_', ' ', $activity->activity_type) }}
                                        <span class="mx-1">·</span>
                                        {{ $activity->start_time ? substr($activity->start_time, 0, 5) : 'Waktu belum ditentukan' }}
                                    </p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">{{ $activity->activity_date?->format('d M Y') ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-semibold text-gray-800">{{ $participantCount }}</span>
                                    <span class="text-xs text-gray-400">orang</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="mb-2 flex items-center justify-between gap-3 text-xs">
                                        <span class="font-semibold text-green-700">{{ $completedCount }} selesai</span>
                                        <span class="text-gray-400">{{ $progressPercent }}%</span>
                                    </div>
                                    <div class="h-1.5 w-28 overflow-hidden rounded-full bg-gray-100">
                                        <div class="h-full rounded-full bg-green-500" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-400">{{ $inProgressCount }} berlangsung</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <a href="{{ route('muthawif.activities.show', $activity) }}" class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                                        Kelola Status
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center">
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-xl text-gray-400">—</div>
                                    <p class="mt-4 font-semibold text-gray-700">Belum ada aktivitas ibadah</p>
                                    <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-gray-400">
                                        @if ($groups->isEmpty())
                                            Aktivitas akan tersedia setelah Karyawan memberikan penugasan kelompok kepada Anda.
                                        @else
                                            Pilih kelompok dan buat jadwal kegiatan melalui formulir di atas.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($activities->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4 sm:px-6">
                        {{ $activities->links() }}
                    </div>
                @endif
            </section>
        </div>
    </main>
</div>
</body>
</html>
