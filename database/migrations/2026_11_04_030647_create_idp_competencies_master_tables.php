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
        // 1. Tabel Judul / Kelompok Spider Chart IDP
        Schema::create('idp_competency_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('target_position')->nullable()->default('all');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 2. Tabel Item Kompetensi di dalam Chart
        Schema::create('idp_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idp_competency_group_id')->constrained('idp_competency_groups')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->decimal('target_score', 3, 1)->default(5.0);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. Tabel Nilai Aktual Karyawan per Kompetensi IDP
        Schema::create('idp_competency_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_idp')->constrained('idp')->cascadeOnDelete();
            $table->foreignId('id_karyawan')->constrained('karyawan')->cascadeOnDelete();
            $table->foreignId('idp_competency_id')->constrained('idp_competencies')->cascadeOnDelete();
            $table->decimal('score', 3, 1)->default(0.0);
            $table->timestamps();

            $table->unique(['id_idp', 'idp_competency_id'], 'idp_score_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idp_competency_scores');
        Schema::dropIfExists('idp_competencies');
        Schema::dropIfExists('idp_competency_groups');
    }
};
