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
        Schema::table('invoices', function (Blueprint $table) {
            // KDV hesaplama alanları
            $table->decimal('subtotal', 15, 2)->default(0)->after('invoice_number')->comment('KDV hariç tutar');
            $table->decimal('vat_total', 15, 2)->default(0)->after('subtotal')->comment('Toplam KDV');
            $table->decimal('grand_total', 15, 2)->default(0)->after('vat_total')->comment('KDV dahil genel toplam');
            $table->decimal('vat_withholding', 15, 2)->default(0)->after('grand_total')->comment('KDV tevkifatı (varsa)');
            
            // Eski total_amount ve tax_amount alanlarını kaldırmıyoruz, geriye dönük uyumluluk için
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'vat_total', 'grand_total', 'vat_withholding']);
        });
    }
};
