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
        Schema::table('accounts', function (Blueprint $table) {
            $table->integer('level')->default(3)->after('code')->comment('1=Ana Hesap, 2=Grup, 3=Detay');
            $table->boolean('is_system')->default(false)->after('is_active')->comment('Sistem hesabı mı? (Silinemez)');
            $table->text('description')->nullable()->after('name')->comment('Hesap açıklaması');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['level', 'is_system', 'description']);
        });
    }
};
