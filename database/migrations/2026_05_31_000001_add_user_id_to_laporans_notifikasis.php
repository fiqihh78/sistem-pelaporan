<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah user_id ke laporans
        if (!Schema::hasColumn('laporans', 'user_id')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->foreignId('user_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('users')
                      ->nullOnDelete();
            });
        }

        // Tambah user_id & tipe ke notifikasis
        Schema::table('notifikasis', function (Blueprint $table) {
            if (!Schema::hasColumn('notifikasis', 'user_id')) {
                $table->foreignId('user_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('users')
                      ->nullOnDelete();
            }
            if (!Schema::hasColumn('notifikasis', 'tipe')) {
                $table->string('tipe')->default('info')->after('pesan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'tipe']);
        });
    }
};
