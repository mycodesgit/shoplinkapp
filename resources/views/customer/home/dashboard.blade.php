@extends('customer.layouts.app')

@section('body')
    <section class="py-lg-13 py-8 bg-white position-relative" id="hero">
        <div class="circle-bg d-none d-lg-block"></div>
        <div class="container">
            <div class="row align-items-center gy-8">
                <div class="col-lg-7">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-3 fw-normal border border-primary rounded-pill">
                        <span>
                            <i class="ti ti-trending-up"></i>
                        </span>
                        <span class="ms-1">Trending Now</span>
                    </span>
                    <h1 class="display-4 fw-bold  mt-4">{{ $banner->welcometext }}</h1>

                    <p class="my-6 lead fw-normal">
                        {{ $banner->welcomesubtext }}
                    </p>
                    <div class="d-flex flex-md-row flex-column justify-content-start   gap-3">
                        <a href="#!" class="btn btn-primary">
                            <span>Shop Now</span>
                            <span>
                                <i class="ti ti-arrow-right"></i>
                            </span>
                        </a>
                        <a href="#" class="btn btn-light">
                            <i class="ti ti-user-plus"></i>
                            <span class="ms-1">Create an Account</span>
                        </a>
                    </div>
                    <div class="d-flex gap-6 mt-8">
                        <div class="d-flex align-items-center gap-2">
                            <span>
                                <i class="ti ti-users text-primary"></i>
                            </span>
                            <small class="mb-0"><span class="fw-bold">100+</span> Users</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span>
                                <i class="ti ti-brand-producthunt text-primary"></i>
                            </span>
                            <small class="mb-0"><span class="fw-bold">200+ </span> Products</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span>
                                <i class="ti ti-star text-primary"></i>
                            </span>
                            <small class="mb-0"><span class="fw-bold">4.9</span> Rating</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="p-3 rounded-5 shadow-sm">
                        <div class="stack-gallery position-relative">

                            @foreach ($products->take(3) as $index => $item)
                                <img src="{{ asset('storage/' . $item->prdctimage) }}" 
                                    class="stack-img stack-{{ $index }}" 
                                    alt="{{ $item->prdctname }}">
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-lg-13 py-8 bg-light" id="shop">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Shop by Category</h5>
                <a href="#" class="text-success">View All <i class="bi bi-arrow-right"></i></a>
            </div>

            <!-- Mobile Carousel -->
            <div id="categoryCarousel" class="carousel slide d-md-none" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="d-flex overflow-auto gap-3 py-2">
                        @foreach($categories as $category)
                            <div class="card text-center shadow-sm rounded-3 p-3 flex-shrink-0" style="width: 120px;">
                                <i class="ti {{ $category->caticon }} fs-1"></i> <!-- Replace with dynamic icon if you have -->
                                <p class="small mt-2 mb-0">{{ $category->catname }}</p>
                            </div>
                        @endforeach
                    </div>
                    {{-- <div class="carousel-item active">
                        <div class="row justify-content-center g-2">
                            <div class="col-4">
                                <div class="card text-center shadow-sm rounded-3 p-3">
                                    <i class="ti ti-cup fs-1"></i>
                                    <p class="small mt-2 mb-0">Bamboo</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card text-center shadow-sm rounded-3 p-3">
                                    <i class="ti ti-briefcase fs-1"></i>
                                    <p class="small mt-2 mb-0">Leather</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card text-center shadow-sm rounded-3 p-3">
                                    <i class="ti ti-diamond fs-1"></i>
                                    <p class="small mt-2 mb-0">Jewelry</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row justify-content-center g-2">
                            <div class="col-4">
                                <div class="card text-center shadow-sm rounded-3 p-3">
                                    <i class="ti ti-cup fs-1"></i>
                                    <p class="small mt-2 mb-0">Bamboo</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card text-center shadow-sm rounded-3 p-3">
                                    <i class="ti ti-briefcase fs-1"></i>
                                    <p class="small mt-2 mb-0">Leather</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card text-center shadow-sm rounded-3 p-3">
                                    <i class="ti ti-diamond fs-1"></i>
                                    <p class="small mt-2 mb-0">Jewelry</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

            <!-- Desktop Cards -->
            <div class="row d-none d-md-flex text-center g-3">
                <div class="col-md-2">
                    <div class="card shadow-sm rounded-3 p-3">
                        <i class="ti ti-cup fs-1"></i>
                        <p class="small mt-2 mb-0">Bamboo</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card shadow-sm rounded-3 p-3">
                        <i class="ti ti-briefcase fs-1"></i>
                        <p class="small mt-2 mb-0">Leather</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card shadow-sm rounded-3 p-3">
                        <i class="ti ti-diamond fs-1"></i>
                        <p class="small mt-2 mb-0">Jewelry</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-lg-5 py-8" id="shop">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-6 mx-auto">
                    <div class="mb-10">
                        <h2 class="fw-bold mt-4 mb-4">Explore and Shop Our Popular <span class="text-primary">Products</span></h2>
                        <p class="mb-0">
                            Browse our wide range of popular products carefully selected to meet your needs and enhance your lifestyle.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row g-3 g-md-4">
                @foreach ($products->take(8) as $item)
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="card p-2 p-md-3 shadow-sm h-100 rounded-4">
                            <a href="{{ route('item-info', $item->id) }}">
                                <div class="product-img-wrapper position-relative overflow-hidden rounded-4" style="border: 1px solid #e5e5e5">
                                    @php
                                        $images = json_decode($item->prdctimage, true);
                                        $firstImage = $images[0] ?? null;
                                    @endphp

                                    <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}"
                                        class="rounded-top-4 card-img-top"
                                        alt="{{ $item->prdctname }}"
                                        style="height: 220px; object-fit: cover;">

                                    <div class="position-absolute top-0 start-0 p-2 product-tag-label">
                                        @if ($item->prdcttag === 'Popular')
                                            <span class="badge bg-warning rounded-pill text-xs spantag">
                                                {{ $item->prdcttag }} - {{ $item->prdctpercentageoff }}% Off
                                            </span>
                                        @elseif ($item->prdcttag === 'New Arrival')
                                            <span class="badge bg-primary rounded-pill text-xs">
                                                {{ $item->prdcttag }} - {{ $item->prdctpercentageoff }}% Off
                                            </span>
                                        @elseif ($item->prdcttag === 'Sale')
                                            <span class="badge bg-danger rounded-pill text-xs">
                                                {{ $item->prdcttag }} - {{ $item->prdctpercentageoff }}% Off
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>

                            <div class="card-body p-2 p-md-3">
                                <h3 class="mb-1 fs-6 text-truncate">
                                    {{ strlen($item->prdctname) > 25 
                                        ? substr($item->prdctname, 0, 25) . '...' 
                                        : $item->prdctname }}
                                </h3>
                                <span class="fs-6 fw-bold d-block">
                                    ₱{{ number_format($item->prdctprice, 2) }}
                                </span>
                                <div class="text-muted small mb-2">
                                    {{ $item->prdctvariation }}
                                </div>
                                <div class="d-flex flex-wrap justify-content-between small text-muted border-bottom pb-2 mb-2">
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="ti ti-clock"></i>
                                        <span>
                                            {{ $item->created_at->diffForHumans(null, true) === '0 seconds'
                                                ? 'Now'
                                                : $item->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="ti ti-users"></i>
                                        <span>2.4K</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="ti ti-star"></i>
                                        <span>4.9</span>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-md-row gap-2 mt-2">
                                    <a href="#" class="btn btn-outline-success w-100">
                                        <i class="ti ti-shopping-cart-plus"></i>
                                        {{-- <span class="d-none d-md-inline"> Add Cart</span> --}}
                                        <span> Add Cart</span>
                                    </a>
                                    <a href="#" class="btn btn-secondary w-100">
                                        Buy Now
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12 text-center mt-10">
                    <a href="#" class="btn btn-outline-dark"> <span>
                            View All Products
                        </span>
                        <span>
                            <i class="ti ti-arrow-right"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('.stack-gallery').forEach(gallery => {
            let imgs = gallery.querySelectorAll('.stack-img');
            let index = 0;

            gallery.addEventListener('mouseenter', () => {
                gallery.interval = setInterval(() => {
                    imgs.forEach((img, i) => {
                        img.style.zIndex = i === index ? imgs.length : imgs.length - i - 1;
                    });
                    index = (index + 1) % imgs.length;
                }, 3000);
            });

            gallery.addEventListener('mouseleave', () => {
                clearInterval(gallery.interval);
                imgs.forEach((img, i) => img.style.zIndex = imgs.length - i);
            });
        });
    </script>

    {{-- <script>
        document.querySelectorAll('.stack-gallery').forEach(gallery => {
            const imgs = Array.from(gallery.querySelectorAll('.stack-img'));
            const total = imgs.length;
            let index = 0;

            setInterval(() => {
                imgs.forEach((img, i) => {
                    let z = (i === index) ? total : total - (i + 1);
                    img.style.zIndex = z;
                });

                index = (index + 1) % total;
            }, 5000);
        });
    </script> --}}
@endsection