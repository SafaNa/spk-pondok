<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'pengurus_departemen', 'pengurus_perizinan'])->default('admin')->after('password');
            $table->uuid('departemen_id')->nullable()->after('role');

            // Foreign key to departemen table
            $table->foreign('departemen_id')->references('id')->on('departemen')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['departemen_id']);
            $table->dropColumn(['role', 'departemen_id']);
        });
    }
};
