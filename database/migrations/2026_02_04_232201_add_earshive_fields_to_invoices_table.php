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
            $table->uuid('ettn')->nullable()->unique()->after('invoice_number')->comment('Evrensel Tekil Tanımlayıcı (UUID)');
            $table->string('invoice_type')->default('SATIS')->after('ettn')->comment('SATIS, IADE, TEVKIFAT vb.');
            $table->string('invoice_scenario')->default('EARSIV')->after('invoice_type')->comment('EARSIV, EARSIV_BILGI_FISI');
            $table->string('gib_status')->default('draft')->after('status')->comment('GIB Durumu: draft, signed, sent, approved');
            $table->json('receiver_info')->nullable()->after('notes')->comment('Alıcı bilgileri snapshot (VKN, Adres vb)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['ettn', 'invoice_type', 'invoice_scenario', 'gib_status', 'receiver_info']);
        });
    }
};
