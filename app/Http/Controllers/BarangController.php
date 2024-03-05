<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class BarangController extends Controller
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
        $barang = DB::table('tb_barang')
            ->select('*', DB::raw('tb_barang.status as status_barang'))
            ->join('kategori', function ($join) {
                $join->on('tb_barang.kategori_id', '=', 'kategori.id_kategori');
            })->get();

        $kategori = DB::table('kategori')->where('status', 'active')->get();

        $pemasok = DB::table('tb_pemasok')->where('status', 'active')->get();

        return view('barang/index', compact('barang', 'kategori', 'pemasok'));
    }

    public function cetak()
    {
        $barang = DB::table('tb_barang')->get();
        return view('barang/cetak', compact('barang'));
    }

    public function store(Request $request)
    {
        $cek = DB::table('tb_barang')->where('id_barang', $request->id_barang)->count();
        if ($cek == 1) {
            return redirect()->back();
        } else {
            DB::table('tb_barang')->insert([
                'id_barang' => $request->id_barang,
                'nama_barang' => $request->nama_barang,
                'kategori_id' => $request->kategori_id,
                'pemasok_id' => $request->pemasok_id,
                'jumlah_barang' => 0,
                'harga_pokok' => str_replace(",", "", $request->harga_pokok),
                'harga_jual' => str_replace(",", "", $request->harga_jual),
            ]);


            if ($request->tipe_pembayaran == 'cash') {
                DB::table('tb_pasok')->insert([
                    'barang_pasok_id' =>  $request->id_barang,
                    'jumlah_pasok' => $request->jumlah_barang,
                    'pemasok_id' => $request->pemasok_id,
                    'total_harga' => str_replace(",", "", $request->total_harga),
                    'tanggal_pasok' => $request->tanggal_pasok,
                    'tipe_pembayaran' => $request->tipe_pembayaran
                ]);
            } else {
                DB::table('tb_pasok')->insert([
                    'barang_pasok_id' =>  $request->id_barang,
                    'jumlah_pasok' => $request->jumlah_barang,
                    'pemasok_id' => $request->pemasok_id,
                    'total_harga' => str_replace(",", "", $request->total_harga),
                    'tanggal_pasok' => $request->tanggal_pasok,
                    'tipe_pembayaran' => $request->tipe_pembayaran,
                    'tanggal_kredit' => $request->tanggal_kredit
                ]);
            }

            return redirect()->back()->with('masuk', 'Data Berhasil Di Input');
        }
    }

    public function edit($id)
    {
        $barang = DB::table('tb_barang')->where('id_barang', $id)
            ->select('*', DB::raw('tb_barang.status as status_barang'))
            ->join('kategori', function ($join) {
                $join->on('tb_barang.kategori_id', '=', 'kategori.id_kategori');
            })
            ->join('tb_pemasok', function ($join) {
                $join->on('tb_barang.pemasok_id', '=', 'tb_pemasok.id');
            })

            ->first();
        $kategori = DB::table('kategori')->where('status', 'active')->get();
        $kategori_selected = DB::table('kategori')->where('id_kategori', $barang->kategori_id)->first();

        $pemasok = DB::table('tb_pemasok')->where('status', 'active')->get();
        $pemasok_selected = DB::table('tb_pemasok')->where('id', $barang->pemasok_id)->first();

        return view('barang/edit', compact('barang', 'kategori', 'kategori_selected', 'pemasok', 'pemasok_selected'));
    }

    public function update(Request $request)
    {

        DB::table('tb_barang')->where('id_barang', $request->id_barang2)->update([
            'id_barang' => $request->id_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'pemasok_id' => $request->nama_pemasok,
            'jumlah_barang' => $request->jumlah_barang,
            'status' => $request->status,
            'harga_pokok' => str_replace(",", "", $request->harga_pokok),
            'harga_jual' => str_replace(",", "", $request->harga_jual),
        ]);

        return redirect('barang')->with('update', 'Data Berhasil Di Update');
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $cek_transaksi = DB::table('tb_transaksi')->where('barang_id', $request->id)->count();

            if ($cek_transaksi > 0) {
                DB::rollBack();
                return json_encode(array(
                    "statusCode" => 405,
                    "message" => "Data barang terdapat di transaksi"
                ));
            } else {
                DB::table('tb_barang')->where('id_barang', $request->id)->delete();
                DB::table('tb_pasok')->where('barang_pasok_id', $request->id)->delete();
                DB::commit();
                return json_encode(array(
                    "statusCode" => 200,
                    "message" => "Data Berhasil Di Hapus"
                ));
            }
        } catch (\Exception $e) {
            DB::rollback();
            return json_encode(array(
                "statusCode" => 405,
                "message" => $e->getMessage()
            ));
        }
    }

    public function get_barang($barang)
    {
        $barang = DB::table('tb_barang')->where('id_barang', $barang)->orWhere('nama_barang', $barang)->first();

        if ($barang) {
            return json_encode(array(
                "statusCode" => 200,
                "barang" => $barang
            ));
        } else {
            return json_encode(array(
                "statusCode" => 404,
                "message" => "No Data"
            ));
        }
    }
}
