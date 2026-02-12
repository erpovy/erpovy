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
        Schema::table('sm_vehicles', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('company_id')->constrained('contacts')->nullOnDelete();
            $table->string('chassis_number')->nullable()->after('vin'); // VIN is already there but usually same, let's keep consistent if needed or just use vin
             // Actually, the plan said "chassis_number (VIN)", but the table *already* has 'vin'.
             // Let's just add customer_id for now and maybe rename plate_number -> license_plate if really needed, but plate_number is fine.
             // I'll stick to just adding customer_id.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sm_vehicles', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            // $table->dropColumn('chassis_number');
        });
    }
};
