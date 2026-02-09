<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
            $table->date('analysis_date');
            
            // Satış Metrikleri
            $table->decimal('daily_avg_sales', 10, 2)->default(0);
            $table->decimal('weekly_avg_sales', 10, 2)->default(0);
            $table->decimal('monthly_avg_sales', 10, 2)->default(0);
            $table->enum('sales_trend', ['increasing', 'stable', 'decreasing'])->default('stable');
            
            // Stok Metrikleri
            $table->integer('current_stock')->default(0);
            $table->decimal('stock_value', 15, 2)->default(0);
            $table->integer('days_of_stock')->default(0)->comment('Kaç günlük stok var');
            $table->decimal('stock_turnover_rate', 10, 2)->default(0)->comment('Yıllık devir hızı');
            
            // Tahminler
            $table->date('predicted_stockout_date')->nullable();
            $table->integer('recommended_order_qty')->nullable();
            $table->date('recommended_order_date')->nullable();
            
            // Risk Skorları (0-100)
            $table->integer('stockout_risk_score')->default(0);
            $table->integer('overstock_risk_score')->default(0);
            $table->integer('obsolescence_risk_score')->default(0);
            
            // Sınıflandırmalar
            $table->char('abc_class', 1)->nullable();
            $table->enum('velocity_class', ['fast', 'medium', 'slow', 'dead'])->default('medium');
            
            $table->timestamps();
            
            // İndeksler
            $table->index('analysis_date');
            $table->index(['product_id', 'analysis_date']);
            $table->index(['company_id', 'analysis_date']);
            $table->index('stockout_risk_score');
            $table->index('abc_class');
            $table->unique(['product_id', 'warehouse_id', 'analysis_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_analytics');
    }
};
