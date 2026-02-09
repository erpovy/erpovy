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
        Schema::create('vat_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name'); // KDV %20, KDV %8 vb.
            $table->decimal('rate', 5, 2); // 1.00, 8.00, 10.00, 20.00
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable()->comment('Bu orandan itibaren geçerli');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vat_rates');
    }
};
