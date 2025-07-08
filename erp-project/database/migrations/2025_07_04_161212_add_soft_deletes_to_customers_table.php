<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * เมธอดนี้จะทำงานเมื่อเรารัน php artisan migrate
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // เพิ่มคอลัมน์ deleted_at สำหรับ Soft Deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     * เมธอดนี้จะทำงานเมื่อมีการ rollback
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // ลบคอลัมน์ deleted_at หากมีการ rollback
            $table->dropSoftDeletes();
        });
    }
};
