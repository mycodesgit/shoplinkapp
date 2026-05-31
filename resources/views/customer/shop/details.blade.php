@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10"> 
        <!-- Toast Notification -->
        <div id="toast" class="fixed bottom-20 md:bottom-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-2.5 rounded-full shadow-lg z-50 transition-all duration-300 opacity-0 pointer-events-none text-sm whitespace-nowrap">
            <i class="fas fa-check-circle text-green-400 mr-2"></i>
            <span id="toastMessage">Added to cart</span>
        </div>

        <!-- Navigation Row: Back Arrow + Breadcrumb -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-3 border-b border-gray-100">
            <!-- Left: Back Arrow Button -->
            <div class="flex-shrink-0">
                <button 
                    onclick="window.history.back()" 
                    class="group flex items-center justify-center w-10 h-10 md:w-11 md:h-11 rounded-full bg-white shadow-sm border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900 transition-all duration-200 hover:-translate-x-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    aria-label="Go back">
                    <i class="fas fa-arrow-left text-base md:text-lg"></i>
                </button>
            </div>

            <!-- Right: Aesthetic Breadcrumb (Responsive) -->
            <div class="flex-1 min-w-0 flex justify-end">
                <nav class="flex items-center" aria-label="Breadcrumb">
                    <ol class="flex items-center flex-wrap justify-end gap-1 text-sm md:text-base">
                        <!-- Home -->
                        <li class="inline-flex items-center">
                            <a href="{{ url('/') }}" class="text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                <i class="fas fa-home text-xs md:text-sm"></i>
                                <span class="sr-only">Home</span>
                            </a>
                            <i class="fas fa-chevron-right text-gray-300 text-xs mx-1 md:mx-1.5"></i>
                        </li>
                        <!-- Shop / Products -->
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard.items') }}" class="text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                <span class="sm:hidden">Shop</span>
                            </a>
                            <i class="fas fa-chevron-right text-gray-300 text-xs mx-1 md:mx-1.5"></i>
                        </li>
                        <!-- Category (optional - if you have category relationship) -->
                        @if($product->category)
                        <li class="inline-flex items-center">
                            <a href="{{ route('dasboard.items', $product->category->slug ?? '#') }}" class="text-gray-500 hover:text-indigo-600 transition-colors duration-200 hidden sm:inline-block">
                                {{ strlen($product->category->name ?? 'Uncategorized') > 15 ? substr($product->category->name, 0, 12) . '...' : ($product->category->name ?? 'Uncategorized') }}
                            </a>
                            <a href="#" class="text-gray-500 hover:text-indigo-600 transition-colors duration-200 sm:hidden">
                                Cat
                            </a>
                            <i class="fas fa-chevron-right text-gray-300 text-xs mx-1 md:mx-1.5"></i>
                        </li>
                        @endif
                        <!-- Current Product (truncated on mobile) -->
                        <li class="inline-flex items-center">
                            <span class="text-gray-800 font-medium" aria-current="page">
                                <span class="hidden sm:inline">{{ $product->prdctname }}</span>
                                <span class="sm:hidden">{{ strlen($product->prdctname) > 20 ? substr($product->prdctname, 0, 18) . '...' : $product->prdctname }}</span>
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Product Detail Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8" id="productDetail">
            <div>
                @php
                    $images = json_decode($product->prdctimage, true);
                    $firstImage = is_array($images) ? ($images[0] ?? null) : $product->prdctimage;
                @endphp
                <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}" alt="{{ $product->name }}" class="main-image w-full rounded-2xl shadow-md">
                <div class="flex gap-3 mt-4">
                    @if(is_array(json_decode($product->prdctimage, true)))
                        @foreach(json_decode($product->prdctimage, true) as $img)
                            <img src="{{ asset('storage/' . $img) }}" class="thumbnail w-20 h-20 rounded-lg thumb" width="70">
                        @endforeach
                    @else
                        <img src="{{ asset('storage/' . $product->prdctimage) }}" class="thumbnail w-20 h-20 rounded-lg thumb" width="70">
                    @endif
                </div>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="font-semibold">4.3</span>
                    <span class="text-gray-400">reviews</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    {{ strlen($product->prdctname) > 25 
                                            ? substr($product->prdctname, 0, 25) . '...' 
                                            : $product->prdctname }}
                </h1>
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-3xl font-bold text-gray-900">₱{{ number_format($product->prdctprice, 2) }}</span>
                    <span class="text-lg text-gray-400 line-through">₱{{ number_format($product->originalPrice, 2) }}</span>
                    <span class="bg-red-100 text-red-600 text-sm px-2 py-1 rounded-full">Save ₱{{ number_format($product->originalPrice - $product->prdctprice, 2) }}</span>
                </div>
                <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->description }}</p>
                
                <div class="mb-5">
                    <p class="font-semibold mb-2">Select Color</p>
                    <div class="flex gap-3" id="colorContainer">Color</div>
                </div>
                
                <div class="mb-5">
                    <p class="font-semibold mb-2">Select Size</p>
                    <div class="flex gap-3" id="sizeContainer">Size</div>
                </div>
                
                <div class="mb-6">
                    <p class="font-semibold mb-2">Quantity</p>
                    <div class="flex items-center gap-4">
                        <button class="w-10 h-10 rounded-full border border-gray-300 hover:bg-gray-100 transition" id="decBtn">−</button>
                        <span id="quantity" class="text-lg font-semibold w-8 text-center">1</span>
                        <button class="w-10 h-10 rounded-full border border-gray-300 hover:bg-gray-100 transition" id="incBtn">+</button>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button class="add-cart-btn flex-1 py-3 rounded-full font-semibold flex items-center justify-center gap-2 btn-press" id="addToCartBtn">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <button class="buy-now-btn flex-1 py-3 rounded-full font-semibold text-white flex items-center justify-center gap-2 btn-press" id="buyNowBtn">
                        <i class="fas fa-bolt"></i> Buy Now
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('.thumb').forEach(img => {
            img.addEventListener('click', function() {
                document.getElementById('mainImage').src = this.src;
            });
        });
    </script>
@endsection