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
        Schema::table('products', function (Blueprint $table) {
            // İlişkiler
            $table->foreignId('category_id')->nullable()->after('company_id')->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->after('category_id')->constrained('brands')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->after('brand_id')->constrained('units')->nullOnDelete();
            
            // Barkod ve tanımlayıcılar
            $table->string('barcode')->nullable()->unique()->after('code');
            
            // Fiziksel özellikler
            $table->decimal('weight', 10, 3)->nullable()->after('vat_rate')->comment('Ağırlık (kg)');
            $table->json('dimensions')->nullable()->after('weight')->comment('Boyutlar: {width, height, depth} cm');
            
            // Görsel ve açıklama
            $table->string('image_path')->nullable()->after('dimensions');
            $table->text('description')->nullable()->after('image_path');
            
            // Garanti ve stok yönetimi
            $table->integer('warranty_period')->nullable()->after('description')->comment('Garanti süresi (ay)');
            $table->integer('critical_stock_level')->nullable()->after('min_stock_level')->comment('Kritik stok seviyesi');
            
            // Durum
            $table->boolean('is_active')->default(true)->after('stock_track');
            
            // İndeksler
            $table->index('barcode');
            $table->index(['category_id', 'brand_id']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['unit_id']);
            
            $table->dropIndex(['barcode']);
            $table->dropIndex(['category_id', 'brand_id']);
            $table->dropIndex(['is_active']);
            
            $table->dropColumn([
                'category_id',
                'brand_id',
                'unit_id',
                'barcode',
                'weight',
                'dimensions',
                'image_path',
                'description',
                'warranty_period',
                'critical_stock_level',
                'is_active',
            ]);
        });
    }
};
