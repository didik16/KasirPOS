<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasok extends Model
{

    protected $table = "tb_pasok";
    protected $fillable = [
        'id_pasok',
        'barang_pasok_id',
        'jumlah_pasok',
        'pemasok_id',
        'tanggal_pasok',
        'total_harga',
        'tipe_pembayaran',
        'tanggal_kredit',
        'status_kredit',
    ];
}
