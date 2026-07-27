<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mass_leaves', function (Blueprint $table) {
            $table->timestamp('bulk_checkout_at')->nullable()->after('status');
            $table->string('bulk_checkout_by')->nullable()->after('bulk_checkout_at');
        });
    }

    public function down(): void
    {
        Schema::table('mass_leaves', function (Blueprint $table) {
            $table->dropColumn(['bulk_checkout_at', 'bulk_checkout_by']);
        });
    }
};
