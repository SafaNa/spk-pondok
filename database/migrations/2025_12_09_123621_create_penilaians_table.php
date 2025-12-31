<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignUuid('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->foreignUuid('subkriteria_id')->constrained('subkriteria')->onDelete('cascade');
            $table->decimal('nilai', 5, 2);
            $table->timestamps();

            $table->unique(['santri_id', 'kriteria_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian');
    }
};