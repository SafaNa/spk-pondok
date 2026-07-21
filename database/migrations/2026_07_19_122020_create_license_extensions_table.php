<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('license_extensions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_license_id')->constrained('student_licenses')->cascadeOnDelete();
            $table->date('requested_new_end_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('source', ['guardian', 'admin'])->default('guardian');
            $table->string('attachment')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            // Morph: siapa yang membuat (Guardian atau User/admin)
            $table->string('created_by_type')->nullable();
            $table->uuid('created_by_id')->nullable();
            $table->index(['created_by_type', 'created_by_id'], 'lex_created_by_index');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_extensions');
    }
};
