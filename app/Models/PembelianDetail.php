<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    protected $table = 'pembelian_details';
    protected $primaryKey = 'id_detail';
    protected $guarded = [];

    protected $appends = ['qty_real', 'status_kesesuaian', 'selisih_qty'];

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
        return $this->qty_terima !== null ? (float)$this->qty_terima : (float)$this->qty;
    }

    public function getQtyRealAttribute()
    {
        return $this->qty_terima !== null ? (float)$this->qty_terima : (float)$this->qty;
    }

    public function getSelisihQtyAttribute()
    {
        if ($this->qty_terima === null) {
            return 0;
        }
        return (float)$this->qty_terima - (float)$this->qty;
    }

    public function getStatusKesesuaianAttribute()
    {
        if ($this->qty_terima === null) {
            return 'Belum Verifikasi';
        }
        $selisih = $this->selisih_qty;
        if ($selisih == 0) {
            return 'Sesuai';
        } elseif ($selisih < 0) {
            return 'Kurang ' . abs($selisih);
        } else {
            return 'Lebih ' . abs($selisih);
        }
    }

    public function getTanggalMasukAttribute()
    {
        return $this->pembelian ? $this->pembelian->tgl_terima : $this->created_at;
    }
}

