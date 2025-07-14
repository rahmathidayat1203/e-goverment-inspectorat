<?php

namespace Database\Seeders;

use App\Models\instansi;
use Illuminate\Database\Seeder;

class INSTANSIseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0;$i <15 ;$i++) {
            instansi::create([
                'nama'=> 'nama'.$i,
                'alamat'=> 'alamat'.$i,
                'keterangan'=> 'keterangan_instansi'.$i,
                'created_by'=> now()->addDays(rand(1, 365)),
                'updated_by'=> now()->addDays(rand(1, 365)),
                'deleted_by'=> now()->addDays(rand(1, 365)),
            ]);
        }
    }
}
