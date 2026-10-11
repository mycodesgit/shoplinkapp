@extends('customer.layouts.app')

@section('title', 'Shoplink | Home')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade">    
        <!-- Hero Banner - UP TO 25% OFF -->
        <div class="hero-banner rounded-2xl overflow-hidden mb-8 relative card-item">
            <div class="px-6 py-12 md:py-16 text-center">
                <p class="text-white/80 text-sm mb-2">LIMITED TIME OFFER</p>
                <h2 class="text-4xl md:text-6xl font-bold text-white mb-3">UP TO <span class="text-white underline decoration-white/60 decoration-4 underline-offset-8">25% OFF</span></h2>
                <p class="text-white/70 text-sm mb-6">ENDS SOON</p>
                <a href="{{ Auth::guard('customer')->check() ? route('dashboard.auth.items') : route('dashboard.items') }}" class="inline-block bg-white text-black px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition btn-press">Shop Now →</a>
            </div>
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
        </div>

        <!-- Moving Marquee Category Tabs -->
        <div class="category-marquee-section mb-10 overflow-hidden relative">
            <div class="relative">
                <!-- Gradient fade edges for smooth effect -->
                <div class="absolute left-0 top-0 bottom-0 w-8 md:w-16 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
                <div class="absolute right-0 top-0 bottom-0 w-8 md:w-16 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
                
                <!-- Infinite Marquee Container -->
                <div class="overflow-hidden py-3">
                    <div class="marquee-track flex gap-3 md:gap-4 animate-marquee">
                        @foreach($categories as $index => $category)
                            <button class="category-chip flex-shrink-0 px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm md:text-base font-medium {{ $index === 0 ? 'bg-gray-100 text-gray-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }} transition-all duration-200 hover:scale-105" data-category="{{ $category->catname }}">
                                <i class="{{ $category->caticon }} mr-1.5 text-xs"></i> {{ $category->subcategory }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Styles Section -->
        <div class="mb-10">
            <div class="flex justify-between items-center mb-5 animate-slide-right">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">Recommended Styles</h2>
                <a href="{{ Auth::guard('customer')->check() ? route('dashboard.auth.items') : route('dashboard.items') }}" class="text-gray-500 text-sm hover:text-black">See All →</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
                @forelse ($products->take(8) as $item)
                    <div class="product-card group card-item flex h-full flex-col">
                        @php
                            $images = json_decode($item->prdctimage, true);
                            $firstImage = $images[0] ?? null;
                            $minPrice = $item->variations->min('variant_price');
                        @endphp
                        @if(Auth::guard('customer')->check())
                            <a href="{{ route('itemdetails.auth.index', $item->encrypted_id) }}" class="block" tabindex="-1" aria-hidden="true">
                        @else
                            <a href="{{ route('itemdetails.index', $item->encrypted_id) }}" class="block" tabindex="-1" aria-hidden="true">
                        @endif
                            <div class="relative">
                                @if ($item->prdcttag === 'Popular')
                                    <div class="discount-badge popular text-xs">
                                        {{ $item->prdcttag }}
                                    </div>
                                @elseif ($item->prdcttag === 'New Arrival')
                                    <div class="discount-badge new-arrival text-xs">
                                        {{ $item->prdcttag }}
                                    </div>
                                @elseif ($item->prdcttag === 'Sale')
                                    <div class="discount-badge sale text-xs">
                                        {{ $item->prdcttag }}
                                    </div>
                                @endif

                                <div class="image-container aspect-[4/5]">
                                    <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}" class="product-image" alt="{{ $item->prdctname }}" loading="lazy">
                                </div>
                            </div>
                        </a>
                        <div class="flex flex-1 flex-col p-3 sm:p-4">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="line-clamp-2 min-h-10 text-sm font-semibold text-gray-900 sm:min-h-12 sm:text-base">
                                    {{ $item->prdctname }}
                                </h3>
                                <div class="flex shrink-0 items-center gap-1 pt-0.5">
                                    <i class="fas fa-star rating-star"></i>
                                    <span class="text-xs font-medium text-gray-700">4.5</span>
                                </div>
                            </div>

                            <div class="mt-1 flex min-h-6 flex-wrap items-baseline gap-2 sm:min-h-7">
                                <span class="text-base font-bold text-gray-900 sm:text-xl">₱{{ number_format($minPrice, 2) }}</span>
                                <span class="hidden text-xs text-gray-400 line-through sm:inline">₱200.00</span>
                            </div>

                            <p class="mt-1 min-h-5 truncate text-xs text-gray-500">{{ $item->variations->count() }} variation(s) available</p>

                            <div class="mt-1 flex min-h-5 items-center justify-between gap-2 text-xs text-gray-500">
                                <div class="hidden min-w-0 items-center gap-1.5 sm:flex">
                                    <i class="far fa-clock shrink-0 text-[11px] text-gray-400"></i>
                                    <span class="truncate">
                                        {{ $item->created_at->diffForHumans(null, true) === '0 seconds'
                                                    ? 'Now'
                                                    : $item->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="flex shrink-0 items-center gap-1">
                                    <i class="fas fa-eye text-[11px] text-gray-400"></i>
                                    <span>reviews</span>
                                </div>
                            </div>

                            <div class="mt-auto flex flex-col gap-2 pt-3 sm:flex-row">
                                @if(Auth::guard('customer')->check())
                                    <button class="add-cart-btn min-h-11 flex-1" onclick="window.location.href='{{ route('itemdetails.auth.index', $item->encrypted_id) }}'">
                                        <i class="fas fa-shopping-cart text-sm"></i>
                                        <span>Add Cart</span>
                                    </button>
                                    <button class="buy-now-btn min-h-11 flex-1" onclick="window.location.href='{{ route('itemdetails.auth.index', $item->encrypted_id) }}'">
                                        <i class="fas fa-shopping-bag text-sm"></i>
                                        <span>Buy Now</span>
                                    </button>
                                @else
                                    <button class="add-cart-btn min-h-11 flex-1" onclick="window.ShopLink.redirectToLogin()">
                                        <i class="fas fa-shopping-cart text-sm"></i>
                                        <span>Add Cart</span>
                                    </button>
                                    <button class="buy-now-btn min-h-11 flex-1" onclick="window.ShopLink.redirectToLogin()">
                                        <i class="fas fa-shopping-bag text-sm"></i>
                                        <span>Buy Now</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16"><i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i><p class="text-gray-500">No products found</p></div>
                @endforelse
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Category chip active state (delegated; works with marquee duplicates)
        document.addEventListener('click', function (e) {
            var chip = e.target.closest('.category-chip');
            if (!chip) return;
            document.querySelectorAll('.category-chip').forEach(function (c) {
                c.classList.remove('active', 'bg-black', 'text-white');
                c.classList.add('bg-gray-100', 'text-gray-800');
            });
            chip.classList.add('active', 'bg-black', 'text-white');
            chip.classList.remove('bg-gray-100', 'text-gray-800');
        });
    </script>
@endpush