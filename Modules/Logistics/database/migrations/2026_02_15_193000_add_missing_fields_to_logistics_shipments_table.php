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
        Schema::table('logistics_shipments', function (Blueprint $table) {
            if (!Schema::hasColumn('logistics_shipments', 'origin')) {
                $table->string('origin')->nullable()->after('contact_id');
            }
            if (!Schema::hasColumn('logistics_shipments', 'destination')) {
                $table->string('destination')->nullable()->after('origin');
            }
            if (!Schema::hasColumn('logistics_shipments', 'weight_kg')) {
                $table->decimal('weight_kg', 10, 2)->nullable()->after('weight');
            }
            if (!Schema::hasColumn('logistics_shipments', 'volume_m3')) {
                $table->decimal('volume_m3', 10, 2)->nullable()->after('weight_kg');
            }
            if (!Schema::hasColumn('logistics_shipments', 'estimated_delivery')) {
                $table->date('estimated_delivery')->nullable()->after('volume_m3');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logistics_shipments', function (Blueprint $table) {
            $table->dropColumn(['origin', 'destination', 'weight_kg', 'volume_m3', 'estimated_delivery']);
        });
    }
};
