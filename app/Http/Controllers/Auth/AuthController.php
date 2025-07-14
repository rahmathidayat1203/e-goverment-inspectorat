<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba autentikasi
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Generate token (pastikan kamu menggunakan Laravel Sanctum atau Passport)
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'created successfully',
                'data' => [
                    'token' => $token
                ],
            ]);
        }

        // Gagal login
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
            'data' => null,
        ], 401);
    }
    public function register(Request $request)
    {
        Log::info($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'nip' => 'required',
            'password' => 'required',
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
        $user->assignRole("User");
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
            'data' => null
        ]);
    }
    public function logout(Request $request)
    {
        // Hapus token saat ini
        try {
            Log::info($request->user());
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            // Log::info($e->getMessage());
            return response()->json([
                'success' => false,
                'massage' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            $role = $user->getRoleNames()->first();

            // Ambil data pegawai berdasarkan user_id
            $pegawai = Pegawai::where('user_id', $user->id)->first();

            // Gabungkan semua data dalam satu array
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nip' => $user->nip,
                'role' => $role,
                'pangkat_golongan' => $pegawai->pangkat_golongan ?? null,
                'alamat' => $pegawai->alamat ?? null,
                'nik' => $pegawai->nik ?? null,
                'instansi' => $pegawai->instansi ?? null,
                'alamat_instansi' => $pegawai->alamat_instansi ?? null,
                'jabatan' => $pegawai->jabatan ?? null,
                'unit_kerja' => $pegawai->unit_kerja ?? null
            ];

            return response()->json([
                'success' => true,
                'message' => 'Get profile success',
                'data' => $userData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function update_profile(Request $request)
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'nip' => 'required',
                'email' => 'required|email',
                'pangkat_golongan' => 'nullable|string',
                'alamat' => 'nullable|string',
                'nik' => 'nullable|string',
                'instansi' => 'nullable|string',
                'alamat_instansi' => 'nullable|string',
                'jabatan' => 'nullable|string',
                'unit_kerja' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            // Update user
            $user->update([
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email
            ]);

            // Update pegawai
            $pegawai = Pegawai::where('user_id', $user->id)->first();
            if ($pegawai) {
                $pegawai->update([
                    'nama' => $request->name,
                    'nip' => $request->nip,
                    'pangkat_golongan' => $request->pangkat_golongan,
                    'alamat' => $request->alamat,
                    'nik' => $request->nik,
                    'instansi' => $request->instansi,
                    'alamat_instansi' => $request->alamat_instansi,
                    'jabatan' => $request->jabatan,
                    'unit_kerja' => $request->unit_kerja
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
