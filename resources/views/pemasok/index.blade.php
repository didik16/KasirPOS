@extends('layouts.template')
@section('content')
    <title>
        Pemasok | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pemasok</h6>
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
                <button class="btn btn-success" data-toggle="modal" data-target="#tambah">Tambah Data</button>
                <br>
                <br>
                <table id="dataTable" class="table table-bordered" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pemasok</th>
                            <th>No HP</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pasok as $i => $u)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $u->nama_pemasok }}</td>
                                <td>{{ $u->no_hp }}</td>
                                <td>{{ $u->alamat }}</td>
                                <td>
                                    @if ($u->status == 'active')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-warning">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td><a href="/pemasok/edit/{{ $u->id }}" class="btn btn-primary btn-sm ml-2">Edit</a>
                                    <button class="btn btn-danger btn-sm ml-2 btn_remove" data-id="{{ $u->id }}"
                                        route="{{ route('pemasok.destroy') }}">Hapus</button>
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
                    <form action="/pemasok/store" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="">Nama Pemasok</label>
                            <input type="text" name="nama_pemasok" class="form-control" placeholder="Masukan Nama Pemasok"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="">No HP</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Masukan No HP Pemasok">
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <input type="text" name="alamat" class="form-control" placeholder="Masukan Alamat Pemasok">
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


                                    $(wrapper).append(
                                        '<div><table><tr><td><select name="id_barang[]" id="" class="myselect form-control" required><option selected disabled value="">Pilih Jenis Barang</option> < /
                                        select > < /div > < /
                                        td > < td class = "pl-4" > < input type = "number"
                                        name = "jumlah[]"
                                        class = "form-control"
                                        required placeholder = "Masukan Jumlah"
                                        required > < /td > < /
                                        tr > < /table > < a href = "#"
                                        class = "remove_field" > Remove < /a></div > ');
                                        $('.myselect').select2();
                                    }
                                });

                            $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
                                e.preventDefault();
                                $(this).parent('div').remove();
                                x--;

                            }) $('.myselect').select2();
                        });
        </script>
    @endpush
@endsection
