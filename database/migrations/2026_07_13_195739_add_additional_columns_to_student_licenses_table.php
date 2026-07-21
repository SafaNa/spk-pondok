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
        Schema::table('student_licenses', function (Blueprint $table) {
            if (!Schema::hasColumn('student_licenses', 'actual_return_date')) {
                $table->date('actual_return_date')->nullable()->after('end_date');
            }
            if (!Schema::hasColumn('student_licenses', 'return_notes')) {
                $table->text('return_notes')->nullable()->after('description');
            }
            if (!Schema::hasColumn('student_licenses', 'source')) {
                $table->enum('source', ['admin', 'guardian'])->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('student_licenses', 'attachment')) {
                $table->string('attachment')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('student_licenses', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable();
            }
            if (!Schema::hasColumn('student_licenses', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (!Schema::hasColumn('student_licenses', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }

            if (!Schema::hasColumn('student_licenses', 'creator_type')) {
                $table->string('creator_type')->after('rejected_at')->nullable();
                $table->string('creator_id')->after('creator_type')->nullable();
                $table->index(['creator_type', 'creator_id'], 'licenses_creator_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_licenses', function (Blueprint $table) {
            $columnsToDrop = [];
            
            $checkColumns = [
                'actual_return_date',
                'return_notes',
                'source',
                'attachment',
                'submitted_at',
                'approved_at',
                'rejected_at',
            ];

            foreach ($checkColumns as $col) {
                if (Schema::hasColumn('student_licenses', $col)) {
                    $columnsToDrop[] = $col;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            if (Schema::hasColumn('student_licenses', 'creator_type')) {
                $table->dropIndex('licenses_creator_index');
                $table->dropColumn(['creator_type', 'creator_id']);
            }
        });
    }
};
