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
        Schema::table('stock_movements', function (Blueprint $table) {
            // Kullanıcı takibi
            $table->foreignId('user_id')->nullable()->after('company_id')->constrained('users')->nullOnDelete();
            
            // Belge referansı
            $table->string('document_type')->nullable()->after('reference')->comment('invoice, order, transfer, etc.');
            $table->unsignedBigInteger('document_id')->nullable()->after('document_type');
            
            // Maliyet bilgisi
            $table->decimal('unit_cost', 15, 2)->nullable()->after('quantity')->comment('Birim maliyet');
            
            // Notlar
            $table->text('notes')->nullable()->after('unit_cost');
            
            // İndeksler
            $table->index('user_id');
            $table->index(['document_type', 'document_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            
            $table->dropIndex(['user_id']);
            $table->dropIndex(['document_type', 'document_id']);
            $table->dropIndex(['created_at']);
            
            $table->dropColumn([
                'user_id',
                'document_type',
                'document_id',
                'unit_cost',
                'notes',
            ]);
        });
    }
};
