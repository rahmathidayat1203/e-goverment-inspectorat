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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string("nama");
            $table->string('user_id');
            $table->string("nip");
            $table->string("pangkat_golongan");
            $table->string("alamat");
            $table->string("nik");
            $table->string("instansi");
            $table->string("alamat_instansi");
            $table->string("jabatan");
            $table->string("unit_kerja");
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
        Schema::dropIfExists('pegawais');
    }
};
