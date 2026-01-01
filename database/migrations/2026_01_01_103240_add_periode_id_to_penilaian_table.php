<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('penilaian', function (Blueprint $table) {
            if (!Schema::hasColumn('penilaian', 'periode_id')) {
                $table->uuid('periode_id')->nullable()->after('subkriteria_id');
            }
        });

        Schema::table('penilaian', function (Blueprint $table) {
            // Check if foreign key exists is hard, so we assume if column existed, FK might not.
            // But to be safe, we can try-catch or just rely on 'if not exists' logic not being standard for FK.
            // Let's just add the FK. If it fails due to duplicate constraint, we might need manual fix.
            // However, the previous failure was "Cannot add foreign key", so it wasn't added.

            // We need to name the foreign key explicitly to check or drop it, but allow Laravel to generate current one.
            // $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
        });

        Schema::table('penilaian', function (Blueprint $table) {
            // We drop the unique index inside a try-catch to avoid errors if it doesn't exist.
            // We can't put try-catch inside the closure effectively so we do it via raw SQL or separate Schema call wrapped.
        });

        // No need to drop old unique as it was removed from create_penilaians_table migration

        Schema::table('penilaian', function (Blueprint $table) {
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
            $table->unique(['santri_id', 'kriteria_id', 'periode_id']);
        });
    }

    public function down()
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropIndex(['santri_id', 'kriteria_id', 'periode_id']); // Drop the new unique index
            $table->dropColumn('periode_id');

            // Restore old unique constraint
            $table->unique(['santri_id', 'kriteria_id']);
        });
    }
};
