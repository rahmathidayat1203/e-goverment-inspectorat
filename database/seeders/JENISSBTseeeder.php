<?php

namespace Database\Seeders;

use App\Models\jenis_sbt;
use Illuminate\Database\Seeder;

class JENISSBTseeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0;$i <15 ;$i++) {
            jenis_sbt::create ([
                'nama'=> 'nama'.$i,
                'keterangan'=> 'keterangan'.$i,
                'status'=> 'pending',
                'kategori'=> 'mutasi',
                'tanggal_pengajuan'=> now()->addDays(rand(1, 365)),
                'created_by'=> now()->addDays(rand(1, 365)),
                'updated_by'=> now()->addDays(rand(1, 365)),
                'deleted_by'=> now()->addDays(rand(1, 365)),
            ]);
        }
    }
}
