<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industri', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('bidang_usaha');
            $table->text('alamat');
            $table->string('kontak', 20)->nullable();
            $table->string('email')->nullable();
            $table->foreignId('guru_pembimbing')->nullable()->constrained('guru')->cascadeOnDelete(); // <-- fix
            $table->string('website');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('industri'); // <-- fix
    }
};