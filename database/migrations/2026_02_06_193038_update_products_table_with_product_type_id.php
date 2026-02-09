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
            $table->foreignId('product_type_id')->nullable()->after('name')->constrained('product_types')->nullOnDelete();
        });

        // Migrate existing data
        // Get IDs of default types
        $goodTypeId = DB::table('product_types')->where('code', 'good')->value('id');
        $serviceTypeId = DB::table('product_types')->where('code', 'service')->value('id');

        // Update products based on old enum 'type'
        if ($goodTypeId) {
            DB::table('products')->where('type', 'good')->update(['product_type_id' => $goodTypeId]);
        }
        if ($serviceTypeId) {
            DB::table('products')->where('type', 'service')->update(['product_type_id' => $serviceTypeId]);
        }

        // Drop the old column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('type', ['good', 'service'])->default('good')->after('name');
        });

        // Restore data (basic mapping)
        $goodTypeId = DB::table('product_types')->where('code', 'good')->value('id');
        
        if ($goodTypeId) {
            DB::table('products')->where('product_type_id', $goodTypeId)->update(['type' => 'good']);
            DB::table('products')->where('product_type_id', '!=', $goodTypeId)->update(['type' => 'service']);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_type_id']);
            $table->dropColumn('product_type_id');
        });
    }
};
