<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte-style.css') }}">

    <link rel="stylesheet" href="{{ asset('template/dist/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/plugins/fullcalendar/fullcalendar.css') }}">

    <link rel="shortcut icon" type="" href="{{ asset('template/img/slogo.png') }}">

    <style>
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active{
            background-color: #d3e2d7 !important ;
            color: #007B3A;
            box-shadow: none
        }
        [class*="sidebar-light-"] .nav-treeview>.nav-item>.nav-link.active, [class*="sidebar-light-"] .nav-treeview>.nav-item>.nav-link.active:hover, [class*="sidebar-light-"] .nav-treeview>.nav-item>.nav-link.active:focus {
            background-color: #007B3A !important;
            color: white;
        }
        [class*="sidebar-light-"] .nav-treeview>.nav-item>.nav-link {
            color: #343a40;
        }
        .breadcrumbactive{
            color: #32ac71 !important;
            font-weight: bold;
        }
        .breadcrumbactive a{
            color: #32ac71 !important;
            font-weight: bold;
        }
        .nav-item{
            cursor: pointer !important;
        }
        .nav-link:hover{
            background-color: none !important;
        }
        .btn-primary{
            background-color: #1f5036 !important;
            border: #1f5036 !important;
        }
        .folder-icon {
            transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        .folder-icon:hover {
            transform: scale(1.1);  /* Enlarge the icon on hover */
            color: #000;  /* Change color when hovered */
        }
        .content-wrapper {
            background-color: #f4f6f9 !important;
        }
        .table-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .page-item.active .page-link {
            z-index: 3;
            color: #fff !important;
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #1f5036 !important;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }
        .nav-tabs .nav-link.active {
            color: #000000 !important;
            background-color: #ffffff;
            border-top: 2px solid #32ac71;
            border-color: #32ac71 #32ac71 #ffffff;
        }

        [class*="sidebar-dark-"] {
            background-color: #4b545c !important;
        }
        .btn-shoplink{
            background-color: #d3e2d7 !important;
            border: #32ac71 !important;
            color: #007B3A !important;
        }
        /* .image {
            display: flex;
            align-items: center;
            justify-content: center;
        } */

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

    </style>
    
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed text-sm">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #ffffff;">

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars" style="color: #c0c0c0;"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="nav-link" href="#" role="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-power-off" style="color: #000000;"></i> <span style="color: #000000;">Sign Out</span>
                    </a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-light-primary elevation-0">
            <a href="{{ route('dash-index') }}" class="brand-link" style="background-color: #ffffff;">
                {{-- <img src="{{ asset('template/img/slogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
                <center>
                    <span class="brand-text font-weight-bold text-dark text-center">ShopLink</span>
                </center>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        @php
                            $user = Auth::guard('web')->user();
                            $initials = strtoupper(substr($user->fname, 0, 1) . substr($user->lname, 0, 1));

                            // optional color selection
                            $colors = ['#4F46E5', '#16A34A', '#DC2626', '#F59E0B', '#2563EB', '#9333EA'];
                            $color = $colors[$user->id % count($colors)];
                        @endphp
                        <div class="avatar-circle" style="background-color: {{ $color }};">
                            {{ $initials }}
                        </div>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            Juan Dela Cruz
                        </a>
                        <span style="font-size: 10pt; color: #1f1f1f;">
                            <i class="fa fa-circle text-success" style="font-size: 8pt"></i>
                            {{ Auth::guard('web')->user()->role; }}
                        </span>
                    </div>
                </div>

                @include('admin.partials.sidebar')

            </div>
        </aside>

        @yield('body')

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                
            </div>
            Lorem Ipsum Footer
        </footer>

    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>

    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('template/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('template/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/plugins/fullcalendar/fullcalendar.js') }}"></script>

    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    {{-- <script src="{{ asset('js/basic/contextmenu.js') }}"></script> --}}
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": false,
                "lengthChange": true,
                "autoWidth": true,
                "info": true,
                "lengthChange": false,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


            $("#example3").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

            }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

            $("#example4").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "searching": true,
                "buttons": ["excel"]

            }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');
        });
        $(function () {
            $('.select2').select2()

            $('.select2bs4').select2({
            theme: 'bootstrap4'
            })
        });
    </script>

    @if (request()->routeIs('category.index'))
        @include('admin.adscript.cat.categorySerialize')
    @endif
    @if (request()->routeIs('product.index'))
        @include('admin.adscript.prod.prodSerialize')
    @endif
    @if (request()->routeIs('user.index'))
        @include('admin.adscript.user.userSerialize')
    @endif
</body>
</html>
