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
        // 1. Tabel Kategori Penilaian Assessment (misal: Cognitive Ability, Work Attitude, dsb)
        Schema::create('assessment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 2. Tabel Item Aspek / Indikator Penilaian
        Schema::create('assessment_aspects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('assessment_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('target_score', 3, 1)->default(5.0);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. Tabel Lembaga / Provider Assessment
        Schema::create('assessment_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 4. Tabel Nilai Dinamis per Aspek Assessment
        Schema::create('assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_report_id')->constrained('assessment_report')->cascadeOnDelete();
            $table->foreignId('assessment_aspect_id')->constrained('assessment_aspects')->cascadeOnDelete();
            $table->decimal('score', 3, 1)->default(0.0);
            $table->timestamps();

            $table->unique(['assessment_report_id', 'assessment_aspect_id'], 'as_report_aspect_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_scores');
        Schema::dropIfExists('assessment_providers');
        Schema::dropIfExists('assessment_aspects');
        Schema::dropIfExists('assessment_categories');
    }
};
