@extends('layouts.template')
@section('content')
    <title>Pembayaram Kredit | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pembayaram Kredit</h6>
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

                <table id="dataTable" class="table table-bordered" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Nama Pemasok</th>
                            <th>Waktu Pasok</th>
                            <th>Jatuh Tempo</th>
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
                                <td>{{ $u->tanggal_kredit }}</td>
                                <td>{{ number_format($u->total_harga) }}</td>
                                <td><a href="/stok/edit/{{ $u->id_pasok }}" class="btn btn-primary btn-sm ml-2">Update</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="tambah" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Masukan Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/stok/store" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input_fields_wrap">
                                <button class="add_field_button btn btn-primary">Tambah Stok Lainnya</button>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="">Nama Barang</label>
                                            <br>
                                            <select name="id_barang[]" id="" class="myselect form-control" required>
                                                <option selected disabled value="">Pilih Jenis Barang</option>
                                                @foreach ($barang as $j)
                                                    <option value="{{ $j->id_barang }}">{{ $j->nama_barang }}</option>
                                                @endforeach
                                            </select>
                            </div>
                            </td>
                            <td class="pl-4">
                                <label for="">Jumlah</label>
                                <input type="number" name="jumlah[]" class="form-control" required
                                    placeholder="Masukan Jumlah" min="1" required>
                            </td>
                            </tr>
                            </table>

                        </div>
                        <div class="form-group">
                            <label for="">Nama Pemasok</label>
                            {{-- <input type="text" name="nama_pemasok" class="form-control" placeholder="Masukan Nama Pemasok"
                                required> --}}
                            <br>
                            <select name="pemasok_id" id="" class="myselect form-control" required style="width: 100%">
                                <option selected disabled value="">Pilih Pemasok</option>
                                @foreach ($pemasok as $x)
                                    <option value="{{ $x->id }}">{{ $x->nama_pemasok }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Tanggal Pasok</label>
                            <input type="date" name="tanggal_pasok" class="form-control" required>
                        </div>

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
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                var max_fields = 100; //maximum input boxes allowed
                var wrapper = $(".input_fields_wrap"); //Fields wrapper
                var add_button = $(".add_field_button"); //Add button ID

                var x = 1; //initlal text box count
                $(add_button).click(function(e) { //on add input button click
                    e.preventDefault();
                    if (x < max_fields) { //max input box allowed
                        x++; //text box increment


                        var barang =
                            '<div><table><tr><td><select name="id_barang[]" id="" class="myselect form-control" required><option selected disabled value="">Pilih Jenis Barang</option>';
                        @foreach ($barang as $j)
                            barang += '<option value="{{ $j->id_barang }}">{{ $j->nama_barang }}</option>'
                        @endforeach
                        barang +=
                            '</select></div></td><td class="pl-4"><input type="number" name="jumlah[]" min="1" class="form-control" required placeholder="Masukan Jumlah" required></td></tr></table><a href="#" class="remove_field">Remove</a></div>'

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
                $(this).val() == "cash" ? $(".tanggal_kredit").attr("required", false) : $(".tanggal_kredit").attr(
                    "required", true);

            });
        </script>
    @endpush
@endsection
