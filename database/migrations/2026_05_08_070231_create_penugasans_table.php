<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporans');
            $table->foreignId('petugas_id')->constrained('petugas');
            $table->timestamp('ditugaskan_pada')->nullable();
            $table->enum('status', ['dalam_proses', 'selesai'])->default('dalam_proses');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};
