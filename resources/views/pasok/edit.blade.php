@extends('layouts.template')
@section('content')
    <title>Pasok | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>
        <div class="card-body">
            <form action="/stok/update" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Nama Barang</label>
                        <input type="hidden" name="id_pasok" value={{ $pasok->id_pasok }}>
                        <input type="hidden" name="id_barang" class="form-control" value="{{ $pasok->barang_pasok_id }}"
                            required>
                        <input type="text" name="nama_barang" readonly class="form-control"
                            value="{{ $pasok->nama_barang }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Jumlah</label>
                        <input type="text" name="jumlah" class="form-control" value="{{ $pasok->jumlah_pasok }}"
                            required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Nama Pemasok</label>
                        <select name="nama_pemasok" class="myselect form-control" required style="width: 100%">
                            @foreach ($pemasok as $x)
                                <option value="{{ $x->id }}" @if ($x->id == $pasok->data_pemasok) selected @endif>
                                    {{ $x->nama_pemasok }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Total Harga</label>
                        <input type="text" name="total_harga" class="form-control" value="{{ $pasok->total_harga }}"
                            required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Tipe Pembayaran</label>
                        <select name="tipe_pembayaran" class=" form-control tipe_pembayaran" required style="width: 100%">
                            <option value="cash" @if ($pasok->tipe_pembayaran == 'cash') selected @endif>Cash</option>
                            <option value="credit" @if ($pasok->tipe_pembayaran == 'credit') selected @endif>Credit</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Waktu Pasok</label>
                        <input type="date" name="tanggal_pasok" class="form-control" value="{{ $pasok->tanggal_pasok }}"
                            required>
                    </div>

                    <div class="form-group  col-md-4">
                        <label for="">Waktu Jatuh Tempo Kredit</label>
                        <input type="date" name="tanggal_kredit" class="form-control tanggal_kredit"
                            value="{{ $pasok->tanggal_kredit }}" required
                            @if ($pasok->tanggal_kredit == null) disabled @endif>
                    </div>

                </div>

                <input type="submit" value="Update" class="btn btn-warning">
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.myselect').select2();

        $('.tipe_pembayaran').change(function() {
            if ($(this).val() == 'credit') {
                $('.tanggal_kredit').removeAttr('disabled');
            } else {
                $('.tanggal_kredit').attr('disabled', true);
            }
        });
    </script>
@endpush
