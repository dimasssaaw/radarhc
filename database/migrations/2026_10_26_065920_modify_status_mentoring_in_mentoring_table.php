<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mentoring', function (Blueprint $table) {
            $table->string('status_mentoring', 50)->nullable()->default('in_progress')->change();
            $table->text('development')->nullable()->change();
        });

        DB::table('mentoring')
            ->where('status_mentoring', 'pending')
            ->orWhereNull('status_mentoring')
            ->update(['status_mentoring' => 'in_progress']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentoring', function (Blueprint $table) {
            $table->enum('status_mentoring', ['pending', 'completed', 'cancelled'])->nullable()->change();
            $table->string('development', 255)->nullable()->change();
        });
    }
};
