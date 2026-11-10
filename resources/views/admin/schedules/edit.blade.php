
@extends('admin.layouts.app')

@section('title', 'Edit Jadwal')
@section('page-heading', 'Edit Jadwal')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    <div>
        <a href="{{ route('admin.schedules.index') }}"
           class="mb-3 inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800">
            &larr; Kembali ke Jadwal Keberangkatan
        </a>

        <h1 class="text-2xl font-bold text-gray-800">
            Edit Jadwal Keberangkatan
        </h1>

        <p class="mt-2 text-sm text-gray-500">
            Perbarui informasi jadwal {{ $schedule->name }}.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">
            <p class="mb-2 text-sm font-semibold text-red-700">
                Terdapat kesalahan pada data yang dimasukkan:
            </p>

            <ul class="list-inside list-disc space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">

        @csrf
        @method('PUT')

        @include('admin.schedules._form')

        <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">

            <a href="{{ route('admin.schedules.index') }}"
               class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                Batal
            </a>

            <button type="submit"
                    class="rounded-lg bg-[#FFB22C] px-7 py-3 text-sm font-semibold text-black shadow-sm hover:bg-amber-400">
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection
