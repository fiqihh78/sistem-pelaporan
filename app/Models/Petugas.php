<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $fillable = [
        'user_id',
        'id_petugas',
        'spesialisasi',
        'status',
        'beban_kerja',
        'lokasi_saat_ini'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
}