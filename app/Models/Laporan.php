<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'user_id',
        'kode',
        'pelapor',
        'foto_pelapor',
        'kategori_id',
        'judul',
        'deskripsi',
        'lokasi',
        'foto_sebelum',
        'foto_sesudah',
        'status',
        'prioritas',
        'terverifikasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function penugasan()
    {
        return $this->hasOne(Penugasan::class, 'laporan_id');
    }
}
