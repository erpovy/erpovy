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
        if (!Schema::hasTable('sm_job_cards')) {
            Schema::create('sm_job_cards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained()->onDelete('cascade');
                $table->foreignId('vehicle_id')->constrained('sm_vehicles')->onDelete('cascade');
                $table->foreignId('customer_id')->nullable()->constrained('contacts')->nullOnDelete(); // Redundant but good for indexing/queries
                
                $table->string('job_number')->unique(); // e.g. JC-2024-0001
                $table->string('status')->default('pending'); // pending, diagnosing, approved, parts_ordered, in_progress, completed, invoiced, cancelled
                $table->string('priority')->default('normal'); // low, normal, high, urgent
                
                $table->dateTime('entry_date');
                $table->dateTime('expected_completion_date')->nullable();
                $table->dateTime('actual_completion_date')->nullable();
                
                $table->text('customer_complaint')->nullable(); // What the customer says is wrong
                $table->text('diagnosis')->nullable(); // What the technician found
                $table->text('internal_notes')->nullable();
                
                $table->integer('odometer_reading')->nullable(); // Middleware at intake
                $table->decimal('fuel_level', 3, 2)->nullable(); // 0.00 to 1.00 (optional)

                $table->decimal('total_parts', 15, 2)->default(0);
                $table->decimal('total_labor', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_job_cards');
    }
};
