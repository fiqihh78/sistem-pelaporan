<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategoris';
    protected $fillable = ['nama', 'ikon', 'deskripsi', 'status'];

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'kategori_id');
    }
}
