<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KategoriController extends Controller
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
        $kategori = DB::table('kategori')->get();

        return view('kategori/index', compact('kategori'));
    }

    public function store(Request $request)
    {
        DB::table('kategori')->insert([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect()->back()->with('masuk', 'Data Berhasil Di Input');
    }

    public function edit($id)
    {
        $kategori = DB::table('kategori')->where('id_kategori', $id)->first();

        return view('kategori/edit', compact('kategori'));
    }

    public function update(Request $request)
    {
        DB::table('kategori')->where('id_kategori', $request->id_kategori)->update([
            'nama_kategori' => $request->nama_kategori,
            'status' => $request->status
        ]);

        return redirect('kategori')->with('update', 'Data Berhasil Di Update');
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $cek_barang = DB::table('tb_barang')->where('kategori_id', $request->id)->count();

            if ($cek_barang > 0) {
                DB::rollBack();
                return json_encode(array(
                    "statusCode" => 405,
                    "message" => "Data sudah dipakai di Barang"
                ));
            } else {
                DB::table('kategori')->where('id_kategori', $request->id)->delete();
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
}
