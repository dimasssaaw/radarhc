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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnDelete();
            $table->string('npk')->nullable();
            $table->string('nama')->nullable();
            $table->string('foto')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('kontak')->nullable();
            $table->string('email')->nullable()->unique();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->string('grade')->nullable();
            $table->date('masuk_kerja')->nullable();
            $table->integer('iq')->nullable();
            $table->enum('readiness', ['R1 ready', 'R2 ready', 'R3 ready', 'R4 ready', 'R5 ready', 'not_ready']);
            $table->string('disc')->nullable();
            $table->enum('hav', ['star', 'high potential', 'potential', 'excellent career person', 'career person', 'problem employee', 'deadwood']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
