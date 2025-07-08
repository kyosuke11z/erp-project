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
        Schema::create('customers', function (Blueprint $table) {
          $table->id(); // รหัสลูกค้า (Primary Key)
            $table->string('name'); // ชื่อ-นามสกุล ลูกค้า
            $table->string('email')->unique(); // อีเมล (ไม่ซ้ำกัน)
            $table->string('phone')->nullable(); // เบอร์โทรศัพท์ (อาจมีหรือไม่มีก็ได้)
            $table->text('address')->nullable(); // ที่อยู่ (อาจมีหรือไม่มีก็ได้)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
