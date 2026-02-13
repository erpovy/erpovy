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
        Schema::create('logistics_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('tracking_number')->unique();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->foreignId('contact_id')->constrained('contacts')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->string('shipping_method')->nullable(); // overland, air, sea
            $table->string('carrier_name')->nullable(); // Yurtiçi, Aras, etc.
            $table->string('carrier_tracking_no')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics_shipments');
    }
};
