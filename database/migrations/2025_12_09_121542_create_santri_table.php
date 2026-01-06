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
        Schema::create('santri', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nis')->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('nama_ortu');
            $table->string('no_hp_ortu');
            $table->float('nilai_akhir')->nullable();
            $table->enum('status', ['aktif', 'non-aktif', 'lulus', 'drop-out'])->default('aktif');

            // SPP & Hafalan fields (untuk SAW)
            $table->enum('status_spp', ['lunas', 'belum_lunas', 'menunggak'])->default('lunas');
            $table->decimal('jumlah_tunggakan_spp', 10, 2)->nullable();
            $table->enum('status_hafalan', ['lengkap', 'belum_lengkap'])->default('lengkap');
            $table->integer('jumlah_hafalan_tercapai')->nullable();
            $table->integer('target_hafalan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santri');
    }
};
