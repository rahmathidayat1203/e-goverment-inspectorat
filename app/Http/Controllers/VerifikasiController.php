<?php

namespace App\Http\Controllers;

use App\Models\verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        try {        
            $perpage = $request->get('per_page',10);
            $verifikasi = verifikasi::paginate($perpage);    
    
            return response ()->json([
                'success' => true,
                'massage' => 'data fetch successfully',
                'data' => $verifikasi
            ],200);
        }
        catch (\Exception $e) {
            return response ()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null
            ],500);
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
            $validator = Validator::make($request->all(),[
                'id_pengajuan' => 'required',
                'id_verifikator' => 'required',
                'status_verifikator' => 'required',
                'nomor_surat_instansi_pengaju' => 'required',
                'nomor_surat_bebas_temuan' => 'required',
                'asal_instansi' => 'required',
                'tujuan_mutasi' => 'required',
                'catatan_verifikasi' => 'required',
                'tanggal_terbit' => 'required',
                
            ]);
            
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ],403);
            }

            $input = $request->all();
            $input['created_by'] = 1;

            verifikasi::create($input);
            return response()->json([
                'success' => false,
                'message' => 'created successfully',
                'data' => null,
            ]);  

        } catch (\Exception $e) {
            return response ()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $verifikasi = verifikasi::findOrFail($id);
        try {
            return response()->json([
                'success' => true,
                'message' => 'get data successfully',
                'data' => $verifikasi,  
            ]);
        } catch (\Exception $e) {
            return response ()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(verifikasi $verifikasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(),[
                'id_pengajuan' => 'required',
                'id_verifikator' => 'required',
                'status_verifikator' => 'required',
                'nomor_surat_bebas_temuan' => 'required',
                'asal_instansi' => 'required',
                'tujuan_mutasi' => 'required',
                'catatan_verifikasi' => 'required',
                'tanggal_terbit' => 'required',
                
            ]);
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors()
                ],403);
            }
            $input = $request->all();
            $input['created_by'] = 1;
            $verifikasi = verifikasi::findOrFail($id);
            $verifikasi->update($input);
            return response()->json([
                'success' => true,
                'message' => 'product update succesfully',
                'data' => $verifikasi,
            ]);  
        }catch(\Exception $e){
            return response ()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $verifikasi  = verifikasi::findOrFail($id);
            $verifikasi->delete();
            return response()->json([
                'success' => true,
                'message' => 'product deleted succesfully by id'.$verifikasi->id,
                'data' => $verifikasi,
            ]); 
        } catch (\Exception $e) {
            return response ()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ],500);
        }
    }
}
