@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10"> 
        <!-- Toast Notification -->
        <div id="toast" class="fixed top-20 md:top-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-2.5 rounded-full shadow-lg z-50 transition-all duration-300 opacity-0 pointer-events-none text-sm whitespace-nowrap">
            <i class="fas fa-check-circle text-green-400 mr-2"></i>
            <span id="toastMessage">Added to cart</span>
        </div>

        <!-- Navigation Row -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-3 border-b border-gray-100">
            <div class="flex-shrink-0">
                <button onclick="window.history.back()" class="group flex items-center justify-center w-10 h-10 md:w-11 md:h-11 rounded-full bg-white shadow-sm border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900 transition-all duration-200 hover:-translate-x-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-300" aria-label="Go back">
                    <i class="fas fa-arrow-left text-base md:text-lg"></i>
                </button>
            </div>
            <div class="flex-1 min-w-0 flex justify-end">
                <nav class="flex items-center" aria-label="Breadcrumb">
                    <ol class="flex items-center flex-wrap justify-end gap-1 text-sm md:text-base">
                        <li class="inline-flex items-center">
                            <a href="{{ url('/') }}" class="text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                <i class="fas fa-home text-xs md:text-sm"></i>
                                <span class="sr-only">Home</span>
                            </a>
                            <i class="fas fa-chevron-right text-gray-300 text-xs mx-1 md:mx-1.5"></i>
                        </li>
                        <li class="inline-flex items-center sm:inline-block">
                            <a href="{{ route('dashboard.items') }}" class="text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                <span>Shop</span>
                            </a>
                            <i class="fas fa-chevron-right text-gray-300 text-xs mx-1 md:mx-1.5"></i>
                        </li>
                        @if($product->category)
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard.items', $product->category->slug ?? '#') }}" class="text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                {{ strlen($product->category->catname ?? 'Uncategorized') > 10 ? substr($product->category->catname, 0, 10) . '...' : ($product->category->catname ?? 'Uncategorized') }}
                            </a>
                            <i class="fas fa-chevron-right text-gray-300 text-xs mx-1 md:mx-1.5"></i>
                        </li>
                        @endif
                        <li class="inline-flex items-center">
                            <span class="text-gray-800 font-medium" aria-current="page">
                                <span class="hidden sm:inline">{{ $product->prdctname }}</span>
                                <span class="sm:hidden">{{ strlen($product->prdctname) > 10 ? substr($product->prdctname, 0, 10) . '...' : $product->prdctname }}</span>
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Product Detail Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8" id="productDetail">
            <!-- Product Images Section -->
            <div>
                @php
                    $images = json_decode($product->prdctimage, true);
                    $firstImage = is_array($images) ? ($images[0] ?? null) : $product->prdctimage;
                    
                    // Collect ALL variation images from ALL variations
                    $allVariationImages = [];
                    foreach($product->variations as $variation) {
                        if ($variation->variant_image) {
                            // Parse variant_image (could be JSON or string)
                            $varImages = [];
                            if (is_string($variation->variant_image)) {
                                if (str_starts_with($variation->variant_image, '[')) {
                                    $varImages = json_decode($variation->variant_image, true);
                                } else {
                                    $varImages = [$variation->variant_image];
                                }
                            } elseif (is_array($variation->variant_image)) {
                                $varImages = $variation->variant_image;
                            }
                            
                            // Add to collection with variation info
                            foreach($varImages as $img) {
                                if (!empty($img)) {
                                    $allVariationImages[] = [
                                        'image' => $img,
                                        'variation_name' => $variation->variation_value,
                                        'variation_type' => $variation->variation_name,
                                        'price' => $variation->variant_price,
                                        'stock' => $variation->variant_stock,
                                        'variation_id' => $variation->id
                                    ];
                                }
                            }
                        }
                    }
                @endphp
                
                <img id="mainImage" src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}" alt="{{ $product->prdctname }}" class="main-image w-full rounded-2xl shadow-md">
                
                <!-- Thumbnails Container - Shows ALL variation images -->
                <div class="mt-6 ml-3">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Product Variations ({{ count($allVariationImages) }} images)</p>
                    <div id="thumbnailContainer" class="flex gap-4 overflow-x-auto pb-2">
                        <!-- All variation thumbnails will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Product Details Section -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="font-semibold">4.3</span>
                    <span class="text-gray-400">(128 reviews)</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    {{ $product->prdctname }}
                </h1>
                
                <div class="flex items-center gap-3 mb-4" id="priceDisplay">
                    <span class="text-3xl font-bold text-gray-900" id="currentPrice">₱{{ number_format($product->variations->min('variant_price') ?? $product->prdctprice, 2) }}</span>
                    @if($product->originalPrice ?? false)
                        <span class="text-lg text-gray-400 line-through">₱{{ number_format($product->originalPrice, 2) }}</span>
                        <span class="bg-red-100 text-red-600 text-sm px-2 py-1 rounded-full">Save ₱{{ number_format($product->originalPrice - ($product->variations->min('variant_price') ?? $product->prdctprice), 2) }}</span>
                    @endif
                </div>
                
                <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->prdctdesc }}</p>
                
                <!-- Color Selection -->
                @php
                    $colors = $product->variations->where('variation_name', 'Color')->unique('variation_value');
                    $sizes = $product->variations->where('variation_name', 'Size')->unique('variation_value');
                @endphp
                
                @if($colors->count() > 0)
                <div class="mb-5">
                    <p class="font-semibold mb-2">Select Color</p>
                    <div class="flex gap-3 flex-wrap" id="colorContainer">
                        @foreach($colors as $color)
                            <button 
                                class="color-option px-4 py-2 rounded-full border-2 transition-all {{ $loop->first ? 'border-indigo-600 bg-indigo-50' : 'border-gray-300 hover:border-indigo-300' }}"
                                data-color="{{ $color->variation_value }}"
                                data-price="{{ $color->variant_price }}"
                                data-stock="{{ $color->variant_stock }}"
                                data-variation-id="{{ $color->id }}">
                                {{ $color->variation_value }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Size Selection -->
                @if($sizes->count() > 0)
                <div class="mb-5">
                    <p class="font-semibold mb-2">Select Size</p>
                    <div class="flex gap-3 flex-wrap" id="sizeContainer">
                        @foreach($sizes as $size)
                            <button 
                                class="size-option w-12 h-12 rounded-full border-2 transition-all {{ $loop->first ? 'border-indigo-600 bg-indigo-50' : 'border-gray-300 hover:border-indigo-300' }}"
                                data-size="{{ $size->variation_value }}"
                                data-price="{{ $size->variant_price }}"
                                data-stock="{{ $size->variant_stock }}"
                                data-variation-id="{{ $size->id }}">
                                {{ $size->variation_value }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Stock Status -->
                <div class="mb-4">
                    <p class="text-sm" id="stockStatus">
                        @php
                            $totalStock = $product->variations->sum('variant_stock');
                        @endphp
                        @if($totalStock > 0)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> In Stock ({{ $totalStock }} available)</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times-circle"></i> Out of Stock</span>
                        @endif
                    </p>
                </div>
                
                <!-- Selected Variation Info -->
                <div class="mb-4 text-sm text-gray-600" id="selectedVariationInfo">
                    <i class="fas fa-info-circle"></i> Click on any thumbnail to see variation details
                </div>
                
                <!-- Quantity Selector -->
                <div class="mb-6">
                    <p class="font-semibold mb-2">Quantity</p>
                    <div class="flex items-center gap-4">
                        <button class="w-10 h-10 rounded-full border border-gray-300 hover:bg-gray-100 transition" id="decBtn">−</button>
                        <span id="quantity" class="text-lg font-semibold w-8 text-center">1</span>
                        <button class="w-10 h-10 rounded-full border border-gray-300 hover:bg-gray-100 transition" id="incBtn">+</button>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-4">
                    @if(Auth::guard('customer')->check())
                        <button class="add-cart-btn flex-1 py-3 rounded-full font-semibold flex items-center justify-center gap-2 btn-press bg-indigo-600 text-white hover:bg-indigo-700 transition" id="addToCartBtn">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="buy-now-btn flex-1 py-3 rounded-full font-semibold text-white flex items-center justify-center gap-2 btn-press bg-green-600 hover:bg-green-700 transition" id="buyNowBtn">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    @else
                        <button onclick="redirectToLogin()" class="add-cart-btn flex-1 py-3 rounded-full font-semibold flex items-center justify-center gap-2 btn-press bg-indigo-600 text-white hover:bg-indigo-700 transition">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button onclick="redirectToLogin()" class="buy-now-btn flex-1 py-3 rounded-full font-semibold text-white flex items-center justify-center gap-2 btn-press bg-green-600 hover:bg-green-700 transition">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script>
        // All variation images data from backend
        const allVariationImages = @json($allVariationImages);
        const defaultImages = @json(array_map(function($img) {
            return asset('storage/' . $img);
        }, is_array($images) ? $images : ($images ? [$images] : [])));
        
        // All variations for price/stock lookup
        const allVariations = @json($product->variations);
        
        // Current state
        let currentQuantity = 1;
        let currentVariation = null;
        let currentImageData = null;

        function redirectToLogin() {
            const currentUrl = window.location.href;
            window.location.href = "{{ route('shop.login') }}?redirect=" + encodeURIComponent(currentUrl);
        }
        
        // Function to display ALL variation thumbnails
        function displayAllThumbnails() {
            const container = document.getElementById('thumbnailContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            if (allVariationImages.length === 0 && defaultImages.length === 0) {
                container.innerHTML = '<div class="text-gray-400 text-sm">No images available</div>';
                return;
            }
            
            // First, add all variation images
            allVariationImages.forEach((item, index) => {
                const thumb = document.createElement('div');
                thumb.className = 'relative flex-shrink-0 cursor-pointer group';
                
                const img = document.createElement('img');
                img.src = '{{ asset("storage/") }}/' + item.image;
                img.className = 'thumbnail w-20 h-20 rounded-lg object-cover border-2 transition-all group-hover:scale-105 group-hover:border-indigo-500';
                img.setAttribute('data-image', '{{ asset("storage/") }}/' + item.image);
                img.setAttribute('data-variation-id', item.variation_id);
                img.setAttribute('data-variation-name', item.variation_name || '');
                img.setAttribute('data-variation-type', item.variation_type || '');
                img.setAttribute('data-price', item.price || 0);
                img.setAttribute('data-stock', item.stock || 0);
                
                // Add variation label overlay
                if (item.variation_name) {
                    const label = document.createElement('div');
                    label.className = 'absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs text-center py-1 rounded-b-lg opacity-0 group-hover:opacity-100 transition-opacity';
                    label.innerText = item.variation_name;
                    thumb.appendChild(label);
                }
                
                thumb.appendChild(img);
                
                // Add click event to change main image and update variation details
                thumb.addEventListener('click', function() {
                    // Update main image
                    document.getElementById('mainImage').src = img.src;
                    
                    // Update active state
                    document.querySelectorAll('#thumbnailContainer .thumbnail').forEach(t => {
                        t.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-200');
                        t.classList.add('border-gray-200');
                    });
                    img.classList.remove('border-gray-200');
                    img.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-200');
                    
                    // Update variation details based on clicked thumbnail
                    const variationId = parseInt(img.dataset.variationId);
                    const variationName = img.dataset.variationName;
                    const price = parseFloat(img.dataset.price);
                    const stock = parseInt(img.dataset.stock);
                    
                    // Find the full variation data
                    currentVariation = allVariations.find(v => v.id === variationId);
                    currentImageData = {
                        variation_id: variationId,
                        variation_name: variationName,
                        price: price,
                        stock: stock,
                        image: img.dataset.image
                    };
                    
                    // Update price display
                    if (price > 0) {
                        document.getElementById('currentPrice').innerText = '₱' + price.toFixed(2);
                    }
                    
                    // Update stock status
                    const stockElement = document.getElementById('stockStatus');
                    if (stock > 0) {
                        stockElement.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle"></i> In Stock (' + stock + ' available)</span>';
                        document.getElementById('addToCartBtn').disabled = false;
                        document.getElementById('buyNowBtn').disabled = false;
                        
                        // Update max quantity
                        if (currentQuantity > stock) {
                            currentQuantity = stock;
                            document.getElementById('quantity').innerText = currentQuantity;
                        }
                    } else {
                        stockElement.innerHTML = '<span class="text-red-600"><i class="fas fa-times-circle"></i> Out of Stock</span>';
                        document.getElementById('addToCartBtn').disabled = true;
                        document.getElementById('buyNowBtn').disabled = true;
                    }
                    
                    // Update selected variation info
                    const infoElement = document.getElementById('selectedVariationInfo');
                    if (variationName) {
                        infoElement.innerHTML = '<i class="fas fa-check-circle text-green-600"></i> Selected: <strong>' + variationName + '</strong> - ₱' + price.toFixed(2);
                    } else {
                        infoElement.innerHTML = '<i class="fas fa-info-circle"></i> Variation selected';
                    }
                    
                    // Auto-select the corresponding color button if available
                    if (variationName) {
                        document.querySelectorAll('.color-option').forEach(btn => {
                            if (btn.dataset.color === variationName) {
                                // Trigger color selection
                                document.querySelectorAll('.color-option').forEach(b => {
                                    b.classList.remove('border-indigo-600', 'bg-indigo-50');
                                    b.classList.add('border-gray-300');
                                });
                                btn.classList.remove('border-gray-300');
                                btn.classList.add('border-indigo-600', 'bg-indigo-50');
                            }
                        });
                    }
                });
                
                container.appendChild(thumb);
            });
            
            // Also add default product images if any (as fallback)
            if (defaultImages.length > 0 && allVariationImages.length === 0) {
                defaultImages.forEach((imgUrl, index) => {
                    const img = document.createElement('img');
                    img.src = imgUrl;
                    img.className = 'thumbnail w-20 h-20 rounded-lg cursor-pointer object-cover border-2 transition-all hover:scale-105';
                    img.setAttribute('data-image', imgUrl);
                    
                    img.addEventListener('click', function() {
                        document.getElementById('mainImage').src = this.src;
                        document.querySelectorAll('#thumbnailContainer .thumbnail').forEach(t => {
                            t.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-200');
                            t.classList.add('border-gray-200');
                        });
                        this.classList.remove('border-gray-200');
                        this.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-200');
                    });
                    
                    container.appendChild(img);
                });
            }
            
            // Set active state on first thumbnail
            if (container.firstChild) {
                const firstImg = container.firstChild.querySelector('.thumbnail');
                if (firstImg) {
                    firstImg.classList.remove('border-gray-200');
                    firstImg.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-200');
                    // Trigger click on first thumbnail to load its data
                    container.firstChild.click();
                }
            }
        }
        
        // Handle color selection
        document.querySelectorAll('.color-option').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active state
                document.querySelectorAll('.color-option').forEach(b => {
                    b.classList.remove('border-indigo-600', 'bg-indigo-50');
                    b.classList.add('border-gray-300');
                });
                this.classList.remove('border-gray-300');
                this.classList.add('border-indigo-600', 'bg-indigo-50');
                
                const selectedColor = this.dataset.color;
                const price = parseFloat(this.dataset.price);
                const stock = parseInt(this.dataset.stock);
                const variationId = parseInt(this.dataset.variationId);
                
                // Find and click the corresponding thumbnail
                const thumbnails = document.querySelectorAll('#thumbnailContainer .thumbnail');
                for (let thumb of thumbnails) {
                    if (thumb.dataset.variationName === selectedColor) {
                        thumb.parentElement.click();
                        break;
                    }
                }
                
                // Update current variation
                currentVariation = allVariations.find(v => v.id === variationId);
                
                // Update price and stock
                if (price) {
                    document.getElementById('currentPrice').innerText = '₱' + price.toFixed(2);
                }
                
                const stockElement = document.getElementById('stockStatus');
                if (stock > 0) {
                    stockElement.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle"></i> In Stock (' + stock + ' available)</span>';
                } else {
                    stockElement.innerHTML = '<span class="text-red-600"><i class="fas fa-times-circle"></i> Out of Stock</span>';
                }
            });
        });
        
        // Handle size selection
        document.querySelectorAll('.size-option').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.size-option').forEach(b => {
                    b.classList.remove('border-indigo-600', 'bg-indigo-50');
                    b.classList.add('border-gray-300');
                });
                this.classList.remove('border-gray-300');
                this.classList.add('border-indigo-600', 'bg-indigo-50');
                
                const selectedSize = this.dataset.size;
                const price = parseFloat(this.dataset.price);
                const stock = parseInt(this.dataset.stock);
                const variationId = parseInt(this.dataset.variationId);
                
                // Find and click the corresponding thumbnail
                const thumbnails = document.querySelectorAll('#thumbnailContainer .thumbnail');
                for (let thumb of thumbnails) {
                    if (thumb.dataset.variationName === selectedSize) {
                        thumb.parentElement.click();
                        break;
                    }
                }
                
                currentVariation = allVariations.find(v => v.id === variationId);
                
                if (price) {
                    document.getElementById('currentPrice').innerText = '₱' + price.toFixed(2);
                }
                
                const stockElement = document.getElementById('stockStatus');
                if (stock > 0) {
                    stockElement.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle"></i> In Stock (' + stock + ' available)</span>';
                } else {
                    stockElement.innerHTML = '<span class="text-red-600"><i class="fas fa-times-circle"></i> Out of Stock</span>';
                }
            });
        });
        
        // Quantity controls
        const quantitySpan = document.getElementById('quantity');
        const decBtn = document.getElementById('decBtn');
        const incBtn = document.getElementById('incBtn');
        
        decBtn.addEventListener('click', () => {
            if (currentQuantity > 1) {
                currentQuantity--;
                quantitySpan.innerText = currentQuantity;
            }
        });
        
        incBtn.addEventListener('click', () => {
            let maxStock = currentVariation ? currentVariation.variant_stock : (currentImageData ? currentImageData.stock : 999);
            if (currentQuantity < maxStock) {
                currentQuantity++;
                quantitySpan.innerText = currentQuantity;
            } else {
                showToast('Maximum available stock reached');
            }
        });
        
        // Add to Cart
        document.getElementById('addToCartBtn').addEventListener('click', function() {
            if (!currentVariation && !currentImageData) {
                showToast('Please select a variation by clicking on an image');
                return;
            }
            
            const variationToUse = currentVariation || currentImageData;
            
            const cartData = {
                variation_id: variationToUse.id || variationToUse.variation_id,
                product_id: {{ $product->id }},
                quantity: currentQuantity,
                price: variationToUse.variant_price,
                variation_name: variationToUse.variation_name || variationToUse.variation_value
            };
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(cartData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Added to cart successfully!');
                } else {
                    showToast(data.message || 'Failed to add to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding to cart');
            });
        });
        
        // Buy Now
        document.getElementById('buyNowBtn').addEventListener('click', function() {
            if (!currentVariation && !currentImageData) {
                showToast('Please select a variation by clicking on an image');
                return;
            }
            
            const variationToUse = currentVariation || currentImageData;
            
            const buyData = {
                variation_id: variationToUse.id || variationToUse.variation_id,
                product_id: {{ $product->id }},
                quantity: currentQuantity,
                variation_name: variationToUse.variation_name || variationToUse.variation_value
            };
            
            sessionStorage.setItem('buyNowData', JSON.stringify(buyData));
            window.location.href = '#';
        });
        
        // Toast notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.innerText = message;
            toast.classList.remove('opacity-0', 'pointer-events-none');
            toast.classList.add('opacity-100');
            
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 3000);
        }
        
        // Initialize page
        function init() {
            console.log('All variation images:', allVariationImages);
            displayAllThumbnails();
        }
        
        // Start the app
        init();
    </script>
    
    <style>
        /* Custom styles for thumbnails */
        #thumbnailContainer {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
            padding: 10px
        }
        
        #thumbnailContainer::-webkit-scrollbar {
            height: 6px;
        }
        
        #thumbnailContainer::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        #thumbnailContainer::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        #thumbnailContainer::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .thumbnail {
            transition: all 0.2s ease;
        }
    </style>
@endsection