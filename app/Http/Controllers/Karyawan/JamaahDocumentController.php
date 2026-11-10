<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\JamaahDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JamaahDocumentController extends Controller
{
    private const REQUIRED_DOCUMENTS = [
        'ktp' => 'KTP',
        'kartu_keluarga' => 'Kartu Keluarga (KK)',
        'pas_foto' => 'Pas Foto',
        'paspor' => 'Paspor',
        'vaksin_meningitis' => 'Surat Vaksin Meningitis',
        'surat_polio' => 'Surat Polio',
    ];

    public function index(Request $request)
    {
        $query = Jamaah::with('package')
            ->withCount([
                'documents as uploaded_documents_count' => function ($q) {
                    $q->whereNotNull('file_path');
                },
                'documents as verified_documents_count' => function ($q) {
                    $q->where('status', 'terverifikasi');
                },
            ])
            ->latest('registration_date');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $jamaahs = $query->paginate(10)->withQueryString();

        $totalJamaahs = Jamaah::count();

        $totalDocuments = JamaahDocument::count();

        $verifiedDocuments = JamaahDocument::where(
            'status',
            'terverifikasi'
        )->count();

        return view('karyawan.documents.index', compact(
            'jamaahs',
            'totalJamaahs',
            'totalDocuments',
            'verifiedDocuments'
        ));
    }

    public function show(Jamaah $jamaah)
    {
        // Membuat checklist enam dokumen jika belum tersedia.
        foreach (self::REQUIRED_DOCUMENTS as $type => $name) {
            JamaahDocument::firstOrCreate(
                [
                    'jamaah_id' => $jamaah->id,
                    'document_type' => $type,
                ],
                [
                    'document_name' => $name,
                    'status' => 'belum_diupload',
                ]
            );
        }

        $jamaah->load('package');

        $documents = $jamaah->documents()
            ->with(['uploader', 'verifier'])
            ->orderBy('id')
            ->get();

        return view('karyawan.documents.show', compact(
            'jamaah',
            'documents'
        ));
    }

    public function upload(
        Request $request,
        Jamaah $jamaah,
        JamaahDocument $document
    ) {
        abort_unless($document->jamaah_id === $jamaah->id, 404);

        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $file = $request->file('file');

        if ($document->file_path) {
            Storage::disk('local')->delete($document->file_path);
        }

        $path = $file->store(
            'jamaah-documents/' . $jamaah->id,
            'local'
        );

        $document->update([
            'file_path' => $path,
            'original_name' => substr(
                $file->getClientOriginalName(),
                0,
                255
            ),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'menunggu_verifikasi',
            'notes' => null,
            'uploaded_by' => auth()->id(),
            'verified_by' => null,
            'uploaded_at' => now(),
            'verified_at' => null,
        ]);

        return back()->with(
            'success',
            'Dokumen berhasil diunggah dan menunggu verifikasi.'
        );
    }

    public function verify(
        Request $request,
        Jamaah $jamaah,
        JamaahDocument $document
    ) {
        abort_unless($document->jamaah_id === $jamaah->id, 404);

        abort_if(
            !$document->file_path ||
            !Storage::disk('local')->exists($document->file_path),
            422,
            'Berkas dokumen belum tersedia.'
        );

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'terverifikasi',
                    'perlu_perbaikan',
                ]),
            ],
            'notes' => [
                Rule::requiredIf(
                    $request->input('status') === 'perlu_perbaikan'
                ),
                'nullable',
                'string',
            ],
        ]);

        $document->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with(
            'success',
            'Status dokumen berhasil diperbarui.'
        );
    }

    public function download(
        Jamaah $jamaah,
        JamaahDocument $document
    ) {
        abort_unless($document->jamaah_id === $jamaah->id, 404);

        abort_if(
            !$document->file_path ||
            !Storage::disk('local')->exists($document->file_path),
            404,
            'Berkas dokumen tidak ditemukan.'
        );

        return Storage::disk('local')->download(
            $document->file_path,
            $document->original_name ?? 'dokumen-jamaah'
        );
    }
}
