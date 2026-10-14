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
        Schema::table('mentoring', function (Blueprint $table) {
            $table->enum('competency', ['Rendah', 'Tinggi'])->nullable()->default(null);
            $table->enum('commitment', ['Rendah', 'Tinggi'])->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentoring', function (Blueprint $table) {
            $table->dropColumn(['competency', 'commitment']);
        });
    }
};
