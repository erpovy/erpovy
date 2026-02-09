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
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('reference_number')->unique();
            $table->date('check_date');
            $table->string('type')->default('final'); // incoming, in_process, final
            $table->string('status')->default('pass'); // pass, fail, conditional
            $table->decimal('checked_quantity', 10, 2)->default(0);
            $table->decimal('rejected_quantity', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('inspector_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
    }
};
