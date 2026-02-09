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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('code')->index(); // SKU or Barcode
            $table->string('name');
            $table->enum('type', ['good', 'service'])->default('good');
            $table->decimal('sale_price', 15, 2)->default(0);
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(18); // %18 or %20
            $table->boolean('stock_track')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
