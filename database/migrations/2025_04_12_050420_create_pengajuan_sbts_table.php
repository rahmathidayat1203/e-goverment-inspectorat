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
        Schema::create('pengajuan_sbts', function (Blueprint $table) {
            $table->id();
            $table->string("jenis_surat");
            $table->string("id_user");
            $table->enum("status",['diterima','tidak_diterima','pending'])->default('pending');
            $table->string('unit_kerja');
            $table->string("alasan_penolakan")->nullable();
            $table->string("alasan_pengajuan")->nullable();
            $table->string("tanggal_pengajuan");
            $table->string("created_by");
            $table->string("tujuan_mutasi");
            $table->string("pdf_url")->nullable();
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
        Schema::dropIfExists('pengajuan_sbts');
    }
};
