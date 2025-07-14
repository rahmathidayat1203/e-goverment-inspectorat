<?php

namespace App\Http\Controllers;

use App\Models\jenis_sbt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisSbtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        try {        
            $perpage = $request->get('per_page',10);
            $jenissbt = jenis_sbt::paginate($perpage);    
    
            return response ()->json([
                'success' => true,
                'massage' => 'data fetch successfully',
                'data' => $jenissbt
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
                'nama' => 'required',
                'keterangan' => 'required',
                'status' => 'required',
                'kategori' => 'required',
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

            jenis_sbt::create($input);
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
        $jenis_sbt = jenis_sbt::findOrFail($id);
        try {
            return response()->json([
                'success' => true,
                'message' => 'get data successfully',
                'data' => $jenis_sbt,
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
    public function edit(jenis_sbt $jenis_sbt)
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
                'nama' => 'required',
                'keterangan' => 'required',
                'status' => 'required',
                'kategori' => 'required',
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
            $jenis_sbt = jenis_sbt::findOrFail($id);
            $jenis_sbt->update($input);
            return response()->json([
                'success' => true,
                'message' => 'product update succesfully',
                'data' => $jenis_sbt,
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
            $jenis_sbt  = jenis_sbt::findOrFail($id);
            $jenis_sbt->delete();
            return response()->json([
                'success' => true,
                'message' => 'product deleted succesfully by id'.$jenis_sbt->id,
                'data' => $jenis_sbt,
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
