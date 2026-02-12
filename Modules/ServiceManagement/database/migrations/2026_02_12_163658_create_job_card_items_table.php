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
        if (!Schema::hasTable('sm_job_card_items')) {
            Schema::create('sm_job_card_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_card_id')->constrained('sm_job_cards')->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete(); // Link to Inventory if part logic used
                
                $table->string('type')->default('part'); // part, labor, service, other
                $table->string('name'); // Description of item/service
                $table->text('description')->nullable();
                
                $table->decimal('quantity', 10, 2)->default(1);
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->decimal('tax_rate', 5, 2)->default(0); // 0, 10, 20 etc.
                $table->decimal('total_price', 15, 2)->default(0);
                
                $table->boolean('is_stock_deducted')->default(false); // To track if we reduced inventory
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_job_card_items');
    }
};
