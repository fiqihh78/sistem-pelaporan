<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;
    protected $table = 'petugas';
    protected $fillable = ['kode', 'nama', 'email', 'foto', 'spesialisasi', 'status', 'beban_kerja'];

    public function penugasans()
    {
        return $this->hasMany(Penugasan::class, 'petugas_id');
    }
}
