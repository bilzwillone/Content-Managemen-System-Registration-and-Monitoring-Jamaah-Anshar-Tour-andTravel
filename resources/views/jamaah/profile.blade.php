<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Ansar Travel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
<div class="flex min-h-screen">
    @include('partials.sidebar')
    <main class="min-w-0 flex-1 p-5 lg:p-8">
        <div class="mx-auto max-w-5xl space-y-6">
            <header><h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1><p class="mt-1 text-sm text-gray-500">Periksa dan perbarui informasi pribadi yang digunakan untuk pelayanan perjalanan.</p></header>
            @if (session('success'))<div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>@endif
            @if ($errors->any())<div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('jamaah.profile.update') }}" method="POST" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                @csrf @method('PUT')
                <div class="border-b border-gray-100 px-5 py-4"><h2 class="font-semibold text-gray-800">Data Pribadi</h2></div>
                <div class="grid grid-cols-1 gap-x-5 gap-y-4 p-5 md:grid-cols-2">
                    @php $fields = [
                        ['name','Nama Lengkap','text',$jamaah->name],
                        ['phone','Nomor HP','text',$jamaah->phone],
                        ['national_id','NIK','text',$jamaah->national_id],
                        ['birth_place','Tempat Lahir','text',$jamaah->birth_place],
                        ['birth_date','Tanggal Lahir','date',$jamaah->birth_date?->format('Y-m-d')],
                        ['father_name','Nama Ayah','text',$jamaah->father_name],
                        ['home_phone','Nomor Telepon Rumah','text',$jamaah->home_phone],
                        ['emergency_contact_phone','Nomor Kontak Darurat','text',$jamaah->emergency_contact_phone],
                    ]; @endphp
                    @foreach ($fields as [$field, $label, $type, $value])
                        <div><label for="{{ $field }}" class="mb-1.5 block text-sm font-medium text-gray-700">{{ $label }}</label><input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}" value="{{ old($field, $value) }}" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:ring-yellow-400"></div>
                    @endforeach
                    <div><label class="mb-1.5 block text-sm font-medium text-gray-700">Email Akun</label><input type="email" value="{{ auth()->user()->email }}" disabled class="w-full rounded-lg border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-500"><p class="mt-1 text-xs text-gray-400">Email login tidak diubah dari formulir profil.</p></div>
                    <div><label for="gender" class="mb-1.5 block text-sm font-medium text-gray-700">Jenis Kelamin</label><select id="gender" name="gender" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm"><option value="">Pilih</option><option value="laki_laki" @selected(old('gender', $jamaah->gender) === 'laki_laki')>Laki-laki</option><option value="perempuan" @selected(old('gender', $jamaah->gender) === 'perempuan')>Perempuan</option></select></div>
                    <div><label for="marital_status" class="mb-1.5 block text-sm font-medium text-gray-700">Status Pernikahan</label><select id="marital_status" name="marital_status" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm"><option value="">Pilih</option>@foreach (['belum_menikah'=>'Belum Menikah','menikah'=>'Menikah','cerai'=>'Cerai'] as $v=>$l)<option value="{{ $v }}" @selected(old('marital_status', $jamaah->marital_status) === $v)>{{ $l }}</option>@endforeach</select></div>
                    <div><label for="education_level" class="mb-1.5 block text-sm font-medium text-gray-700">Pendidikan</label><select id="education_level" name="education_level" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm"><option value="">Pilih</option>@foreach (['sd'=>'SD','smp'=>'SMP','sma'=>'SMA','diploma'=>'Diploma','s1_s2_s3'=>'S1/S2/S3'] as $v=>$l)<option value="{{ $v }}" @selected(old('education_level', $jamaah->education_level) === $v)>{{ $l }}</option>@endforeach</select></div>
                    <div><label for="occupation" class="mb-1.5 block text-sm font-medium text-gray-700">Pekerjaan</label><select id="occupation" name="occupation" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm"><option value="">Pilih</option>@foreach (['pns'=>'PNS','swasta'=>'Swasta','lainnya'=>'Lainnya','pelajar'=>'Pelajar','rumah_tangga'=>'Rumah Tangga'] as $v=>$l)<option value="{{ $v }}" @selected(old('occupation', $jamaah->occupation) === $v)>{{ $l }}</option>@endforeach</select></div>
                    <div class="md:col-span-2"><label for="address" class="mb-1.5 block text-sm font-medium text-gray-700">Alamat</label><textarea id="address" name="address" rows="3" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:ring-yellow-400">{{ old('address', $jamaah->address) }}</textarea></div>
                    <div class="md:col-span-2"><label for="medical_history" class="mb-1.5 block text-sm font-medium text-gray-700">Riwayat Kesehatan / Catatan Medis</label><textarea id="medical_history" name="medical_history" rows="3" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-yellow-400 focus:ring-yellow-400">{{ old('medical_history', $jamaah->medical_history) }}</textarea></div>
                </div>
                <div class="flex justify-end border-t border-gray-100 bg-gray-50 px-5 py-4"><button class="rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-gray-900 hover:bg-yellow-500">Simpan Profil</button></div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
