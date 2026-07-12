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
        Schema::create('student_memorization_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_memorization_id');
            $table->uuid('memorization_type_id');
            $table->boolean('is_checked')->default(false);
            $table->timestamps();

            $table->foreign('student_memorization_id')->references('id')->on('student_memorizations')->onDelete('cascade');
            $table->foreign('memorization_type_id')->references('id')->on('memorization_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_memorization_items');
    }
};
