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
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('contact_id')->index();
            $table->unsignedBigInteger('transaction_id')->nullable()->index(); // Muhasebe fişi referansı
            $table->unsignedBigInteger('invoice_id')->nullable()->index(); // Fatura referansı
            $table->enum('type', ['debit', 'credit'])->comment('debit=borç, credit=alacak');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->decimal('balance_after', 15, 2)->comment('İşlem sonrası bakiye');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_transactions');
    }
};
