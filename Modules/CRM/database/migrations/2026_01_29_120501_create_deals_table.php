<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete(); // Can be converted from lead
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete(); // Or linked to existing contact
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('currency')->default('TRY');
            $table->string('stage'); // Pipeline stage
            $table->date('expected_close_date')->nullable();
            $table->integer('probability')->default(0); // Win probability %
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('lost_reason')->nullable(); // If lost
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
