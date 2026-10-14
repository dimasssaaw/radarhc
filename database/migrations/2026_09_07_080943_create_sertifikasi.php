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
        Schema::create('sertifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan')->cascadeOnDelete();
            $table->string('nama_sertifikasi')->nullable();
            $table->string('penerbit')->nullable();
            $table->date('terbit')->nullable();
            $table->date('kadaluarsa')->nullable();
            $table->enum('status_sertifikat', ['aktif', 'nonaktif'])->nullable();
            $table->string('lampiran_sertifikasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikasi');
    }
};
