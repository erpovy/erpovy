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
        Schema::create('logistics_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('logistics_vehicles')->onDelete('set null');
            $table->string('name');
            $table->date('planned_date');
            $table->string('status')->default('draft'); // draft, optimized, in_progress, completed
            $table->json('stops')->nullable(); // JSON list of stops/locations
            $table->decimal('total_distance', 10, 2)->nullable();
            $table->integer('estimated_duration')->nullable(); // in minutes
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics_routes');
    }
};
