<?php

namespace Database\Seeders;

use App\Models\riwayat_chat;
use Illuminate\Database\Seeder;

class RIWAYATCHATseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0;$i <15 ;$i++) {
            riwayat_chat::create([
                'id_user'=> 'id_user'.$i,
                'pertanyaan'=> 'pertanyaan'.$i,
                'jawaban'=> 'jawaban'.$i,
                'similarity_score'=> 'similarity_score'.$i,
                'created_by'=> now()->addDays(rand(1, 365)),
                'updated_by'=> now()->addDays(rand(1, 365)),
                'deleted_by'=> now()->addDays(rand(1, 365)),
            ]);
        }
    }
}
