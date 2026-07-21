<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    protected $table = 'pembelian_details';
    protected $primaryKey = 'id_detail';
    protected $guarded = [];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function getJumlahAttribute()
    {
        return $this->qty;
    }

    public function getTanggalMasukAttribute()
    {
        return $this->pembelian ? $this->pembelian->tgl_terima : $this->created_at;
    }
}
