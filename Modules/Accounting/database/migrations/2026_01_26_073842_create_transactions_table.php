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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('fiscal_period_id')->constrained('fiscal_periods');
            $table->string('type')->default('regular'); // opening, closing, regular
            $table->string('receipt_number');
            $table->date('date');
            $table->string('description')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'receipt_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
