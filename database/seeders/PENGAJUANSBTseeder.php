<?php

namespace Database\Seeders;

use App\Models\pengajuan_sbt;
use Illuminate\Database\Seeder;

class PENGAJUANSBTseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0;$i <15 ;$i++) {
            pengajuan_sbt::create ([
                'nama'=> 'nama'.$i,
                'id_user'=> 'id_user'.$i,
                'status'=> 'status',
                'alasan_penolakan'=> 'penolakan'.$i,
                'tanggal_pengajuan'=> now()->addDays(rand(1, 365)),
                'created_by'=> now()->addDays(rand(1, 365)),
                'updated_by'=> now()->addDays(rand(1, 365)),
                'deleted_by'=> now()->addDays(rand(1, 365)),
            ]);
        }
    }
}
