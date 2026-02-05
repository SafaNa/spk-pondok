<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Identity
            $table->string('name'); // name
            $table->string('nis')->unique(); // nis
            $table->string('nik', 16)->nullable(); // nik
            $table->enum('gender', ['male', 'female']);
            $table->string('photo')->nullable(); // Foto Santri

            // Birth Info
            $table->string('birth_place'); // tempat_lahir
            $table->date('birth_date'); // tanggal_lahir

            // Education Levels
            // kelas Diniyah
            $table->foreignUuid('religious_education_level_id')->nullable()->constrained('education_levels')->nullOnDelete();
            // kelas Umum
            $table->foreignUuid('formal_education_level_id')->nullable()->constrained('education_levels')->nullOnDelete();

            // Region / Address
            // Desa, Kecamatan, Kabupaten, Provinsi -> Using Laravolt Codes
            $table->char('province_code', 2)->nullable();
            $table->char('city_code', 4)->nullable();
            $table->char('district_code', 7)->nullable();
            $table->char('village_code', 10)->nullable();
            $table->text('address')->nullable(); // Alamat Lengkap

            $table->foreign('province_code')->references('code')->on('indonesia_provinces')->onDelete('set null');
            $table->foreign('city_code')->references('code')->on('indonesia_cities')->onDelete('set null');
            $table->foreign('district_code')->references('code')->on('indonesia_districts')->onDelete('set null');
            $table->foreign('village_code')->references('code')->on('indonesia_villages')->onDelete('set null');

            // Rayon & Room
            $table->foreignUuid('rayon_id')->nullable()->constrained('rayons')->onDelete('set null');
            $table->foreignUuid('room_id')->nullable()->constrained('rooms')->onDelete('set null');

            // Parents
            $table->string('father_name')->nullable(); // nama ayah
            $table->string('father_education')->nullable(); // pendidikan ayah
            $table->string('father_occupation')->nullable(); // pekerjaan ayah
            $table->string('mother_name')->nullable(); // nama ibu
            $table->string('mother_education')->nullable(); // pendidikan ibu
            $table->string('mother_occupation')->nullable(); // pekerjaan ibu

            // Meta
            $table->date('entry_date')->nullable(); // tanggal diterima pondok
            $table->string('phone')->nullable(); // nomor (generic phone/contact)

            $table->enum('status', ['active', 'inactive', 'graduated', 'dropped_out'])->default('active'); // status



            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
