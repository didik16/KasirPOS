@extends('layouts.template')
@section('content')
    <title>Barang | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>
        <div class="card-body">
            <form action="/barang/update" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Kode Barang</label>
                            <input class="form-control" name="id_barang2" required type="hidden"
                                value="{{ $barang->id_barang }}">
                            <input class="form-control" name="id_barang" required type="text"
                                value="{{ $barang->id_barang }}">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Barang</label>
                            <input class="form-control" name="nama_barang" required type="text"
                                value="{{ $barang->nama_barang }}">
                        </div>
                        <div class="form-group">
                            <label for="">Kategori</label>
                            <select class="myselect form-control" id="" name="kategori_id" required
                                style="width: 100%">
                                <option disabled selected value="">Pilih Kategori</option>

                                @if ($kategori_selected->status == 'disable')
                                    <option selected value="{{ $kategori_selected->id_kategori }}">
                                        {{ $kategori_selected->nama_kategori }} ( Tidak Aktif )</option>
                                @endif

                                @foreach ($kategori as $k)
                                    @if ($barang->kategori_id == $k->id_kategori)
                                        <option selected value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                    @else
                                        <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Pemasok</label>
                            <select class="myselect form-control" name="nama_pemasok" required style="width: 100%">
                                @if ($pemasok_selected->status == 'disable')
                                    <option selected value="{{ $pemasok_selected->id }}">
                                        {{ $pemasok_selected->nama_pemasok }} ( Tidak Aktif )</option>
                                @endif

                                @foreach ($pemasok as $x)
                                    <option @if ($x->id == $barang->pemasok_id) selected @endif value="{{ $x->id }}">
                                        {{ $x->nama_pemasok }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah</label>
                            <input class="form-control" name="jumlah_barang" required type="text"
                                value="{{ $barang->jumlah_barang }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Harga Pokok</label>
                            <input class="form-control" id="harga_pokok" name="harga_pokok"
                                onkeyup="addcommatoinputwhenchange(this)" required type="text"
                                value="{{ number_format($barang->harga_pokok) }}">
                        </div>
                        <div class="form-group">
                            <label for="">Keuntungan ( Persen % )</label>
                            <input class="form-control" id="keuntungan" name="keuntungan" required type="text"
                                value="{{ number_format((($barang->harga_jual - $barang->harga_pokok) / $barang->harga_pokok) * 100) }}">
                        </div>
                        <div class="form-group">
                            <label for="">Total Keuntungan</label>
                            <input class="form-control" id="total_keuntungan" name="harga_jual" readonly type="text"
                                value="{{ number_format($barang->harga_jual - $barang->harga_pokok) }}">
                        </div>
                        <div class="form-group">
                            <label for="">Harga Jual ( Satuan )</label>
                            <input class="form-control" id="harga_jual" name="harga_jual" type="text"
                                value="{{ number_format($barang->harga_jual) }}">
                        </div>
                        <label for="">Status</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input @if ($barang->status_barang == 'active') checked @endif class="form-check-input"
                                id="inlineRadio1" name="status" type="radio" value="active">
                            <label class="form-check-label" for="inlineRadio1">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input @if ($barang->status_barang == 'disable') checked @endif class="form-check-input"
                                id="inlineRadio2" name="status" type="radio" value="disable">
                            <label class="form-check-label" for="inlineRadio2">Tidak Aktif</label>
                        </div>
                        <br>
                    </div>
                    <hr>
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6">

                    </div>
                    <input class="btn btn-warning" type="submit" value="Update">
                </div>


            </form>
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

        $("#harga_jual").keyup(function() {

            var harga_pokok = $("#harga_pokok").val().replace(",", "");
            var harga_jual = $(this).val().replace(",", "");

            var keuntungan = harga_jual - harga_pokok

            var persen = (keuntungan / harga_pokok) * 100

            addcommajquery($("#keuntungan").val(persen))
            addcommajquery($("#total_keuntungan").val(keuntungan))

        });
    </script>
@endpush
