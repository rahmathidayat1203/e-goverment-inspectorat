<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileSyaratPengajuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_syarat_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('id_pengajuan_sbts');
            $table->string("rekomendasi_kepala_perangkat_biro");
            $table->string("surat_keterangan_terkait_permasalahan");
            $table->string("keputusan_pangkat_terakhir");
            $table->string("sasaran_jabatan_terakhir");
            $table->string("sasaran_kinerja_pegawai");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_syarat_pengajuans');
    }
}
