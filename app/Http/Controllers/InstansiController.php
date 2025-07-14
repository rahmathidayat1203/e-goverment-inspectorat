<?php

namespace App\Http\Controllers;

use App\Models\instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        try {
            $perpage = $request->get('per_page', 10);
            $instansi = instansi::paginate($perpage);

            return response()->json([
                'success' => true,
                'massage' => 'data fetch successfully',
                'data' => $instansi
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
        Log::info($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'alamat' => 'required',
                'keterangan' => 'required',
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

            instansi::create($input);
            return response()->json([
                'success' => false,
                'message' => 'created successfully',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            Log::info($e);
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
        $instansi = instansi::findOrFail($id);
        try {
            return response()->json([
                'success' => true,
                'message' => 'get data successfully',
                'data' => $instansi,
            ]);
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(instansi $instansi)
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
                'alamat' => 'required',
                'keterangan' => 'required',
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
            $instansi = instansi::findOrFail($id);
            $instansi->update($input);
            return response()->json([
                'success' => true,
                'message' => 'product update succesfully',
                'data' => $instansi,
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
            $instansi = instansi::findOrFail($id);
            $instansi->delete();
            return response()->json([
                'success' => true,
                'message' => 'product deleted succesfully by id' . $instansi->id,
                'data' => $instansi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
