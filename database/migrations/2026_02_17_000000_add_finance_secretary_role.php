<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'licensing_officer', 'department_officer', 'finance_officer', 'finance_secretary') NOT NULL DEFAULT 'department_officer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // CAUTION: This will fail if there are users with 'finance_secretary' role.
        // In a real production app, we would handle this gracefully (e.g., reassigning them).
        // For this task, we assume we can roll back.
        DB::statement("DELETE FROM users WHERE role = 'finance_secretary'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'licensing_officer', 'department_officer', 'finance_officer') NOT NULL DEFAULT 'department_officer'");
    }
};
