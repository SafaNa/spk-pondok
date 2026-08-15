<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate existing string values → JSON array
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL: convert existing string to JSON array
            DB::statement("
                UPDATE student_licenses
                SET attachment = CASE
                    WHEN attachment IS NULL OR attachment = '' THEN NULL
                    ELSE to_jsonb(ARRAY[attachment])::jsonb
                END
            ");
            Schema::table('student_licenses', function (Blueprint $table) {
                $table->jsonb('attachment')->nullable()->change();
            });
        } else {
            // MySQL: convert string to JSON array
            DB::statement("
                UPDATE student_licenses
                SET attachment = CASE
                    WHEN attachment IS NULL OR attachment = '' THEN NULL
                    ELSE JSON_ARRAY(attachment)
                END
                WHERE attachment IS NOT NULL AND attachment != '' AND attachment NOT LIKE '[%'
            ");
            Schema::table('student_licenses', function (Blueprint $table) {
                $table->json('attachment')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // Revert JSON → first element string (lossy, but acceptable for rollback)
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("
                UPDATE student_licenses
                SET attachment = attachment->0
                WHERE attachment IS NOT NULL
            ");
            Schema::table('student_licenses', function (Blueprint $table) {
                $table->string('attachment')->nullable()->change();
            });
        } else {
            DB::statement("
                UPDATE student_licenses
                SET attachment = JSON_UNQUOTE(JSON_EXTRACT(attachment, '$[0]'))
                WHERE attachment IS NOT NULL
            ");
            Schema::table('student_licenses', function (Blueprint $table) {
                $table->string('attachment')->nullable()->change();
            });
        }
    }
};
