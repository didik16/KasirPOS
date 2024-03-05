<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">
    <meta content="{{ csrf_token() }}" name="csrf-token">


    <!-- Custom fonts for this template -->
    <link href="{{ url('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ url('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this page -->

    <link href="{{ url('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />


    <!--Daterangepicker -->
    <link href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/home">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Toko Mesari</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->is('home*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('home') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->


            <!-- Nav Item - Utilities Collapse Menu -->

            <!-- Nav Item - Charts -->
            @if (Auth::user()->level == 'A')
                <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('user') }}">
                        <i class="fas fa-fw fa-user"></i>
                        <span>Data Admin</span></a>
                </li>

                <li class="nav-item {{ request()->is('kasir*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('kasir') }}">
                        <i class="fas fa-fw fa-users"></i>
                        <span>Data Kasir</span></a>
                </li>

                <li class="nav-item">
                    <a aria-controls="collapseTwo" aria-expanded="true" class="nav-link collapsed"
                        data-target="#collapseTwo" data-toggle="collapse" href="#">
                        <i class="fas fa-fw fa-shopping-basket  "></i>

                        <span>Barang</span>
                    </a>
                    <div aria-labelledby="headingTwo"
                        class="collapse {{ ((request()->is('kategori*') ? 'show' : request()->is('pemasok*')) ? 'show' : request()->is('barang*')) ? 'show' : '' }}"
                        data-parent="#accordionSidebar" id="collapseTwo">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Data Barang:</h6>
                            <a class="collapse-item {{ request()->is('kategori*') ? 'active' : '' }}"
                                href="{{ url('kategori') }}">Kategori</a>
                            <a class="collapse-item {{ request()->is('pemasok*') ? 'active' : '' }}"
                                href="{{ url('pemasok') }}">Pemasok</a>
                            <a class="collapse-item {{ request()->is('barang*') ? 'active' : '' }}"
                                href="{{ url('barang') }}">Barang</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a aria-controls="collapseThree" aria-expanded="true" class="nav-link collapsed"
                        data-target="#collapseThree" data-toggle="collapse" href="#">
                        <i class="fas fa-fw fa-briefcase"></i>
                        <span>Data Stok</span>
                    </a>
                    <div aria-labelledby="collapseThree"
                        class="collapse {{ ((request()->is('stok*') ? 'show' : request()->is('pembayaran_kredit*')) ? 'show' : request()->is('list_stok*')) ? 'show' : '' }}"
                        data-parent="#accordionSidebar" id="collapseThree">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Data Stok:</h6>
                            <a class="collapse-item {{ request()->is('stok*') ? 'active' : '' }}"
                                href="{{ url('stok') }}">Stok Barang</a>
                            <a class="collapse-item {{ request()->is('list_stok*') ? 'active' : '' }}"
                                href="{{ url('list_stok') }}">List Stok</a>
                            <a class="collapse-item {{ request()->is('pembayaran_kredit*') ? 'active' : '' }}"
                                href="{{ url('pembayaran_kredit') }}">Pembayaran Kredit</a>

                        </div>
                    </div>
                </li>


                <!-- Nav Item - Tables -->


                <li class="nav-item {{ request()->is('transaksi*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('transaksi') }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Transaksi</span></a>
                </li>


                <li class="nav-item">
                    <a aria-controls="collapseLaporan" aria-expanded="true" class="nav-link collapsed"
                        data-target="#collapseLaporan" data-toggle="collapse" href="#">
                        <i class="fas fa-fw fa-file"></i>
                        <span>Laporan</span>
                    </a>
                    <div aria-labelledby="collapseLaporan"
                        class="collapse {{ (request()->is('laporan*') ? 'show' : request()->is('pendapatan*')) ? 'show' : '' }}"
                        data-parent="#accordionSidebar" id="collapseLaporan">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Laporan:</h6>
                            <a class="collapse-item {{ request()->is('laporan*') ? 'active' : '' }}"
                                href="{{ url('laporan') }}">Transaksi List</a>
                            <a class="collapse-item {{ request()->is('pendapatan*') ? 'active' : '' }}"
                                href="{{ url('pendapatan') }}">Pendapatan</a>
                        </div>
                    </div>
                </li>

                {{-- <li class="nav-item {{ request()->is('laporan*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('laporan') }}">
                        <i class="fas fa-fw fa-file  "></i>
                        <span>Laporan</span></a>
                </li> --}}
                <!--<li class="nav-item {{ request()->is('printer*') ? 'active' : '' }}">-->
                <!--    <a class="nav-link" href="{{ url('printer') }}">-->
                <!--        <i class="fas fa-fw fa-print  "></i>-->
                <!--        <span>Pengaturan Printer</span></a>-->
                <!--</li>-->
            @endif
            @if (Auth::user()->level == 'K')
                <li class="nav-item {{ request()->is('transaksi*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('transaksi') }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Transaksi</span></a>
                </li>
                <li class="nav-item {{ request()->is('barang*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('barang') }}">
                        <i class="fas fa-fw fa-shopping-basket  "></i>
                        <span>Barang</span></a>
                </li>

                <li class="nav-item  {{ request()->is('laporan*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('laporan') }}">
                        <i class="fas fa-fw fa-file  "></i>
                        <span>Laporan</span></a>
                </li>
                <!--<li class="nav-item {{ request()->is('printer*') ? 'active' : '' }}">-->
                <!--    <a class="nav-link" href="{{ url('printer') }}">-->
                <!--        <i class="fas fa-fw fa-print  "></i>-->
                <!--        <span>Pengaturan Printer</span></a>-->
                <!--</li>-->
            @endif

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div class="d-flex flex-column" id="content-wrapper">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop">
                        <i class="fa fa-bars"></i>
                    </button>

                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="group">
                            <div class="mr-2">
                                {{ Carbon\Carbon::now()->format('l, d F Y') }}
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                                data-toggle="dropdown" href="#" id="userDropdown" role="button">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>

                            </a>
                            <!-- Dropdown - User Information -->
                            <div aria-labelledby="userDropdown"
                                class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <a class="dropdown-item" href="{{ url('change-password') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Change Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-target="#logoutModal" data-toggle="modal"
                                    href="#">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->

                    <!-- DataTales Example -->
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Geon Company {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="logoutModal" role="dialog"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form action="{{ route('logout') }}" id="logout-form" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ url('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ url('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ url('assets/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ url('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    @if (!request()->is('laporan*'))
        <script src="{{ url('assets/js/demo/datatables-demo.js') }}"></script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/date-1.1.2/datatables.min.js"></script>


    <!--DateRangePicker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    @stack('scripts')

    <script>
        //Remove Data
        $(document).on('click', '.btn_remove', function() {
            var route = $(this).attr('route');
            var id = $(this).attr('data-id')

            Swal.fire({
                title: 'Hapus Data ?',
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
                        url: route,

                        type: 'post',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                        },
                        success: function(data) {
                            var responseOutput = JSON.parse(data);

                            console.log(responseOutput.message);

                            if (responseOutput.statusCode == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: responseOutput.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: responseOutput.message,
                                    showConfirmButton: false,
                                    timer: 2500
                                })

                            }

                        }
                    });

                }
                // else if (result.isDenied) {
                //     Swal.fire('Perubahan tidak tersimpan', '', 'info')
                // }
            })
        });
    </script>

</body>

</html>
