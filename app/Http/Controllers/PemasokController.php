<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class PemasokController extends Controller
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
        $pasok = DB::table('tb_pemasok')
            ->get();

        return view('pemasok/index', compact('pasok'));
    }

    public function store(Request $request)
    {

        DB::table('tb_pemasok')->insert([
            'nama_pemasok' => $request->nama_pemasok,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('masuk', 'Data Berhasil Di Input');
    }

    public function edit($id)
    {
        $pasok = DB::table('tb_pemasok')->where('id', $id)
            ->first();

        return view('pemasok/edit', compact('pasok'));
    }

    public function update(Request $request)
    {

        DB::table('tb_pemasok')->where('id', $request->id_pasok)->update([
            'nama_pemasok' => $request->nama_pemasok,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);
        // alihkan halaman ke halaman pegawai
        return redirect('/pemasok')->with('update', 'Data Berhasil Di Update');
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $cek_barang = DB::table('tb_barang')->where('pemasok_id', $request->id)->count();

            if ($cek_barang > 0) {
                DB::rollBack();
                return json_encode(array(
                    "statusCode" => 405,
                    "message" => "Data sudah dipakai di Barang"
                ));
            } else {
                DB::table('tb_pemasok')->where('id', $request->id)->delete();
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
