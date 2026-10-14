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
        Schema::table('riwayat_training', function (Blueprint $table) {
            $table->string('eva1_status')->nullable();
            $table->string('eva1_lampiran')->nullable();
            $table->string('eva2_status')->nullable();
            $table->string('eva2_lampiran')->nullable();
            $table->string('eva3_status')->nullable();
            $table->string('eva3_lampiran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_training', function (Blueprint $table) {
            $table->dropColumn([
                'eva1_status', 'eva1_lampiran',
                'eva2_status', 'eva2_lampiran',
                'eva3_status', 'eva3_lampiran',
            ]);
        });
    }
};
