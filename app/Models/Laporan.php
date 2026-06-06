<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected $casts = [
        'terverifikasi' => 'boolean',
    ];

    // ── Auto-generate kode saat create ──
    protected static function booted(): void
    {
        static::creating(function (Laporan $laporan) {
            if (empty($laporan->kode)) {
                do {
                    $kode = '#REP-' . strtoupper(Str::random(6));
                } while (static::where('kode', $kode)->exists());
                $laporan->kode = $kode;
            }
        });
    }

    // ── RELASI ──

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function penugasan()
    {
        return $this->hasOne(Penugasan::class, 'laporan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
