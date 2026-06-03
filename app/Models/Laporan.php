<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'kode',
        'user_id',
        'pelapor',
        'foto_pelapor',
        'kategori_id',
        'petugas_id',
        'judul',
        'deskripsi',
        'lokasi',
        'foto_sebelum',
        'foto_sesudah',
        'status',
        'prioritas',
        'terverifikasi',
        'catatan_admin',
    ];

    // Relasi ke user pelapor
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi ke admin/petugas
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // Relasi penugasan
    public function penugasan()
    {
        return $this->hasOne(Penugasan::class, 'laporan_id');
    }
}
