
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">

    @php
        $packageTypes = [
            'reguler' => 'Reguler',
            'vip' => 'VIP',
            'ramadhan' => 'Ramadhan',
            'request' => 'Request',
            'plus_turki' => 'Plus Turki',
        ];

        $roomTypes = [
            'quad' => 'Quad',
            'triple' => 'Triple',
            'double' => 'Double',
            'single' => 'Single',
        ];

        $genders = [
            'laki_laki' => 'Laki-laki',
            'perempuan' => 'Perempuan',
        ];

        $maritalStatuses = [
            'menikah' => 'Menikah',
            'cerai' => 'Cerai',
            'belum_menikah' => 'Belum Menikah',
        ];

        $experiences = [
            'pertama' => 'Pertama',
            'kedua' => 'Kedua',
            'lebih_dari_2' => 'Lebih dari 2 kali',
        ];

        $educations = [
            'sd' => 'SD',
            'smp' => 'SMP',
            'sma' => 'SMA',
            'diploma' => 'Diploma',
            's1_s2_s3' => 'S1/S2/S3',
        ];

        $occupations = [
            'pns' => 'PNS',
            'swasta' => 'Swasta',
            'lainnya' => 'Lainnya',
            'pelajar' => 'Pelajar',
            'rumah_tangga' => 'Rumah Tangga',
        ];

        $information = fn ($value, $map = []) =>
            $value ? ($map[$value] ?? ucfirst(str_replace('_', ' ', $value))) : 'Belum diisi';
    @endphp

    <div class="flex min-h-screen">

        @include('partials.sidebar')

        <!-- KONTEN UTAMA -->
        <main class="min-w-0 flex-1">

            <div class="space-y-5 p-5 md:p-7">

                <!-- JUDUL DAN AKSI -->
                <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Detail Profil Jamaah
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Informasi lengkap pendaftaran dan identitas Jamaah.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">

                        <a
                            href="{{ route('karyawan.jamaahs.index') }}"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100"
                        >
                            Kembali
                        </a>

                        <a
                            href="{{ route('karyawan.jamaahs.edit', $jamaah) }}"
                            class="rounded-lg bg-[#FDD52E] px-4 py-2 text-sm font-semibold text-gray-900 transition hover:bg-yellow-400"
                        >
                            Edit Profil
                        </a>

                    </div>

                </section>


                <!-- NOTIFIKASI -->
                @if (session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif


                <!-- IDENTITAS UTAMA -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="flex flex-col justify-between gap-4 border-b border-gray-200 p-5 sm:flex-row sm:items-center">

                        <div class="flex items-center gap-4">

                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#FDD52E] text-xl font-bold text-gray-900">
                                {{ strtoupper(substr($jamaah->name, 0, 1)) }}
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ $jamaah->name }}
                                </h3>

                                <p class="mt-1 text-xs text-gray-500">
                                    ID Jamaah: #{{ $jamaah->id }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    Nomor Registrasi:
                                    {{ $jamaah->registration_number ?: 'Belum dibuat' }}
                                </p>
                            </div>

                        </div>

                        <span class="inline-flex w-fit rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                            {{ ucfirst(str_replace('_', ' ', $jamaah->status)) }}
                        </span>

                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 xl:grid-cols-3">

                        <div>
                            <p class="text-xs text-gray-400">Nomor HP</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->phone }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->email ?: 'Belum diisi' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Kantor Cabang</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->branch_office ?: 'Belum diisi' }}
                            </p>
                        </div>

                        <div class="sm:col-span-2 xl:col-span-3">
                            <p class="text-xs text-gray-400">Alamat</p>
                            <p class="mt-1 whitespace-pre-line text-sm font-medium leading-6 text-gray-800">
                                {{ $jamaah->address ?: 'Belum diisi' }}
                            </p>
                        </div>

                    </div>

                </section>


                <!-- INFORMASI PENDAFTARAN -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Informasi Pendaftaran
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Informasi paket dan rencana keberangkatan.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 xl:grid-cols-3">

                        <div>
                            <p class="text-xs text-gray-400">Paket Perjalanan</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->package?->name ?? 'Belum dipilih' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Jenis Paket Umroh</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $information($jamaah->umrah_package_type, $packageTypes) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Tipe Kamar</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $information($jamaah->room_type, $roomTypes) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Rencana Keberangkatan</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->planned_departure_date?->format('d M Y') ?? 'Belum ditentukan' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Tanggal Pendaftaran</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->registration_date?->format('d M Y') ?? 'Belum diisi' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Kantor Cabang</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->branch_office ?: 'Belum diisi' }}
                            </p>
                        </div>

                    </div>

                </section>


                <!-- IDENTITAS PRIBADI -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Identitas Pribadi
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Data kependudukan dan informasi pribadi Jamaah.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 xl:grid-cols-3">

                        <div>
                            <p class="text-xs text-gray-400">Nama Lengkap</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Nomor KTP / NIK</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->national_id ?: 'Belum diisi' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Tempat Lahir</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->birth_place ?: 'Belum diisi' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Tanggal Lahir</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->birth_date?->format('d M Y') ?? 'Belum diisi' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Nama Ayah Kandung</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->father_name ?: 'Belum diisi' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Jenis Kelamin</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $information($jamaah->gender, $genders) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Status Perkawinan</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $information($jamaah->marital_status, $maritalStatuses) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Telepon Rumah</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->home_phone ?: 'Belum diisi' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">HP Keluarga yang Dapat Dihubungi</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $jamaah->emergency_contact_phone ?: 'Belum diisi' }}
                            </p>
                        </div>

                    </div>

                </section>


                <!-- RIWAYAT DAN LATAR BELAKANG -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Riwayat dan Latar Belakang
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 xl:grid-cols-3">

                        <div>
                            <p class="text-xs text-gray-400">Pengalaman Umroh</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $information($jamaah->umrah_experience, $experiences) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Pendidikan Terakhir</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $information($jamaah->education_level, $educations) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Pekerjaan</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $information($jamaah->occupation, $occupations) }}
                            </p>
                        </div>

                        <div class="sm:col-span-2 xl:col-span-3">
                            <p class="text-xs text-gray-400">Penyakit yang Diderita</p>

                            <p class="mt-1 whitespace-pre-line text-sm font-medium leading-6 text-gray-800">
                                {{ $jamaah->medical_history ?: 'Belum diisi' }}
                            </p>
                        </div>

                    </div>

                </section>


                <!-- INFORMASI TERKAIT -->
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-bold text-gray-900">
                            Pengelolaan Jamaah
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Akses ke pengelolaan data lain yang terhubung dengan Jamaah.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 p-5 sm:grid-cols-2">

                        <a
                            href="{{ route('karyawan.documents.show', $jamaah) }}"
                            class="flex items-center justify-between rounded-lg border border-gray-200 p-4 transition hover:border-[#D8B400] hover:bg-yellow-50"
                        >
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">
                                    Dokumen Jamaah
                                </h4>

                                <p class="mt-1 text-xs text-gray-500">
                                    Kelola KTP, KK, paspor, dan dokumen lainnya.
                                </p>
                            </div>

                            <span class="text-gray-400">→</span>
                        </a>

                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4">

                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">
                                    Pembayaran Jamaah
                                </h4>

                                <p class="mt-1 text-xs text-gray-500">
                                    Pengelolaan pembayaran akan tersedia pada modul berikutnya.
                                </p>
                            </div>

                            <span class="rounded-full bg-gray-100 px-2 py-1 text-[10px] text-gray-500">
                                Segera
                            </span>

                        </div>

                    </div>

                </section>


                <!-- ASAL DATA CRM -->
                @if ($jamaah->prospect)
                    <section class="rounded-xl border border-blue-100 bg-blue-50 p-4">

                        <p class="text-sm font-semibold text-blue-800">
                            Data Asal CRM
                        </p>

                        <p class="mt-1 text-sm text-blue-700">
                            Jamaah ini berasal dari calon jamaah
                            <span class="font-semibold">
                                {{ $jamaah->prospect->name }}
                            </span>.
                        </p>

                        <a
                            href="{{ route('karyawan.prospects.show', $jamaah->prospect) }}"
                            class="mt-2 inline-block text-xs font-semibold text-blue-700 underline"
                        >
                            Lihat Riwayat CRM
                        </a>

                    </section>
                @endif


                <!-- FOOTER -->
                <footer class="border-t border-gray-200 pt-5 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Ansar Travel.
                </footer>

            </div>

        </main>

    </div>

</body>
</html>
