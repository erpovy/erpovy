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
        Schema::table('sm_service_records', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_service_records', 'next_planned_date')) {
                $table->date('next_planned_date')->nullable()->after('service_date');
            }
            if (!Schema::hasColumn('sm_service_records', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sm_service_records', function (Blueprint $table) {
            if (Schema::hasColumn('sm_service_records', 'next_planned_date')) {
                $table->dropColumn('next_planned_date');
            }
            if (Schema::hasColumn('sm_service_records', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
        });
    }
};
