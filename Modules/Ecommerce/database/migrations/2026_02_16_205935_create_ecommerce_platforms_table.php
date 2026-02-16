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
        Schema::create('ecommerce_platforms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('woocommerce'); // 'woocommerce', 'shopify', etc.
            $table->string('store_url');
            $table->text('consumer_key')->nullable();
            $table->text('consumer_secret')->nullable();
            $table->string('status')->default('active');
            $table->json('settings')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_platforms');
    }
};
