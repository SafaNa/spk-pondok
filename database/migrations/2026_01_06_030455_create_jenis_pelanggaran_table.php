<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('jenis_pelanggaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('departemen_id');
            $table->uuid('kategori_pelanggaran_id');
            $table->string('kode_pelanggaran')->unique();
            $table->string('nama_pelanggaran');
            $table->text('deskripsi')->nullable();
            $table->text('sanksi_default');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign keys
            $table->foreign('departemen_id')->references('id')->on('departemen')->onDelete('restrict');
            $table->foreign('kategori_pelanggaran_id')->references('id')->on('kategori_pelanggaran')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_pelanggaran');
    }
};
