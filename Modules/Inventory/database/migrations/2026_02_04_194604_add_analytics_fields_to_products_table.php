<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tedarik ve Stok Yönetimi
            $table->integer('lead_time_days')->default(7)->after('critical_stock_level')
                ->comment('Tedarik süresi (gün)');
            $table->integer('safety_stock_level')->nullable()->after('lead_time_days')
                ->comment('Güvenlik stoku seviyesi');
            $table->integer('reorder_point')->nullable()->after('safety_stock_level')
                ->comment('Yeniden sipariş noktası');
            $table->integer('max_stock_level')->nullable()->after('reorder_point')
                ->comment('Maksimum stok seviyesi');
            
            // ABC Sınıflandırması
            $table->enum('abc_classification', ['A', 'B', 'C'])->nullable()->after('max_stock_level')
                ->comment('ABC analiz sınıfı');
            
            // Analiz Tarihleri
            $table->timestamp('last_stock_analysis_at')->nullable()->after('abc_classification')
                ->comment('Son stok analizi tarihi');
            
            // İndeksler
            $table->index('abc_classification');
            $table->index('last_stock_analysis_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['abc_classification']);
            $table->dropIndex(['last_stock_analysis_at']);
            
            $table->dropColumn([
                'lead_time_days',
                'safety_stock_level',
                'reorder_point',
                'max_stock_level',
                'abc_classification',
                'last_stock_analysis_at',
            ]);
        });
    }
};
