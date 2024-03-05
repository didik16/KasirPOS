@extends('layouts.template')
@section('content')
    <title>Stok | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Stok</h6>
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
                <button class="btn btn-success" data-target="#tambah" data-toggle="modal">Tambah Data</button>
                <br>
                <br>
                <table cellspacing="0" class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Nama Pemasok</th>
                            <th>Waktu Pasok</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pasok as $i => $u)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $u->nama_barang }}</td>
                                <td>{{ $u->jumlah_pasok }}</td>
                                <td>{{ $u->nama_pemasok }}</td>
                                <td>{{ $u->tanggal_pasok }}</td>
                                <td>{{ $u->tipe_pembayaran }}</td>
                                <td>{{ number_format($u->total_harga) }}</td>
                                <td><a class="btn btn-primary btn-sm ml-2" href="/stok/edit/{{ $u->id_pasok }}">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambah" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Masukan Data</h4>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/stok/store" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input_fields_wrap">
                                <button class="add_field_button btn btn-primary" tabindex="-1">Tambah Stok Lainnya</button>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="">Kode</label>
                                            <br>
                                            <input class="form-control kode_barang" id="kode_1" name="kode[]"
                                                placeholder="Kode" required required type="text">
                                        </td>
                                        <td>
                                            <label for="">Barang</label>
                                            <br>
                                            <select class="myselect form-control nama_barang" id="barang_1"
                                                name="id_barang[]" required>
                                                <option disabled selected value="">Pilih Barang</option>
                                                @foreach ($barang as $j)
                                                    <option value="{{ $j->id_barang }}">{{ $j->nama_barang }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="pl-4">
                                            <label for="">Jumlah</label>
                                            <input class="form-control" min="1" name="jumlah[]" placeholder="Jumlah"
                                                required required type="number">
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="">Nama Pemasok</label>
                            {{-- <input type="text" name="nama_pemasok" class="form-control" placeholder="Masukan Nama Pemasok"
                                required> --}}
                            <br>
                            <select class="myselect form-control" id="" name="pemasok_id" required
                                style="width: 100%">
                                <option disabled selected value="">Pilih Pemasok</option>
                                @foreach ($pemasok as $x)
                                    <option value="{{ $x->id }}">{{ $x->nama_pemasok }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Tanggal Pasok</label>
                            <input class="form-control" name="tanggal_pasok" required type="date">
                        </div>
                        <div class="form-group">
                            <label for="">Total Harga</label>
                            <input class="form-control total_harga" name="total_harga" placeholder="Masukan total harga"
                                required type="text">
                        </div>

                        <div class="form-group">
                            <label for="">Tipe Pembayaran</label>
                            <br>
                            <select class="form-control" id="tipe_pembayaran" name="tipe_pembayaran" name="tipe_pembayaran"
                                required style="width: 100%">
                                <option value="cash">Cash</option>
                                <option value="credit">Credit</option>
                            </select>
                        </div>

                        <div class="form-group" id="tanggal_kredit" style="display: none">
                            <label for="">Tanggal Jatuh Tempo Kredit</label>
                            <input class="form-control tanggal_kredit" name="tanggal_kredit" type="date">
                        </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script type="text/javascript">
            // $(".kode_barang").on("keydown", function(event) {
            $(document).on('keydown', '.kode_barang', function(event) {

                if (event.which == 13) {
                    event.preventDefault();

                    var id = $(this).attr('id')
                    id = id.replace("kode_", "");

                    console.log(id + ' idddd')


                    $.ajax({
                        url: '/get_barang/' + $(this).val(),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.statusCode == 200) {
                                console.log(data.barang.nama_barang)

                                $('#barang_' + id).select2().val(data.barang.id_barang).trigger("change");
                            } else {
                                alert("Data barang tidak ada!");
                                $('#barang_' + id).select2().val('').trigger("change");
                            }
                        }
                    });
                }
            });


            $(document).on('change', '.nama_barang', function(event) {

                event.preventDefault();

                var id = $(this).attr('id')
                id = id.replace("barang_", "");

                console.log(id + ' idddd')


                $.ajax({
                    url: '/get_barang/' + $(this).val(),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.statusCode == 200) {
                            console.log(data.barang.nama_barang)

                            $('#kode_' + id).val(data.barang.id_barang);
                        } else {
                            alert("Data barang tidak ada!");
                            $('#kode_' + id).val('');

                        }
                    }
                });
            });

            $('.total_harga').on('keyup', function(event) {

                console.log(event.key + ' ' + event.keyCode + ' ' + event.which)
                $(this).val(function(index, value) {
                    return value
                        .replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                });
            })

            $(document).ready(function() {
                var max_fields = 100; //maximum input boxes allowed
                var wrapper = $(".input_fields_wrap"); //Fields wrapper
                var add_button = $(".add_field_button"); //Add button ID

                var x = 1; //initlal text box count
                $(add_button).click(function(e) { //on add input button click
                    console.log('di klik')
                    e.preventDefault();
                    if (x < max_fields) { //max input box allowed
                        x++; //text box increment

                        var jumlah_sekarang = $('.kode_barang').length;

                        var barang =
                            `<div><table><tr><td><input class="form-control kode_barang" name="kode[]" id="kode_` +
                            (jumlah_sekarang + 1) + `" placeholder="Kode" required type="text"></td>`
                        barang +=
                            `<td><select name="id_barang[]" class="myselect form-control nama_barang" id="barang_` +
                            (
                                jumlah_sekarang + 1) +
                            `" required><option selected disabled value="">Pilih Barang</option>`;
                        @foreach ($barang as $j)
                            barang += '<option value="{{ $j->id_barang }}">{{ $j->nama_barang }}</option>'
                        @endforeach
                        barang +=
                            '</select></div></td><td class="pl-4"><input type="number" name="jumlah[]" min="1" class="form-control" required placeholder="Jumlah" required></td></tr></table><a href="#" class="remove_field">Remove</a></div>'

                        $(wrapper).append(barang);
                        $('.myselect').select2();
                    }
                });

                $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
                    e.preventDefault();
                    $(this).parent('div').remove();
                    x--;

                })
                $('.myselect').select2();
            });

            $("#tipe_pembayaran").change(function() {
                $(this).val() == "cash" ? $("#tanggal_kredit").hide() : $("#tanggal_kredit").show();
                $(this).val() == "cash" ? $(".tanggal_kredit").attr("required", false) : $(
                    ".tanggal_kredit").attr(
                    "required", true);

            });
        </script>
    @endpush
@endsection
