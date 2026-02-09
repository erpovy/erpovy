<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->cascadeOnDelete();
            $table->foreignId('deal_id')->nullable()->constrained('deals')->setNullOnDelete();
            $table->decimal('value', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('Draft'); // Draft, Sent, Signed, Active, Expired, Terminated
            $table->text('description')->nullable();
            $table->text('content')->nullable(); // HTML or text content of contract
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete(); // Internal signer
            $table->string('customer_signer_name')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
