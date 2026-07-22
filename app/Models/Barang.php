<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $primaryKey = 'id_barang';
    protected $guarded = [];

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class, 'id_subkategori', 'id_subkategori');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan', 'id_satuan');
    }

    public function pembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class, 'id_barang', 'id_barang');
    }

    public function barangMasuks()
    {
        return $this->hasMany(PembelianDetail::class, 'id_barang', 'id_barang')
            ->whereHas('pembelian', function($q) {
                $q->where('status', 'Diterima');
            });
    }

    public function pengeluaranDetails()
    {
        return $this->hasMany(PengeluaranDetail::class, 'id_barang', 'id_barang');
    }

    public function barangKeluars()
    {
        return $this->hasMany(PengeluaranDetail::class, 'id_barang', 'id_barang');
    }

    public function getStokAttribute()
    {
        $masuk = $this->pembelianDetails()
            ->whereHas('pembelian', function($q) {
                $q->where('status', 'Diterima');
            })
            ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(qty_terima, qty)'));

        $keluar = $this->pengeluaranDetails()->sum('qty');

        return $masuk - $keluar;
    }
}
