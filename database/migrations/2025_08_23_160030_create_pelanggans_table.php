<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('id_pelanggan')->unique(); // ID pelanggan unik
            $table->string('nama_pelanggan');
            $table->string('bandwidth');
            $table->string('alamat');
            $table->decimal('latitude', 10, 7)->nullable(); // Koordinat peta (latitude)
            $table->decimal('longitude', 10, 7)->nullable(); // Koordinat peta (longitude)
            $table->string('no_telepon');
            $table->string('cluster');
            $table->string('kode_fat')->nullable(); // Kode kotak distribusi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
