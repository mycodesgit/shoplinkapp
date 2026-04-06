<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('uilibs/images/cpsulogov4.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('uilibs/images/cpsulogov4.png') }}">

    <link rel="stylesheet" href="{{ asset('uilibs/css/main.css') }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/toastr/toastr.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- DataTables  -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fullcalendar/fullcalendar.css') }}">

    <style>
        .nav-link {
            font-size: 14px;
        }

        .nav-link:hover {
            background-color: #f8f9fa;
            border-radius: 6px;
        }

        .collapse .nav-link {
            color: #555;
        }
        .sidebar .nav-link.active {
            color: #000000 !important;
            background-color: #65ac86 !important;
        }
        /* When sidebar is collapsed, remove active background */
        .sidebar.collapsed .nav-link.active,
        .sidebar.collapsed .nav-link:hover {
            background-color: transparent !important;
            color: inherit !important;
        }
        /* main {
            background-color: #f4f6f9;
        } */
        .fc-event {
            border-color: #198754; background-color: #198754;
        }
        @media (max-width: 768px) {
            .fc .fc-daygrid-day-frame {
                min-height: 45px;
            }
        }
        .card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-hover:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .sidebar .nav-link .fa {
            font-size: 18px !important;
        }
        .fa {
            font-family: tabler-icons !important;
            speak: none;
            font-style: normal;
            font-weight: 400;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .bottom-nav {
            display: none;
        }
        @media (max-width: 991px) {
            .bottom-nav {
                position: fixed;
                bottom: 7px;
                left: 50%;
                transform: translateX(-50%);
                /* background: rgba(37, 37, 37, 0); */
                background:
                radial-gradient(ellipse at 15% 0%, rgba(147,197,253,.3) 0%, transparent 50%),
                radial-gradient(ellipse at 85% 15%, rgba(196,181,253,.2) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 100%, rgba(167,243,208,.15) 0%, transparent 50%),
                #f4f5f7;
                backdrop-filter: blur(3px);
                width: 90%;
                max-width: 400px;
                padding: 10px 0;
                border-radius: 20px;
                display: flex;
                justify-content: space-around;
                box-shadow: 0 6px 16px rgba(128, 128, 128, 0.404);
                z-index: 999;
            }

            .bottom-nav a {
                text-decoration: none !important;
                color: inherit; /* keep text/icon color the same */
            }

            .bottom-nav a:visited,
            .bottom-nav a:active,
            .bottom-nav a:focus {
                text-decoration: none !important;
            }

            .nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                font-size: 7pt;
                /* color: #377858; */
                color: #377858;
                cursor: pointer;
                transition: 0.2s;
                padding: 5px;
            }

            .nav-item .icon {
                font-size: 18px;
                margin-bottom: 1px;
                margin-top: 3px;
            }

            .nav-item.active {
                /* color: #377858; */
                color: #377858;
                font-weight: normal;
                background: rgba(37, 37, 37, 0.2);
                backdrop-filter: blur(10px);
                border-radius: 10px;
                padding: 5px;
                /* width: 60px;
                height: 47px; */
            }
        }
        @media (max-width: 991px) {
            .main-sidebar.sidebar-style-2 {
                display: none !important;
            }
            .togglebar{
                display: none !important;
            }
        }
        .radio-group {
            display: flex;
            gap: 20px; /* Space between radio options */
            margin-top: 15px;
        }

        /* Style the radio buttons */
        .radio-group input[type="radio"] {
            width: 22px;
            height: 22px;
            accent-color: black; /* Change selected radio button color */
            cursor: pointer;
            vertical-align: middle; /* Ensure alignment with text */
        }

        /* Style the links and keep alignment */
        .radio-group a {
            display: flex;
            align-items: center;
            gap: 2px; /* Space between radio and text */
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none; /* Remove underline from links */
            color: black;
        }

        /* Ensure text and radio button are aligned */
        .radio-group a span {
            display: inline-block;
            margin-left: 5px;
        }

        /* Hide the default radio button */
        .radio-group input[type="radio"] {
            display: none;
        }

        /* Style for the custom radio button */
        .radio-group label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px; /* Size of the radio button */
            height: 35px;
            border-radius: 50%;
            border: 2px solid #999; /* Default border */
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            position: relative;
        }

        @media (max-width: 768px) {
            .radio-group label {
                width: 28px;
                height: 28px;
            }
            #topbar {
                border-bottom: 0 !important;
            }
        }

        /* When radio is selected, change background color */
        .radio-group input[type="radio"]:checked + label {
            background-color: #28a745; /* Blue background */
            color: white; /* White text */
            border-color: #28a745;
        }
        .textbold{
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top topbar px-3">
        <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm ">
            <i class="fas fa-bars"></i>
        </button>

        <!-- MOBILE -->
        <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2 d-none d-md-block">
            <i class="ti ti-layout-sidebar-left-expand"></i>
        </button>

        <div class="d-md-none">
            <div class="d-flex align-items-center gap-3">
            <div class="d-inline-flex">
                {{-- <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="logo" width="24"> --}}
                <i class="ti ti-shopping-cart"></i>
                <span class="logo-text ms-2" style="font-weight: bold">Student Portal</span>
            </div>
            </div>
        </div>

        <div>
            <!-- Navbar nav -->
            <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
                <!-- Dropdown -->
                <li class="ms-3 dropdown">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('uilibs/images/user.png') }}" alt="" class="avatar avatar-sm rounded-circle" /> {{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 200px;">
                        <div>
                            <div class="d-flex gap-3 align-items-center border-dashed border-bottom px-3 py-3">
                                <img src="{{ asset('uilibs/images/user.png') }}" alt="" class="avatar avatar-md rounded-circle" /> {{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }} <br> {{ Auth::guard('web')->user()->role }}
                                <div>
                                    <h5 class="mb-0 small"></h5>
                                    <p class="mb-0 small text-success"></p>
                                </div>
                            </div>
                            <div class="p-3 d-flex flex-column gap-1 medium lh-lg">
                                <a href="#!" class="text-secondary">
                                    <i class="ti ti-id"></i> <span>{{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }}</span>
                                </a>
                                <div style="border-bottom: 1px solid #dddddd;" class="mb-2"></div>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger">
                                    <i class="ti ti-logout"></i><span> Signout</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </nav>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar">
        <div class="logo-area">
            <div class="d-inline-flex">
                {{-- <img src="{{ asset('uilibs/images/cpsulogov4.png') }}" alt="logo" width="24"> --}}
                <i class="ti ti-shopping-cart"></i>
                <span class="logo-text ms-2" style="font-weight: bold">ShopLink</span>
            </div>
        </div>
        @include('admin.partials.sidebar')

    </aside>

    <!-- MAINmainCONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            @yield('body')

            <div class="row d-none d-md-block">
                <div class="col-12">
                    <footer class="text-center py-2 mt-6 text-secondary fixed-bottom bg-white" style="z-index: 99">
                        <p class="mb-0">CISS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</p>
                    </footer>
                </div>
            </div>
        </div>
        {{-- @include('partials.control_mobile_bottomenu') --}}
    </main>

    <!-- Bootstrap JS -->

    <script type="text/javascript" src="{{ asset('uilibs/js/main.js') }}"></script>
    <!-- jQuery -->
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('uilibs/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{ asset('uilibs/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/fullcalendar/fullcalendar.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('uilibs/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('uilibs/plugins/toastr/toastr.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('uilibs/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Validation JS -->
    <script src="{{ asset('uilibs/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    <script>
        $(function () {
            $('.select2').select2();

            $('.select2bs4').select2({
                theme: 'bootstrap4',
                //height: '150'
            })
        });
        $(document).ready(function() {
            @if(session('error'))
                toastr.error("{{ session('error') }}", "Error", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-bottom-left",
                    timeOut: 5000
                });
            @endif

            @if(session('success'))
                toastr.success("{{ session('success') }}", "Success", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-bottom-left",
                    timeOut: 10000
                });
            @endif
        });
    </script>

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
    @if (request()->routeIs('welcomebanner.index'))
        @include('admin.adscript.baner.welcomeSerialize')
    @endif

</body>

</html>