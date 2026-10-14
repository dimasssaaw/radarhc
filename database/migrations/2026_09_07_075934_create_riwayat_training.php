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
        Schema::create('riwayat_training', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_training')->constrained('training')->cascadeOnDelete();
            $table->foreignId('id_karyawan')->constrained('karyawan')->cascadeOnDelete();
            $table->enum('kategori', ['mandatory', 'skill', 'managerial'])->nullable();
            $table->string('nama_training')->nullable();
            $table->enum('status_training', ['completed', 'in_progress', 'remedial', 'not_started'])->nullable();
            $table->date('tanggal_training')->nullable();
            $table->enum('tingkat_evaluasi', ['eva1', 'eva2', 'eva3'])->nullable();
            $table->string('lampiran_training')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_training');
    }
};
