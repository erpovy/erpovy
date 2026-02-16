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
            $table->enum('direction', ['in', 'out'])->default('out')->after('contact_id')->comment('Fatura yönü');
            $table->boolean('is_e_invoice')->default(false)->after('invoice_scenario')->comment('e-Fatura mı?');
            $table->boolean('is_e_archive')->default(false)->after('is_e_invoice')->comment('e-Arşiv mi?');
            $table->string('external_id')->nullable()->after('ettn')->comment('Entegratör/GİB Belge No');
            $table->longText('ubl_xml')->nullable()->after('external_id')->comment('Orijinal XML içeriği');
            $table->string('process_status')->nullable()->after('gib_status')->comment('İşlem durumu (Entegratör tarafı)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['direction', 'is_e_invoice', 'is_e_archive', 'external_id', 'ubl_xml', 'process_status']);
        });
    }
};
