<?php

namespace Database\Seeders;

use App\Models\chatbot_knowledge;
use Illuminate\Database\Seeder;

class CHATBOTKNOWLEDGEseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0;$i <15 ;$i++) {
            chatbot_knowledge::create ([
                'nama'=> 'nama'.$i,
                'pertanyaan'=> 'pertanyaan'.$i,
                'jawaban'=> 'jawaban'.$i,
                'created_by'=> now()->addDays(rand(1, 365)),
                'updated_by'=> now()->addDays(rand(1, 365)),
                'deleted_by'=> now()->addDays(rand(1, 365)),
            ]);
        }
    }
}
