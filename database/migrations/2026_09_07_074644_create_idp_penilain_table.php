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
        Schema::create('idp_penilain', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_idp')->constrained('idp')->cascadeOnDelete();
            $table->foreignId('id_karyawan')->constrained('karyawan')->cascadeOnDelete();
            $table->unsignedBigInteger('id_penilain');
            $table->float('head_managing_change')->nullable();
            $table->float('head_empowerment')->nullable();
            $table->float('head_fostering_teamwork')->nullable();
            $table->float('head_analysis_decision')->nullable();
            $table->float('head_planning_controlling')->nullable();
            $table->float('head_integrity_ethics')->nullable();
            $table->float('head_excellence')->nullable();
            $table->float('head_compassion')->nullable();
            $table->float('head_humility')->nullable();
            $table->float('staff_leads_courageously')->nullable();
            $table->float('staff_coach_develop')->nullable();
            $table->float('staff_teamwork')->nullable();
            $table->float('staff_trouble_shooting')->nullable();
            $table->float('staff_customer_satisfaction')->nullable();
            $table->float('staff_cross_functional')->nullable();
            $table->float('staff_quality_focus')->nullable();
            $table->float('staff_continuous_improvement')->nullable();
            $table->float('staff_planning_monitoring')->nullable();
            $table->float('staff_personal_integrity')->nullable();
            $table->float('staff_drive_for_result')->nullable();
            $table->float('staff_learning')->nullable();
            $table->float('unit_directiveness')->nullable();
            $table->float('unit_monitoring')->nullable();
            $table->float('unit_teamwork')->nullable();
            $table->float('unit_quality_customer')->nullable();
            $table->float('unit_continuous_improvement')->nullable();
            $table->float('unit_personal_integrity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idp_penilain');
    }
};
