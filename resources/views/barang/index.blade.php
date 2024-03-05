@extends('layouts.template')
@section('content')
    <title>Barang | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if (Session::get('masuk') != '')
                    <div class='alert alert-success'>
                        <center><b>{{ Session::get('masuk') }}</b></center>
                    </div>
                @endif
                @if (Session::get('update') != '')
                    <div class='alert alert-success'>
                        <center><b>{{ Session::get('update') }}</b></center>
                    </div>
                @endif
                @if (Auth::user()->level == 'A')
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-success" data-toggle="modal" data-target="#tambah">Tambah Data</button>
                        </div>
                        {{-- <div class="col-md-6 text-right">
                        <a href="/cetak" class="btn btn-warning">Cetak Harga</a>
                    </div> --}}
                    </div>
                    <br>
                    <br>
                @endif


                <table id="dataTable" class="table table-bordered" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            {{-- <th>Barcode</th> --}}
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Status</th>
                            @if (Auth::user()->level == 'A')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barang as $i => $u)
                            <tr>
                                <td>{{ ++$i }}</td>
                                {{-- <td> <img
                                        src="data:image/png;base64,{{ DNS1D::getBarcodePNG($u->id_barang, 'EAN13', 1, 38, [1, 1, 1], true) }}"
                                        height="80" width="180"></td> --}}
                                <td>{{ $u->id_barang }}</td>
                                <td>{{ $u->nama_barang }}</td>
                                <td>{{ $u->nama_kategori }}</td>
                                <td>{{ $u->jumlah_barang }}</td>
                                <td>{{ number_format($u->harga_jual) }}</td>
                                <td class="text-center">
                                    @if ($u->status_barang == 'active')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-warning">Tidak Aktif</span>
                                    @endif
                                </td>
                                @if (Auth::user()->level == 'A')
                                    <td><a href="/barang/edit/{{ $u->id_barang }}"
                                            class="btn btn-primary btn-sm ml-2">Edit</a>
                                        <button class="btn btn-danger btn-sm ml-2 btn_remove"
                                            data-id="{{ $u->id_barang }}"
                                            route="{{ route('barang.destroy') }}">Hapus</button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="tambah" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Masukan Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/barang/store" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Kode Barang</label>
                                    <button class="btn btn-primary btn_random" style="padding: 3px;">Random</button>
                                    <input type="text" name="id_barang" class="form-control id_barang"
                                        placeholder="Masukan Kode Barang" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama Barang</label>
                                    <input type="text" name="nama_barang" placeholder="Masukan Nama Barang"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="kategori_id" id="" class="form-control">
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Pemasok</label>
                                    <select name="pemasok_id" class="myselect form-control" required style="width: 100%">
                                        <option selected disabled value="">Pilih Pemasok</option>
                                        @foreach ($pemasok as $x)
                                            <option value="{{ $x->id }}">
                                                {{ $x->nama_pemasok }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Jumlah</label>
                                    <input type="text" name="jumlah_barang" class="form-control" id="jumlah_barang"
                                        placeholder="Masukan Jumlah Barang" required>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="">Harga Pokok</label>
                                    <input type="text" id="harga_pokok" name="harga_pokok" placeholder="Masukan Harga Pokok"
                                        class="form-control" onkeyup="addcommatoinputwhenchange(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Keuntungan ( Persen % )</label>
                                    <input type="text" id="keuntungan" name="keuntungan" value="20" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="">Total Keuntungan</label>
                                    <input type="text" id="total_keuntungan" name="harga_jual" value="0"
                                        onchange="addcommatoinputwhenchange(this)" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Harga Jual</label>
                                    <input type="text" id="harga_jual" name="harga_jual" value="0"
                                        onchange="addcommatoinputwhenchange(this)" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tanggal Pasok</label>
                                    <input type="date" name="tanggal_pasok" class="form-control" required value={{ date('Y-m-d')}}>
                                </div>
                                <div class="form-group">
                                    <label for="">Total Harga</label>
                                    <input type="text" name="total_harga" id="total_harga" placeholder="Masukan total harga"
                                        class="form-control total_harga" onkeyup="addcommatoinputwhenchange(this)" required>
                                </div>


                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tipe Pembayaran</label>
                                    <br>
                                    <select name="tipe_pembayaran" id="tipe_pembayaran" class="form-control" required
                                        style="width: 100%" name="tipe_pembayaran">
                                        <option value="cash">Cash</option>
                                        <option value="credit">Credit</option>
                                    </select>
                                </div>

                                <div class="form-group" id="tanggal_kredit" style="display: none">
                                    <label for="">Tanggal Jatuh Tempo Kredit</label>
                                    <input type="date" name="tanggal_kredit" class="form-control tanggal_kredit">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.myselect').select2();

        $("#harga_pokok, #keuntungan").keyup(function() {
            var harga_pokok = $("#harga_pokok").val().replace(",", "");
            var keuntungan = $("#keuntungan").val();
            var harga_jual = (parseInt(harga_pokok) + (harga_pokok * keuntungan / 100));

            addcommajquery($("#total_keuntungan").val(parseInt(harga_pokok * keuntungan / 100, 10)));
            addcommajquery($("#harga_jual").val(parseInt(harga_jual, 10)));
        });
        
        
        $("#harga_pokok, #jumlah_barang").keyup(function() {
            console.log('dirubah')
            var total = $('#harga_pokok').val().replace(",", "")* $('#jumlah_barang').val().replace(",", "")
            addcommajquery($('#total_harga').val(total))
            
            
        })

        $("#harga_jual").keyup(function() {

            var harga_pokok = $("#harga_pokok").val().replace(",", "");
            var harga_jual = $(this).val().replace(",", "");
            // var keuntungan = $("#keuntungan").val();

            // if ($(this).val() < harga_pokok) {

            // }
            
            // Jumlah Bagian/Jumlah Keseluruhan x 100%
            
            var keuntungan = harga_jual-harga_pokok

            var persen =  ( keuntungan / harga_pokok ) * 100
            
            addcommajquery($("#keuntungan").val(persen))
            addcommajquery($("#total_keuntungan").val(keuntungan))

        });

        function addcommatoinputwhenchange(input) {
            var val = input.value;
            var newval = val.replace(/,/g, '');
            input.value = newval.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function addcommajquery(input) {
            var val = input.val();
            var newval = val.replace(/,/g, '');
            input.val(newval.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        }
       

        $("#tipe_pembayaran").change(function() {
            $(this).val() == "cash" ? $("#tanggal_kredit").hide() : $("#tanggal_kredit").show();
            $(this).val() == "cash" ? $(".tanggal_kredit").attr("required", false) : $(
                ".tanggal_kredit").attr(
                "required", true);

        });

        // $(".btn_random").click(function() {
        //     var text = "";
        //     var possible = "0123456789";

        //     for (var i = 0; i < 13; i++)
        //         text += possible.charAt(Math.floor(Math.random() * possible.length));
        //     $(".id_barang").val(text);
        // })
    </script>
@endpush
