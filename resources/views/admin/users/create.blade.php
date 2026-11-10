
@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')

<div class="mx-auto max-w-3xl space-y-5">

    <!-- HEADER -->
    <div>
        <a href="{{ route('admin.users.index') }}"
           class="mb-3 inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900">
            &larr; Kembali ke Manajemen Pengguna
        </a>

        <h1 class="text-2xl font-bold text-gray-900">
            Tambah Pengguna
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Masukkan informasi akun dan tentukan role pengguna.
        </p>
    </div>

    <!-- ERROR VALIDASI -->
    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-3">
            <ul class="list-inside list-disc text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM -->
    <form action="{{ route('admin.users.store') }}" method="POST"
          class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        @csrf

        <div class="space-y-5 p-5">

            <!-- INFORMASI AKUN -->
            <div>
                <h2 class="text-base font-bold text-gray-800">
                    Informasi Akun
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    Lengkapi identitas pengguna.
                </p>
            </div>

            <!-- NAMA DAN EMAIL -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">
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
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                    >

                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        maxlength="255"
                        placeholder="contoh@email.com"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                    >

                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- ROLE -->
            <div>
                <label for="role" class="mb-1.5 block text-sm font-medium text-gray-700">
                    Role Pengguna <span class="text-red-500">*</span>
                </label>

                <select
                    id="role"
                    name="role"
                    required
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100">

                    <option value="">Pilih Role</option>

                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                    <option value="karyawan" {{ old('role') === 'karyawan' ? 'selected' : '' }}>
                        Karyawan
                    </option>

                    <option value="muthawif" {{ old('role') === 'muthawif' ? 'selected' : '' }}>
                        Muthawif
                    </option>

                    <option value="jamaah" {{ old('role') === 'jamaah' ? 'selected' : '' }}>
                        Jamaah
                    </option>

                </select>

                @error('role')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- PEMBATAS -->
            <div class="border-t border-gray-100 pt-4">
                <h2 class="text-base font-bold text-gray-800">
                    Keamanan Akun
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    Buat password untuk akun pengguna.
                </p>
            </div>

            <!-- PASSWORD -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Password <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Masukkan password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                    >

                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                    >
                </div>

            </div>

        </div>

        <!-- TOMBOL -->
        <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4">

            <a href="{{ route('admin.users.index') }}"
               class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100">
                Batal
            </a>

            <button type="submit"
                    class="rounded-lg bg-[#FDD52E] px-5 py-2.5 text-sm font-bold text-gray-900 hover:bg-amber-400">
                Simpan Pengguna
            </button>

        </div>

    </form>

</div>

@endsection
