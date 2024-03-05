@extends('layouts.template')
@section('content')
    <title>Data Laporan | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Laporan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table id="dataTable" class="table table-bordered" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Jumlah Bayar</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporan as $i => $u)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $u->kode_transaksi_kembalian }}</td>
                                <td>{{ number_format($u->bayar) }}</td>
                                <td>{{ number_format($u->bayar - $u->kembalian) }}</td>
                                <td>{{ date('d/m/Y', strtotime($u->tanggal_transaksi)) }}</td>
                                <td>{{ date('H:i:s', strtotime($u->tanggal_transaksi)) }}</td>
                                <td><a href="/laporan/{{ $u->kode_transaksi_kembalian }}"
                                        class="btn btn-primary btn-sm ml-2">View</a></td>
                            </tr>
                        @endforeach
                    {{-- </tbody> --}}
                </table>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            //fungsi untuk filtering data berdasarkan tanggal 
            var start_date;
            var end_date;
            var DateFilterFunction = (function(oSettings, aData, iDataIndex) {
                var dateStart = parseDateValue(start_date);
                var dateEnd = parseDateValue(end_date);
                //Kolom tanggal yang akan kita gunakan berada dalam urutan 2, karena dihitung mulai dari 0
                //nama depan = 0
                //nama belakang = 1
                //tanggal terdaftar =2
                var evalDate = parseDateValue(aData[4]);
                if ((isNaN(dateStart) && isNaN(dateEnd)) ||
                    (isNaN(dateStart) && evalDate <= dateEnd) ||
                    (dateStart <= evalDate && isNaN(dateEnd)) ||
                    (dateStart <= evalDate && evalDate <= dateEnd)) {
                    return true;
                }
                return false;
            });

            // fungsi untuk converting format tanggal dd/mm/yyyy menjadi format tanggal javascript menggunakan zona aktubrowser
            function parseDateValue(rawDate) {
                var dateArray = rawDate.split("/");
                var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
                    0]); // -1 because months are from 0 to 11   
                return parsedDate;
            }

            $(document).ready(function() {
                //konfigurasi DataTable pada tabel dengan id example dan menambahkan  div class dateseacrhbox dengan dom untuk meletakkan inputan daterangepicker
                var $dTable = $('#dataTable').DataTable({

                    "dom": "<'row'<'col-sm-4'l><'col-sm-5' <'datesearchbox'>><'col-sm-3'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>"
                });

                //menambahkan daterangepicker di dalam datatables
                $("div.datesearchbox").html(
                    '<div class="input-group"> <div class="input-group-addon"> <i class="glyphicon glyphicon-calendar"></i> </div><input type="text" class="form-control pull-right" id="datesearch" placeholder="Search by date range.."> </div>'
                );

                document.getElementsByClassName("datesearchbox")[0].style.textAlign = "right";

                //konfigurasi daterangepicker pada input dengan id datesearch
                $('#datesearch').daterangepicker({
                    autoUpdateInput: false
                });

                //menangani proses saat apply date range
                $('#datesearch').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                        'DD/MM/YYYY'));
                    start_date = picker.startDate.format('DD/MM/YYYY');
                    end_date = picker.endDate.format('DD/MM/YYYY');
                    $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
                    $dTable.draw();
                });

                $('#datesearch').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    start_date = '';
                    end_date = '';
                    $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
                    $dTable.draw();
                });
            });
        </script>
    @endpush
