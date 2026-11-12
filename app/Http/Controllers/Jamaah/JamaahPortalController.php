<?php

namespace App\Http\Controllers\Jamaah;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\JamaahDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class JamaahPortalController extends Controller
{
    public function dashboard()
    {
        $jamaah = $this->currentJamaah();

        if (!$jamaah) {
            return view('jamaah.dashboard', [
                'jamaah' => null,
                'paidTotal' => 0,
                'remainingBill' => null,
                'billIsSet' => false,
                'verifiedDocuments' => 0,
                'documentCount' => 0,
                'groups' => collect(),
                'recentPayments' => collect(),
            ]);
        }

        $jamaah->load(['package', 'documents', 'payments']);
        $groups = $this->activeGroups($jamaah);

        $paidTotal = $this->paidTotal($jamaah);
        [$billIsSet, $bill] = $this->resolveBill($jamaah);

        return view('jamaah.dashboard', [
            'jamaah' => $jamaah,
            'paidTotal' => $paidTotal,
            'remainingBill' => $billIsSet ? max($bill - $paidTotal, 0) : null,
            'billIsSet' => $billIsSet,
            'verifiedDocuments' => $jamaah->documents->where('status', 'terverifikasi')->count(),
            'documentCount' => $jamaah->documents->count(),
            'groups' => $groups,
            'recentPayments' => $jamaah->payments->sortByDesc('created_at')->take(5),
        ]);
    }

    public function profile()
    {
        $jamaah = $this->requireJamaah();

        return view('jamaah.profile', compact('jamaah'));
    }

    public function updateProfile(Request $request)
    {
        $jamaah = $this->requireJamaah();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:2000'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:laki_laki,perempuan'],
            'marital_status' => ['nullable', 'in:menikah,cerai,belum_menikah'],
            'home_phone' => ['nullable', 'string', 'max:30'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'education_level' => ['nullable', 'in:sd,smp,sma,diploma,s1_s2_s3'],
            'occupation' => ['nullable', 'in:pns,swasta,lainnya,pelajar,rumah_tangga'],
            'medical_history' => ['nullable', 'string', 'max:2000'],
        ]);

        $jamaah->update($validated);

        return redirect()->route('jamaah.profile')->with('success', 'Data profil berhasil diperbarui.');
    }

    /** Menampilkan status dan formulir unggah dokumen. */
    public function documents()
    {
        $jamaah = $this->requireJamaah();

        // Gunakan identitas dokumen yang sama dengan modul Karyawan
        // dan proses konversi Prospek menjadi Jamaah.
        $templates = [
            'ktp' => 'Kartu Tanda Penduduk (KTP)',
            'kartu_keluarga' => 'Kartu Keluarga (KK)',
            'pas_foto' => 'Pas Foto',
            'paspor' => 'Paspor',
            'vaksin_meningitis' => 'Surat Vaksin Meningitis',
            'surat_polio' => 'Surat Polio',
        ];

        foreach ($templates as $type => $name) {
            $jamaah->documents()->firstOrCreate(
                ['document_type' => $type],
                ['document_name' => $name, 'status' => 'belum_diupload']
            );
        }

        $documents = $jamaah->documents()->orderBy('id')->get();

        return view('jamaah.documents', compact('jamaah', 'documents'));
    }

    /** Mengunggah atau mengganti dokumen milik jamaah yang sedang login. */
    public function uploadDocument(Request $request, JamaahDocument $document)
    {
        $jamaah = $this->requireJamaah();
        $document = $jamaah->documents()->whereKey($document->id)->firstOrFail();

        $validated = $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $file = $validated['document'];

        DB::transaction(function () use ($jamaah, $document, $file) {
            if ($document->file_path) {
                Storage::disk('local')->delete($document->file_path);
            }

            $path = $file->store('jamaah-documents/' . $jamaah->id, 'local');

            $document->update([
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'status' => 'menunggu_verifikasi',
                'notes' => null,
                'uploaded_by' => auth()->id(),
                'verified_by' => null,
                'uploaded_at' => now(),
                'verified_at' => null,
            ]);
        });

        return redirect()->route('jamaah.documents')->with('success', 'Dokumen berhasil diunggah dan menunggu pemeriksaan Karyawan.');
    }

    /** Menampilkan riwayat pembayaran milik jamaah. */
    public function payments()
    {
        $jamaah = $this->requireJamaah();
        $jamaah->load(['package', 'payments']);
        $paidTotal = $this->paidTotal($jamaah);
        [$billIsSet, $bill] = $this->resolveBill($jamaah);
        $payments = $jamaah->payments()->latest()->paginate(10);

        return view('jamaah.payments', [
            'jamaah' => $jamaah,
            'payments' => $payments,
            'paidTotal' => $paidTotal,
            'billIsSet' => $billIsSet,
            'totalBill' => $billIsSet ? $bill : null,
            'remainingBill' => $billIsSet ? max($bill - $paidTotal, 0) : null,
        ]);
    }

    /** Menampilkan informasi keberangkatan, kelompok, Muthawif dan akomodasi. */
    public function travel()
    {
        $jamaah = $this->requireJamaah();
        $groups = $this->activeGroups($jamaah);

        return view('jamaah.travel', compact('jamaah', 'groups'));
    }

    /**
     * Ambil data jamaah yang terhubung dengan akun.
     * Jika belum terhubung, tautkan berdasarkan email hanya setelah email akun diverifikasi.
     * Pencocokan ambigu tidak pernah dilakukan otomatis.
     */
    private function currentJamaah(): ?Jamaah
    {
        $user = auth()->user();

        $jamaah = Jamaah::where('user_id', $user->id)->first();
        if ($jamaah) {
            return $jamaah;
        }

        if (!$user->email_verified_at || !$user->email) {
            return null;
        }

        return DB::transaction(function () use ($user) {
            // Hitung semua baris dengan email yang sama, termasuk yang sudah
            // terhubung ke akun lain, agar pencocokan ganda tidak salah taut.
            $matches = Jamaah::query()
                ->whereRaw('LOWER(email) = ?', [mb_strtolower(trim($user->email))])
                ->lockForUpdate()
                ->limit(2)
                ->get();

            if ($matches->count() !== 1 || $matches->first()->user_id !== null) {
                return null;
            }

            $jamaah = $matches->first();
            $jamaah->user_id = $user->id;
            $jamaah->save();

            return $jamaah->fresh();
        });
    }

    private function requireJamaah(): Jamaah
    {
        $jamaah = $this->currentJamaah();

        if (!$jamaah) {
            abort(403, 'Akun Anda belum terhubung dengan data jamaah. Pastikan email akun sama dengan email pada data pendaftaran, sudah terverifikasi, atau hubungi Karyawan Ansar Travel.');
        }

        return $jamaah;
    }

    private function activeGroups(Jamaah $jamaah)
    {
        return $jamaah->groups()
            ->wherePivot('status', 'aktif')
            ->with(['schedule.departurePreparation.roomAssignments', 'muthawifs'])
            ->get();
    }

    private function paidTotal(Jamaah $jamaah): float
    {
        return (float) $jamaah->payments()
            ->whereIn('status', ['paid', 'settlement', 'capture', 'success'])
            ->sum('amount');
    }

    /** @return array{0: bool, 1: float} */
    private function resolveBill(Jamaah $jamaah): array
    {
        if ($jamaah->total_bill !== null) {
            return [true, (float) $jamaah->total_bill];
        }

        if ($jamaah->package && $jamaah->package->price !== null) {
            return [true, (float) $jamaah->package->price];
        }

        return [false, 0.0];
    }
}
