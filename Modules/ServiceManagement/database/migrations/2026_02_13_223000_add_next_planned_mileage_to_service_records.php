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
            if (!Schema::hasColumn('sm_service_records', 'next_planned_mileage')) {
                $table->integer('next_planned_mileage')->nullable()->after('next_planned_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sm_service_records', function (Blueprint $table) {
            if (Schema::hasColumn('sm_service_records', 'next_planned_mileage')) {
                $table->dropColumn('next_planned_mileage');
            }
        });
    }
};
