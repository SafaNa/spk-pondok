<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'licensing_officer', 'department_officer', 'finance_officer', 'finance_secretary') NOT NULL DEFAULT 'department_officer'");
        } else {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'licensing_officer', 'department_officer', 'finance_officer', 'finance_secretary'))");
        }
    }

    public function down(): void
    {
        DB::statement("DELETE FROM users WHERE role = 'finance_secretary'");
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'licensing_officer', 'department_officer', 'finance_officer') NOT NULL DEFAULT 'department_officer'");
        } else {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'licensing_officer', 'department_officer', 'finance_officer'))");
        }
    }
};
