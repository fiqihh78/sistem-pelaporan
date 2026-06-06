<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'spesialisasi',
        'status',
        'foto',
    ];

    // Relasi ke User (jika petugas punya akun login)
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function penugasans()
    {
        return $this->hasMany(Penugasan::class, 'petugas_id');
    }
}
