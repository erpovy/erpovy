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
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixed_asset_id');
            $table->date('maintenance_date');
            $table->date('next_maintenance_date')->nullable();
            $table->string('type'); // Routine, Repair, Upgrade
            $table->decimal('cost', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('performed_by')->nullable(); // Vendor or Employee Name
            $table->timestamps();

            $table->foreign('fixed_asset_id')->references('id')->on('fixed_assets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
