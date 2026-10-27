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
        Schema::create('idp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan')->cascadeOnDelete();
            $table->string('tahun')->nullable();
            $table->string('periode')->nullable();
            $table->enum('status_idp', ['in_progress', 'completed', 'pending'])->nullable()->default('in_progress');
            $table->string('mentor')->nullable();
            $table->text('development')->nullable();
            $table->date('target')->nullable();
            $table->date('monitoring')->nullable();
            $table->date('final')->nullable();
            $table->float('comp_planning')->nullable();
            $table->float('comp_adaptability')->nullable();
            $table->float('comp_leadership')->nullable();
            $table->float('comp_communication')->nullable();
            $table->float('comp_problem_solving')->nullable();
            $table->float('comp_technical_skill')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idp');
    }
};
