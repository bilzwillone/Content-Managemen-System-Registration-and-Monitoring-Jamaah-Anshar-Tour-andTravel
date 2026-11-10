
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Calon Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">

    @php
        $statusStyles = [
            'baru' => ['label' => 'Baru', 'class' => 'bg-blue-50 text-blue-700'],
            'dihubungi' => ['label' => 'Dihubungi', 'class' => 'bg-indigo-50 text-indigo-700'],
            'follow_up' => ['label' => 'Follow-up', 'class' => 'bg-amber-50 text-amber-700'],
            'tertarik' => ['label' => 'Tertarik', 'class' => 'bg-green-50 text-green-700'],
            'mendaftar' => ['label' => 'Mendaftar', 'class' => 'bg-purple-50 text-purple-700'],
            'tidak_tertarik' => ['label' => 'Tidak Tertarik', 'class' => 'bg-red-50 text-red-700'],
        ];

        $statusInfo = $statusStyles[$prospect->status] ?? [
            'label' => ucfirst($prospect->status),
            'class' => 'bg-gray-100 text-gray-700',
        ];

        $channelLabels = [
            'whatsapp' => 'WhatsApp',
            'phone' => 'Telepon',
            'email' => 'Email',
            'meeting' => 'Pertemuan',
            'other' => 'Lainnya',
        ];
    @endphp

    <div class="flex min-h-screen">

        @include('partials.sidebar')

        <main class="min-w-0 flex-1">

            <div class="space-y-5 p-5 md:p-7">

                <!-- JUDUL HALAMAN -->
                <section class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Detail Calon Jamaah
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Informasi prospek dan riwayat komunikasi.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a
                            href="{{ route('karyawan.prospects.index') }}"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-100"
                        >
                            Kembali
                        </a>

                        <a
                            href="{{ route('karyawan.prospects.edit', $prospect) }}"
                            class="rounded-lg bg-[#FDD52E] px-4 py-2 text-sm font-semibold text-gray-900 transition hover:bg-yellow-400"
                        >
                            Edit Data
                        </a>
                            @if ($prospect->status === 'mendaftar' && !$prospect->jamaah)
                                <form
                                    method="POST"
                                    action="{{ route('karyawan.prospects.convert', $prospect) }}"
                                    onsubmit="return confirm('Konversi calon jamaah ini menjadi data Jamaah?')"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700"
                                    >
                                        Konversi Menjadi Jamaah
                                    </button>
                                </form>
                            @elseif ($prospect->jamaah)
                                <span class="rounded-lg bg-green-50 px-4 py-2 text-sm font-semibold text-green-700">
                                    Sudah Menjadi Jamaah
                                </span>
                            @endif
                    </div>

                </section>


                <!-- NOTIFIKASI -->
                @if (session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                        <p class="text-sm font-semibold text-red-700">
                            Periksa kembali formulir follow-up:
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <!-- INFORMASI UTAMA -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="flex flex-col justify-between gap-3 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center">

                        <div>
                            <h3 class="font-bold text-gray-900">
                                {{ $prospect->name }}
                            </h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Terdaftar {{ $prospect->created_at->format('d M Y') }}
                            </p>
                        </div>

                        <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusInfo['class'] }}">
                            {{ $statusInfo['label'] }}
                        </span>

                    </div>

                    <div class="grid grid-cols-1 gap-x-8 gap-y-5 p-5 sm:grid-cols-2 xl:grid-cols-3">

                        <div>
                            <p class="text-xs text-gray-400">Nomor Telepon / WhatsApp</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $prospect->phone }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $prospect->email ?: 'Belum diisi' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Paket Diminati</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $prospect->package?->name ?? 'Belum dipilih' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Penanggung Jawab (PIC)</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $prospect->pic?->name ?? 'Belum ditentukan' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Dibuat Oleh</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $prospect->creator?->name ?? 'Tidak diketahui' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Follow-up Berikutnya</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $prospect->next_follow_up_at?->format('d M Y, H:i') ?? 'Belum dijadwalkan' }}
                            </p>
                        </div>

                        <div class="sm:col-span-2 xl:col-span-3">
                            <p class="text-xs text-gray-400">Alamat</p>
                            <p class="mt-1 text-sm leading-6 text-gray-800">
                                {{ $prospect->address ?: 'Belum diisi' }}
                            </p>
                        </div>

                        <div class="sm:col-span-2 xl:col-span-3">
                            <p class="text-xs text-gray-400">Catatan</p>
                            <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-800">
                                {{ $prospect->notes ?: 'Belum ada catatan.' }}
                            </p>
                        </div>

                    </div>

                </section>


                <!-- FORM FOLLOW-UP -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Catat Aktivitas Follow-up
                        </h3>
                        <p class="mt-1 text-xs text-gray-500">
                            Tambahkan riwayat komunikasi dengan calon jamaah.
                        </p>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('karyawan.prospects.follow-ups.store', $prospect) }}"
                        class="space-y-5 p-5"
                    >
                        @csrf

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            <!-- MEDIA -->
                            <div>
                                <label for="channel" class="mb-2 block text-sm font-medium text-gray-700">
                                    Media Komunikasi <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="channel"
                                    name="channel"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="whatsapp" @selected(old('channel', 'whatsapp') === 'whatsapp')>
                                        WhatsApp
                                    </option>
                                    <option value="phone" @selected(old('channel') === 'phone')>
                                        Telepon
                                    </option>
                                    <option value="email" @selected(old('channel') === 'email')>
                                        Email
                                    </option>
                                    <option value="meeting" @selected(old('channel') === 'meeting')>
                                        Pertemuan Langsung
                                    </option>
                                    <option value="other" @selected(old('channel') === 'other')>
                                        Lainnya
                                    </option>
                                </select>
                            </div>

                            <!-- WAKTU AKTIVITAS -->
                            <div>
                                <label for="activity_at" class="mb-2 block text-sm font-medium text-gray-700">
                                    Waktu Aktivitas <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="datetime-local"
                                    id="activity_at"
                                    name="activity_at"
                                    value="{{ old('activity_at', now()->format('Y-m-d\TH:i')) }}"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- RINGKASAN -->
                            <div class="md:col-span-2">
                                <label for="summary" class="mb-2 block text-sm font-medium text-gray-700">
                                    Ringkasan Komunikasi <span class="text-red-500">*</span>
                                </label>

                                <textarea
                                    id="summary"
                                    name="summary"
                                    rows="3"
                                    required
                                    placeholder="Contoh: Menghubungi calon jamaah untuk menjelaskan informasi paket umroh..."
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >{{ old('summary') }}</textarea>
                            </div>

                            <!-- CATATAN -->
                            <div class="md:col-span-2">
                                <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">
                                    Catatan Tambahan
                                </label>

                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="2"
                                    placeholder="Catatan tambahan mengenai hasil komunikasi..."
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >{{ old('notes') }}</textarea>
                            </div>

                            <!-- JADWAL BERIKUTNYA -->
                            <div>
                                <label for="next_follow_up_at" class="mb-2 block text-sm font-medium text-gray-700">
                                    Jadwal Follow-up Selanjutnya
                                </label>

                                <input
                                    type="datetime-local"
                                    id="next_follow_up_at"
                                    name="next_follow_up_at"
                                    value="{{ old('next_follow_up_at') }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >

                                <p class="mt-1 text-xs text-gray-400">
                                    Kosongkan apabila belum ada jadwal berikutnya.
                                </p>
                            </div>

                        </div>

                        <div class="flex justify-end border-t border-gray-100 pt-4">
                            <button
                                type="submit"
                                class="rounded-lg bg-[#FDD52E] px-6 py-3 text-sm font-bold text-gray-900 transition hover:bg-yellow-400"
                            >
                                Simpan Aktivitas
                            </button>
                        </div>

                    </form>

                </section>


                <!-- RIWAYAT FOLLOW-UP -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Riwayat Follow-up
                        </h3>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ $prospect->followUps->count() }} aktivitas komunikasi tercatat.
                        </p>
                    </div>

                    <div class="p-5">

                        @forelse ($prospect->followUps->sortByDesc('activity_at') as $followUp)

                            <div class="relative border-l-2 border-gray-200 pb-6 pl-5 last:border-transparent last:pb-0">

                                <span class="absolute -left-[7px] top-1 h-3 w-3 rounded-full border-2 border-white bg-[#D8B400]"></span>

                                <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-start">

                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">
                                            {{ $channelLabels[$followUp->channel] ?? ucfirst($followUp->channel) }}
                                        </h4>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $followUp->activity_at?->format('d M Y, H:i') }}
                                            &middot;
                                            {{ $followUp->user?->name ?? 'Pengguna tidak diketahui' }}
                                        </p>
                                    </div>

                                    <span class="text-xs text-gray-400">
                                        Aktivitas #{{ $followUp->id }}
                                    </span>

                                </div>

                                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700">
                                    {{ $followUp->summary }}
                                </p>

                                @if ($followUp->notes)
                                    <div class="mt-3 rounded-lg bg-gray-50 p-3">
                                        <p class="text-xs font-semibold text-gray-500">
                                            Catatan
                                        </p>
                                        <p class="mt-1 whitespace-pre-line text-sm text-gray-700">
                                            {{ $followUp->notes }}
                                        </p>
                                    </div>
                                @endif

                                @if ($followUp->next_follow_up_at)
                                    <p class="mt-3 text-xs text-amber-700">
                                        Follow-up berikutnya:
                                        {{ $followUp->next_follow_up_at->format('d M Y, H:i') }}
                                    </p>
                                @endif

                            </div>

                        @empty

                            <div class="py-8 text-center">
                                <p class="text-sm font-medium text-gray-700">
                                    Belum ada riwayat follow-up.
                                </p>
                                <p class="mt-1 text-xs text-gray-400">
                                    Aktivitas komunikasi yang dicatat akan muncul di bagian ini.
                                </p>
                            </div>

                        @endforelse

                    </div>

                </section>


                <!-- FOOTER -->
                <footer class="border-t border-gray-200 pt-5 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Ansar Travel.
                </footer>

            </div>

        </main>

    </div>

</body>
</html>
