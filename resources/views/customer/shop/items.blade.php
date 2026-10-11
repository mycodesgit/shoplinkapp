@extends('customer.layouts.app')

@section('title', 'Shoplink | Shop')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade"> 
        <!-- Modal Popup - FULLY RESPONSIVE -->
        <div id="productModal" class="fixed inset-0 z-50 block p-3 sm:p-6" style="display: none;" role="dialog" aria-modal="true">
            <!-- Modal Content -->
            <div class="bg-white rounded-2xl w-full modal-responsive shadow-2xl relative mx-auto" onclick="event.stopPropagation()">
                <div class="sticky top-0 bg-white border-b border-gray-100 px-4 sm:px-5 py-3 sm:py-4 flex justify-between items-center rounded-t-2xl z-10">
                    <h3 class="font-semibold text-base sm:text-lg text-gray-900">Product Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                        <i class="fas fa-times text-lg sm:text-xl"></i>
                    </button>
                </div>
                
                <div class="p-4 sm:p-5 md:p-6">
                    <div class="flex flex-col sm:flex-row sm:gap-5 md:gap-6">
                        <!-- Product Image Section -->
                        <div class="sm:w-2/5 lg:w-2/5">
                            <img id="modalProductImage" src="" alt="Product" class="w-full h-48 sm:h-52 md:h-56 object-cover rounded-xl">
                            <div id="imageGallery" class="flex gap-2 mt-2 overflow-x-auto pb-2" style="scrollbar-width: thin;"></div>
                        </div>
                        
                        <!-- Product Details Section -->
                        <div class="flex-1 mt-4 sm:mt-0">
                            <h4 id="modalProductName" class="font-semibold text-gray-900 text-lg sm:text-xl mb-2"></h4>
                            
                            <div class="flex items-center gap-2 mb-3">
                                <span id="modalProductPrice" class="text-2xl sm:text-3xl font-bold text-gray-900">₱0.00</span>
                                <span id="modalOldPrice" class="text-sm text-gray-400 line-through hidden"></span>
                            </div>

                            <!-- Stock Status -->
                            <div id="modalStock" class="mb-3">
                                <span class="text-sm text-green-600">✓ In Stock</span>
                            </div>

                            <!-- Dynamic Variation Options Container -->
                            <div id="variationOptions" class="mb-4">
                                <!-- Variations will be dynamically inserted here -->
                            </div>

                            <!-- Quantity Selector -->
                            <div class="mb-5 sm:mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <div class="flex items-center gap-3">
                                    <button onclick="decrementQuantity()" class="quantity-btn w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition bg-white">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span id="modalQuantity" class="text-xl sm:text-2xl font-semibold w-12 text-center">1</span>
                                    <button onclick="incrementQuantity()" class="quantity-btn w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition bg-white">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Total Price Display -->
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Total Price:</span>
                                    <span id="modalTotalPrice" class="text-xl font-bold text-gray-900">₱0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Button (sticky footer — always reachable on mobile) -->
                    <div class="sticky bottom-0 -mx-4 mt-4 rounded-b-3xl border-t border-gray-100 bg-white/95 px-4 py-3 backdrop-blur sm:-mx-5 sm:mt-5 sm:px-5 md:-mx-6 md:px-6">
                        <button onclick="confirmAddToCart()" class="add-cart-btn min-h-12 w-full bg-black py-3 font-medium text-white transition hover:bg-gray-800">
                            <i class="fas fa-shopping-cart text-sm mr-2"></i>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Search products..." class="w-full pl-12 pr-4 py-3 bg-white rounded-full border border-gray-200 focus:ring-2 focus:ring-black focus:border-transparent outline-none transition">
            </div>
        </div>

        <!-- Category Tabs - Mobile Scrollable -->
        <div class="overflow-x-auto overflow-y-hidden mb-8 pb-2 scrollbar-hide [-webkit-overflow-scrolling:touch]">
            <div class="flex gap-6 min-w-max border-b border-gray-200">
                <div class="category-tab active cursor-pointer px-2 py-3 text-sm font-medium transition-colors duration-200 whitespace-nowrap" 
                    data-category="all">
                    All
                </div>
                
                @foreach($categories as $category)
                    <div class="category-tab cursor-pointer px-2 py-3 text-sm font-medium transition-colors duration-200 text-gray-500 hover:text-gray-700 whitespace-nowrap" 
                        data-category="{{ strtolower($category->catname) }}">
                        {{ $category->subcategory }}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-5" id="productsGrid">
            @php
                $productMap = $products->take(8)->mapWithKeys(function ($p) {
                    return [$p->encrypted_id => [
                        'id' => $p->id,
                        'prdctname' => $p->prdctname,
                        'prdctimage' => $p->prdctimage,
                        'variations' => $p->variations->map(function ($v) {
                            return [
                                'id' => $v->id,
                                'variation_name' => $v->variation_name,
                                'variation_value' => $v->variation_value,
                                'variant_price' => $v->variant_price,
                                'variant_sku' => $v->variant_sku,
                                'variant_stock' => $v->variant_stock,
                                'available_stock' => $v->available_stock ?? $v->variant_stock,
                                'variant_image' => $v->variant_image,
                            ];
                        })->values(),
                    ]];
                });
            @endphp
            @forelse ($products->take(8) as $item)
                <div class="product-card group card-item flex h-full flex-col" data-product-id="{{ $item->encrypted_id }}" data-category="{{ strtolower(optional($item->category)->catname ?? 'all') }}">
                    @php
                        $images = json_decode($item->prdctimage, true);
                        $firstImage = $images[0] ?? null;
                        $minPrice = $item->variations->min('variant_price');
                        $maxPrice = $item->variations->max('variant_price');
                        $hasMultiplePrices = $minPrice != $maxPrice;

                        // Get unique variation types (colors, sizes, etc.)
                        $variationTypes = $item->variations->groupBy('variation_name');
                        $colors = isset($variationTypes['Color']) ? $variationTypes['Color'] : collect();

                        // Calculate if product has any available stock
                        $hasAvailableStock = false;
                        foreach($item->variations as $variation) {
                            if ($variation->available_stock > 0) {
                                $hasAvailableStock = true;
                                break;
                            }
                        }
                    @endphp
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
                            @if(Auth::guard('customer')->check())
                                <a href="{{ route('itemdetails.auth.index', $item->encrypted_id) }}" class="block h-full" tabindex="-1" aria-hidden="true">
                            @else
                                <a href="{{ route('itemdetails.index', $item->encrypted_id) }}" class="block h-full" tabindex="-1" aria-hidden="true">
                            @endif
                                <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}" class="product-image" alt="{{ $item->prdctname }}" loading="lazy">
                            </a>
                        </div>
                        @if(!$hasAvailableStock)
                            <div class="absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-black/50 p-2">
                                <span class="rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white shadow-lg sm:text-sm">Out of Stock</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-3 sm:p-4">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="line-clamp-2 min-h-10 cursor-pointer text-sm font-semibold text-gray-900 sm:min-h-12 sm:text-base" onclick="window.location.href='{{ Auth::guard('customer')->check() ? route('itemdetails.auth.index', $item->encrypted_id) : route('itemdetails.index', $item->encrypted_id) }}'">
                                {{ $item->prdctname }}
                            </h3>
                            <div class="flex shrink-0 items-center gap-1 pt-0.5">
                                <i class="fas fa-star rating-star"></i>
                                <span class="text-xs font-medium text-gray-700">4.5</span>
                            </div>
                        </div>

                        <div class="mt-1 flex min-h-6 flex-wrap items-baseline gap-x-2 sm:min-h-7">
                            @if($hasMultiplePrices)
                                <span class="text-base font-bold text-gray-900 sm:text-xl">₱{{ number_format($minPrice, 2) }}</span>
                                <span class="text-xs text-gray-400 sm:text-sm">-</span>
                                <span class="text-sm font-semibold text-gray-900 sm:text-lg">₱{{ number_format($maxPrice, 2) }}</span>
                            @else
                                <span class="text-base font-bold text-gray-900 sm:text-xl">₱{{ number_format($minPrice, 2) }}</span>
                            @endif
                        </div>

                        <!-- Color Options (if available) -->
                        @if($colors->count() > 0)
                            <p class="mt-1 min-h-5 truncate text-xs text-gray-500">
                                Colors:
                                @foreach($colors as $index => $color)
                                    <span class="ml-1 inline-block h-3 w-4 rounded-full border border-gray-300"
                                        style="background-color: {{ strtolower($color->variation_value) }}"
                                        title="{{ $color->variation_value }}"></span>
                                @endforeach
                            </p>
                        @else
                            <p class="mt-1 min-h-5 truncate text-xs text-gray-500">{{ $item->variations->count() }} variation(s) available</p>
                        @endif

                        <div class="mt-1 flex min-h-5 items-center justify-between gap-2 text-xs text-gray-500">
                            <div class="flex min-w-0 items-center gap-1.5">
                                <i class="far fa-clock shrink-0 text-[11px] text-gray-400"></i>
                                <span class="truncate">
                                    {{ $item->created_at->diffForHumans(null, true) === '0 seconds' ? 'Now' : $item->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex shrink-0 items-center gap-1">
                                <i class="fas fa-eye text-[11px] text-gray-400"></i>
                                <span>reviews</span>
                            </div>
                        </div>

                        <div class="mt-auto flex flex-col gap-2 pt-3 sm:flex-row">
                            @if(Auth::guard('customer')->check())
                                <button class="add-cart-btn min-h-11 flex-1 text-xs sm:text-[13px]" onclick="openModalById('{{ $item->encrypted_id }}')" {{ !$hasAvailableStock ? 'disabled' : '' }}>
                                    <i class="fas fa-shopping-cart text-sm"></i>
                                    Add Cart
                                </button>
                                <button class="buy-now-btn min-h-11 flex-1 text-xs sm:text-[13px]" onclick="openBuyNowById('{{ $item->encrypted_id }}')" {{ !$hasAvailableStock ? 'disabled' : '' }}>
                                    Buy Now
                                </button>
                            @else
                                <button class="add-cart-btn min-h-11 flex-1 text-xs sm:text-[13px]" onclick="window.ShopLink.redirectToLogin()">
                                    <i class="fas fa-shopping-cart text-sm"></i>
                                    Add Cart
                                </button>
                                <button class="buy-now-btn min-h-11 flex-1 text-xs sm:text-[13px]" onclick="window.ShopLink.redirectToLogin()">
                                    Buy Now
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No products found</p>
                </div>
            @endforelse
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        let currentProduct = null;
        let currentProductVariations = [];
        let currentVariation = null;
        let currentQuantity = 1;
        let selectedVariationId = null;
        let mainProductImages = [];
        let buyNowMode = false;
        var buyNowRoute = "{{ route('cart.buyNow') }}";
        var shopProducts = @json($productMap);

        function openModalById(encId) {
            var product = shopProducts[encId];
            if (!product) {
                showToast('Product not available', 'error');
                return;
            }
            openModal(product);
        }

        function openBuyNowById(encId) {
            var product = shopProducts[encId];
            if (!product) {
                showToast('Product not available', 'error');
                return;
            }
            openBuyNow(product);
        }

        function redirectToLogin() {
            window.ShopLink.redirectToLogin();
        }

        function setModalConfirmLabel() {
            var btn = document.querySelector('#productModal .add-cart-btn');
            if (!btn) return;
            btn.innerHTML = buyNowMode
                ? '<i class="fas fa-bolt text-sm mr-2"></i> Buy Now →'
                : '<i class="fas fa-shopping-cart text-sm mr-2"></i> Add to Cart';
        }

        // Updated openModal function - FIXED for bottom of page
        function openModal(product) {
            currentProduct = product;
            currentProductVariations = product.variations || [];
            currentQuantity = 1;
            
            // Get the first IN-STOCK variation as default
            if (currentProductVariations.length > 0) {
                const inStockVariation = currentProductVariations.find(v => (v.available_stock || v.variant_stock) > 0);
                currentVariation = inStockVariation || currentProductVariations[0];
                selectedVariationId = currentVariation.id;
            }
            
            // Set modal content
            displayProductImages(product.prdctimage);
            document.getElementById('modalProductName').innerText = product.prdctname;
            
            // Build variation options
            buildVariationOptions();
            
            // Update price and total
            updatePriceAndTotal();
            
            // Reset quantity display
            document.getElementById('modalQuantity').innerText = currentQuantity;
            
            // Show modal (block layout so a tall card scrolls instead of clipping)
            const modal = document.getElementById('productModal');
            modal.style.display = 'block';
            modal.scrollTop = 0;
            document.body.style.overflow = 'hidden';

            // AFTER modal is shown, update image for selected variation
            setTimeout(() => {
                if (currentVariation) {
                    updateProductImageForVariation(currentVariation);
                    const availableStock = currentVariation.available_stock || currentVariation.variant_stock;
                    updateStockDisplay(availableStock);
                }
            }, 100);

            buyNowMode = false;
            setModalConfirmLabel();
        }

        // Buy Now: same picker, but the confirm button skips the cart entirely
        function openBuyNow(product) {
            openModal(product);
            buyNowMode = true;
            setModalConfirmLabel();
        }

        // Display product images
        function displayProductImages(prdctimage) {
            let images = [];
            try {
                images = typeof prdctimage === 'string' ? JSON.parse(prdctimage) : prdctimage;
            } catch(e) {
                images = [prdctimage];
            }
            
            // Store main images for fallback
            mainProductImages = images;
            
            const mainImage = images.length > 0 && images[0] ? '/storage/' + images[0] : '/placeholder.jpg';
            document.getElementById('modalProductImage').src = mainImage;
            
            // Build thumbnail gallery
            const galleryContainer = document.getElementById('imageGallery');
            if (galleryContainer && images.length > 1) {
                let galleryHtml = '';
                images.forEach((img, index) => {
                    galleryHtml += `
                        <img src="/storage/${img}" 
                            class="w-12 h-12 object-cover rounded cursor-pointer border-2 hover:border-warning transition"
                            onclick="document.getElementById('modalProductImage').src='/storage/${img}'">
                    `;
                });
                galleryContainer.innerHTML = galleryHtml;
            } else if (galleryContainer) {
                galleryContainer.innerHTML = '';
            }
        }

        // Build variation options
        function buildVariationOptions() {
            const container = document.getElementById('variationOptions');
            if (!container) return;
            
            if (currentProductVariations.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">No variations available</p>';
                return;
            }
            
            // Group variations by type
            const groupedVariations = {};
            currentProductVariations.forEach(variation => {
                if (!groupedVariations[variation.variation_name]) {
                    groupedVariations[variation.variation_name] = [];
                }
                groupedVariations[variation.variation_name].push(variation);
            });
            
            // Build HTML
            let html = '';
            for (const [type, variations] of Object.entries(groupedVariations)) {
                html += `
                    <div class="variation-group mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select ${type}</label>
                        <div class="variation-buttons-container flex gap-2 flex-wrap">
                `;
                
                variations.forEach(variation => {
                    const isSelected = currentVariation && currentVariation.id === variation.id;
                    const availableStock = variation.available_stock !== undefined ? variation.available_stock : variation.variant_stock;
                    const isOutOfStock = availableStock <= 0;
                    
                    let stockText = '';
                    if (availableStock <= 0) {
                        stockText = 'Out of Stock';
                    } else if (availableStock <= 10) {
                        stockText = `Only ${availableStock} left!`;
                    } else {
                        stockText = `${availableStock} in stock`;
                    }
                    
                    html += `
                        <button 
                            data-variation-id="${variation.id}"
                            data-variation-name="${variation.variation_name}"
                            data-variation-value="${variation.variation_value}"
                            data-variation-price="${variation.variant_price}"
                            data-variation-stock="${availableStock}"
                            data-original-stock="${variation.variant_stock}"
                            data-variation-image="${variation.variant_image || ''}"
                            class="variation-option-btn ${isSelected ? 'active selected' : ''}"
                            ${isOutOfStock ? 'disabled' : ''}
                            title="${stockText}"
                        >
                            ${variation.variation_value}
                            ${isOutOfStock ? ' ❌' : (availableStock <= 10 ? ' ⚠️' : '')}
                        </button>
                    `;
                });
                
                html += `</div></div>`;
            }
            
            container.innerHTML = html;
            
            // Add event listeners to variation buttons
            document.querySelectorAll('.variation-option-btn:not([disabled])').forEach(btn => {
                btn.addEventListener('click', function() {
                    const variationId = parseInt(this.getAttribute('data-variation-id'));
                    const selectedVariation = currentProductVariations.find(v => v.id === variationId);
                    
                    if (selectedVariation) {
                        currentVariation = selectedVariation;
                        selectedVariationId = variationId;
                        
                        document.querySelectorAll('.variation-option-btn').forEach(btn => {
                            btn.classList.remove('active', 'selected');
                        });
                        this.classList.add('active', 'selected');
                        
                        updatePriceAndTotal();
                        const availableStock = selectedVariation.available_stock !== undefined 
                            ? selectedVariation.available_stock 
                            : selectedVariation.variant_stock;
                        updateStockDisplay(availableStock);
                        updateProductImageForVariation(selectedVariation);
                        
                        if (currentQuantity > availableStock && availableStock > 0) {
                            currentQuantity = availableStock;
                            document.getElementById('modalQuantity').innerText = currentQuantity;
                            updatePriceAndTotal();
                        }
                    }
                });
            });
        }

        // Update product image based on selected variation
        function updateProductImageForVariation(variation) {
            const imgElement = document.getElementById('modalProductImage');
            imgElement.style.opacity = '0.5';
            imgElement.style.transform = 'scale(0.98)';
            
            let newImageSrc = '';
            
            if (variation.variant_image && variation.variant_image !== 'null' && variation.variant_image !== '') {
                if (variation.variant_image.startsWith('product-variations/')) {
                    newImageSrc = '/storage/' + variation.variant_image;
                } else if (variation.variant_image.startsWith('storage/')) {
                    newImageSrc = '/' + variation.variant_image;
                } else if (variation.variant_image.startsWith('http')) {
                    newImageSrc = variation.variant_image;
                } else {
                    newImageSrc = '/storage/' + variation.variant_image;
                }
            } else {
                newImageSrc = getMainProductImage();
            }
            
            const tempImage = new Image();
            tempImage.onload = function() {
                imgElement.src = newImageSrc;
                imgElement.style.opacity = '1';
                imgElement.style.transform = 'scale(1)';
            };
            tempImage.onerror = function() {
                imgElement.src = getMainProductImage();
                imgElement.style.opacity = '1';
                imgElement.style.transform = 'scale(1)';
            };
            tempImage.src = newImageSrc;
        }

        // Helper function to get main product image
        function getMainProductImage() {
            if (currentProduct && currentProduct.prdctimage) {
                let images = [];
                try {
                    images = typeof currentProduct.prdctimage === 'string' 
                        ? JSON.parse(currentProduct.prdctimage) 
                        : currentProduct.prdctimage;
                } catch(e) {
                    images = [currentProduct.prdctimage];
                }
                
                if (images.length > 0 && images[0]) {
                    return '/storage/' + images[0];
                }
            }
            return '/placeholder.jpg';
        }

        // Update price and total
        function updatePriceAndTotal() {
            if (currentVariation) {
                const price = parseFloat(currentVariation.variant_price);
                const total = price * currentQuantity;
                document.getElementById('modalProductPrice').innerHTML = '₱' + price.toFixed(2);
                document.getElementById('modalTotalPrice').innerHTML = '₱' + total.toFixed(2);
            }
        }

        // Update stock display
        function updateStockDisplay(stock) {
            const stockElement = document.getElementById('modalStock');
            const addBtn = document.querySelector('#productModal .add-cart-btn');
            
            if (stock <= 0) {
                stockElement.innerHTML = '<span class="text-danger text-sm">❌ Out of Stock</span>';
                if (addBtn) {
                    addBtn.disabled = true;
                    addBtn.style.opacity = '0.5';
                    addBtn.style.cursor = 'not-allowed';
                }
            } else if (stock < 10) {
                stockElement.innerHTML = `<span class="text-warning text-sm">⚠️ Only ${stock} left! Order soon.</span>`;
                if (addBtn) {
                    addBtn.disabled = false;
                    addBtn.style.opacity = '1';
                    addBtn.style.cursor = 'pointer';
                }
            } else {
                stockElement.innerHTML = '<span class="text-success text-sm">✓ In Stock (' + stock + ' available)</span>';
                if (addBtn) {
                    addBtn.disabled = false;
                    addBtn.style.opacity = '1';
                    addBtn.style.cursor = 'pointer';
                }
            }
        }

        function closeModal() {
            const modal = document.getElementById('productModal');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function incrementQuantity() {
            if (currentVariation) {
                const availableStock = currentVariation.available_stock !== undefined 
                    ? currentVariation.available_stock 
                    : currentVariation.variant_stock;
                    
                if (currentQuantity < availableStock) {
                    currentQuantity++;
                    document.getElementById('modalQuantity').innerText = currentQuantity;
                    updatePriceAndTotal();
                } else if (currentQuantity >= availableStock) {
                    showToast('Maximum stock reached', 'warning');
                }
            } else {
                currentQuantity++;
                document.getElementById('modalQuantity').innerText = currentQuantity;
                updatePriceAndTotal();
            }
        }

        function decrementQuantity() {
            if (currentQuantity > 1) {
                currentQuantity--;
                document.getElementById('modalQuantity').innerText = currentQuantity;
                updatePriceAndTotal();
            }
        }

        // Add to cart function (branches to Buy Now checkout when in buy-now mode)
        function confirmAddToCart() {
            if (buyNowMode) {
                confirmBuyNow();
                return;
            }
            if (!currentVariation) {
                showToast('Please select a variation', 'error');
                return;
            }
            
            const availableStock = currentVariation.available_stock !== undefined 
                ? currentVariation.available_stock 
                : currentVariation.variant_stock;
            
            if (availableStock <= 0) {
                showToast('This variation is out of stock', 'error');
                return;
            }
            
            if (currentQuantity > availableStock) {
                showToast(`Only ${availableStock} items available in stock`, 'error');
                return;
            }
            
            const addBtn = document.querySelector('#productModal .add-cart-btn');
            const originalHtml = addBtn.innerHTML;
            addBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adding...';
            addBtn.disabled = true;
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: currentProduct.id,
                    variation_id: currentVariation.id,
                    variation_sku: currentVariation.variant_sku,
                    quantity: currentQuantity,
                    price: currentVariation.variant_price,
                    variation_name: currentVariation.variation_name,
                    variation_value: currentVariation.variation_value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    showToast(data.message || 'Added to cart successfully!', 'success');
                    if (data.cart_count !== undefined) {
                        updateCartCount(data.cart_count);
                    }
                } else if (data.require_login) {
                    closeModal();
                    showToast('Please login to add items to cart', 'warning');
                    setTimeout(() => redirectToLogin(), 1500);
                } else {
                    showToast(data.message || 'Failed to add to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Something went wrong', 'error');
            })
            .finally(() => {
                addBtn.innerHTML = originalHtml;
                addBtn.disabled = false;
            });
        }

        // Buy Now: stage the picked variation server-side, then jump to checkout (no cart)
        function confirmBuyNow() {
            if (!currentVariation) {
                showToast('Please select a variation', 'error');
                return;
            }

            const availableStock = currentVariation.available_stock !== undefined
                ? currentVariation.available_stock
                : currentVariation.variant_stock;

            if (availableStock <= 0) {
                showToast('This variation is out of stock', 'error');
                return;
            }

            if (currentQuantity > availableStock) {
                showToast(`Only ${availableStock} items available in stock`, 'error');
                return;
            }

            const buyBtn = document.querySelector('#productModal .add-cart-btn');
            const originalHtml = buyBtn.innerHTML;
            buyBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            buyBtn.disabled = true;

            fetch(buyNowRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: currentProduct.id,
                    variation_id: currentVariation.id,
                    quantity: currentQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.require_login) {
                    closeModal();
                    showToast('Please login to continue', 'warning');
                    setTimeout(() => redirectToLogin(), 1500);
                } else {
                    showToast(data.message || 'Failed to start quick checkout', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Something went wrong', 'error');
            })
            .finally(() => {
                buyBtn.innerHTML = originalHtml;
                buyBtn.disabled = false;
            });
        }

        function updateCartCount(count) {
            window.ShopLink.setCartCount(count);
        }

        function fetchCartCount() {
            if (window.cartManager && window.cartManager.manualRefresh) {
                window.cartManager.manualRefresh();
            }
        }

        function showToast(message, type = 'success') {
            window.ShopLink.showToast(message, type);
        }

        // Category search + tab filtering
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('searchInput');
            var tabs = document.querySelectorAll('.category-tab');
            var activeCategory = 'all';

            function applyFilters() {
                var q = (searchInput ? searchInput.value : '').toLowerCase().trim();
                document.querySelectorAll('#productsGrid .product-card').forEach(function (card) {
                    var name = (card.querySelector('h3') ? card.querySelector('h3').textContent : '').toLowerCase();
                    var cat = (card.getAttribute('data-category') || 'all').toLowerCase();
                    var matchQ = !q || name.includes(q);
                    var matchC = activeCategory === 'all' || cat === activeCategory;
                    card.style.display = (matchQ && matchC) ? '' : 'none';
                });
            }

            if (searchInput) searchInput.addEventListener('input', applyFilters);
            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    tabs.forEach(function (t) { t.classList.remove('active'); });
                    tab.classList.add('active');
                    activeCategory = (tab.getAttribute('data-category') || 'all').toLowerCase();
                    applyFilters();
                });
            });
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Close modal when clicking on background overlay
        document.getElementById('productModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Modal Styles - Fully Responsive with Scrolling */
        
        /* Modal Container — block layout + overlay scroll so tall
           content on mobile scrolls instead of clipping */
        #productModal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6) !important;
            z-index: 9999;
            display: none;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            padding: 20px;
            cursor: pointer;
        }
        
        /* Modal Content — no max-height / no clipping; the overlay scrolls */
        .modal-responsive {
            max-width: 95%;
            width: 100%;
            margin: 20px auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(0, 0, 0, 0.05);
            animation: modalSlideDown 0.3s ease-out;
            position: relative;
            cursor: default;
        }
        
        /* Tablet and Desktop sizes */
        @media (min-width: 640px) {
            #productModal {
                padding: 30px;
            }
            
            .modal-responsive {
                max-width: 85%;
                margin: 30px auto;
            }
        }
        
        @media (min-width: 768px) {
            .modal-responsive {
                max-width: 700px;
                margin: 40px auto;
            }
        }
        
        @media (min-width: 1024px) {
            .modal-responsive {
                max-width: 800px;
                margin: 50px auto;
            }
        }
        
        /* Mobile specific adjustments */
        @media (max-width: 640px) {
            #productModal {
                padding: 10px;
            }
            
            .modal-responsive {
                max-width: 98%;
                border-radius: 20px;
                margin: 10px auto 24px;
            }
            
            .modal-responsive .p-4 {
                padding: 16px !important;
            }
            
            .modal-responsive .sticky {
                padding: 12px 16px !important;
            }
            
            h3.font-semibold {
                font-size: 1rem !important;
            }
            
            #modalProductPrice {
                font-size: 1.5rem !important;
            }
            
            /* Better touch targets */
            .quantity-btn,
            .variation-option-btn,
            .add-cart-btn,
            .buy-now-btn,
            button[onclick="closeModal()"] {
                min-height: 44px;
                touch-action: manipulation;
            }
            
            .quantity-btn {
                min-width: 44px;
            }
            
            .add-cart-btn {
                min-height: 48px;
                font-size: 14px;
            }
        }
        
        /* Very small devices */
        @media (max-width: 480px) {
            .modal-responsive {
                max-width: 99%;
                border-radius: 16px;
            }
            
            .modal-responsive .p-4 {
                padding: 12px !important;
            }
        }
        
        /* Landscape orientation */
        @media (max-width: 768px) and (orientation: landscape) {
            .modal-responsive .flex-col.sm\:flex-row {
                flex-direction: row !important;
            }
            
            .modal-responsive .sm\:w-2\/5 {
                width: 40% !important;
            }
            
            .modal-responsive .flex-1 {
                width: 60% !important;
            }
            
            #modalProductImage {
                max-height: 150px !important;
            }
        }
        
        /* Modal animations */
        @keyframes modalSlideDown {
            0% {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        #productModal {
            animation: fadeIn 0.2s ease-out;
        }
        
        /* Modal header sticky */
        .modal-responsive .sticky {
            background: white;
            border-radius: 24px 24px 0 0;
        }
        
        /* Product image */
        #modalProductImage {
            transition: all 0.3s ease-in-out;
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: cover;
        }
        
        @media (min-width: 640px) {
            #modalProductImage {
                max-height: 220px;
            }
        }
        
        @media (min-width: 768px) {
            #modalProductImage {
                max-height: 240px;
            }
        }
        
        #modalProductImage:hover {
            transform: scale(1.02);
        }
        
        /* Buttons styling */
        .add-cart-btn, .buy-now-btn {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .add-cart-btn:active, .buy-now-btn:active {
            transform: scale(0.97);
        }
        
        /* Quantity buttons */
        .quantity-btn {
            transition: all 0.2s ease;
        }
        
        .quantity-btn:active {
            transform: scale(0.9);
        }
        
        /* Close button */
        button[onclick="closeModal()"]:hover i {
            transform: rotate(90deg);
        }
        
        button[onclick="closeModal()"] i {
            transition: transform 0.3s ease;
        }
        
        /* Variation option buttons */
        .variation-option-btn {
            padding: 8px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        @media (max-width: 640px) {
            .variation-option-btn {
                padding: 6px 12px;
                font-size: 12px;
                min-height: 36px;
            }
        }
        
        .variation-option-btn:hover:not([disabled]) {
            border-color: #000;
            background: #f9fafb;
            color: #000;
        }
        
        .variation-option-btn.active {
            background: #000;
            color: white;
            border-color: #000;
        }
        
        .variation-option-btn[disabled] {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f3f4f6;
            text-decoration: line-through;
        }
        
        /* Stock status colors */
        #modalStock .text-success {
            color: #10b981;
        }
        
        #modalStock .text-warning {
            color: #f59e0b;
        }
        
        #modalStock .text-danger {
            color: #ef4444;
        }
        
        /* Loading animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn-loading i {
            animation: spin 0.8s linear infinite;
        }
        
        /* Product cards */
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Image transition effect */
        .image-changing {
            opacity: 0.5;
            transform: scale(0.98);
        }
        
        /* Scrollbar hide for category tabs */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Make sure modal content is scrollable on mobile */
        @media (max-width: 640px) {
            .modal-responsive {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
@endpush