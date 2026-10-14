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
        Schema::create('assessment_report', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan')->cascadeOnDelete();
            $table->year('tahun')->nullable();
            $table->string('periode')->nullable();
            $table->string('provider')->nullable();
            $table->float('kb_kecerdasan_umum')->nullable();
            $table->float('kb_fleksibilitas_berfikir')->nullable();
            $table->float('kb_kemampuan_konseptual')->nullable();
            $table->float('kb_analisa_sintesa')->nullable();
            $table->float('sk_sistematika_kerja')->nullable();
            $table->float('sk_daya_tahan')->nullable();
            $table->float('sk_fleksibilitas')->nullable();
            $table->float('sk_perhatian_detail')->nullable();
            $table->float('kp_stabilitas_emosi')->nullable();
            $table->float('kp_penyesuaian_diri')->nullable();
            $table->float('kp_kepercayaan_diri')->nullable();
            $table->float('kp_kepemimpinan')->nullable();
            $table->float('kp_integritas')->nullable();
            $table->float('kp_motivasi_kerja')->nullable();
            $table->float('ms_planning_controlling')->nullable();
            $table->float('ms_managing_changes')->nullable();
            $table->float('ms_empowerment')->nullable();
            $table->float('ms_fostering_team_work')->nullable();
            $table->float('ms_analysis_decision')->nullable();
            $table->float('ms_humility')->nullable();
            $table->float('ms_compassion')->nullable();
            $table->float('ms_excellence')->nullable();
            $table->float('ms_integrity_ethics')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_report');
    }
};
