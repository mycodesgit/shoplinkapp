@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade"> 
        <!-- Toast Notification -->
        <div id="toast" class="fixed bottom-20 md:bottom-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-2.5 rounded-full shadow-lg z-50 transition-all duration-300 opacity-0 pointer-events-none text-sm whitespace-nowrap">
            <i class="fas fa-check-circle text-green-400 mr-2"></i>
            <span id="toastMessage">Added to cart</span>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Search products..." class="w-full pl-12 pr-4 py-3 bg-white rounded-full border border-gray-200 focus:ring-2 focus:ring-black focus:border-transparent outline-none transition">
            </div>
        </div>

        <!-- Category Tabs (Horizontal Scroll on Mobile) -->
        <div class="overflow-x-auto mb-8 pb-2">
            <div class="flex gap-6 min-w-max border-b border-gray-200">
                <div class="category-tab active" data-category="all">Popular</div>
                <div class="category-tab" data-category="menswear">Menswear</div>
                <div class="category-tab" data-category="womenwear">Womenwear</div>
                <div class="category-tab" data-category="accessories">Accessories</div>
                <div class="category-tab" data-category="electronics">Electronics</div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5" id="productsGrid">

            @forelse ($products->take(8) as $item)
                <div class="product-card group card-item">
                    <a href="{{ route('itemdetails.index', $item->id) }}">
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
                    </a>
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
                            <span class="text-sm text-gray-400 line-through">₱200.00</span>
                        </div>
                        
                        <p class="text-xs text-gray-500 mb-2">Colors</p>
                        
                        <div class="flex items-center justify-between mb-3">
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
                        
                        <div class="flex gap-2">
                            <button class="add-cart-btn flex-1">
                                <i class="fas fa-shopping-cart text-sm"></i>
                                Add Cart
                            </button>
                            <button class="buy-now-btn flex-1">
                                Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16"><i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i><p class="text-gray-500">No products found</p></div>
            @endforelse
        </div>
    </main>
@endsection