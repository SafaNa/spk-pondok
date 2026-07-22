<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_licenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignUuid('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->enum('type', ['mass', 'individual'])->default('mass');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignUuid('leave_category_id')->nullable()->constrained('leave_categories')->nullOnDelete();
            $table->foreignUuid('leave_reason_id')->nullable()->constrained('leave_reasons')->nullOnDelete();
            $table->boolean('is_emergency')->default(false);
            $table->date('actual_return_date')->nullable();
            $table->enum('source', ['admin', 'guardian'])->nullable();
            $table->string('attachment')->nullable();
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
            $table->text('return_notes')->nullable();
            
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            $table->string('creator_type')->nullable();
            $table->string('creator_id')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_licenses');
    }
};
