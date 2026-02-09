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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Adet, Kilogram, Litre, etc.
            $table->string('symbol'); // Adet, Kg, Lt, etc.
            $table->enum('type', ['piece', 'weight', 'volume', 'length', 'area', 'other'])->default('piece');
            $table->boolean('is_base_unit')->default(false); // Ana birim mi?
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
