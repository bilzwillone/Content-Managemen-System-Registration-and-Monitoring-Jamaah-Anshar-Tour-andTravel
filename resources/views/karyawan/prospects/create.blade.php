
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Calon Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        @include('partials.sidebar')

        <!-- KONTEN UTAMA -->
        <main class="min-w-0 flex-1">

            <div class="space-y-5 p-5 md:p-7">

               <!-- JUDUL HALAMAN -->
                <section class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Tambah Calon Jamaah
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Isi informasi calon jamaah.
                        </p>
                    </div>

                    <a
                        href="{{ route('karyawan.prospects.index') }}"
                        class="shrink-0 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-600 hover:bg-gray-100"
                    >
                        Kembali
                    </a>
                </section>
                
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


                <!-- FORM -->
                <form
                    method="POST"
                    action="{{ route('karyawan.prospects.store') }}"
                    class="space-y-5"
                >
                    @csrf

                    <!-- DATA PRIBADI -->
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-5 py-4">
                            <h3 class="font-bold text-gray-900">
                                Informasi Calon Jamaah
                            </h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Data identitas dan informasi kontak calon jamaah.
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
                                    value="{{ old('name') }}"
                                    required
                                    maxlength="255"
                                    placeholder="Masukkan nama lengkap"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- NOMOR TELEPON -->
                            <div>
                                <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    maxlength="30"
                                    placeholder="Contoh: 081234567890"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- EMAIL -->
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- ALAMAT -->
                            <div>
                                <label for="address" class="mb-2 block text-sm font-medium text-gray-700">
                                    Alamat
                                </label>

                                <textarea
                                    id="address"
                                    name="address"
                                    rows="3"
                                    placeholder="Masukkan alamat calon jamaah"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >{{ old('address') }}</textarea>
                            </div>

                        </div>

                    </section>


                    <!-- INFORMASI PROSPEK -->
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-5 py-4">
                            <h3 class="font-bold text-gray-900">
                                Informasi Prospek
                            </h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Tentukan paket yang diminati dan penanggung jawab calon jamaah.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

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
                                            @selected(old('package_id') == $package->id)
                                        >
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @if ($packages->isEmpty())
                                    <p class="mt-2 text-xs text-amber-600">
                                        Belum ada paket yang dipublikasikan.
                                    </p>
                                @endif
                            </div>

                            <!-- STATUS -->
                            <div>
                                <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                                    Status Prospek <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="status"
                                    name="status"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="baru" @selected(old('status', 'baru') === 'baru')>
                                        Baru
                                    </option>

                                    <option value="dihubungi" @selected(old('status') === 'dihubungi')>
                                        Dihubungi
                                    </option>

                                    <option value="follow_up" @selected(old('status') === 'follow_up')>
                                        Follow-up
                                    </option>

                                    <option value="tertarik" @selected(old('status') === 'tertarik')>
                                        Tertarik
                                    </option>

                                    <option value="mendaftar" @selected(old('status') === 'mendaftar')>
                                        Mendaftar
                                    </option>

                                    <option value="tidak_tertarik" @selected(old('status') === 'tidak_tertarik')>
                                        Tidak Tertarik
                                    </option>
                                </select>
                            </div>

                            <!-- PIC -->
                            <div>
                                <label for="pic_id" class="mb-2 block text-sm font-medium text-gray-700">
                                    Karyawan Penanggung Jawab (PIC)
                                </label>

                                <select
                                    id="pic_id"
                                    name="pic_id"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                                    <option value="">
                                        Tetapkan kepada saya
                                    </option>

                                    @foreach ($employees as $employee)
                                        <option
                                            value="{{ $employee->id }}"
                                            @selected(old('pic_id', auth()->id()) == $employee->id)
                                        >
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- FOLLOW UP -->
                            <div>
                                <label for="next_follow_up_at" class="mb-2 block text-sm font-medium text-gray-700">
                                    Jadwal Follow-up Berikutnya
                                </label>

                                <input
                                    type="datetime-local"
                                    id="next_follow_up_at"
                                    name="next_follow_up_at"
                                    value="{{ old('next_follow_up_at') }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >

                                <p class="mt-1 text-xs text-gray-400">
                                    Opsional, dapat diisi ketika sudah ada kesepakatan tindak lanjut.
                                </p>
                            </div>

                            <!-- CATATAN -->
                            <div class="md:col-span-2">
                                <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">
                                    Catatan
                                </label>

                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="4"
                                    placeholder="Informasi tambahan mengenai calon jamaah..."
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >{{ old('notes') }}</textarea>
                            </div>

                        </div>

                    </section>


                    <!-- TOMBOL AKSI -->
                    <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">

                        <a
                            href="{{ route('karyawan.prospects.index') }}"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-100"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="rounded-lg bg-[#FDD52E] px-6 py-3 text-sm font-bold text-gray-900 transition hover:bg-yellow-400"
                        >
                            Simpan Calon Jamaah
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
