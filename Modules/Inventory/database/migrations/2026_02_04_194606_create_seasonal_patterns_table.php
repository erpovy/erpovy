<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seasonal_patterns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('month')->comment('1-12');
            $table->decimal('seasonal_index', 5, 2)->default(1.00)
                ->comment('1.0 = normal, >1 = yüksek talep, <1 = düşük talep');
            $table->decimal('confidence_level', 5, 2)->default(0.00)
                ->comment('0-1 arası güven seviyesi');
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['company_id', 'product_id', 'month']);
            
            // İndeksler
            $table->index(['product_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasonal_patterns');
    }
};
