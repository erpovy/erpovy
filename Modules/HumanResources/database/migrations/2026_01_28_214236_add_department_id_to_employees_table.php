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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
            // We keep the old column for now or drop it? Plan said drop. But let's check if we want to be safe.
            // Plan: Migration (department column -> department_id)
            // User approved plan.
            $table->dropColumn('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('department')->nullable();
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
