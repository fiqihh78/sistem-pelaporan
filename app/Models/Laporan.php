<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'nomor_laporan',
        'user_id',
        'kategori_id',
        'petugas_id',
        'judul',
        'deskripsi',
        'lokasi',
        'foto_bukti',
        'status',
        'catatan_admin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class);
    }
}