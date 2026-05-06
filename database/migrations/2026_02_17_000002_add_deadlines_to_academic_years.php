<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->date('stage1_deadline')->after('status')->nullable()->comment('Batas Waktu Pembayaran Tahap 1');
            $table->date('stage2_deadline')->after('stage1_deadline')->nullable()->comment('Batas Waktu Pembayaran Tahap 2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropColumn(['stage1_deadline', 'stage2_deadline']);
        });
    }
};
