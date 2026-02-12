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
        Schema::create('sm_service_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('sm_vehicles')->onDelete('cascade');
            $table->string('service_type'); // periodic, repair, cleaning, emergency
            $table->date('service_date');
            $table->integer('mileage_at_service');
            $table->text('description');
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->string('performed_by')->nullable();
            $table->string('status')->default('completed'); // pending, in_progress, completed
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_service_records');
    }
};
