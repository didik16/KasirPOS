<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Custom fonts for this template -->
    <link href="{{ url('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ url('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this page -->

    <link href="{{ url('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body id="page-top">

    <title>Barang | Kasir</title>
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .td {
            color: black;
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

    </style>
    @if (Session::get('gagal') != '')
        <div class='alert alert-danger'>
            <center><b>{{ Session::get('gagal') }}</b></center>
        </div>
    @endif

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">
            <div class="container-fluid">
                <div class="d-flex flex-column justify-content-center w-100">
                    <div class="row">
                        <div class="col-md-12 w-100">
                            {{-- <div class="d-flex flex-column justify-content-center align-items-center vh-100"> --}}
                            <div class="row ">
                                <div class="col-md-8 pt-3 pb-3"
                                    style="background:#ebebeb;border-bottom:3px solid #c5c2c2">
                                    {{-- <h1>Total</h1> --}}
                                    <p style="margin: 0px">Kasir : <b>{{ Auth::user()->name }}</b></p>
                                    <font class="kode_transaksi" color="blue">Kode Transaksi : <b>{{ $max_code }}</b>
                                    </font>
                                </div>
                                <div class="col-md-3 pt-3 pb-3"
                                    style="background:#ebebeb;border-bottom:3px solid #c5c2c2">
                                    <h1 style="font-weight:900;text-align:left" id="total_harga">
                                        {{ number_format(Cart::getTotal()) }}</h1>
                                </div>
                                <div class="col-md-1 d-flex justify-content-center align-items-center"
                                    style="background:#ebebeb;border-bottom:3px solid #c5c2c2">
                                    <p><a href="{{ url('home') }}" class=" btn btn-dark">Dashboard</a></p>

                                </div>
                                <div class="col-md-12 mt-4 d-flex flex-column w-100"
                                    style="height: 64vh;display: inline-block; overflow: auto;">

                                    <div class="row" style="position: sticky;top:0;background:#ffffff">
                                        <div class="  col-md-8">
                                            <div class="form-group">
                                                <label for="">Masukan Kode Barang (scan/manual)</label>
                                                <input type="text" id="id_barang" name="id_barang"
                                                    class="form-control" required="" placeholder="Masukan Kode">
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                                            <div class="form-group" style="width:100%">
                                                <label for="">Cari Barang</label>
                                                <select name="barang" class="myselect barang" style="width: 100%">
                                                    <option value="">Pilih Barang</option>
                                                    @foreach ($barang as $item)
                                                        <option value="{{ $item->id_barang }}">
                                                            {{ $item->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <button class="btn btn-primary" id="tambah_barang">Cari Barang</button> --}}
                                        </div>
                                    </div>


                                    <table class="table" style=" width:100%">
                                        <thead style="position: sticky;top:80px;background: #ebebeb;">
                                            <tr>
                                                <th scope="col" style="width: 15%">Kode</th>
                                                <th scope="col" style="width: 32%">Nama</th>
                                                <th scope="col" style="width: 7%">Qty</th>
                                                <th scope="col">Harga</th>
                                                <th scope="col">Total</th>
                                                <th scope="col" style="width: 5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="list_barang">
                                            @foreach ($cartItems as $item)
                                                <tr class="data_barang data_{{ $item->id }}" style="height: 10px">
                                                    <td>{{ $item->id }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>
                                                        <input type="number" value="{{ $item->quantity }}"
                                                            class="form-control jumlah" id="jumlah_{{ $item->id }}"
                                                            name="jumlah_{{ $item->id }}" min="1"
                                                            data-id="{{ $item->id }}"
                                                            style=" width: 100%;text-align: center;">
                                                        {{-- {{ number_format($item->quantity) }} --}}
                                                    </td>
                                                    <td id="harga_{{ $item->id }}">
                                                        {{ number_format($item->price) }}</td>
                                                    <td id="total_{{ $item->id }}">
                                                        {{ number_format($item->price * $item->quantity) }}</td>
                                                    <td>
                                                        <button class="btn btn-danger btn_remove"
                                                            data-id="{{ $item->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>

                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>

                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex align-items-center justify-content-center"
                        style="background:#ebebeb;border-top:3px solid #c5c2c2">
                        <div class="col-md-5 pt-3 pb-3">
                            <div class="form-group row">
                                <label for="colFormLabelLg"
                                    class="col-sm-2 col-form-label col-form-label-lg">Bayar</label>
                                <div class="col-sm-10">
                                    <input style="font-size: 1.9rem;" type="text"
                                        class="form-control form-control-lg bayar" id="colFormLabelLg"
                                        placeholder="Total Bayar">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 pt-3 pb-3">
                            <p style="margin: 0px">Kembalian</p>
                            <h1 class="kembalian">Rp. 0</h1>
                        </div>
                        <div class="col-md-2 pt-3 pb-3">
                            <button class="btn btn-primary proses" style="font-size: 3rem;" disabled>Proses</button>
                            <input type="hidden" name="kode_transaksi_kembalian" value="{{ $max_code }}">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Cari barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <select name="barang" class="myselect barang" style="width: 100%">
                        @foreach ($barang as $item)
                            <option value="{{ $item->id_barang }}">{{ $item->nama_barang }}</option>
                        @endforeach
                    </select> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="{{ url('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"
integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function addComma(val) {
        while (/(\d+)(\d{3})/.test(val.toString())) {
            val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
        }
        return val;
    }

    $(document).ready(function() {
        $('#id_barang').focus();
    });

    $('.myselect').select2({
        // dropdownParent: $('.modal-body')
    });

    //jika di close
    $(document).on('keyup', '.select2-search__field', function(e) {
        if ($('.select2-search__field')[0].value == "`") {
            $('.myselect').select2('close');
            $('#id_barang').focus()
        }

    })

    $(".myselect").select2( /*Your code*/ )
        .on('change', function(e) {


            var getID = $(this).select2('data');
            // alert(getID[0]['id']); // That's the selected ID :)
            var id = getID[0]['id'];

            $('#id_barang').val(id);
            if (id != "") {
                add_barang('#id_barang')
            }

            $(".myselect").select2().val('').trigger('change.select2');
            $('#id_barang').focus();
        });



    document.addEventListener("keypress", function onEvent(event) {
        // console.log('aaaa' + event.key);
        if (event.keyCode == 96) {

            var searchfield = $('.myselect').parent().find('.select2-search__field');
            $('#id_barang').val('')
            $('#id_barang').text('')
            $('.myselect').select2('open');
        }

    });


    function add_barang(val) {

        $("#id_barang").attr('readonly', 'readonly');

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var id = $(val).val();
        var jumlah = $('.data_barang').length;
        // var price = $('.price_' + id).val();
        // var image = $('.image_' + id).val();
        // var quantity = $('.quantity_' + id).val();
        console.log("jumlah " + jumlah)

        $.ajax({
            url: "{{ route('add_chart') }}",

            type: 'post',
            data: {
                _token: CSRF_TOKEN,
                id: id,
                jumlah: jumlah

            },
            success: function(data) {
                var responseOutput = JSON.parse(data);
                console.log(responseOutput)

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal
                            .resumeTimer)
                    }
                })

                if (responseOutput.statusCode == 200) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Barang berhasil ditambahkan'
                    })

                    if ($('.data_' + responseOutput.id).length) {
                        var current_jumlah = $("#jumlah_" + responseOutput.id).val()
                        var harga = $('.data_' + responseOutput.id).find("#harga_" +
                                responseOutput.id)
                            .text().replace(",", "")
                        var current_total = $('.data_' + responseOutput.id).find("#total_" +
                                responseOutput.id)
                            .text().replace(",", "")

                        console.log('current jumlah ' + current_jumlah)

                        $('.data_' + responseOutput.id).find("#jumlah_" + responseOutput.id)
                            .val(parseInt(current_jumlah) + 1);
                        $('.data_' + responseOutput.id).find("#total_" + responseOutput.id)
                            .text(addComma((parseInt(current_jumlah) + 1) * parseInt(harga)));

                        $('#total_harga').text(addComma(responseOutput.total));

                        if ($('.bayar').val() != "") {
                            $(".bayar").trigger("keyup");
                        }
                    } else {
                        $('#list_barang').append(responseOutput.data);
                        $('#total_harga').text(addComma(responseOutput.total));

                        if ($('.bayar').val() != "") {
                            $(".bayar").trigger("keyup");
                        }
                    }

                    $('#id_barang').val(null);
                    $("#id_barang").removeAttr('readonly');
                } else if (responseOutput.statusCode == 206) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Stok barang habis!'
                    })
                    $('#id_barang').val(null);
                    $("#id_barang").removeAttr('readonly');
                
                }else {
                    Toast.fire({
                        icon: 'error',
                        title: 'ID Barang tidak tersedia'
                    })
                    $('#id_barang').val(null);
                    $("#id_barang").removeAttr('readonly');


                }



                // $('#quantity').html(
                //     '<span class="badge badge-warning" id="lblCartCount">' +
                //     responseOutput.qty + '</span>')

                // const Toast = Swal.mixin({
                //     toast: true,
                //     position: 'top-end',
                //     showConfirmButton: false,
                //     timer: 2000,
                //     timerProgressBar: true,
                //     didOpen: (toast) => {
                //         toast.addEventListener('mouseenter', Swal.stopTimer)
                //         toast.addEventListener('mouseleave', Swal
                //             .resumeTimer)
                //     }
                // })

                // Toast.fire({
                //     icon: 'success',
                //     title: 'Menu successfully added'
                // })

            }
        });
    }
    //tambah barang
    $('#id_barang').keypress(function(e) {
        if (e.keyCode == 13) {
            add_barang('#id_barang')

        }
    });


    //Update Jumlah
    $(document).on('change keyup', '.jumlah', function() {

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var id = $(this).attr('data-id');
        var quantity = $(this).val();

        if ($.isNumeric(quantity)) {
            if (quantity == 0 || quantity < 0) {
                quantity = 1;
                $(this).val(1);
            }
            $.ajax({
                url: "{{ route('update_chart') }}",

                type: 'post',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                    quantity: quantity,
                },
                success: function(data) {
                    
                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal
                            .resumeTimer)
                    }
                })
                
                    var responseOutput = JSON.parse(data);
                    
                    if(responseOutput.statusCode==404){
                        Toast.fire({
                        icon: 'error',
                        title: 'Barang tidak ada!'
                        })
                    }else if (responseOutput.statusCode==206){
                         Toast.fire({
                            icon: 'error',
                            title: 'Stok barang habis!'
                            })
                            
                            $('#jumlah_'+id).val(responseOutput.max)
                    
                    }else{
                        
                        var harga = $('.data_' + responseOutput.id).find("#harga_" +
                                responseOutput.id)
                            .text().replace(",", "")
    
                        $('.data_' + responseOutput.id).find("#total_" + responseOutput.id)
                            .text(addComma(quantity * parseInt(harga)));
    
                        $('#total_harga').text(addComma(responseOutput.total));
    
                        if ($('.bayar').val() != "") {
                            $(".bayar").trigger("keyup");
                        }
                    
                    }

                }
            });
        }
    });

    //Atur fokus
    $(document).on('click', 'body', function() {

        if ($(".jumlah").is(":focus") || $(".myselect").select2("isOpen") || $(".bayar").is(":focus")) {
            console.log('dokus')

        } else {
            console.log('tidak dokus')
            $('#id_barang').focus();
        }
    })

    $('.bayar').on('keyup', function(event) {

        console.log(event.key + ' ' + event.keyCode + ' ' + event.which)
        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });

        var total = $('#total_harga').text().replace(",", "");
        var bayar = $('.bayar').val().replace(",", "");

        if ($('.data_barang').length <= 0) {

            $('.kembalian').text('Barang kosong');
            $('.kembalian').attr('value', 0);
            $(".proses").attr("disabled", true);

        } else if (parseInt(bayar) < parseInt(total)) {
            $('.kembalian').text('Uang Anda Kurang');
            $(".proses").attr("disabled", true);
            $('.kembalian').attr('value', 0);
        } else {

            if ($('.bayar').val() == "") {
                $('.kembalian').text('Rp. 0');
                $('.kembalian').attr('value', 0);
                $(".proses").attr("disabled", true);
            } else {
                $('.kembalian').text('Rp. ' + addComma(parseInt(bayar) - parseInt(total)));
                $('.kembalian').attr('value', parseInt(bayar) - parseInt(total));

                $(".proses").removeAttr("disabled");

                if (event.key == "Enter") {
                    store()
                }

                // if (event.key == "Enter") {
                //     store()
                // }
            }
        }


    });


    //Remove Cart
    $(document).on('click', '.btn_remove', function() {
        var id = $(this).attr('data-id')

        Swal.fire({
            title: 'Hapus barang ?',
            showDenyButton: true,
            icon: 'warning',
            // showCancelButton: true,
            confirmButtonText: 'Hapus',
            denyButtonText: `Batal`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ route('remove_chart') }}",

                    type: 'post',
                    data: {
                        _token: CSRF_TOKEN,
                        id: id,
                    },
                    success: function(data) {
                        var responseOutput = JSON.parse(data);
                        $('.data_' + id).remove()
                        $('#total_harga').text(addComma(responseOutput.total))

                        if ($('.bayar').val() != "") {
                            $(".bayar").trigger("keyup");
                        }
                        // $('#quantity').html(
                        //     '<span class="badge badge-warning" id="lblCartCount">' +
                        //     responseOutput.qty + '</span>')
                        // Swal.fire('Deleted!', '', 'success')
                        Swal.fire({
                         
                          icon: 'success',
                          title: 'Deleted!',
                          
                          timer: 500
                        })
                        $('#id_barang').focus()
                    }
                });

            } else if (result.isDenied) {
                // Swal.fire('Changes are not saved', '', 'info')

                if ($('.bayar').val() != "") {
                    $(".bayar").trigger("keyup");
                }
            }
        })
    });

    function store() {
        var total_bayar = $('.bayar').val().replace(",", "");
        var kembalian = $('.kembalian').attr('value');

        Swal.fire({
            title: 'Proses Transaksi ?',
            showDenyButton: true,
            icon: 'warning',
            confirmButtonText: 'Proses',
            denyButtonText: `Batal`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ route('store_chart') }}",

                    type: 'post',
                    data: {
                        _token: CSRF_TOKEN,
                        total_bayar: total_bayar,
                        kembalian: kembalian,
                    },
                    success: function(data) {
                        var responseOutput = JSON.parse(data);

                        if (responseOutput.statusCode == 200) {
                            $('.data_barang').remove();
                            $('.bayar').val('');
                            $('.kembalian').attr('value', 0);
                            $('.kembalian').text('Rp. 0');
                            $('#total_harga').text('0');
                            $('.kode_transaksi').html(
                                '<font class="kode_transaksi" color="blue">Kode Transaksi : <b>' +
                                responseOutput.new_kode + '</b></font>');
                            Swal.fire('Transaksi Sukses!', '', 'success')
                            $(".proses").attr("disabled", true);

                            $('#id_barang').focus()

                        } else {
                            Swal.fire(responseOutput.message, '', 'error')
                        }
                    }
                });

            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')

                if ($('.bayar').val() != "") {
                    $(".bayar").trigger("keyup");
                }
            }
        })
    }

    //Store Transaksi
    $(document).on('click', '.proses', function() {
        store()

    });

    //function number format jquery 
</script>


</html>
