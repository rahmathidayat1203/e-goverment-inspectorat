<?php

namespace App\Http\Controllers;

use App\Models\FileSyaratPengajuan;
use App\Models\pegawai;
use App\Models\pengajuan_sbt;
use App\Models\Progresberkas;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PengajuanSbtController extends Controller
{

    public function index(request $request)
    {
        try {
            $perpage = $request->get('per_page', 10);
            $pengajuansbt = pengajuan_sbt::paginate($perpage);

            return response()->json([
                'success' => true,
                'massage' => 'data fetch successfully',
                'data' => $pengajuansbt
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'id_user' => 'required',
                'status' => 'required',
                'alasan_penolakan' => 'required',
                'tanggal_pengajuan' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ], 403);
            }

            $input = $request->all();
            $input['created_by'] = 1;

            pengajuan_sbt::create($input);
            return response()->json([
                'success' => false,
                'message' => 'created successfully',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            Log::info('error list' . $e->getMessage());
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Eager load file_syarat_pengajuan
            $pengajuan_sbt = pengajuan_sbt::with('fileSyaratPengajuan')->findOrFail($id);

            // Transform file_syarat_pengajuan into dokumen_pendukung array
            $dokumenPendukung = [];
            $baseUrl = config('app.url') . '/storage/';
            if ($pengajuan_sbt->fileSyaratPengajuan) {
                $files = $pengajuan_sbt->fileSyaratPengajuan;
                if ($files->rekomendasi_kepala_perangkat_biro) {
                    $dokumenPendukung[] = [
                        'id' => '1',
                        'nama' => 'Rekomendasi Kepala Perangkat Biro',
                        'url' => $baseUrl . $files->rekomendasi_kepala_perangkat_biro,
                    ];
                }
                if ($files->surat_keterangan_terkait_permasalahan) {
                    $dokumenPendukung[] = [
                        'id' => '2',
                        'nama' => 'Surat Keterangan Terkait Permasalahan',
                        'url' => $baseUrl . $files->surat_keterangan_terkait_permasalahan,
                    ];
                }
                if ($files->keputusan_pangkat_terakhir) {
                    $dokumenPendukung[] = [
                        'id' => '3',
                        'nama' => 'Keputusan Pangkat Terakhir',
                        'url' => $baseUrl . $files->keputusan_pangkat_terakhir,
                    ];
                }
                if ($files->sasaran_jabatan_terakhir) {
                    $dokumenPendukung[] = [
                        'id' => '4',
                        'nama' => 'Sasaran Jabatan Terakhir',
                        'url' => $baseUrl . $files->sasaran_jabatan_terakhir,
                    ];
                }
                if ($files->sasaran_kinerja_pegawai) {
                    $dokumenPendukung[] = [
                        'id' => '5',
                        'nama' => 'Sasaran Kinerja Pegawai',
                        'url' => $baseUrl . $files->sasaran_kinerja_pegawai,
                    ];
                }
            }

            $pegawai = pegawai::where('user_id','=',$pengajuan_sbt->user->id)->first();


            // Prepare response data matching DetailPengajuan DTO
            $responseData = [
                'id' => (string) $pengajuan_sbt->id,
                'jenis_surat' => $pengajuan_sbt->jenis_surat ?? 'Surat Bebas Temuan',
                'alasan_pengajuan' => $pengajuan_sbt->alasan_pengajuan ?? '',
                'nama_lengkap' => $pengajuan_sbt->user->name ?? '',
                'nip' => $pegawai->nip,
                'pangkat_gol_ruang' => $pegawai->pangkat_golongan?? '',
                'jabatan' => $pegawai->jabatan ?? '',
                'unit_kerja' => $pengajuan_sbt->unit_kerja ?? '',
                'tanggal_pengajuan' => $pengajuan_sbt->tanggal_pengajuan ? $pengajuan_sbt->tanggal_pengajuan : '',
                'status' => $pengajuan_sbt->status ?? 'Menunggu Verifikasi',
                'dokumen_pendukung' => $dokumenPendukung,
                'pdf_url' => $pengajuan_sbt->pdf_url, // Adjust if PDF URL is stored
            ];

            return response()->json([
                'success' => true,
                'message' => 'Get data successfully',
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            Log::info('error show' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(pengajuan_sbt $pengajuan_sbt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'id_user' => 'required',
                'status' => 'required',
                'alasan_penolakan' => 'required',
                'tanggal_pengajuan' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ], 403);
            }
            $input = $request->all();
            $input['created_by'] = 1;
            $pengajuan_sbt = pengajuan_sbt::findOrFail($id);
            $pengajuan_sbt->update($input);
            return response()->json([
                'success' => true,
                'message' => 'product update succesfully',
                'data' => $pengajuan_sbt,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pengajuan_sbt  = pengajuan_sbt::findOrFail($id);
            $pengajuan_sbt->delete();
            return response()->json([
                'success' => true,
                'message' => 'product deleted succesfully by id' . $pengajuan_sbt->id,
                'data' => $pengajuan_sbt,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function pengajuanForm(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenisSurat' => 'required|string',
                'alasan' => 'required|string',
                'nama' => 'required|string',
                'unitKerja' => 'required|string',
                'rekomendasi' => 'sometimes|file|mimes:pdf|max:2048',
                'surat_keterangan' => 'sometimes|file|mimes:pdf|max:2048',
                'sk_pangkat' => 'sometimes|file|mimes:pdf|max:2048',
                'sk_jabatan' => 'sometimes|file|mimes:pdf|max:2048',
                'skp' => 'sometimes|file|mimes:pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'data' => $validator->errors(),
                ], 422);
            }

            // Get authenticated user
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 401);
            }

            // Save pengajuan data
            $pengajuan = new pengajuan_sbt();
            $pengajuan->jenis_surat = $request->jenisSurat;
            $pengajuan->alasan_pengajuan = $request->alasan;
            $pengajuan->unit_kerja = $request->unitKerja;
            $pengajuan->id_user = $user->id;
            $pengajuan->tanggal_pengajuan = now();
            $pengajuan->tujuan_mutasi = $request->tujuan_mutasi;
            $pengajuan->status = 'pending';
            $pengajuan->created_by = $user->id;
            $pengajuan->save();

            // Save supporting documents
            $fileData = new FileSyaratPengajuan();
            $fileData->id_pengajuan_sbts = $pengajuan->id;

            $dokumenPendukung = [];
            $baseUrl = config('app.url') . '/storage/';

            if ($request->hasFile('rekomendasi')) {
                $path = $request->file('rekomendasi')->store('dokumen_pendukung', 'public');
                $fileData->rekomendasi_kepala_perangkat_biro = $path;
                $dokumenPendukung[] = [
                    'id' => '1',
                    'nama' => 'Rekomendasi Kepala Perangkat Biro',
                    'url' => $baseUrl . $path,
                ];
            }

            if ($request->hasFile('surat_keterangan')) {
                $path = $request->file('surat_keterangan')->store('dokumen_pendukung', 'public');
                $fileData->surat_keterangan_terkait_permasalahan = $path;
                $dokumenPendukung[] = [
                    'id' => '2',
                    'nama' => 'Surat Keterangan Terkait Permasalahan',
                    'url' => $baseUrl . $path,
                ];
            }

            if ($request->hasFile('sk_pangkat')) {
                $path = $request->file('sk_pangkat')->store('dokumen_pendukung', 'public');
                $fileData->keputusan_pangkat_terakhir = $path;
                $dokumenPendukung[] = [
                    'id' => '3',
                    'nama' => 'Keputusan Pangkat Terakhir',
                    'url' => $baseUrl . $path,
                ];
            }

            if ($request->hasFile('sk_jabatan')) {
                $path = $request->file('sk_jabatan')->store('dokumen_pendukung', 'public');
                $fileData->sasaran_jabatan_terakhir = $path;
                $dokumenPendukung[] = [
                    'id' => '4',
                    'nama' => 'Sasaran Jabatan Terakhir',
                    'url' => $baseUrl . $path,
                ];
            }

            if ($request->hasFile('skp')) {
                $path = $request->file('skp')->store('dokumen_pendukung', 'public');
                $fileData->sasaran_kinerja_pegawai = $path;
                $dokumenPendukung[] = [
                    'id' => '5',
                    'nama' => 'Sasaran Kinerja Pegawai',
                    'url' => $baseUrl . $path,
                ];
            }

            $fileData->save();

            // Generate PDF (optional, adjust based on requirements)
            $pdfData = [
                'title' => 'Pengajuan Surat Bebas Temuan',
                'content' => "Pengajuan oleh: {$pengajuan->nama_lengkap}\nAlasan: {$pengajuan->alasan_pengajuan}",
            ];

            $pdf = Pdf::loadView('templates', $pdfData);
            $templatePath = public_path('template');
            if (!File::exists($templatePath)) {
                File::makeDirectory($templatePath, 0755, true);
            }

            $uniqueName = Str::uuid()->toString() . '.pdf';
            $filePath = $templatePath . '/' . $uniqueName;
            $pdf->save($filePath);

            // Prepare response matching DetailPengajuan DTO
            $responseData = [
                'id' => $pengajuan->id,
                'jenis_surat' => $pengajuan->jenis_surat,
                'alasan_pengajuan' => $pengajuan->alasan_pengajuan,
                'nama_lengkap' => $pengajuan->nama_lengkap,
                'nip' => $pengajuan->nip ?? '',
                'pangkat_gol_ruang' => $pengajuan->pangkat_gol_ruang ?? '',
                'jabatan' => $pengajuan->jabatan ?? '',
                'unit_kerja' => $pengajuan->unit_kerja,
                'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan->toDateString(),
                'status' => $pengajuan->status,
                'dokumen_pendukung' => $dokumenPendukung,
                'pdf_url' => config('app.url') . '/template/' . $uniqueName,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil disimpan',
                'data' => $responseData,
            ], 201);
        } catch (\Exception $e) {
            Log::error('PengajuanForm Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function progress_berkas()
    {
        try {
            $data = Progresberkas::all();
            Log::info("iam hitted");
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil disimpan',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllPengajuan(Request $request)
    {
        try {
            $user = $request->user();
            if ($user->hasAnyRole(['Admin', 'Verifikator'])) {
                // Retrieve all pengajuan_sbt records with related fileSyaratPengajuan
                $pengajuan = pengajuan_sbt::with('fileSyaratPengajuan')->get();
            } else {
                // For other roles (e.g., User), retrieve only their own pengajuan_sbt records
                $pengajuan = pengajuan_sbt::where('id_user', $user->id)
                    ->with('fileSyaratPengajuan')
                    ->get();
            }
            $data = $pengajuan->map(function ($item) {
                return [
                    'id' => $item->id,
                    'id_user' => $item->id_user,
                    'id_jenis_sbts' => $item->id_jenis_sbts ?: null,
                    'alasan_pengajuan' => $item->alasan_pengajuan ?: null,
                    'unit_kerja' => $item->unit_kerja ?: null,
                    'status' => $item->status,
                    'alasan_penolakan' => $item->alasan_penolakan,
                    'tanggal_pengajuan' => $item->tanggal_pengajuan,
                    'file_syarat' => $item->fileSyarat ? [
                        'rekomendasi_kepala_perangkat_biro' => $item->fileSyarat->rekomendasi_kepala_perangkat_biro,
                        'surat_keterangan_terkait_permasalahan' => $item->fileSyarat->surat_keterangan_terkait_permasalahan,
                        'keputusan_pangkat_terakhir' => $item->fileSyarat->keputusan_pangkat_terakhir,
                        'sasaran_jabatan_terakhir' => $item->fileSyarat->sasaran_jabatan_terakhir,
                        'sasaran_kinerja_pegawai' => $item->fileSyarat->sasaran_kinerja_pegawai,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil disimpan',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }


    public function get_detail($id)
    {
        try {
            $data = pengajuan_sbt::where('id', '=', $id)->with('fileSyarat')->get();
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil disimpan',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    private function formatPengajuanResponse($pengajuan)
    {
        return [
            'id' => $pengajuan->id,
            'namaLengkap' => $pengajuan->nama_lengkap,
            'nip' => $pengajuan->nip,
            'pangkatGolRuang' => $pengajuan->pangkat_gol_ruang,
            'jabatan' => $pengajuan->jabatan,
            'unitKerja' => $pengajuan->unit_kerja,
            'jenisSurat' => $pengajuan->jenis_surat,
            'alasanPengajuan' => $pengajuan->alasan_pengajuan,
            'tanggalPengajuan' => $pengajuan->created_at->format('Y-m-d'),
            'status' => $pengajuan->status,
            'namaProgress' => $pengajuan->progress->last()->nama_progress ?? null,
            'pdfUrl' => $pengajuan->pdf_url ?? null,
            'dokumenPendukung' => $pengajuan->dokumen->map(function ($dokumen) {
                return [
                    'nama' => $dokumen->nama,
                    'url' => $dokumen->url,
                ];
            })->toArray(),
        ];
    }

    public function verifyPengajuan(Request $request, $id)
    {
        // Validate input
        Log::info('progress data' . json_encode($request->all()));
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:diterima,tidak_diterima,pending',
            'nama_progress' => 'required',
            'keterangan' => 'required|string|max:255',
        ]);

        Log::info("error verify" . json_encode($request->all()));
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => $validator->errors(),
            ], 422);
        }

        // Check if pengajuan exists
        $pengajuan = pengajuan_sbt::find($id);
        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan not found',
                'data' => null
            ], 404);
        }

        // Get authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }

        try {
            DB::beginTransaction();

            // Insert progress record
            $progress = Progresberkas::create([
                'id_psbts' => $id,
                'nama_progress' => $request->nama_progress,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            if ($request->nama_progress == "Pengajuan Dokumen" && $request->status == "diterima") {
                $pengajuan->status = "pending";
                $pengajuan->save();
            } else if ($request->nama_progress == "Review Berkas" && $request->status == "diterima") {
                $pengajuan->status = "pending";
                $pengajuan->save();
            } else if ($request->nama_progress == "Verifikasi Lapangan" && $request->status == "diterima") {
                $pengajuan->status = "pending";
                $pengajuan->save();
            } else if ($request->nama_progress == "Penerbitan Surat" && $request->status == "diterima") {
                $pengajuan->status = "diterima";
                $pengajuan->save();
            }

            if ($request->nama_progress == "Pengajuan Dokumen" && $request->status == "tidak_diterima") {
                $pengajuan->status = "tidak_diterima";
                $pengajuan->save();
            } else if ($request->nama_progress == "Review Berkas" && $request->status == "tidak_diterima") {
                $pengajuan->status = "tidak_diterima";
                $pengajuan->save();
            } else if ($request->nama_progress == "Verifikasi Lapangan" && $request->status == "tidak_diterima") {
                $pengajuan->status = "tidak_diterima";
                $pengajuan->save();
            } else if ($request->nama_progress == "Penerbitan Surat" && $request->status == "tidak_diterima") {
                $pengajuan->status = "tidak_diterima";
                $pengajuan->save();
            }

            // Update pengajuan status if needed

            DB::commit();

            // Return updated pengajuan with latest progress
            $pengajuan->load('progress');
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan verified successfully',
                'data' => $this->formatPengajuanResponse($pengajuan),
            ], 200);
        } catch (\Exception $e) {
            Log::info("error exception" . json_encode($e->getMessage()));
            DB::rollBack();
            return response()->json([
                'success' => true,
                'message' => 'Failed to verify pengajuan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
    
    public function generatePDF($id)
    {
        try {
            // Ambil data pengajuan
            $pengajuan = pengajuan_sbt::findOrFail($id);
    
            // Ambil data pegawai dari user_id
            $pegawai = Pegawai::where('user_id', $pengajuan->id_user)->first();
    
            if (!$pegawai) {
                throw new Exception('Data pegawai tidak ditemukan untuk user ini.');
            }
    
            // Generate nomor surat
            $random = strtoupper(Str::random(6));
            $tahun = date('Y');
            $nomorSurat = "SURAT-$random-$tahun";
    
            // Load view PDF
            $pdf = Pdf::loadView('templates', [
                'data' => $pengajuan,
                'pegawai' => $pegawai,
                'pengajuan' => $pengajuan
            ])->setPaper([0, 0, 612, 936], 'portrait');
    
            // Folder tempat simpan PDF
            $folderName = 'template';
            $templatePath = public_path($folderName);
            if (!File::exists($templatePath)) {
                File::makeDirectory($templatePath, 0755, true);
            }
    
            // Nama file
            $uniqueFileName = Str::uuid()->toString() . '.pdf';
            $filePath = $templatePath . DIRECTORY_SEPARATOR . $uniqueFileName;
    
            // Simpan ke disk
            $pdf->save($filePath);
            if (!File::exists($filePath)) {
                throw new Exception('File PDF gagal disimpan.');
            }
    
            // Buat URL publik
            $fileUrl = url("$folderName/$uniqueFileName");
    
            // Simpan URL ke pengajuan
            $pengajuan->update([
                'pdf_url' => $fileUrl
            ]);
    
        } catch (Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
        }
    }
    function generateNomorSurat($idUser)
    {
        $random = strtoupper(Str::random(5));
        $tanggal = now()->format('dmY');
        return "SRT/$idUser/$random/$tanggal";
        // Contoh: SRT/12/AB39K/03072025
    }
    public function getProgress(Request $request, $id)
    {
        try {
            // Ambil pengajuan dan relasi progress
            $pengajuan = pengajuan_sbt::with('progress')->find($id);
    
            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan not found',
                    'data' => null
                ], 404);
            }
    
            // Cek user login
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => null
                ], 401);
            }
    
            // Buat PDF setelah validasi pengajuan
            $this->generatePDF($pengajuan->id);
    
            // Format data progress
            $progressData = $pengajuan->progress->map(function ($progress) {
                return [
                    'title' => $progress->nama_progress,
                    'subtitle' => $progress->keterangan,
                    'timestamp' => $progress->created_at->diffForHumans(),
                    'status' => strtoupper($progress->status),
                ];
            });
    
            return response()->json([
                'success' => true,
                'message' => 'Progress retrieved successfully',
                'data' => [
                    'pengajuan_id' => $pengajuan->id,
                    'pdf_url' => $pengajuan->pdf_url,
                    'progress' => $progressData,
                ],
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('getProgress error: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve progress: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
