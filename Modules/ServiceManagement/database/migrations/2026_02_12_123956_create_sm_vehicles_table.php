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
        Schema::create('sm_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('plate_number')->unique();
            $table->string('brand');
            $table->string('model');
            $table->integer('year')->nullable();
            $table->string('vin')->nullable()->unique();
            $table->string('color')->nullable();
            $table->integer('current_mileage')->default(0);
            $table->string('status')->default('active'); // active, maintenance, inactive
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_vehicles');
    }
};
