<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class PrinterController extends Controller
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


    public function edit()
    {
        $printer = DB::table('tb_printer')->where('id', 1)
            ->first();

        return view('printer/index', compact('printer'));
    }

    public function update(Request $request)
    {

        DB::table('tb_printer')->where('id', 1)->update([
            'nama_printer' => $request->nama_printer,
            'ip' => $request->ip,
        ]);
        // alihkan halaman ke halaman pegawai
        return redirect('/printer')->with('update', 'Data Berhasil Di Update');
    }
}
