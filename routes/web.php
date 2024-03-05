<?php

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/kategori', 'KategoriController@index');
Route::post('/kategori/store', 'KategoriController@store');
Route::post('/kategori/update', 'KategoriController@update');
Route::post('/kategori/destroy', 'KategoriController@destroy')->name('kategori.destroy');
Route::get('/kategori/edit/{id_kategori}', 'KategoriController@edit');


Route::get('/user', 'UserController@index');
Route::post('/user/store', 'UserController@store');
Route::post('/user/update', 'UserController@update');
Route::get('/user/edit/{id}', 'UserController@edit');

Route::get('/kasir', 'UserController@index2');
Route::post('/kasir/store', 'UserController@store2');
Route::post('/kasir/update', 'UserController@update2');
Route::get('/kasir/edit/{id}', 'UserController@edit2');
Route::post('/kasir/destroy', 'UserController@destroy')->name('user.destroy');

Route::get('/barang', 'BarangController@index');
Route::get('/get_barang/{barang}', 'BarangController@get_barang');
Route::get('/cetak', 'BarangController@cetak');
Route::post('/barang/store', 'BarangController@store');
Route::post('/barang/update', 'BarangController@update');
Route::get('/barang/edit/{id_barang}', 'BarangController@edit');
Route::post('/barang/destroy', 'BarangController@destroy')->name('barang.destroy');

Route::get('/stok', 'PasokController@index');
Route::get('/list_stok', 'PasokController@list_stok');
Route::post('/stok/store', 'PasokController@store');
Route::post('/stok/update', 'PasokController@update');
Route::get('/stok/edit/{id_pasok}', 'PasokController@edit');
Route::get('/pembayaran_kredit', 'PasokController@pembayaran_kredit');


Route::get('/pemasok', 'PemasokController@index');
Route::post('/pemasok/store', 'PemasokController@store');
Route::post('/pemasok/update', 'PemasokController@update');
Route::get('/pemasok/edit/{id_pasok}', 'PemasokController@edit');
Route::post('/pemasok/destroy', 'PemasokController@destroy')->name('pemasok.destroy');


Route::get('/ambil', 'TransaksiController@ambil');
Route::get(
    '/ambil2',
    'TransaksiController@ambil2'
);

Route::get('/transaksi2', 'TransaksiController@index');
Route::get('/transaksi', 'TransaksiController@index2');
Route::post('add-chart', 'TransaksiController@addToCart')->name('add_chart');
Route::post('remove-chart', 'TransaksiController@removeCart')->name('remove_chart');
Route::post('update-chart', 'TransaksiController@updateCart')->name('update_chart');
Route::post('store-chart', 'TransaksiController@store_transaksi')->name('store_chart');

Route::get('/nyokot', 'TransaksiController@nyokot');
Route::get('/nyokot2/{id}', 'TransaksiController@nyokot2');

Route::post('/masuk/sementara', 'TransaksiController@store');
Route::post('/masuk/semua', 'TransaksiController@storesemua');
Route::get('/cetak/{kode_transaksi}', 'TransaksiController@cetak');

Route::get('/laporan', 'LaporanController@index');
Route::get('/pendapatan', 'LaporanController@pendapatan');
Route::get('/laporan/{kode_transaksi_kembalian}', 'LaporanController@detail');

Route::get('change-password', 'ChangePasswordController@index');
Route::post('change-password', 'ChangePasswordController@store')->name('change.password');


Route::get('/printer', 'PrinterController@edit');
Route::post('/printer/update', 'PrinterController@update')->name('printer.update');


Route::get('/cetak2', 'TransaksiController@cetak_test');


Route::post('/print', function (Request $request) {
    if ($request->ajax()) {
        try {
            $ip = '127.0.0.1'; // IP Komputer kita atau printer lain yang masih satu jaringan
            $printer = 'Blueprint BP-TD110X2'; // Nama Printer yang di sharing
            $connector = new WindowsPrintConnector("smb://" . $ip . "/" . $printer);

            // $connector = new WindowsPrintConnector("php://stdout");


            $printer = new Printer($connector);
            /* Initialize */
            $printer->initialize();

            // $printer->text("Email :" . $request->email . "\n");
            // $printer->text("Username:" . $request->username . "\n");
            // $printer->cut();
            $printer->pulse();
            $printer->close();
            $response = "true";
        } catch (Exception $e) {
            $response = 'anu ' . $e->getMessage();
        }
        // return response()
        //     ->json($response);

        return json_encode(array('statusCode' => $response));
    }
    return;
});
