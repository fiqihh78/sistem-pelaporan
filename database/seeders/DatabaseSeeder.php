<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Petugas;
use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'     => 'Admin Utama',
            'email'    => 'admin@sistem.test',
            'password' => Hash::make('password'),
        ]);

        // Kategori
        $kategoris = [
            ['nama' => 'Infrastruktur',    'ikon' => '🔧', 'deskripsi' => 'Jalan, jembatan, bangunan umum', 'status' => 'aktif'],
            ['nama' => 'Kebersihan',       'ikon' => '🌿', 'deskripsi' => 'Sampah, selokan tersumbat, taman', 'status' => 'aktif'],
            ['nama' => 'Keamanan',         'ikon' => '🛡️', 'deskripsi' => 'Kriminalitas, gangguan ketertiban', 'status' => 'aktif'],
            ['nama' => 'Penerangan Jalan', 'ikon' => '💡', 'deskripsi' => 'Lampu jalan, kerusakan tiang', 'status' => 'non-aktif'],
        ];
        foreach ($kategoris as $k) Kategori::create($k);

        // Petugas
        $petugas = [
            ['kode' => '#PTG-00124', 'nama' => 'Ahmad Subarjo',  'email' => 'ahmad.s@admin.com', 'spesialisasi' => 'Infrastruktur', 'status' => 'tersedia', 'beban_kerja' => 0],
            ['kode' => '#PTG-00128', 'nama' => 'Siti Aminah',    'email' => 'siti.a@admin.com',  'spesialisasi' => 'Lingkungan',    'status' => 'bertugas', 'beban_kerja' => 3],
            ['kode' => '#PTG-00135', 'nama' => 'Bambang Wijaya', 'email' => 'bambang.w@admin.com', 'spesialisasi' => 'Keamanan',     'status' => 'istirahat', 'beban_kerja' => 1],
            ['kode' => '#PTG-00142', 'nama' => 'Rina Lestari',   'email' => 'rina.l@admin.com',  'spesialisasi' => 'Infrastruktur', 'status' => 'bertugas', 'beban_kerja' => 2],
        ];
        foreach ($petugas as $p) Petugas::create($p);

        // Laporan
        $statuses = ['pending', 'diproses', 'selesai'];
        $prioritas = ['high', 'medium', 'low'];
        for ($i = 1; $i <= 20; $i++) {
            Laporan::create([
                'kode'       => '#REP-' . (8200 + $i),
                'pelapor'    => 'Warga ' . $i,
                'kategori_id' => rand(1, 4),
                'judul'      => 'Laporan Kerusakan ' . $i,
                'deskripsi'  => 'Deskripsi lengkap laporan nomor ' . $i,
                'lokasi'     => 'Jl. Contoh No. ' . $i . ', Solo',
                'status'     => $statuses[array_rand($statuses)],
                'prioritas'  => $prioritas[array_rand($prioritas)],
                'terverifikasi' => rand(0, 1),
            ]);
        }

        // Notifikasi
        Notifikasi::create(['judul' => 'Laporan Baru: Lampu Jalan Mati', 'pesan' => 'Warga di Banjarsari melaporkan kerusakan infrastruktur penerangan jalan.', 'tipe' => 'laporan_baru']);
        Notifikasi::create(['judul' => 'Penugasan: Perbaikan Drainase', 'pesan' => 'Petugas Kebersihan telah ditugaskan untuk menangani penyumbatan selokan.', 'tipe' => 'penugasan']);
        Notifikasi::create(['judul' => 'Verifikasi Selesai: Izin Spanduk', 'pesan' => 'Laporan perizinan spanduk di area Jebres telah diverifikasi dan disetujui.', 'tipe' => 'status_berubah', 'sudah_dibaca' => true]);
    }
}
