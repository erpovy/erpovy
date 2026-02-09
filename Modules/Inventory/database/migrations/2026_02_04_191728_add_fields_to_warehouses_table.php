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
        Schema::table('warehouses', function (Blueprint $table) {
            // Kod alanı ekle (benzersiz)
            $table->string('code')->unique()->after('name');
            
            // Yönetici
            $table->foreignId('manager_id')->nullable()->after('address')->constrained('users')->nullOnDelete();
            
            // Depo tipi ve aktif durumu
            $table->enum('type', ['main', 'branch', 'virtual'])->default('main')->after('manager_id');
            $table->boolean('is_active')->default(true)->after('type');
            
            // İndeksler
            $table->index('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            
            $table->dropIndex(['code']);
            $table->dropIndex(['is_active']);
            
            $table->dropColumn([
                'code',
                'manager_id',
                'type',
                'is_active',
            ]);
        });
    }
};
