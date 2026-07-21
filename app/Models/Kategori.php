<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';
    protected $guarded = [];

    public function subKategoris()
    {
        return $this->hasMany(SubKategori::class, 'id_kategori', 'id_kategori');
    }
}
