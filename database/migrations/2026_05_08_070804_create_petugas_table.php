<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('id_petugas')->unique();
            $table->string('spesialisasi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->integer('beban_kerja')->default(0);
            $table->string('lokasi_saat_ini')->nullable();
            $table->timestamps();
        });

        Schema::table('laporans', function (Blueprint $table) {
            $table->foreign('petugas_id')->references('id')->on('petugas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
        });

        Schema::dropIfExists('petugas');
    }
};
