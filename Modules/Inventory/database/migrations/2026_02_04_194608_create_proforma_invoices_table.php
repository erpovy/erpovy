<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proforma_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->comment('Oluşturan kullanıcı');
            $table->string('proforma_number')->unique();
            $table->date('proforma_date');
            $table->date('valid_until')->comment('Geçerlilik tarihi');
            
            // Tedarikçi Bilgileri (opsiyonel - henüz tedarikçi modülü yok)
            $table->string('supplier_name')->nullable();
            $table->text('supplier_address')->nullable();
            $table->string('supplier_email')->nullable();
            $table->string('supplier_phone')->nullable();
            
            // Ürün Bilgileri (JSON)
            $table->json('items')->comment('Ürün listesi: [{product_id, name, qty, unit_price, total}]');
            
            // Tutarlar
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            
            // Notlar
            $table->text('notes')->nullable();
            $table->text('terms')->nullable()->comment('Şartlar ve koşullar');
            
            // Durum
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'converted'])->default('draft');
            $table->foreignId('converted_to_invoice_id')->nullable()->comment('Faturaya dönüştürüldü mü?');
            
            $table->timestamps();
            $table->softDeletes();
            
            // İndeksler
            $table->index('proforma_number');
            $table->index('proforma_date');
            $table->index('status');
            $table->index(['company_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proforma_invoices');
    }
};
