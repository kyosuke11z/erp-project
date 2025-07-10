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
            // Using decimal is recommended for monetary values.
            // You can adjust precision (8) and scale (2) as needed.
            // 'after' places the column after the 'description' column for better organization.
            $table->decimal('selling_price', 8, 2)->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('selling_price');
        });
    }
};
