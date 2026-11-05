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
        if (Schema::hasTable('idp') && ! Schema::hasColumn('idp', 'selected_charts')) {
            Schema::table('idp', function (Blueprint $table) {
                $table->json('selected_charts')->nullable()->after('status_idp');
            });
        }

        if (Schema::hasTable('assessment_report') && ! Schema::hasColumn('assessment_report', 'selected_charts')) {
            Schema::table('assessment_report', function (Blueprint $table) {
                $table->json('selected_charts')->nullable()->after('provider');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('idp') && Schema::hasColumn('idp', 'selected_charts')) {
            Schema::table('idp', function (Blueprint $table) {
                $table->dropColumn('selected_charts');
            });
        }

        if (Schema::hasTable('assessment_report') && Schema::hasColumn('assessment_report', 'selected_charts')) {
            Schema::table('assessment_report', function (Blueprint $table) {
                $table->dropColumn('selected_charts');
            });
        }
    }
};
