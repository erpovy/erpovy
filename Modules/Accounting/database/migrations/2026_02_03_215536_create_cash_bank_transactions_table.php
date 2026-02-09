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
        Schema::create('cash_bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('cash_bank_account_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null'); // Muhasebe fişi
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null'); // Cari
            
            $table->enum('type', ['income', 'expense', 'transfer']); // Gelir, Gider, Virman
            $table->enum('method', ['cash', 'transfer', 'credit_card', 'check', 'other'])->default('cash'); // Ödeme yöntemi
            
            $table->decimal('amount', 15, 2); // Tutar
            $table->date('transaction_date'); // İşlem tarihi
            $table->text('description'); // Açıklama
            $table->string('reference_number')->nullable(); // Referans no
            
            // Virman için hedef hesap
            $table->foreignId('target_account_id')->nullable()->constrained('cash_bank_accounts')->onDelete('set null');
            
            $table->decimal('balance_after', 15, 2); // İşlem sonrası bakiye
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_bank_transactions');
    }
};
