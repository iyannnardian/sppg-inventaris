<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelians';
    protected $primaryKey = 'id_pembelian';
    protected $guarded = [];

    protected $appends = ['status_kesesuaian'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function details()
    {
        return $this->hasMany(PembelianDetail::class, 'id_pembelian', 'id_pembelian');
    }

    public function getStatusKesesuaianAttribute()
    {
        if ($this->status !== 'Diterima') {
            return 'Belum Diterima';
        }

        $adaSelisih = false;
        foreach ($this->details as $d) {
            if ($d->qty_terima !== null && (float)$d->qty_terima !== (float)$d->qty) {
                $adaSelisih = true;
                break;
            }
        }

        return $adaSelisih ? 'Ada Selisih' : 'Sesuai';
    }
}

