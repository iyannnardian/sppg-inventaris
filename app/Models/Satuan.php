<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuans';
    protected $primaryKey = 'id_satuan';
    protected $guarded = [];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_satuan', 'id_satuan');
    }
}
