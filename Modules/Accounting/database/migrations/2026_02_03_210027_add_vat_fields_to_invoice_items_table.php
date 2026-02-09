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
        Schema::table('invoice_items', function (Blueprint $table) {
            // Her satır için KDV bilgileri
            $table->decimal('vat_rate', 5, 2)->default(20)->after('unit_price')->comment('KDV oranı (%)');
            $table->decimal('vat_amount', 15, 2)->default(0)->after('vat_rate')->comment('KDV tutarı');
            $table->decimal('line_total', 15, 2)->default(0)->after('vat_amount')->comment('Satır toplamı (KDV dahil)');
            
            // Eski tax_rate ve total alanlarını kaldırmıyoruz
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['vat_rate', 'vat_amount', 'line_total']);
        });
    }
};
