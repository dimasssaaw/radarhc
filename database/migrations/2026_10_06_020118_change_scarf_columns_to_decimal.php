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
        Schema::table('scarf_profile', function (Blueprint $table) {
            $table->decimal('status', 5, 2)->nullable()->change();
            $table->decimal('certainty', 5, 2)->nullable()->change();
            $table->decimal('autonomy', 5, 2)->nullable()->change();
            $table->decimal('relatedness', 5, 2)->nullable()->change();
            $table->decimal('fairness', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scarf_profile', function (Blueprint $table) {
            $table->integer('status')->nullable()->change();
            $table->integer('certainty')->nullable()->change();
            $table->integer('autonomy')->nullable()->change();
            $table->integer('relatedness')->nullable()->change();
            $table->integer('fairness')->nullable()->change();
        });
    }
};
