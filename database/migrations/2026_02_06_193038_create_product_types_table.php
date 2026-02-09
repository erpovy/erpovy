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
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index(); // Null for global types
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default types
        DB::table('product_types')->insert([
            [
                'name' => 'Stoklu Ürün',
                'code' => 'good', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Hizmet', 
                'code' => 'service', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_types');
    }
};
