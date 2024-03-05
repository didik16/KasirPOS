@extends('layouts.template')
@section('content')
    <title>Stok | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data List Stok</h6>
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
                <br>
                <table cellspacing="0" class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Total Stok</th>
                            <th>Stok Keluar</th>
                            <th>Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pasok as $i => $u)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $u->nama_barang }}</td>
                                <td>{{ $u->total }}</td>
                                @php
                                    $stok_keluar = \App\Transaksi::select(
                                        \DB::raw(' sum(jumlah_beli) 
                                     as total'),
                                    )
                                        ->where('barang_id', $u->barang_pasok_id)
                                        ->pluck('total');
                                    $stok_keluar = str_replace(['[', ']'], '', $stok_keluar);
                                    if ($stok_keluar == null || $stok_keluar == 'null') {
                                        $hasil_stok_keluar = 0;
                                    } else {
                                        $hasil_stok_keluar = $stok_keluar;
                                    }
                                @endphp
                                <td>{{ $hasil_stok_keluar }}
                                </td>
                                <td>{{ $u->total - $hasil_stok_keluar }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    @push('scripts')
        <script type="text/javascript">
            // $(".kode_barang").on("keydown", function(event) {
        </script>
    @endpush
@endsection
