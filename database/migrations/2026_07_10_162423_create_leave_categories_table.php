<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('max_duration')->nullable();
            $table->boolean('is_fixed_duration')->default(false);
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('leave_reasons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('leave_category_id')->constrained()->cascadeOnDelete();
            $table->string('reason');
            $table->boolean('can_skip_validation')->default(false);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_reasons');
        Schema::dropIfExists('leave_categories');
    }
};
