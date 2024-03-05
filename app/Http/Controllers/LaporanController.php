<?php

namespace App\Http\Controllers;

use auth;
use DB;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $laporan = DB::table('tb_kembalian')->orderBy('tanggal_transaksi', 'desc')->get();

        return view('laporan/index', compact('laporan'));
    }
    public function pendapatan()
    {
        $pendapatan = DB::table('tb_transaksi')
            ->select(DB::raw(' 
            count(DISTINCT  kode_transaksi) as total_transaksi,
            sum(jumlah_beli) 
     as total_jumlah_beli, sum(total_harga) 
     as total_harga_semua, DATE(tanggal_beli) as tanggal '))
            ->orderBy(DB::raw('Date(tanggal_beli)'), 'desc')
            ->groupBy(DB::raw('Date(tanggal_beli)'))
            // ->groupBy('tanggal')
            ->get();

        // dd($pendapatan);

        return view('laporan/pendapatan', compact('pendapatan'));
    }

    public function detail($id)
    {
        $transaksi = DB::table('tb_transaksi')->where('kode_transaksi', $id)
            ->join('tb_barang', function ($join) {
                $join->on('tb_transaksi.barang_id', '=', 'tb_barang.id_barang');
            })->get();
        $ambil = DB::table('tb_transaksi')->where('kode_transaksi', $id)->first();
        $jumlah =  DB::table('tb_transaksi')->where('kode_transaksi', $id)
            ->join('tb_barang', function ($join) {
                $join->on('tb_transaksi.barang_id', '=', 'tb_barang.id_barang');
            })->sum('total_harga');
        $kasir = DB::table('tb_transaksi')->where('kode_transaksi', $id)
            ->join('users', function ($join) {
                $join->on('tb_transaksi.pengguna_id', '=', 'users.id');
            })->first();
        $kembalian = DB::table('tb_kembalian')->where('kode_transaksi_kembalian', $id)->first();

        return view('laporan/detail', compact('transaksi', 'kembalian', 'ambil', 'jumlah', 'kasir'));
    }
}
