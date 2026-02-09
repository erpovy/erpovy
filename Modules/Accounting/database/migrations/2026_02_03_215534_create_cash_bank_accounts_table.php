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
        Schema::create('cash_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['cash', 'bank']); // Kasa veya Banka
            $table->string('name'); // Hesap adı
            $table->string('currency', 3)->default('TRY'); // Para birimi
            $table->decimal('opening_balance', 15, 2)->default(0); // Açılış bakiyesi
            $table->decimal('current_balance', 15, 2)->default(0); // Güncel bakiye
            
            // Banka hesabı için ek alanlar
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('account_number')->nullable();
            $table->string('iban')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_bank_accounts');
    }
};
