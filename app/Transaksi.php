<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{

    protected $table = "tb_transaksi";
    protected $fillable = [
        'kode_transaksi',
        'barang_id',
        'jumlah_beli',
        'total_harga',
        'pengguna_id',
        'tanggal_beli',
    ];
}
