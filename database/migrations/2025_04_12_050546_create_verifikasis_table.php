<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('verifikasis', function (Blueprint $table) {
            $table->id();
            $table->string("id_pengajuan");
            $table->string("id_verifikator");
            $table->enum("status_verifikasi",['disetujui','ditolak']);
            $table->string("nomor_surat_instansi_pengaju");
            $table->string("nomor_surat_bebas_temuan");
            $table->string("asal_instansi");
            $table->string("tujuan_mutasi");
            $table->string("catatan_verifikasi");
            $table->string("tanggal_terbit");
            $table->string("created_by");
            $table->string("updated_by")->nullable();
            $table->string("deleted_by")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasis');
    }
};
