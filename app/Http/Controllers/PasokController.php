<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class PasokController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pasok = DB::table('tb_pasok')
            ->join('tb_barang', function ($join) {
                $join->on('tb_pasok.barang_pasok_id', '=', 'tb_barang.id_barang');
            })
            ->join('tb_pemasok', function ($join) {
                $join->on('tb_pasok.pemasok_id', '=', 'tb_pemasok.id');
            })

            ->get();

        $barang = DB::table('tb_barang')->where('status', 'active')->get();
        $pemasok = DB::table('tb_pemasok')->where('status', 'active')->get();

        return view('pasok/index', compact('pasok', 'barang', 'pemasok'));
    }
    public function list_stok()
    {
        $pasok = DB::table('tb_pasok')
            ->select(DB::raw(' sum(jumlah_pasok) 
     as total, nama_barang, barang_pasok_id'))
            ->join('tb_barang', function ($join) {
                $join->on('tb_pasok.barang_pasok_id', '=', 'tb_barang.id_barang');
            })
            ->groupBy('barang_pasok_id')
            ->get();


        return view('pasok/list_stok', compact('pasok'));
    }

    public function store(Request $request)
    {

        for ($a = 0; $a < count($request->id_barang); $a++) {


            if ($request->tipe_pembayaran == 'cash') {
                DB::table('tb_pasok')->insert([
                    'barang_pasok_id' =>  $request->id_barang[$a],
                    'jumlah_pasok' => $request->jumlah[$a],
                    'pemasok_id' => $request->pemasok_id,
                    'total_harga' => str_replace(",", "", $request->total_harga),
                    'tanggal_pasok' => $request->tanggal_pasok,
                    'tipe_pembayaran' => $request->tipe_pembayaran
                ]);
            } else {
                DB::table('tb_pasok')->insert([
                    'barang_pasok_id' =>  $request->id_barang[$a],
                    'jumlah_pasok' => $request->jumlah[$a],
                    'pemasok_id' => $request->pemasok_id,
                    'total_harga' => str_replace(",", "", $request->total_harga),
                    'tanggal_pasok' => $request->tanggal_pasok,
                    'tipe_pembayaran' => $request->tipe_pembayaran,
                    'tanggal_kredit' => $request->tanggal_kredit
                ]);
            }
        }

        return redirect()->back()->with('masuk', 'Data Berhasil Di Input');
    }

    public function edit($id)
    {
        $pasok = DB::table('tb_pasok')
            ->select('*', DB::raw('tb_pasok.pemasok_id as data_pemasok'))
            ->where('id_pasok', $id)
            ->join('tb_barang', function ($join) {
                $join->on('tb_pasok.barang_pasok_id', '=', 'tb_barang.id_barang');
            })->first();
        $pemasok = DB::table('tb_pemasok')->get();


        return view('pasok/edit', compact('pasok', 'pemasok'));
    }

    public function update(Request $request)
    {

        DB::beginTransaction();
        try {

            $cek1 = DB::table('tb_pasok')->where('id_pasok', $request->id_pasok)->first();
            if ($request->jumlah > $cek1->jumlah_pasok) {
                $cek2 = DB::table('tb_barang')->where('id_barang', $request->id_barang)->first();
                $hitungmasuk = $request->jumlah - $cek1->jumlah_pasok;
                $hitung =  $cek2->jumlah_barang + $hitungmasuk;
                DB::table('tb_barang')->where('id_barang', $request->id_barang)->update([
                    'jumlah_barang' => $hitung
                ]);
                if ($cek2->jumlah_barang < $hitungmasuk) {
                    return redirect()->back();
                }
            } else {
                $cek1 = DB::table('tb_pasok')->where('id_pasok', $request->id_pasok)->first();
                $cek2 = DB::table('tb_barang')->where('id_barang', $request->id_barang)->first();
                $hitungmasuk2 =  $cek1->jumlah_pasok - $request->jumlah;
                $hitung =  $cek2->jumlah_barang - $hitungmasuk2;
                DB::table('tb_barang')->where('id_barang', $request->id_barang)->update([
                    'jumlah_barang' => $hitung
                ]);
            }

            if ($request->tipe_pembayaran == 'cash') {
                DB::table('tb_pasok')->where('id_pasok', $request->id_pasok)->update([
                    'jumlah_pasok' => $request->jumlah,
                    'pemasok_id' => $request->nama_pemasok,
                    'total_harga' => $request->total_harga,
                    'tanggal_pasok' => $request->tanggal_pasok,
                    'tipe_pembayaran' => $request->tipe_pembayaran,
                    'tanggal_kredit' => null
                ]);
            } else {
                DB::table('tb_pasok')->where('id_pasok', $request->id_pasok)->update([
                    'jumlah_pasok' => $request->jumlah,
                    'pemasok_id' => $request->nama_pemasok,
                    'total_harga' => $request->total_harga,
                    'tipe_pembayaran' => $request->tipe_pembayaran,
                    'tanggal_pasok' => $request->tanggal_pasok,
                    'tanggal_kredit' => $request->tanggal_kredit
                ]);
            }

            DB::commit();
            return redirect('/stok')->with('update', 'Data Berhasil Di Update');
        } catch (\Exception $e) {
            DB::rollback();
            return  $e->getMessage();
        }

        // alihkan halaman ke halaman pegawai
    }


    public function pembayaran_kredit()
    {
        $pasok = DB::table('tb_pasok')
            ->join('tb_barang', function ($join) {
                $join->on('tb_pasok.barang_pasok_id', '=', 'tb_barang.id_barang');
            })
            ->join('tb_pemasok', function ($join) {
                $join->on('tb_pasok.pemasok_id', '=', 'tb_pemasok.id');
            })
            ->where('tipe_pembayaran', 'credit')
            ->where('status_kredit', 'unpaid')
            ->orderBy('tanggal_kredit', 'asc')
            ->get();

        $barang = DB::table('tb_barang')->get();
        $pemasok = DB::table('tb_pemasok')->get();

        return view('pasok/pembayaran_kredit', compact('pasok', 'barang', 'pemasok'));
    }
}
