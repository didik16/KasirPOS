<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class UserController extends Controller
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

        $user = DB::table('users')->where('level', 'A')->get();
        return view('user/index', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',

        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => 'A',
        ]);

        return redirect()->back()->with('masuk', 'Data Berhasil Di Input');
    }

    public function edit($id)
    {
        $admin = DB::table('users')->where('id', $id)->first();
        return view('user/edit', compact('admin'));
    }

    public function update(Request $request)
    {

        if ($request->password == null || $request->password == '') {
            DB::table('users')->where('id', $request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        } else {
            DB::table('users')->where('id', $request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        }

        DB::table('users')->where('id', $request->id)->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect('user')->with('update', 'Data Berhasil Di Update');
    }

    #kasir

    public function index2()
    {

        $user = DB::table('users')->where('level', 'K')->get();
        return view('kasir/index', compact('user'));
    }

    public function store2(Request $request)
    {
        $request->validate([

            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',

        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => 'K',
        ]);

        return redirect()->back()->with('masuk', 'Data Berhasil Di Input');
    }

    public function edit2($id)
    {
        $kasir = DB::table('users')->where('id', $id)->first();
        return view('kasir/edit', compact('kasir'));
    }

    public function update2(Request $request)
    {

        if ($request->password == null || $request->password == '') {
            DB::table('users')->where('id', $request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
            ]);
        } else {

            DB::table('users')->where('id', $request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'status' => $request->status,
            ]);
        }

        return redirect('kasir')->with('update', 'Data Berhasil Di Update');
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $cek_transaksi = DB::table('tb_transaksi')->where('pengguna_id', $request->id)->count();

            if ($cek_transaksi > 0) {
                DB::rollBack();
                return json_encode(array(
                    "statusCode" => 405,
                    "message" => "Data User terdapat di transaksi"
                ));
            } else {
                DB::table('users')->where('id', $request->id)->delete();
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
