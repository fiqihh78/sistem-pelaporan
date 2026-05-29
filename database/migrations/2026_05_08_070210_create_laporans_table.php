<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // #REP-8234
            $table->string('pelapor');
            $table->string('foto_pelapor')->nullable();
            $table->foreignId('kategori_id')->constrained('kategoris');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->string('foto_sebelum')->nullable();
            $table->string('foto_sesudah')->nullable();
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->enum('prioritas', ['high', 'medium', 'low'])->default('medium');
            $table->boolean('terverifikasi')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
