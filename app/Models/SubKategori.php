<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    protected $table = 'sub_kategoris';
    protected $primaryKey = 'id_subkategori';
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_subkategori', 'id_subkategori');
    }
}
