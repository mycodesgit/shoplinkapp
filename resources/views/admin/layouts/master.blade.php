<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('uilibs/images/favicon.svg') }}">

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

    @stack('styles')

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
                <li class="nav-item dropdown">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="position-relative btn-icon btn-sm btn-light btn rounded-circle" title="Order notifications">
                        <i class="ti ti-bell"></i>
                        <span id="notifTicketBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none; font-size:0.6rem;">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="width: 340px;">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <strong class="small">Order Notifications</strong>
                            <button type="button" class="btn btn-link btn-sm p-0 small notif-mark-all">Mark all read</button>
                        </div>
                        <ul id="notifTicketList" class="list-unstyled p-0 m-0" style="max-height: 320px; overflow-y: auto;">
                            <li class="px-3 py-3 text-center text-muted small">Loading...</li>
                        </ul>
                        <a href="{{ route('order.index') }}" class="d-block text-center small py-2 border-top text-decoration-none">View all orders</a>
                    </div>
                </li>
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
    {{-- <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script> --}}

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
            if ($('#example1').length) {
                $("#example1").DataTable({
                    "responsive": false,
                    "lengthChange": false,
                    "autoWidth": true,
                    "info": true,
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }

            if ($('#example3').length) {
                $("#example3").DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

                }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
            }

            if ($('#example4').length) {
                $("#example4").DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": true,
                    "searching": true,
                    "buttons": ["excel"]

                }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');
            }
        });
    </script>

    {{-- Page-specific scripts pushed from child views via @push('scripts') --}}
    @stack('scripts')

    {{-- Live order notifications (Laravel Reverb + AJAX fallback refresh) --}}
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@2.6.1/dist/echo.iife.js"></script>
    <script>
        @php
            $reverbConfig = [
                'key' => config('broadcasting.connections.reverb.key'),
                'host' => config('broadcasting.connections.reverb.options.host', 'localhost'),
                'port' => config('broadcasting.connections.reverb.options.port', 8080),
                'scheme' => config('broadcasting.connections.reverb.options.scheme', 'http'),
                'csrf' => csrf_token(),
            ];
        @endphp
        window.ReverbConfig = @json($reverbConfig);
        var adminOrderReadRoute = "{{ route('order.index') }}";
        var adminOrderApiRoute = "{{ route('order.show') }}";

        (function () {
            function handleNewOrder(order) {
                var label = 'New order ' + (order.order_number || '#' + order.id) +
                    ' — ' + (order.customer || 'Customer') +
                    ' (₱' + Number(order.total || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) + ')';
                if (window.toastr) {
                    toastr.success(label, 'New Order Received', { closeButton: true, progressBar: true, timeOut: 10000 });
                }
                // Live-refresh the orders table if this page has one (AJAX, no reload)
                $(document).trigger('orderUpdated');
            }

            // AJAX polling fallback: catches orders placed while socket is down
            var lastSeenOrderId = 0;
            function pollLatestOrders() {
                if (typeof orderReadRoute === 'undefined') return;
                $.ajax({
                    url: orderReadRoute,
                    type: 'GET',
                    success: function (response) {
                        var rows = (response && response.data) || [];
                        if (!rows.length) return;
                        var maxId = Math.max.apply(null, rows.map(function (r) { return r.id; }));
                        if (lastSeenOrderId && maxId > lastSeenOrderId) {
                            rows.filter(function (r) { return r.id > lastSeenOrderId; }).forEach(handleNewOrder);
                            $(document).trigger('orderUpdated');
                        }
                        lastSeenOrderId = Math.max(lastSeenOrderId, maxId);
                    }
                });
            }

            // ---- Notification bell (badge + dropdown, all admin pages) ----
            var notifBaseline = parseInt(localStorage.getItem('notifBaseline') || '0', 10);

            function paintBell(pendingRows) {
                var badge = document.getElementById('notifTicketBadge');
                var list = document.getElementById('notifTicketList');
                if (!badge || !list) return;

                var fresh = pendingRows.filter(function (r) { return r.id > notifBaseline; });

                if (fresh.length) {
                    badge.textContent = fresh.length > 9 ? '9+' : fresh.length;
                    badge.style.display = '';
                } else {
                    badge.style.display = 'none';
                }

                if (!pendingRows.length) {
                    list.innerHTML = '<li class="px-3 py-3 text-center text-muted small">You\'re all caught up — no pending orders.</li>';
                    return;
                }

                list.innerHTML = pendingRows.slice(0, 6).map(function (r) {
                    var isNew = r.id > notifBaseline;
                    return '<li>' +
                        '<a href="' + adminOrderReadRoute + '" class="d-flex gap-2 px-3 py-2 text-decoration-none text-dark border-bottom ' + (isNew ? 'bg-light' : '') + '">' +
                            '<span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-black text-white flex-shrink-0" style="width: 32px; height: 32px; font-size: 13px;"><i class="ti ti-shopping-bag"></i></span>' +
                            '<span class="min-w-0">' +
                                '<span class="d-block small fw-bold text-truncate">' + r.order_number + ' · ' + (r.customer_name || 'Customer') + '</span>' +
                                '<span class="d-block text-muted" style="font-size: 12px;">₱' + Number(r.total || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }) + ' · ' + (r.created_at || '') + '</span>' +
                            '</span>' +
                        '</a>' +
                    '</li>';
                }).join('');
            }

            function refreshBell() {
                $.ajax({
                    url: adminOrderApiRoute,
                    type: 'GET',
                    success: function (response) {
                        var rows = ((response && response.data) || []).filter(function (r) { return r.status === 'pending'; });
                        paintBell(rows);
                    },
                    error: function () {
                        var list = document.getElementById('notifTicketList');
                        if (list && !list.dataset.loaded) {
                            list.innerHTML = '<li class="px-3 py-3 text-center text-muted small">Could not load notifications.</li>';
                        }
                    }
                });
            }

            // New live order also lands in the bell instantly
            var bellAwareHandler = handleNewOrder;
            handleNewOrder = function (order) {
                bellAwareHandler(order);
                refreshBell();
            };

            $(document).on('click', '.notif-mark-all', function () {
                $.ajax({
                    url: adminOrderApiRoute,
                    type: 'GET',
                    success: function (response) {
                        var rows = (response && response.data) || [];
                        if (rows.length) {
                            notifBaseline = Math.max.apply(null, rows.map(function (r) { return r.id; }));
                            localStorage.setItem('notifBaseline', notifBaseline);
                        }
                        refreshBell();
                    }
                });
            });

            $(document).ready(function () {
                // Prime the polling baseline silently (no toast for existing orders)
                pollLatestOrders();
                setInterval(pollLatestOrders, 15000);

                // Notification bell on every admin page
                refreshBell();
                setInterval(refreshBell, 30000);

                if (!window.Pusher || !window.Echo) return;
                try {
                    var EchoCtor = window.Echo.default || window.Echo;
                    if (typeof EchoCtor !== 'function') throw new Error('Echo constructor not found');
                    var echo = new EchoCtor({
                        broadcaster: 'reverb',
                        key: window.ReverbConfig.key,
                        wsHost: window.ReverbConfig.host,
                        wsPort: window.ReverbConfig.port,
                        wssPort: window.ReverbConfig.port,
                        forceTLS: window.ReverbConfig.scheme === 'https',
                        enabledTransports: ['ws', 'wss'],
                        auth: { headers: { 'X-CSRF-TOKEN': window.ReverbConfig.csrf } },
                    });
                    echo.private('admin.orders').listen('.order.placed', handleNewOrder);
                    window.adminEcho = echo;
                } catch (e) {
                    console.error('Reverb connection failed, using AJAX polling:', e);
                }
            });
        })();
    </script>

</body>

</html>