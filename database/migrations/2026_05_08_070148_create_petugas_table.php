<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // #PTG-00124
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('foto')->nullable();
            $table->string('spesialisasi');
            $table->enum('status', ['tersedia', 'bertugas', 'istirahat'])->default('tersedia');
            $table->integer('beban_kerja')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
