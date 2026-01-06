<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kategori_pelanggaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_kategori'); // Ringan, Sedang, Berat
            $table->string('kode_kategori')->unique(); // R, S, B
            $table->integer('bobot_poin'); // 1, 3, 5
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_pelanggaran');
    }
};
