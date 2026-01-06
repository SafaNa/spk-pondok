<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('riwayat_pelanggaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('santri_id');
            $table->uuid('jenis_pelanggaran_id');
            $table->uuid('periode_id');
            $table->date('tanggal_kejadian');
            $table->dateTime('tanggal_input');
            $table->text('sanksi');
            $table->enum('status_sanksi', ['belum_selesai', 'selesai'])->default('belum_selesai');
            $table->dateTime('tanggal_verifikasi')->nullable();
            $table->uuid('verified_by')->nullable();
            $table->text('catatan')->nullable();
            $table->uuid('created_by');
            $table->timestamps();

            // Foreign keys
            $table->foreign('santri_id')->references('id')->on('santri')->onDelete('cascade');
            $table->foreign('jenis_pelanggaran_id')->references('id')->on('jenis_pelanggaran')->onDelete('restrict');
            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_pelanggaran');
    }
};
