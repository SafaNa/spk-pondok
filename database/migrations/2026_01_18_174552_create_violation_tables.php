<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('violation_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // Ringan, Sedang, Berat
            $table->integer('points')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('violation_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignUuid('violation_category_id')->constrained('violation_categories')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name');
            $table->text('default_sanction');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('violation_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignUuid('violation_type_id')->constrained('violation_types');
            $table->foreignUuid('period_id')->constrained('periods');
            $table->date('date'); // tanggal_kejadian
            $table->text('sanction');
            $table->enum('sanction_status', ['pending', 'completed'])->default('pending'); // belum_selesai, selesai
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_records');
        Schema::dropIfExists('violation_types');
        Schema::dropIfExists('violation_categories');
    }
};
