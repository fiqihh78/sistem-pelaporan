<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $fillable = [
        'user_id',
        'id_petugas',
        'nama',
        'email',
        'foto',
        'spesialisasi',
        'status',
        'beban_kerja',
        'lokasi_saat_ini',
    ];
}
