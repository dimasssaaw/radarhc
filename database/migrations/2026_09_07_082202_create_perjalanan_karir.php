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
        Schema::create('perjalanan_karir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->integer('tahun_karir')->nullable();
            $table->string('nama_posisi')->nullable();
            $table->enum('tipe_milestone', ['aktif', 'tetap', 'promosi', 'rotasi', 'demosi', 'nonaktif'])->nullable();
            $table->enum('status_pencapaian', ['riwayat/selesai', 'aktif/tercapai', 'proyeksi/target'])->nullable();
            $table->text('catatan_detail')->nullable();
            $table->text('operational_award')->nullable();
            $table->text('catatan_rekomendasi_atasan')->nullable();
            $table->enum('keputusan',
                ['Promote to Next Grade',
                    'Ready for Promotion',
                    'Fit for Current Role',
                    'Extend Contract',
                    'Needs Further Development',
                    'Recommend for Rotation',
                    'Performance Improvement Plan',
                    'Terminate'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perjalanan_karir');
    }
};
