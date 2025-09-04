<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->string('id_pelanggan', 100)->primary();
            $table->string('nama_pelanggan', 255);
            $table->string('bandwidth', 100);
            $table->text('alamat');
            $table->string('provinsi', 100); // Tambahan field provinsi
            $table->string('kabupaten', 100); // Tambahan field kabupaten
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('nomor_telepon', 50);
            $table->string('cluster', 100);
            $table->string('kode_fat', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};