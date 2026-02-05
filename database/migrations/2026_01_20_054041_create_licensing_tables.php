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
        // Removed licensing_events table as per simplified requirement (Mass licensing needs no event/memorization)

        Schema::create('student_licenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Removed event_id foreign key
            $table->foreignUuid('student_id')->constrained('students')->cascadeOnDelete();
            $table->enum('type', ['mass', 'individual'])->default('mass');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('memorization_check')->default(false); // Only applies to individual licenses
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_licenses');
        // Schema::dropIfExists('licensing_events');
    }
};
