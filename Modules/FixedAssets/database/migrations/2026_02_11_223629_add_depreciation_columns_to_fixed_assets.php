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
        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->integer('useful_life_years')->nullable()->after('purchase_value');
            $table->string('depreciation_method')->default('straight_line')->after('useful_life_years'); // straight_line, declining_balance
            $table->boolean('prorata')->default(false)->after('depreciation_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->dropColumn(['useful_life_years', 'depreciation_method', 'prorata']);
        });
    }
};
