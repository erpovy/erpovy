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
        Schema::create('ecommerce_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('ecommerce_platform_id')->constrained('ecommerce_platforms')->onDelete('cascade');
            $table->morphs('mappable'); // This will create mappable_id and mappable_type
            $table->string('external_id');
            $table->json('remote_data')->nullable();
            $table->timestamps();

            $table->unique(['ecommerce_platform_id', 'mappable_id', 'mappable_type'], 'platform_mappable_unique');
            $table->index(['ecommerce_platform_id', 'external_id'], 'platform_external_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_mappings');
    }
};
