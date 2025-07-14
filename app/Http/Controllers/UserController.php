<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\pegawai;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::with('roles')->orderBy('id', 'DESC')->paginate(5);

        // Transform data to include role as a string and cast id to string
        $transformedData = $data->getCollection()->map(function ($user) {
            return [
                'id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nip' => $user->nip,
                'role' => $user->roles->first()->name ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'User list retrieved successfully.',
            'data' => [
                'data' => $transformedData,
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'nip' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);



        if ($validator->fails()) {
            Log::info($validator->errors());

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }


        $input = $request->only(['name', 'email', 'nip', 'password']);
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('role'));
        Log::info($request->all());
        if ($user) {
            $pegawai = pegawai::create([
                'nama' => $user->name,
                'user_id' => $user->id,
                'nip' => $request->nip,
                'pangkat_golongan' => $request->pangkat_golongan,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'instansi' => $request->instansi,
                'alamat_instansi' => $request->alamat_instansi,
                'jabatan' => $request->jabatan,
                'unit_kerja' => $request->unit_kerja,
                'created_by' => $user->id
            ]);
        }

        // Prepare response data
        $responseData = [
            'id' => (string) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nip' => $user->nip,
            'role' => $user->roles->first()->name ?? null,
        ];

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $responseData
        ]);
    }

    public function show($id)
    {
        // Cari user berdasarkan email
        $user = User::with('roles')->where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'data' => null
            ], 404);
        }

        // Ambil data pegawai berdasarkan user_id
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai data not found for this user.',
                'data' => null
            ], 404);
        }

        $responseData = [
            'id' => (string) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nip' => $user->nip,
            'role' => $user->roles->first()->name ?? null,
            'pangkat_golongan' => $pegawai->pangkat_golongan,
            'alamat' => $pegawai->alamat,
            'nik' => $pegawai->nik,
            'instansi' => $pegawai->instansi,
            'alamat_instansi' => $pegawai->alamat_instansi,
            'jabatan' => $pegawai->jabatan,
            'unit_kerja' => $pegawai->unit_kerja,
            'created_by' => $pegawai->created_by,
            'updated_by' => $pegawai->updated_by,
            'deleted_by' => $pegawai->deleted_by,
            'created_at' => $pegawai->created_at,
            'updated_at' => $pegawai->updated_at,
        ];

        return response()->json([
            'success' => true,
            'message' => 'User and pegawai details retrieved successfully.',
            'data' => $responseData
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'nip' => 'required|string|max:50',
            'password' => 'sometimes|same:confirmPassword',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'data' => null
            ], 404);
        }

        $input = $request->only(['name', 'email', 'nip', 'password']);
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);
        }

        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('role'));

        $responseData = [
            'id' => (string) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nip' => $user->nip,
            'role' => $user->roles->first()->name ?? null,
        ];

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $responseData
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'data' => null
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
            'data' => null
        ]);
    }
}
