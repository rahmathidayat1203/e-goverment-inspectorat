<?php

namespace App\Http\Controllers;

use App\Models\mutasi_asn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MutasiAsnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        try {        
            $perpage = $request->get('per_page',10);
            $mutasiasn = mutasi_asn::paginate($perpage);    
    
            return response ()->json([
                'success' => true,
                'massage' => 'data fetch successfully',
                'data' => $mutasiasn
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
                'id_user' => 'required',
                'id_instansi_asal' => 'required',
                'id_instansi_tujuan' => 'required',
                'tanggal_pengajuan' => 'required',
                
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

            mutasi_asn::create($input);
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
        $mutasi_asn = mutasi_asn::findOrFail($id);
        try {
            return response()->json([
                'success' => true,
                'message' => 'get data successfully',
                'data' => $mutasi_asn,  
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
    public function edit(mutasi_asn $mutasi_asn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        try {
            $validator = Validator::make($request->all(),[
               'id_user' => 'required',
                'id_instansi_asal' => 'required',
                'id_instansi_tujuan' => 'required',
                'tanggal_pengajuan' => 'required',
                
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
            $mutasi_asn = mutasi_asn::findOrFail($id);
            $mutasi_asn->update($input);
            return response()->json([
                'success' => true,
                'message' => 'product update succesfully',
                'data' => $mutasi_asn,
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
            $mutasi_asn  = mutasi_asn::findOrFail($id);
            $mutasi_asn->delete();
            return response()->json([
                'success' => true,
                'message' => 'product deleted succesfully by id'.$mutasi_asn->id,
                'data' => $mutasi_asn,
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
