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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['received', 'issued'])->comment('Alınan/Verilen');
            $table->string('cheque_number');
            $table->string('bank_name');
            $table->string('branch')->nullable();
            $table->string('account_number')->nullable();
            $table->string('drawer')->comment('Keşideci');
            $table->string('endorser')->nullable()->comment('Ciro eden');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('TRY');
            $table->date('issue_date')->comment('Keşide tarihi');
            $table->date('due_date')->comment('Vade tarihi');
            $table->enum('status', ['portfolio', 'deposited', 'cashed', 'bounced', 'transferred', 'cancelled'])
                  ->default('portfolio')
                  ->comment('Portföy/Yatırıldı/Tahsil edildi/Karşılıksız/Ciro edildi/İptal');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cash_bank_account_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'due_date']);
            $table->index('cheque_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
