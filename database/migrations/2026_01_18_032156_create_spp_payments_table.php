<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('spp_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignUuid('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->enum('stage', ['1', '2', 'full'])->nullable()->comment('Tahap Pembayaran');
            $table->bigInteger('amount');
            $table->decimal('late_fee', 10, 2)->default(0)->comment('Denda Keterlambatan');
            $table->boolean('is_late_fee_waived')->default(false)->comment('Denda Dibebaskan');
            $table->date('payment_date');
            $table->date('deadline')->nullable()->comment('Batas Waktu Pembayaran');
            $table->string('status')->default('paid');
            $table->text('note')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_payments');
    }
};
