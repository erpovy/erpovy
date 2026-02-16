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
        Schema::create('logistics_shipment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipment_id')->constrained('logistics_shipments')->onDelete('cascade');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Değişikliği yapan kullanıcı
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logistics_shipment_logs');
    }
};
