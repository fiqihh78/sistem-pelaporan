<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Petugas;
use App\Models\Laporan;
use App\Models\Notifikasi;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah admin sudah ada
        if (User::where('email', 'admin@silapor.com')->exists()) {
            $this->command->info('Data sudah ada, seeder dilewati');
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $admin = User::create([
            'name'     => 'Admin Utama',
            'email'    => 'admin@silapor.com',
            'password' => bcrypt('admin123'),
            'role'     => 'admin',
            'phone'    => '081234567890',
        ]);

        $user1 = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'role'     => 'masyarakat',
            'phone'    => '082345678901',
        ]);

        $user2 = User::create([
            'name'     => 'Siti Aminah',
            'email'    => 'siti@gmail.com',
            'password' => bcrypt('password'),
            'role'     => 'masyarakat',
            'phone'    => '083456789012',
        ]);

        $user3 = User::create([
            'name'     => 'Rahmat Hidayat',
            'email'    => 'rahmat@gmail.com',
            'password' => bcrypt('password'),
            'role'     => 'masyarakat',
        ]);

        /*
        |--------------------------------------------------------------------------
        | PETUGAS USER
        |--------------------------------------------------------------------------
        */

        $petugasUser1 = User::create([
            'name'     => 'Ahmad Subarjo',
            'email'    => 'ahmad@silapor.com',
            'password' => bcrypt('password'),
            'role'     => 'petugas',
        ]);

        $petugasUser2 = User::create([
            'name'     => 'Sri Lestari',
            'email'    => 'sri@silapor.com',
            'password' => bcrypt('password'),
            'role'     => 'petugas',
        ]);

        $petugasUser3 = User::create([
            'name'     => 'Bambang Wijaya',
            'email'    => 'bambang@silapor.com',
            'password' => bcrypt('password'),
            'role'     => 'petugas',
        ]);

        /*
        |--------------------------------------------------------------------------
        | PETUGAS
        |--------------------------------------------------------------------------
        */

        $petugas1 = Petugas::create([
            'user_id'      => $petugasUser1->id,
            'id_petugas'   => 'PTG-0001',
            'spesialisasi' => 'Infrastruktur',
            'status'       => 'aktif',
            'beban_kerja'  => 3,
        ]);

        $petugas2 = Petugas::create([
            'user_id'      => $petugasUser2->id,
            'id_petugas'   => 'PTG-0002',
            'spesialisasi' => 'Kebersihan',
            'status'       => 'aktif',
            'beban_kerja'  => 2,
        ]);

        $petugas3 = Petugas::create([
            'user_id'      => $petugasUser3->id,
            'id_petugas'   => 'PTG-0003',
            'spesialisasi' => 'Keamanan',
            'status'       => 'aktif',
            'beban_kerja'  => 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | KATEGORI
        |--------------------------------------------------------------------------
        */

        $kat1 = Kategori::create([
            'nama'      => 'Infrastruktur',
            'icon'      => '🏗️',
            'warna'     => '#3B82F6',
            'deskripsi' => 'Laporan kerusakan jalan, jembatan, dan fasilitas umum',
            'status'    => 'aktif',
        ]);

        $kat2 = Kategori::create([
            'nama'      => 'Kebersihan',
            'icon'      => '🧹',
            'warna'     => '#10B981',
            'deskripsi' => 'Laporan sampah dan kebersihan lingkungan',
            'status'    => 'aktif',
        ]);

        $kat3 = Kategori::create([
            'nama'      => 'Keamanan',
            'icon'      => '🔒',
            'warna'     => '#EF4444',
            'deskripsi' => 'Laporan gangguan keamanan dan ketertiban',
            'status'    => 'aktif',
        ]);

        $kat4 = Kategori::create([
            'nama'      => 'Penerangan Jalan',
            'icon'      => '💡',
            'warna'     => '#F59E0B',
            'deskripsi' => 'Laporan lampu jalan mati atau rusak',
            'status'    => 'aktif',
        ]);

        /*
        |--------------------------------------------------------------------------
        | LAPORAN
        |--------------------------------------------------------------------------
        */

        // Laporan
        Laporan::create([
            'nomor_laporan' => 'REP-0001',
            'user_id'       => $user1->id,
            'kategori_id'   => $kat1->id,
            'petugas_id'    => $petugas1->id,
            'judul'         => 'Jalan Berlubang di Jl. Slamet Riyadi',
            'deskripsi'     => 'Terdapat lubang besar di jalan utama.',
            'lokasi'        => 'Solo',
            'status'        => 'diproses',
            'created_at'    => '2026-01-15',
        ]);

        Laporan::create([
            'nomor_laporan' => 'REP-0002',
            'user_id'       => $user2->id,
            'kategori_id'   => $kat2->id,
            'petugas_id'    => $petugas2->id,
            'judul'         => 'Tumpukan Sampah di Pasar',
            'deskripsi'     => 'Sampah belum diangkut.',
            'lokasi'        => 'Solo',
            'status'        => 'selesai',
            'created_at'    => '2026-02-10',
        ]);

        Laporan::create([
            'nomor_laporan' => 'REP-0003',
            'user_id'       => $user3->id,
            'kategori_id'   => $kat4->id,
            'petugas_id'    => null,
            'judul'         => 'Lampu Jalan Mati',
            'deskripsi'     => 'Lampu mati selama seminggu.',
            'lokasi'        => 'Solo',
            'status'        => 'diproses',
            'created_at'    => '2026-03-08',
        ]);

        Laporan::create([
            'nomor_laporan' => 'REP-0004',
            'user_id'       => $user1->id,
            'kategori_id'   => $kat3->id,
            'petugas_id'    => null,
            'judul'         => 'Pencurian Motor',
            'deskripsi'     => 'Motor hilang di parkiran.',
            'lokasi'        => 'Solo',
            'status'        => 'pending',
            'created_at'    => '2026-04-20',
        ]);

        Laporan::create([
            'nomor_laporan' => 'REP-0005',
            'user_id'       => $user2->id,
            'kategori_id'   => $kat1->id,
            'petugas_id'    => $petugas1->id,
            'judul'         => 'Jembatan Retak',
            'deskripsi'     => 'Jembatan mulai retak.',
            'lokasi'        => 'Solo',
            'status'        => 'diproses',
            'created_at'    => '2026-05-01',
        ]);

        Laporan::create([
            'nomor_laporan' => 'REP-0006',
            'user_id'       => $user3->id,
            'kategori_id'   => $kat2->id,
            'petugas_id'    => $petugas2->id,
            'judul'         => 'Selokan Tersumbat',
            'deskripsi'     => 'Air meluap saat hujan.',
            'lokasi'        => 'Solo',
            'status'        => 'selesai',
            'created_at'    => '2026-06-11',
        ]);

        Laporan::create([
            'nomor_laporan' => 'REP-0007',
            'user_id'       => $user1->id,
            'kategori_id'   => $kat4->id,
            'petugas_id'    => null,
            'judul'         => 'Lampu Taman Rusak',
            'deskripsi'     => 'Lampu taman tidak menyala.',
            'lokasi'        => 'Solo',
            'status'        => 'pending',
            'created_at'    => '2026-07-05',
        ]);
        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI
        |--------------------------------------------------------------------------
        */

        Notifikasi::create([
            'user_id'    => $admin->id,
            'judul'      => 'Laporan Baru Masuk',
            'pesan'      => 'Ada laporan baru mengenai jalan berlubang.',
            'tipe'       => 'info',
            'dibaca'     => false,
            'laporan_id' => 1,
        ]);

        Notifikasi::create([
            'user_id'    => $admin->id,
            'judul'      => 'Laporan Selesai',
            'pesan'      => 'Laporan REP-0002 telah diselesaikan.',
            'tipe'       => 'success',
            'dibaca'     => false,
            'laporan_id' => 2,
        ]);
    }
}