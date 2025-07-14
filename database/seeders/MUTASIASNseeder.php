<?php

namespace Database\Seeders;

use App\Models\mutasi_asn;
use Illuminate\Database\Seeder;

class MUTASIASNseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0;$i <15 ;$i++) {
            mutasi_asn::create ([
                'id_user'=> 'id_user'.$i,
                'id_instansi_asal'=> 'id_instansi_asal'.$i,
                'id_instansi_tujuan'=> 'id_instansi_tujuan'.$i,
                'tanggal_pengajuan'=> now()->addDays(rand(1, 365)),
                'created_by'=> now()->addDays(rand(1, 365)),
                'updated_by'=> now()->addDays(rand(1, 365)),
                'deleted_by'=> now()->addDays(rand(1, 365)),
            ]);
        }
    }
}
