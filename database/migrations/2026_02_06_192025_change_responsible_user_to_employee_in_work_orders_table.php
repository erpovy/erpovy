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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['responsible_user_id']);
            $table->dropColumn('responsible_user_id');
            
            $table->foreignId('employee_id')->nullable()->after('product_id')->constrained('employees')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');

            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }
};
