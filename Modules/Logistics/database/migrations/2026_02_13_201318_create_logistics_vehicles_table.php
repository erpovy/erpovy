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
        Schema::create('logistics_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('plate_number')->unique();
            $table->string('type'); // truck, van, motorcycle
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->decimal('capacity_weight', 10, 2)->nullable();
            $table->decimal('capacity_volume', 10, 2)->nullable();
            $table->string('status')->default('available'); // available, active, maintenance
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics_vehicles');
    }
};
