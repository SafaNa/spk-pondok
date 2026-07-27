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
        Schema::create('mass_leave_students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mass_leave_id')->constrained('mass_leaves')->cascadeOnDelete();
            $table->foreignUuid('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamp('checked_out_at')->nullable(); // When they left
            $table->timestamp('actual_return_date')->nullable(); // When they came back
            $table->foreignId('checked_out_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mass_leave_students');
    }
};
