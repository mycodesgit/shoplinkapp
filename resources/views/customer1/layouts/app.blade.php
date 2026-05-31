<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Shoplink</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/images/apple-touch-icon.png') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css') }}">

    <style>
        .topmenubuttons {
            font-weight: 700 !important;
            font-family: 'Poppins', sans-serif !important;
            color: black;
        }
    </style>
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-inline-flex gap-2 align-items-center lh-1" href="index.html">
                <i class="ti ti-shopping-cart"></i>
                <span class="fw-bold">ShopLink</span>
            </a>
            {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> --}}
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="#hero" class="nav-link topmenubuttons">HOME</a></li>
                    <li class="nav-item"><a href="#shop" class="nav-link topmenubuttons">SHOP</a></li>
                    <li class="nav-item"><a href="#mentor" class="nav-link topmenubuttons">ORDERS</a></li>
                </ul>
                <div class="d-flex gap-3 align-items-center">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="ti ti-login"></i> Sign In</a>
                </div>
            </div>
        </div>
    </nav>

    @yield('body')

    <footer class="pt-lg-13 bg-light py-8">
        <div class="container">
            <div class="row gy-8">
                <div class="col-md-4">
                    <div class="">
                        <a class="d-inline-flex gap-2 align-items-center lh-1" href="index.html">
                            <span class="text-primary"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                    <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                    <path d="M3 6l0 13" />
                                    <path d="M12 6l0 13" />
                                    <path d="M21 6l0 13" />
                                </svg></span>
                            <span class="fw-bold">LearnHub</span>
                        </a>
                        <p class="mt-4 mb-6">Empowering millions of learners worldwide with industry-leading courses and
                            expert
                            mentors.</p>
                        <div class="d-flex flex-column gap-2">
                            <span class="d-flex align-items-center gap-2">
                                <span class="text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                        <path d="M3 7l9 6l9 -6" />
                                    </svg>

                                </span>
                                <span>hello@learnhub.com</span>

                            </span>
                            <span class="d-flex align-items-center gap-2">
                                <span class="text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                    </svg>

                                </span>
                                <span>+1 (234) 567-890</span>
                            </span>
                            <span class="d-flex align-items-center gap-2">
                                <span class="text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        <path
                                            d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                    </svg>

                                </span>
                                <span>San Francisco, CA</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-top mt-8 pt-6 row small">
                <div class="col-12 d-flex flex-column flex-md-row justify-content-between">
                    <div>
                        <p>© 2026. All rights reserved. Develop by <a href="https://codescandy.com/" class="link-primary" target="_blank">Shoplink</a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script type="text/javascript" src="{{ asset('frontend/js/index.js') }}"></script>
    <!-- jQuery -->
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}"></script>
    
</body>

</html>