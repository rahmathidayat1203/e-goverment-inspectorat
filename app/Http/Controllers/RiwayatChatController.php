<?php

namespace App\Http\Controllers;

use App\Models\riwayat_chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiwayatChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        try {        
            $perpage = $request->get('per_page',10);
            $riwayatchatbot = riwayat_chat::paginate($perpage);    
    
            return response ()->json([
                'success' => true,
                'massage' => 'data fetch successfully',
                'data' => $riwayatchatbot
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
                'pertanyaan' => 'required',
                'jawaban' => 'required',
                'similarity_score' => 'required',
               
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

            riwayat_chat::create($input);
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
        $riwayat_chat = riwayat_chat::findOrFail($id);
        try {
            return response()->json([
                'success' => true,
                'message' => 'get data successfully',
                'data' => $riwayat_chat,  
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
    public function edit(riwayat_chat $riwayat_chat)
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
                'id_user' => 'required',
                'pertanyaan' => 'required',
                'jawaban' => 'required',
                'similarity_score' => 'required',
                
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
            $riwayat_chat = riwayat_chat::findOrFail($id);
            $riwayat_chat->update($input);
            return response()->json([
                'success' => true,
                'message' => 'product update succesfully',
                'data' => $riwayat_chat,
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
            $riwayat_chat  = riwayat_chat::findOrFail($id);
            $riwayat_chat->delete();
            return response()->json([
                'success' => true,
                'message' => 'product deleted succesfully by id'.$riwayat_chat->id,
                'data' => $riwayat_chat,
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
