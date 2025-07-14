<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\pegawai; // Pastikan model Pegawai ada
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'nip' => '1234567890', 
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'remember_token' => \Str::random(10),
        ]);

        // Cek atau buat role Admin
        $role = Role::firstOrCreate(['name' => 'Admin']);

        // Assign hanya role, tanpa permission
        $user->assignRole($role->name);

        // Buat data pegawai untuk user Admin
        pegawai::create([
            'nama' => $user->name,
            'user_id' => $user->id,
            'nip' => $user->nip,
            'pangkat_golongan' => 'IV/a', // Contoh pangkat golongan
            'alamat' => 'Jl. Contoh No. 1', // Contoh alamat
            'nik' => '1234567890123456', // Contoh NIK
            'instansi' => 'Kementerian Contoh', // Contoh instansi
            'alamat_instansi' => 'Jl. Contoh Instansi No. 1', // Contoh alamat instansi
            'jabatan' => 'Admin', // Contoh jabatan
            'unit_kerja' => 'Unit Contoh', // Contoh unit kerja
            'created_by' => 'Seeder',
        ]);

        $user = User::create([
            'name' => 'Yudi',
            'nip' => '1123456789', 
            'email' => 'yudi@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'remember_token' => \Str::random(10),
        ]);

        // Cek atau buat role User
        $role = Role::firstOrCreate(['name' => 'User']);

        // Assign hanya role, tanpa permission
        $user->assignRole($role->name);

        // Buat data pegawai untuk user Yudi
        pegawai::create([
            'nama' => $user->name,
            'user_id' => $user->id,
            'nip' => $user->nip,
            'pangkat_golongan' => 'III/a', // Contoh pangkat golongan
            'alamat' => 'Jl. Contoh No. 2', // Contoh alamat
            'nik' => '1234567890123457', // Contoh NIK
            'instansi' => 'Kementerian Contoh', // Contoh instansi
            'alamat_instansi' => 'Jl. Contoh Instansi No. 1', // Contoh alamat instansi
            'jabatan' => 'Staff', // Contoh jabatan
            'unit_kerja' => 'Unit Contoh', // Contoh unit kerja
            'created_by' => 'Seeder',
        ]);
    }
}