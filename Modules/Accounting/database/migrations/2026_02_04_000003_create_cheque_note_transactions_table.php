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
        Schema::create('cheque_note_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->enum('transaction_type', ['cheque', 'promissory_note']);
            $table->unsignedBigInteger('transaction_id')->comment('cheque_id veya promissory_note_id');
            $table->enum('action', ['received', 'issued', 'deposited', 'cashed', 'transferred', 'bounced', 'protested', 'cancelled'])
                  ->comment('Alındı/Verildi/Yatırıldı/Tahsil edildi/Ciro edildi/Karşılıksız/Protesto/İptal');
            $table->date('transaction_date');
            $table->decimal('amount', 15, 2);
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cash_bank_account_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index(['company_id', 'transaction_type', 'transaction_id']);
            $table->index(['company_id', 'transaction_date']);
            $table->index(['company_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheque_note_transactions');
    }
};
