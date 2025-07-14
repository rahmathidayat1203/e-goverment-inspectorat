<?php

namespace Database\Seeders;

use App\Models\verifikasi;
use Illuminate\Database\Seeder;

class VERIFIKASIseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0;$i <15 ;$i++) {
            verifikasi::create([
                'id_pengajuan'=> 'id_pengajuan'.$i,
                'id_verifikator'=> 'id_verifikator'.$i,
                'status_verifikasi'=> 'disetujui',
                'nomor_surat_instansi_pengaju'=> 'nomor_surat_instansi_pengaju'.$i,
                'nomor_surat_bebas_temuan'=> 'nomor_surat_bebas_temuan'.$i,
                'asal_instansi'=> 'asal_instansi'.$i,
                'tujuan_mutasi'=> 'tujuan_mutasi'.$i,
                'catatan_verifikasi'=> 'catatan_verifikasi'.$i,
                'tanggal_terbit'=> now()->addDays(rand(1, 365)),
                'created_by'=> now()->addDays(rand(1, 365)),
                'updated_by'=> now()->addDays(rand(1, 365)),
                'deleted_by'=> now()->addDays(rand(1, 365)),
            ]);
        }
    }
}
