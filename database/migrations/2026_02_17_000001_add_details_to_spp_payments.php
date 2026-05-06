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
        Schema::table('spp_payments', function (Blueprint $table) {
            $table->enum('stage', ['1', '2', 'full'])->after('academic_year_id')->nullable()->comment('Tahap Pembayaran (1, 2, atau full)');
            $table->date('deadline')->after('payment_date')->nullable()->comment('Batas Waktu Pembayaran');
            $table->decimal('late_fee', 10, 2)->default(0)->after('amount')->comment('Denda Keterlambatan');
            $table->boolean('is_late_fee_waived')->default(false)->after('late_fee')->comment('Denda Dibebaskan (Izin Telat)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spp_payments', function (Blueprint $table) {
            $table->dropColumn(['stage', 'deadline', 'late_fee', 'is_late_fee_waived']);
        });
    }
};
