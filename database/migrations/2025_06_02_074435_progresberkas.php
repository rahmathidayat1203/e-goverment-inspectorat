<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Progresberkas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {
            Schema::create('Progres_berkas', function (Blueprint $table) {
                $table->id();
                $table->string("id_psbts");
                $table->string("nama_progress");
                $table->enum("status",['diterima','tidak_diterima','pending'])->default('pending');
                $table->string("keterangan");
                $table->string("created_by");
                $table->string("updated_by");
                $table->string("deleted_by")->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
