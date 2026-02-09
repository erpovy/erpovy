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
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_station_id')->constrained('work_stations')->cascadeOnDelete();
            $table->string('title');
            $table->string('type')->default('preventive'); // preventive, corrective, emergency
            $table->string('status')->default('planned'); // planned, in_process, completed
            $table->string('priority')->default('medium'); // low, medium, high
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('technician_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
