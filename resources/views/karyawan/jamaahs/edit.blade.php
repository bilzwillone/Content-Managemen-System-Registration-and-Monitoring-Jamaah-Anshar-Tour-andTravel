
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Profil Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        @include('partials.sidebar')

        <!-- KONTEN UTAMA -->
        <main class="min-w-0 flex-1">

            <div class="space-y-5 p-5 md:p-7">

                <!-- JUDUL -->
                <section class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Edit Profil Jamaah
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Perbarui informasi pendaftaran dan identitas Jamaah.
                        </p>
                    </div>

                    <a
                        href="{{ route('karyawan.jamaahs.show', $jamaah) }}"
                        class="w-fit rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100"
                    >
                        Kembali
                    </a>

                </section>


                <!-- ERROR VALIDASI -->
                @if ($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                        <p class="text-sm font-semibold text-red-700">
                            Terdapat kesalahan pada pengisian formulir:
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <!-- FORM EDIT -->
                <form
                    method="POST"
                    action="{{ route('karyawan.jamaahs.update', $jamaah) }}"
                    class="space-y-5"
                >
                    @csrf
                    @method('PUT')


                    <!-- A. INFORMASI PENDAFTARAN -->
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-5 py-4">
                            <h3 class="font-bold text-gray-900">
                                A. Informasi Pendaftaran
                            </h3>

                            <p class="mt-1 text-xs text-gray-500">
                                Informasi registrasi dan paket perjalanan yang dipilih.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <!-- KANTOR CABANG -->
                            <div>
                                <label for="branch_office" class="mb-2 block text-sm font-medium text-gray-700">
                                    Kantor Cabang
                                </label>

                                <input
                                    type="text"
                                    id="branch_office"
                                    name="branch_office"
                                    value="{{ old('branch_office', $jamaah->branch_office) }}"
                                    placeholder="Contoh: Padang"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- NOMOR REGISTRASI -->
                            <div>
                                <label for="registration_number" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nomor Registrasi
                                </label>

                                <input
                                    type="text"
                                    id="registration_number"
                                    name="registration_number"
                                    value="{{ old('registration_number', $jamaah->registration_number) }}"
                                    placeholder="Masukkan nomor registrasi"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- PAKET -->
                            <div>
                                <label for="package_id" class="mb-2 block text-sm font-medium text-gray-700">
                                    Paket Perjalanan
                                </label>

                                <select
                                    id="package_id"
                                    name="package_id"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="">Pilih paket perjalanan</option>

                                    @foreach ($packages as $package)
                                        <option
                                            value="{{ $package->id }}"
                                            @selected(old('package_id', $jamaah->package_id) == $package->id)
                                        >
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- JENIS PAKET -->
                            <div>
                                <label for="umrah_package_type" class="mb-2 block text-sm font-medium text-gray-700">
                                    Jenis Paket Umroh
                                </label>

                                <select
                                    id="umrah_package_type"
                                    name="umrah_package_type"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="">Pilih jenis paket</option>
                                    <option value="reguler" @selected(old('umrah_package_type', $jamaah->umrah_package_type) === 'reguler')>Reguler</option>
                                    <option value="vip" @selected(old('umrah_package_type', $jamaah->umrah_package_type) === 'vip')>VIP</option>
                                    <option value="ramadhan" @selected(old('umrah_package_type', $jamaah->umrah_package_type) === 'ramadhan')>Ramadhan</option>
                                    <option value="request" @selected(old('umrah_package_type', $jamaah->umrah_package_type) === 'request')>Request</option>
                                    <option value="plus_turki" @selected(old('umrah_package_type', $jamaah->umrah_package_type) === 'plus_turki')>Plus Turki</option>
                                </select>
                            </div>

                            <!-- TIPE KAMAR -->
                            <div>
                                <label for="room_type" class="mb-2 block text-sm font-medium text-gray-700">
                                    Tipe Kamar
                                </label>

                                <select
                                    id="room_type"
                                    name="room_type"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="">Pilih tipe kamar</option>
                                    <option value="quad" @selected(old('room_type', $jamaah->room_type) === 'quad')>Quad</option>
                                    <option value="triple" @selected(old('room_type', $jamaah->room_type) === 'triple')>Triple</option>
                                    <option value="double" @selected(old('room_type', $jamaah->room_type) === 'double')>Double</option>
                                    <option value="single" @selected(old('room_type', $jamaah->room_type) === 'single')>Single</option>
                                </select>
                            </div>

                            <!-- TANGGAL KEBERANGKATAN -->
                            <div>
                                <label for="planned_departure_date" class="mb-2 block text-sm font-medium text-gray-700">
                                    Rencana Tanggal Keberangkatan
                                </label>

                                <input
                                    type="date"
                                    id="planned_departure_date"
                                    name="planned_departure_date"
                                    value="{{ old('planned_departure_date', $jamaah->planned_departure_date?->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- TANGGAL PENDAFTARAN -->
                            <div>
                                <label for="registration_date" class="mb-2 block text-sm font-medium text-gray-700">
                                    Tanggal Pendaftaran <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="date"
                                    id="registration_date"
                                    name="registration_date"
                                    value="{{ old('registration_date', $jamaah->registration_date?->format('Y-m-d')) }}"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                        </div>

                    </section>


                    <!-- B. DATA IDENTITAS -->
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-5 py-4">
                            <h3 class="font-bold text-gray-900">
                                B. Data Identitas Jamaah
                            </h3>

                            <p class="mt-1 text-xs text-gray-500">
                                Identitas pribadi sesuai kartu identitas dan dokumen resmi.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <!-- NAMA -->
                            <div>
                                <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $jamaah->name) }}"
                                    required
                                    maxlength="255"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- NIK -->
                            <div>
                                <label for="national_id" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nomor KTP / NIK
                                </label>

                                <input
                                    type="text"
                                    id="national_id"
                                    name="national_id"
                                    value="{{ old('national_id', $jamaah->national_id) }}"
                                    maxlength="30"
                                    placeholder="Masukkan nomor KTP / NIK"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- TEMPAT LAHIR -->
                            <div>
                                <label for="birth_place" class="mb-2 block text-sm font-medium text-gray-700">
                                    Tempat Lahir
                                </label>

                                <input
                                    type="text"
                                    id="birth_place"
                                    name="birth_place"
                                    value="{{ old('birth_place', $jamaah->birth_place) }}"
                                    maxlength="100"
                                    placeholder="Masukkan tempat lahir"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- TANGGAL LAHIR -->
                            <div>
                                <label for="birth_date" class="mb-2 block text-sm font-medium text-gray-700">
                                    Tanggal Lahir
                                </label>

                                <input
                                    type="date"
                                    id="birth_date"
                                    name="birth_date"
                                    value="{{ old('birth_date', $jamaah->birth_date?->format('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- NAMA AYAH -->
                            <div>
                                <label for="father_name" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nama Ayah Kandung
                                </label>

                                <input
                                    type="text"
                                    id="father_name"
                                    name="father_name"
                                    value="{{ old('father_name', $jamaah->father_name) }}"
                                    maxlength="255"
                                    placeholder="Masukkan nama ayah kandung"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- JENIS KELAMIN -->
                            <div>
                                <label for="gender" class="mb-2 block text-sm font-medium text-gray-700">
                                    Jenis Kelamin
                                </label>

                                <select
                                    id="gender"
                                    name="gender"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="laki_laki" @selected(old('gender', $jamaah->gender) === 'laki_laki')>Laki-laki</option>
                                    <option value="perempuan" @selected(old('gender', $jamaah->gender) === 'perempuan')>Perempuan</option>
                                </select>
                            </div>

                            <!-- STATUS PERKAWINAN -->
                            <div>
                                <label for="marital_status" class="mb-2 block text-sm font-medium text-gray-700">
                                    Status Perkawinan
                                </label>

                                <select
                                    id="marital_status"
                                    name="marital_status"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="">Pilih status perkawinan</option>
                                    <option value="menikah" @selected(old('marital_status', $jamaah->marital_status) === 'menikah')>Menikah</option>
                                    <option value="cerai" @selected(old('marital_status', $jamaah->marital_status) === 'cerai')>Cerai</option>
                                    <option value="belum_menikah" @selected(old('marital_status', $jamaah->marital_status) === 'belum_menikah')>Belum Menikah</option>
                                </select>
                            </div>

                            <!-- ALAMAT -->
                            <div class="md:col-span-2">
                                <label for="address" class="mb-2 block text-sm font-medium text-gray-700">
                                    Alamat Lengkap
                                </label>

                                <textarea
                                    id="address"
                                    name="address"
                                    rows="3"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >{{ old('address', $jamaah->address) }}</textarea>
                            </div>

                        </div>

                    </section>


                    <!-- C. KONTAK -->
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-5 py-4">
                            <h3 class="font-bold text-gray-900">
                                C. Informasi Kontak
                            </h3>

                            <p class="mt-1 text-xs text-gray-500">
                                Nomor telepon dan informasi keluarga yang dapat dihubungi.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <div>
                                <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nomor HP / WhatsApp <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $jamaah->phone) }}"
                                    required
                                    maxlength="30"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                                    Alamat Email
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $jamaah->email) }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <div>
                                <label for="home_phone" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nomor Telepon Rumah
                                </label>

                                <input
                                    type="tel"
                                    id="home_phone"
                                    name="home_phone"
                                    value="{{ old('home_phone', $jamaah->home_phone) }}"
                                    maxlength="30"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <div>
                                <label for="emergency_contact_phone" class="mb-2 block text-sm font-medium text-gray-700">
                                    HP Keluarga yang Dapat Dihubungi
                                </label>

                                <input
                                    type="tel"
                                    id="emergency_contact_phone"
                                    name="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone', $jamaah->emergency_contact_phone) }}"
                                    maxlength="30"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                        </div>

                    </section>


                    <!-- D. RIWAYAT DAN LATAR BELAKANG -->
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-5 py-4">
                            <h3 class="font-bold text-gray-900">
                                D. Riwayat dan Latar Belakang
                            </h3>

                            <p class="mt-1 text-xs text-gray-500">
                                Informasi pengalaman Umroh, pendidikan, dan pekerjaan.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <div>
                                <label for="umrah_experience" class="mb-2 block text-sm font-medium text-gray-700">
                                    Pengalaman Umroh
                                </label>

                                <select
                                    id="umrah_experience"
                                    name="umrah_experience"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="">Pilih pengalaman Umroh</option>
                                    <option value="pertama" @selected(old('umrah_experience', $jamaah->umrah_experience) === 'pertama')>Pertama</option>
                                    <option value="kedua" @selected(old('umrah_experience', $jamaah->umrah_experience) === 'kedua')>Kedua</option>
                                    <option value="lebih_dari_2" @selected(old('umrah_experience', $jamaah->umrah_experience) === 'lebih_dari_2')>Lebih dari 2 kali</option>
                                </select>
                            </div>

                            <div>
                                <label for="education_level" class="mb-2 block text-sm font-medium text-gray-700">
                                    Pendidikan Terakhir
                                </label>

                                <select
                                    id="education_level"
                                    name="education_level"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="">Pilih pendidikan terakhir</option>
                                    <option value="sd" @selected(old('education_level', $jamaah->education_level) === 'sd')>SD</option>
                                    <option value="smp" @selected(old('education_level', $jamaah->education_level) === 'smp')>SMP</option>
                                    <option value="sma" @selected(old('education_level', $jamaah->education_level) === 'sma')>SMA</option>
                                    <option value="diploma" @selected(old('education_level', $jamaah->education_level) === 'diploma')>Diploma</option>
                                    <option value="s1_s2_s3" @selected(old('education_level', $jamaah->education_level) === 's1_s2_s3')>S1/S2/S3</option>
                                </select>
                            </div>

                            <div>
                                <label for="occupation" class="mb-2 block text-sm font-medium text-gray-700">
                                    Pekerjaan
                                </label>

                                <select
                                    id="occupation"
                                    name="occupation"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="">Pilih pekerjaan</option>
                                    <option value="pns" @selected(old('occupation', $jamaah->occupation) === 'pns')>PNS</option>
                                    <option value="swasta" @selected(old('occupation', $jamaah->occupation) === 'swasta')>Swasta</option>
                                    <option value="lainnya" @selected(old('occupation', $jamaah->occupation) === 'lainnya')>Lainnya</option>
                                    <option value="pelajar" @selected(old('occupation', $jamaah->occupation) === 'pelajar')>Pelajar</option>
                                    <option value="rumah_tangga" @selected(old('occupation', $jamaah->occupation) === 'rumah_tangga')>Rumah Tangga</option>
                                </select>
                            </div>

                        </div>

                    </section>


                    <!-- E. INFORMASI KESEHATAN -->
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-5 py-4">
                            <h3 class="font-bold text-gray-900">
                                E. Informasi Kesehatan
                            </h3>

                            <p class="mt-1 text-xs text-gray-500">
                                Informasi kesehatan yang perlu diketahui oleh pihak travel.
                            </p>
                        </div>

                        <div class="p-5">

                            <label for="medical_history" class="mb-2 block text-sm font-medium text-gray-700">
                                Penyakit yang Diderita
                            </label>

                            <textarea
                                id="medical_history"
                                name="medical_history"
                                rows="4"
                                placeholder="Tuliskan riwayat penyakit atau informasi kesehatan yang perlu diperhatikan..."
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                            >{{ old('medical_history', $jamaah->medical_history) }}</textarea>

                        </div>

                    </section>


                    <!-- TOMBOL AKSI -->
                    <div class="flex flex-col-reverse justify-end gap-3 border-t border-gray-200 pt-5 sm:flex-row">

                        <a
                            href="{{ route('karyawan.jamaahs.show', $jamaah) }}"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-100"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="rounded-lg bg-[#FDD52E] px-6 py-3 text-sm font-bold text-gray-900 transition hover:bg-yellow-400"
                        >
                            Simpan Perubahan
                        </button>

                    </div>

                </form>


                <!-- FOOTER -->
                <footer class="border-t border-gray-200 pt-5 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Ansar Travel.
                </footer>

            </div>

        </main>

    </div>

</body>
</html>
