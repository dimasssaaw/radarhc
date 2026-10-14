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
        Schema::create('mentoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan')->cascadeOnDelete();
            $table->string('tahun')->nullable();
            $table->string('periode')->nullable();
            $table->enum('status_mentoring', ['pending', 'completed', 'cancelled'])->nullable();
            $table->string('mentor')->nullable();
            $table->string('development')->nullable();
            $table->date('target')->nullable();
            $table->date('monitoring')->nullable();
            $table->date('final')->nullable();
            $table->text('grow_goals')->nullable();
            $table->text('grow_realitas')->nullable();
            $table->text('grow_options')->nullable();
            $table->text('grow_wrap_up')->nullable();
            $table->text('grow_insight')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring');
    }
};
