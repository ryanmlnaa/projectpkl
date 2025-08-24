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
            $table->string('cluster'); // cluster competitor
            $table->string('competitor_name');
            $table->decimal('harga', 15, 2);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('competitors');
    }
};
