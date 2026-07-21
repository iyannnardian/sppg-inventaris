<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id_supplier';
    protected $guarded = [];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'id_supplier', 'id_supplier');
    }
}
