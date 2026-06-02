<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    use HasFactory;

    protected $table = 'penugasans';

    protected $fillable = [
        'laporan_id',
        'petugas_id',
        'ditugaskan_pada',
        'status',
        'catatan',
    ];

    protected $casts = [
        'ditugaskan_pada' => 'datetime',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }
}
