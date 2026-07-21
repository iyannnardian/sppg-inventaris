<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranDetail extends Model
{
    protected $table = 'pengeluaran_details';
    protected $primaryKey = 'id_detail';
    protected $guarded = [];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran', 'id_pengeluaran');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
