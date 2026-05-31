@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade">    
        <!-- Hero Banner - UP TO 25% OFF -->
        <div class="hero-banner rounded-2xl overflow-hidden mb-8 relative card-item">
            <div class="px-6 py-12 md:py-16 text-center">
                <p class="text-white/80 text-sm mb-2">LIMITED TIME OFFER</p>
                <h2 class="text-4xl md:text-6xl font-bold text-white mb-3">UP TO <span class="text-yellow-400">25% OFF</span></h2>
                <p class="text-white/70 text-sm mb-6">ENDS SOON</p>
                <a href="shop.html" class="inline-block bg-white text-black px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition btn-press">Shop Now →</a>
            </div>
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
        </div>

        <!-- Recommended Styles Section -->
        <div class="mb-10">
            <div class="flex justify-between items-center mb-5 animate-slide-right">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">Recommended Styles</h2>
                <a href="{{ route('dashboard.items') }}" class="text-gray-500 text-sm hover:text-black">See All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"> --}}
                @forelse ($products->take(8) as $item)
                    <a href="{{ route('itemdetails.index', $item->id) }}">
                        <div class="product-card group card-item h-100">
                            <div class="relative">
                                @if ($item->prdcttag === 'Popular')
                                    <div class="discount-badge popular rounded-pill text-xs spantag">
                                        {{ $item->prdcttag }} - {{ $item->prdctpercentageoff }}% Off
                                    </div>
                                @elseif ($item->prdcttag === 'New Arrival')
                                    <div class="discount-badge new-arrival rounded-pill text-xs">
                                        {{ $item->prdcttag }} - {{ $item->prdctpercentageoff }}% Off
                                    </div>
                                @elseif ($item->prdcttag === 'Sale')
                                    <div class="discount-badge sale rounded-pill text-xs">
                                        {{ $item->prdcttag }} - {{ $item->prdctpercentageoff }}% Off
                                    </div>
                                @endif

                                @php
                                    $images = json_decode($item->prdctimage, true);
                                    $firstImage = $images[0] ?? null;
                                @endphp
                                <div class="image-container">
                                    <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}" class="product-image" alt="{{ $item->prdctname }}">
                                </div>
                            </div>
                            <div class="p-4 pt-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="font-semibold text-gray-900 text-base" onclick="window.location.href='product.html?id=${product.id}'">
                                        {{ strlen($item->prdctname) > 25 
                                                ? substr($item->prdctname, 0, 25) . '...' 
                                                : $item->prdctname }}
                                    </h3>
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-star rating-star"></i>
                                        <span class="text-xs font-medium text-gray-700">4.5</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xl font-bold text-gray-900">₱{{ number_format($item->prdctprice, 2) }}</span>
                                    <span class="text-sm text-gray-400 line-through hidden sm:inline">₱200.00</span>
                                </div>
                                
                                <p class="text-xs text-gray-500 mb-2 hidden sm:inline">Colors</p>
                                
                                <div class="flex items-center justify-between mb-3 hidden sm:inline">
                                    <div class="flex items-center gap-2">
                                        <i class="far fa-clock text-xs text-gray-400"></i>
                                        <span class="text-xs text-gray-500">
                                            {{ $item->created_at->diffForHumans(null, true) === '0 seconds'
                                                        ? 'Now'
                                                        : $item->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-eye text-xs text-gray-400"></i>
                                        <span class="text-xs text-gray-500">reviews</span>
                                    </div>
                                </div>
                                
                                <div class="fixed bottom-0 left-0 right-0 flex gap-2 p-4 bg-white z-40">
                                    <button class="add-cart-btn flex-1">
                                        <i class="fas fa-shopping-cart text-sm"></i>
                                        <span class="hidden sm:inline">Add Cart</span>
                                    </button>
                                    <button class="buy-now-btn flex-1">
                                        <i class="fas fa-shopping-bag text-sm"></i>
                                        <span class="hidden sm:inline">Buy Now</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-16"><i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i><p class="text-gray-500">No products found</p></div>
                @endforelse
            </div>
        </div>
    </main>
@endsection