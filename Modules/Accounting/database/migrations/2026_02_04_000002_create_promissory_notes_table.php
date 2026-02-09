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
        Schema::create('promissory_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['received', 'issued'])->comment('Alınan/Verilen');
            $table->string('note_number');
            $table->string('drawer')->comment('Borçlu');
            $table->string('endorser')->nullable()->comment('Ciro eden');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('TRY');
            $table->date('issue_date')->comment('Düzenleme tarihi');
            $table->date('due_date')->comment('Vade tarihi');
            $table->string('place_of_issue')->nullable()->comment('Düzenlenme yeri');
            $table->string('place_of_payment')->nullable()->comment('Ödeme yeri');
            $table->enum('status', ['portfolio', 'deposited', 'cashed', 'protested', 'transferred', 'cancelled'])
                  ->default('portfolio')
                  ->comment('Portföy/Yatırıldı/Tahsil edildi/Protesto/Ciro edildi/İptal');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cash_bank_account_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'due_date']);
            $table->index('note_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promissory_notes');
    }
};
