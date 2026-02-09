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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('contact_id')->index(); // Customer
            $table->unsignedBigInteger('product_id')->nullable()->index(); // Service/Product
            $table->string('name');
            $table->decimal('price', 15, 2)->default(0);
            $table->enum('billing_interval', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->enum('status', ['active', 'suspended', 'cancelled', 'expired'])->default('active');
            $table->date('start_date');
            $table->date('next_billing_date')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
