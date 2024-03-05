<?php

namespace App\Http\Controllers;

use App\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class TransaksiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function nyokot(Request $request)
    {
        $barang = DB::table("tb_barang")
            ->where("id_barang", $request->id_barang)
            ->pluck("nama_barang", "harga_jual");
        return response()->json($barang);
    }

    public function nyokot2($id)
    {
        $barang = DB::table("tb_barang")
            ->where("id_barang", $id)
            ->pluck("harga_jual");
        return response()->json($barang);
    }


    public function ambil(Request $request)
    {

        $barang = DB::table("tb_barang")
            ->where("kategori_id", $request->kategori_id)
            ->pluck("nama_barang", "id_barang");
        return response()->json($barang);
    }

    public function ambil2(Request $request)
    {

        $barang2 = DB::table("tb_barang")
            ->where("id_barang", $request->id_barang)
            ->pluck("harga_barang");
        return response()->json($barang2);
    }

    public function index()
    {
        $max = DB::table('tb_transaksi')->where('kode_transaksi', \DB::raw("(select max(`kode_transaksi`) from tb_transaksi)"))->pluck('kode_transaksi');
        $check_max = DB::table('tb_transaksi')->count();
        if ($check_max == null) {
            $max_code = "T0001";
        } else {
            $max_code = $max[0];
            $max_code++;
        }
        $kategori = DB::table('kategori')->get();
        $sementara = DB::table('tb_sementara')
            ->join('tb_barang', function ($join) {
                $join->on('tb_sementara.barang_id', '=', 'tb_barang.id_barang');
            })->get();

        $jumlah = DB::table('tb_sementara')
            ->join('tb_barang', function ($join) {
                $join->on('tb_sementara.barang_id', '=', 'tb_barang.id_barang');
            })->sum('total_harga');

        return view('transaksi/index', compact('kategori', 'max_code', 'sementara', 'jumlah'));
    }

    public function cetak_test()
    {
        return view('transaksi/print');
    }



    public function index2()
    {
        $max = DB::table('tb_transaksi')->where('kode_transaksi', \DB::raw("(select max(`kode_transaksi`) from tb_transaksi)"))->pluck('kode_transaksi');
        $check_max = DB::table('tb_transaksi')->count();
        if ($check_max == null) {
            $max_code = "T0001";
        } else {
            $max_code = $max[0];
            $max_code++;
        }
        $kategori = DB::table('kategori')->get();

        $jumlah = DB::table('tb_sementara')
            ->join('tb_barang', function ($join) {
                $join->on('tb_sementara.barang_id', '=', 'tb_barang.id_barang');
            })->sum('total_harga');

        $barang = DB::table('tb_barang')->where('status', 'active')->get();

        $cartItems = \Cart::getContent();
        $cartTotalQuantity = \Cart::getTotalQuantity();

        return view('transaksi/index2', compact('barang', 'kategori', 'max_code', 'jumlah', 'cartItems'));
    }


    public function addToCart(Request $request)
    {

        $barang = DB::table('tb_barang')
            ->where('id_barang', $request->id)
            ->where('status', 'active')
            ->first();

        if ($barang == null) {

            return json_encode(array(
                "statusCode" => 404,
                "total" => \Cart::getTotal(),
                "qty" => \Cart::getTotalQuantity()
            ));
        } else {

            $cart  = \Cart::getContent()->where('id', $barang->id_barang)->first();

            if ($cart) {
                if ($cart->quantity + 1 > $barang->jumlah_barang) {
                    return json_encode(array(
                        "statusCode" => 206,
                        "total" => \Cart::getTotal(),
                        "qty" => \Cart::getTotalQuantity(),
                        "now" => $cart->quantity
                    ));
                }
            }


            \Cart::add([
                'id' => $barang->id_barang,
                'name' => $barang->nama_barang,
                'price' => $barang->harga_jual,
                'quantity' => 1,
                // 'attributes' => array(
                //     'image' => $request->image,
                // )
            ]);

            $data = '<tr class="data_barang data_' .  $barang->id_barang . '"> 

            <td>' . $barang->id_barang . '</td>
            <td>' . $barang->nama_barang . '</td>
            <td >
            <input type="number" value="1" class="form-control jumlah" id="jumlah_' . $barang->id_barang . '"
                name="jumlah_' . $barang->id_barang . '" min="1" data-id="' . $barang->id_barang . '"
                style=" width: 100%;text-align: center;">
            </td>
            <td id="harga_' . $barang->id_barang . '">' . number_format($barang->harga_jual) . '</td>
            <td id="total_' . $barang->id_barang . '">' . number_format($barang->harga_jual) . '</td>
            <td>
                <button class="btn btn-danger btn_remove" data-id="' . $barang->id_barang . '">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
            </tr>';

            return json_encode(array(
                "statusCode" => 200,
                "id" => $barang->id_barang,
                "total" => \Cart::getTotal(),
                "data" => $data,
                "qty" => \Cart::getTotalQuantity()
            ));
        }
    }

    public function removeCart(Request $request)
    {
        \Cart::remove($request->id);
        // session()->flash('success', 'Item Cart Remove Successfully !');

        return json_encode(array(
            "statusCode" => 200,
            "total" => \Cart::getTotal(),
            "qty" => \Cart::getTotalQuantity()
        ));
    }

     public function updateCart(Request $request)
    {

        $barang = DB::table('tb_barang')
            ->where('id_barang', $request->id)
            ->where('status', 'active')
            ->first();

        if ($barang == null) {

            return json_encode(array(
                "statusCode" => 404,
                "total" => \Cart::getTotal(),
                "qty" => \Cart::getTotalQuantity()
            ));
        } else {

            $cart  = \Cart::getContent()->where('id', $request->id)->first();
            if ($cart) {
                if ($request->quantity > $barang->jumlah_barang) {
                    return json_encode(array(
                        "statusCode" => 206,
                        "total" => \Cart::getTotal(),
                        "qty" => \Cart::getTotalQuantity(),
                        "pesan" => "stok habis",
                        "max"=>$barang->jumlah_barang
                    ));
                }
            }



            \Cart::update(
                $request->id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $request->quantity
                    ],
                ]
            );
            return json_encode(array(
                "statusCode" => 200,
                "qty" => \Cart::getTotalQuantity(),
                "id" =>  $request->id,
                "total" => \Cart::getTotal()
            ));
        }
    }

    public function store_transaksi(Request $request)
    {
        \Carbon\Carbon::setLocale('id');
        $tanggal = Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s');
        $cartItems = \Cart::getContent();

        $max = DB::table('tb_transaksi')->where('kode_transaksi', \DB::raw("(select max(`kode_transaksi`) from tb_transaksi)"))->pluck('kode_transaksi');
        $check_max = DB::table('tb_transaksi')->count();
        if ($check_max == null) {
            $max_code = "T0001";
        } else {
            $max_code = $max[0];
            $max_code++;
        }

        if ($request->total_bayar < \Cart::getTotal()) {
            return json_encode(array(
                "statusCode" => 402,
                "message" => "Uang bayar kurang"
            ));
        } else {

            DB::beginTransaction();
            try {


                foreach ($cartItems as $item) {

                    $data = new Transaksi();
                    $data->kode_transaksi = $max_code;
                    $data->tanggal_beli = $tanggal;
                    $data->barang_id = $item['id'];
                    $data->jumlah_beli = $item['quantity'];
                    $data->total_harga = $item['quantity'] * $item['price'];
                    $data->pengguna_id = \Auth::user()->id;
                    $data->save();
                }

                DB::table('tb_kembalian')->insert([
                    'kode_transaksi_kembalian' => $max_code,
                    'bayar' => $request->total_bayar,
                    'kembalian' => $request->kembalian,
                    'tanggal_transaksi' => $tanggal
                ]);


                if ($data) {

                    //CASH DRAWER
                    if ($request->ajax()) {
                        try {

                            // $printer = DB::table('tb_printer')->where('id', 1)->first();

                            // $ip = $printer->ip; // IP Komputer kita atau printer lain yang masih satu jaringan
                            // $printer = $printer->nama_printer; // Nama Printer yang di sharing
                            // // $printer = 'Blueprint BP-TD110X2'; // Nama Printer yang di sharing
                            // $connector = new WindowsPrintConnector("smb://" . $ip . "/" . $printer);

                            // // $connector = new WindowsPrintConnector("php://stdout");


                            // $printer = new Printer($connector);
                            // /* Initialize */
                            // $printer->initialize();

                            // // $printer->text("Email :" . $request->email . "\n");
                            // // $printer->text("Username:" . $request->username . "\n");
                            // // $printer->cut();
                            // $printer->pulse();
                            // $printer->close();
                            // $response = "true";




                            \Cart::clear();

                            DB::commit();

                            $max = DB::table('tb_transaksi')->where('kode_transaksi', \DB::raw("(select max(`kode_transaksi`) from tb_transaksi)"))->pluck('kode_transaksi');
                            $check_max = DB::table('tb_transaksi')->count();
                            if ($check_max == null) {
                                $max_code = "T0001";
                            } else {
                                $max_code = $max[0];
                                $max_code++;
                            }


                            return json_encode(array(
                                "statusCode" => 200,
                                "message" => "Transaksi Berhasil",
                                "new_kode" => $max_code
                            ));
                        } catch (\Exception $e) {
                            $response = 'anu ' . $e->getMessage();

                            DB::rollback();

                            return json_encode(array(
                                "statusCode" => 404,
                                "message" =>  $e->getMessage(),
                            ));
                        }
                        // return response()
                        //     ->json($response);

                        return json_encode(array('statusCode' => $response));
                    }
                }
            } catch (\Exception $e) {
                DB::rollback();
                return json_encode(array(
                    "statusCode" => 404,
                    "message" => $e->getMessage()
                ));
            }
        }
    }


    public function store(Request $request)
    {
        $cek = DB::table('tb_barang')->where('id_barang', $request->id_barang)->first();
        if ($cek->jumlah_barang < $request->jumlah_beli) {
            return redirect()->back();
        }

        $hitung = $request->harga * $request->jumlah_beli;
        $tanggal = date('Y-m-d');

        $cek_transaksi = DB::table('tb_sementara')->where('barang_id', $request->id_barang)->first();

        if ($cek_transaksi) {

            DB::table('tb_sementara')->where('barang_id', $request->id_barang)->update([
                'jumlah_beli' => $request->jumlah_beli + $cek_transaksi->jumlah_beli,
                'total_harga' =>  $hitung + $cek_transaksi->total_harga,

            ]);
        } else {

            DB::table('tb_sementara')->insert([
                'kode_transaksi' => $request->kode_transaksi,
                'barang_id' => $request->id_barang,
                'jumlah_beli' => $request->jumlah_beli,
                'total_harga' => $hitung,
                'pengguna_id' => Auth::user()->id,
                'tanggal_beli' => $tanggal
            ]);
        }

        return redirect()->back();
    }


    public function storesemua(Request $request)
    {
        $tanggal = date('Y-m-d');
        if ($request->kembalian < 0) {
            return redirect()->back()->with('gagal', 'Bayaran Kurang');
        }

        DB::table('tb_kembalian')->insert([
            'kode_transaksi_kembalian' => $request->kode_transaksi_kembalian,
            'bayar' => $request->bayar,
            'kembalian' => $request->kembalian,
            'tanggal_transaksi' => $tanggal
        ]);

        $select = DB::table('tb_sementara')->get();

        foreach ($select as $s) {
            DB::table('tb_transaksi')->insert([
                'kode_transaksi' => $s->kode_transaksi,
                'barang_id' => $s->barang_id,
                'jumlah_beli' => $s->jumlah_beli,
                'total_harga' => $s->total_harga,
                'pengguna_id' => $s->pengguna_id,
                'tanggal_beli' => $s->tanggal_beli
            ]);
        }

        foreach ($select as $s) {
            DB::table('tb_sementara')->truncate([
                'kode_transaksi' => $s->kode_transaksi,
                'barang_id' => $s->barang_id,
                'jumlah_beli' => $s->jumlah_beli,
                'total_harga' => $s->total_harga,
                'pengguna_id' => $s->pengguna_id,
                'tanggal_beli' => $s->tanggal_beli
            ]);
        }

        $transaksi = DB::table('tb_transaksi')->where('kode_transaksi', $request->kode_transaksi_kembalian)
            ->join('tb_barang', function ($join) {
                $join->on('tb_transaksi.barang_id', '=', 'tb_barang.id_barang');
            })->get();
        $ambil = DB::table('tb_transaksi')->where('kode_transaksi', $request->kode_transaksi_kembalian)->first();
        $jumlah =  DB::table('tb_transaksi')->where('kode_transaksi', $request->kode_transaksi_kembalian)
            ->join('tb_barang', function ($join) {
                $join->on('tb_transaksi.barang_id', '=', 'tb_barang.id_barang');
            })->sum('total_harga');
        $kasir = DB::table('tb_transaksi')->where('kode_transaksi', $request->kode_transaksi_kembalian)
            ->join('users', function ($join) {
                $join->on('tb_transaksi.pengguna_id', '=', 'users.id');
            })->first();
        $kembalian = DB::table('tb_kembalian')->where('kode_transaksi_kembalian', $request->kode_transaksi_kembalian)->first();

        return view('transaksi/detail', compact('transaksi', 'kembalian', 'ambil', 'jumlah', 'kasir'));
    }




    public function cetak($id)
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

        return view('transaksi/struk', compact('transaksi', 'kembalian', 'ambil', 'jumlah', 'kasir'));
    }
}
