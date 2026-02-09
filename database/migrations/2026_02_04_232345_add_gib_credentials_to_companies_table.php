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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('gib_username')->nullable()->after('name')->comment('GIB Portal Kullanıcı Adı');
            $table->string('gib_password')->nullable()->after('gib_username')->comment('GIB Portal Şifresi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['gib_username', 'gib_password']);
        });
    }
};
