<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('nik', 16)->nullable();
            $table->text('address')->nullable();
            $table->string('job')->nullable();
            $table->enum('relationship', ['father', 'mother', 'guardian', 'sibling'])->default('father');
            $table->timestamps();
        });

        Schema::create('student_guardian', function (Blueprint $table) {
            $table->uuid('guardian_id');
            $table->uuid('student_id');
            $table->primary(['guardian_id', 'student_id']);
            $table->foreign('guardian_id')->references('id')->on('guardians')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_guardian');
        Schema::dropIfExists('guardians');
    }
};
