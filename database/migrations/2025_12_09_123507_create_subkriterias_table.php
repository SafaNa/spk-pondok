<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('subkriteria', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->string('nama_subkriteria');
            $table->decimal('nilai', 5, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subkriteria');
    }
};