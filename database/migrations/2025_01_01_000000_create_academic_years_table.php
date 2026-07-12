<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->bigInteger('spp_amount')->default(0);
            $table->unsignedInteger('max_leaves')->nullable();
            $table->date('stage1_deadline')->nullable()->comment('Batas Waktu Pembayaran Tahap 1');
            $table->date('stage2_deadline')->nullable()->comment('Batas Waktu Pembayaran Tahap 2');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
