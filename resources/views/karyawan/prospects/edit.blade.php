
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Calon Jamaah - Ansar Travel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="sticky top-0 hidden h-screen w-64 shrink-0 self-start flex-col bg-[#171717] text-white md:flex">

            <div class="shrink-0 border-b border-white/10 px-5 py-5">
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/ansar-travel-logo.png') }}"
                        alt="Logo Ansar Travel"
                        class="h-11 w-11 object-contain"
                    >

                    <div>
                        <h1 class="text-sm font-bold text-[#FDD52E]">
                            ANSAR TRAVEL
                        </h1>
                        <p class="mt-1 text-xs text-gray-400">
                            Management System
                        </p>
                    </div>
                </div>
            </div>

            <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto px-3 py-5">

                <p class="mb-3 px-3 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                    Menu Utama
                </p>

                <a
                    href="{{ route('karyawan.dashboard') }}"
                    class="block rounded-lg px-4 py-3 text-sm text-gray-300 transition hover:bg-white/10 hover:text-white"
                >
                    Dashboard
                </a>

                <p class="mb-3 mt-7 px-3 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                    Manajemen Jamaah
                </p>

                <a
                    href="{{ route('karyawan.prospects.index') }}"
                    class="block rounded-lg bg-[#FDD52E] px-4 py-3 text-sm font-semibold text-gray-900"
                >
                    Calon Jamaah & CRM
                </a>

                <div class="rounded-lg px-4 py-3 text-sm text-gray-400">
                    Data Jamaah <span class="ml-2 text-[10px]">Segera</span>
                </div>

                <div class="rounded-lg px-4 py-3 text-sm text-gray-400">
                    Dokumen Jamaah <span class="ml-2 text-[10px]">Segera</span>
                </div>

                <p class="mb-3 mt-7 px-3 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                    Operasional Travel
                </p>

                <div class="rounded-lg px-4 py-3 text-sm text-gray-400">
                    Pembayaran <span class="ml-2 text-[10px]">Segera</span>
                </div>

                <div class="rounded-lg px-4 py-3 text-sm text-gray-400">
                    Paket Perjalanan <span class="ml-2 text-[10px]">Segera</span>
                </div>

                <div class="rounded-lg px-4 py-3 text-sm text-gray-400">
                    Persiapan Keberangkatan <span class="ml-2 text-[10px]">Segera</span>
                </div>

                <div class="rounded-lg px-4 py-3 text-sm text-gray-400">
                    Kelompok & Muthawif <span class="ml-2 text-[10px]">Segera</span>
                </div>

            </nav>

            <!-- PROFIL DAN LOGOUT -->
            <div class="shrink-0 border-t border-white/10 px-4 py-4">

                <div class="mb-3 flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 p-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FDD52E] font-bold text-gray-900">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-gray-400">Karyawan</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-gray-300 transition hover:bg-red-500/10 hover:text-red-400"
                    >
                        Logout
                    </button>
                </form>

            </div>

        </aside>


        <!-- KONTEN UTAMA -->
        <main class="min-w-0 flex-1">

            <div class="space-y-5 p-5 md:p-7">

                <!-- JUDUL -->
                <section class="flex items-center justify-between gap-4">

                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            Edit Calon Jamaah
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Perbarui informasi dan status calon jamaah.
                        </p>
                    </div>

                    <a
                        href="{{ route('karyawan.prospects.index') }}"
                        class="shrink-0 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-100"
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
                    action="{{ route('karyawan.prospects.update', $prospect) }}"
                    class="space-y-5"
                >
                    @csrf
                    @method('PUT')


                    <!-- INFORMASI CALON JAMAAH -->
                    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 px-5 py-4">
                            <h3 class="font-bold text-gray-900">
                                Informasi Calon Jamaah
                            </h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Perbarui data identitas dan kontak calon jamaah.
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
                                    value="{{ old('name', $prospect->name) }}"
                                    required
                                    maxlength="255"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
                            </div>

                            <!-- TELEPON -->
                            <div>
                                <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $prospect->phone) }}"
                                    required
                                    maxlength="30"
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
                                    value="{{ old('email', $prospect->email) }}"
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
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >{{ old('address', $prospect->address) }}</textarea>
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
                                Kelola paket, status, PIC, dan jadwal tindak lanjut.
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
                                            @selected(old('package_id', $prospect->package_id) == $package->id)
                                        >
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                    <option value="baru" @selected(old('status', $prospect->status) === 'baru')>
                                        Baru
                                    </option>

                                    <option value="dihubungi" @selected(old('status', $prospect->status) === 'dihubungi')>
                                        Dihubungi
                                    </option>

                                    <option value="follow_up" @selected(old('status', $prospect->status) === 'follow_up')>
                                        Follow-up
                                    </option>

                                    <option value="tertarik" @selected(old('status', $prospect->status) === 'tertarik')>
                                        Tertarik
                                    </option>

                                    <option value="mendaftar" @selected(old('status', $prospect->status) === 'mendaftar')>
                                        Mendaftar
                                    </option>

                                    <option value="tidak_tertarik" @selected(old('status', $prospect->status) === 'tidak_tertarik')>
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
                                        Tidak ada PIC
                                    </option>

                                    @foreach ($employees as $employee)
                                        <option
                                            value="{{ $employee->id }}"
                                            @selected(old('pic_id', $prospect->pic_id) == $employee->id)
                                        >
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- JADWAL FOLLOW UP -->
                            <div>
                                <label for="next_follow_up_at" class="mb-2 block text-sm font-medium text-gray-700">
                                    Jadwal Follow-up Berikutnya
                                </label>

                                <input
                                    type="datetime-local"
                                    id="next_follow_up_at"
                                    name="next_follow_up_at"
                                    value="{{ old('next_follow_up_at', $prospect->next_follow_up_at?->format('Y-m-d\TH:i')) }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >
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
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-[#D8B400] focus:ring-2 focus:ring-yellow-100"
                                >{{ old('notes', $prospect->notes) }}</textarea>
                            </div>

                        </div>

                    </section>


                    <!-- TOMBOL -->
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
                            Simpan Perubahan
                        </button>

                    </div>

                </form>


                <footer class="border-t border-gray-200 pt-5 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Ansar Travel.
                </footer>

            </div>

        </main>

    </div>

</body>
</html>
