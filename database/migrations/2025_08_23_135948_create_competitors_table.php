<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitors', function (Blueprint $table) {
            $table->id();
            $table->string('cluster');                // cluster competitor
            $table->string('competitor_name');        // nama competitor
            $table->string('paket')->nullable();      // nama paket
            $table->string('kecepatan')->nullable();  // kecepatan paket
            $table->string('kuota')->nullable();      // kuota
            $table->decimal('harga', 15, 2);          // harga paket
            $table->string('fitur_tambahan')->nullable(); // fitur tambahan
            $table->text('keterangan')->nullable();   // keterangan lain
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitors');
    }
};
