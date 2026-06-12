@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade"> 
        <!-- Toast Notification -->
        <div id="toast" class="fixed top-20 md:top-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-2.5 rounded-full shadow-lg z-50 transition-all duration-300 opacity-0 pointer-events-none text-sm whitespace-nowrap">
            <i class="fas fa-check-circle text-green-400 mr-2"></i>
            <span id="toastMessage"></span>
        </div>

        <!-- Modal Popup -->
        <div id="productModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 md:p-6" style="display: none;">
            <!-- Modal Content - No visible scrollbar -->
            <div class="bg-white rounded-2xl w-full max-w-2xl modal-responsive shadow-2xl relative max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
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
                        <div class="flex-1">
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

                    <!-- Add to Cart Button -->
                    <button onclick="confirmAddToCart()" class="add-cart-btn w-full py-3 sm:py-3.5 mt-4 sm:mt-5 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition">
                        <i class="fas fa-shopping-cart text-sm mr-2"></i>
                        Add to Cart
                    </button>
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

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5" id="productsGrid">
            @forelse ($products->take(8) as $item)
                <div class="product-card group card-item" data-product-id="{{ $item->encrypted_id }}">
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
                    @if(!$hasAvailableStock)
                        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/30 to-black/50 rounded-lg flex items-center justify-center z-10">
                            <span class="text-white font-bold px-4 py-1.5 bg-red-600 rounded-full text-sm shadow-lg backdrop-blur-sm">Out of Stock</span>
                        </div>
                    @endif
                    <div class="relative">
                        @if ($item->prdcttag === 'Popular')
                            <div class="discount-badge popular rounded-pill text-xs spantag">
                                {{ $item->prdcttag }}
                            </div>
                        @elseif ($item->prdcttag === 'New Arrival')
                            <div class="discount-badge new-arrival rounded-pill text-xs">
                                {{ $item->prdcttag }}
                            </div>
                        @elseif ($item->prdcttag === 'Sale')
                            <div class="discount-badge sale rounded-pill text-xs">
                                {{ $item->prdcttag }}
                            </div>
                        @endif

                        <div class="image-container">
                            @if(Auth::guard('customer')->check())
                                <a href="{{ route('itemdetails.auth.index', $item->encrypted_id) }}">
                            @else
                                <a href="{{ route('itemdetails.index', $item->encrypted_id) }}">
                            @endif
                                <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}" class="product-image" alt="{{ $item->prdctname }}">
                            </a>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-semibold text-gray-900 text-base cursor-pointer" onclick="window.location.href='{{ Auth::guard('customer')->check() ? route('itemdetails.auth.index', $item->encrypted_id) : route('itemdetails.index', $item->encrypted_id) }}'">
                                {{ strlen($item->prdctname) > 25 ? substr($item->prdctname, 0, 25) . '...' : $item->prdctname }}
                            </h3>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star rating-star"></i>
                                <span class="text-xs font-medium text-gray-700">4.5</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 mb-1">
                            @if($hasMultiplePrices)
                                <span class="text-xl font-bold text-gray-900">₱{{ number_format($minPrice, 2) }}</span>
                                <span class="text-sm text-gray-500">-</span>
                                <span class="text-lg font-semibold text-gray-900">₱{{ number_format($maxPrice, 2) }}</span>
                            @else
                                <span class="text-xl font-bold text-gray-900">₱{{ number_format($minPrice, 2) }}</span>
                            @endif
                        </div>
                        
                        <!-- Color Options (if available) -->
                        @if($colors->count() > 0)
                            <p class="text-xs text-gray-500 mb-2">
                                Colors: 
                                @foreach($colors as $index => $color)
                                    <span class="inline-block w-4 h-3 rounded-full border border-gray-300 ml-1" 
                                        style="background-color: {{ strtolower($color->variation_value) }}"
                                        title="{{ $color->variation_value }}"></span>
                                @endforeach
                            </p>
                        @else
                            <p class="text-xs text-gray-500 mb-2">{{ $item->variations->count() }} variation(s) available</p>
                        @endif
                        
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <i class="far fa-clock text-xs text-gray-400"></i>
                                <span class="text-xs text-gray-500">
                                    {{ $item->created_at->diffForHumans(null, true) === '0 seconds' ? 'Now' : $item->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-eye text-xs text-gray-400"></i>
                                <span class="text-xs text-gray-500">reviews</span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            @if(Auth::guard('customer')->check())
                                <button class="add-cart-btn flex-1" onclick="openModal({{ json_encode($item) }})" {{ !$hasAvailableStock ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '' }}>
                                    <i class="fas fa-shopping-cart text-sm"></i>
                                    Add Cart
                                </button>
                                <button class="buy-now-btn flex-1" onclick="buyNow({{ $item->encrypted_id }})" {{ !$hasAvailableStock ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '' }}>
                                    Buy Now
                                </button>
                            @else
                                <button class="add-cart-btn flex-1" onclick="redirectToLogin()">
                                    <i class="fas fa-shopping-cart text-sm"></i>
                                    Add Cart
                                </button>
                                <button class="buy-now-btn flex-1" onclick="redirectToLogin()">
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

    <script>
        // Modal variables
        let currentProduct = null;
        let currentProductVariations = [];
        let currentVariation = null;
        let currentQuantity = 1;
        let selectedVariationId = null;
        let mainProductImages = [];

        function redirectToLogin() {
            const currentUrl = window.location.href;
            window.location.href = "{{ route('shop.login') }}?redirect=" + encodeURIComponent(currentUrl);
        }

        // Updated openModal function
        function openModal(product) {
            currentProduct = product;
            currentProductVariations = product.variations || [];
            currentQuantity = 1;
            
            // Get the first IN-STOCK variation as default (using available_stock)
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
            
            // Show modal with blur effect
            const modal = document.getElementById('productModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // AFTER modal is shown, update image for selected variation (if exists)
            setTimeout(() => {
                if (currentVariation) {
                    updateProductImageForVariation(currentVariation);
                    // Update stock display with available stock
                    const availableStock = currentVariation.available_stock || currentVariation.variant_stock;
                    updateStockDisplay(availableStock);
                }
            }, 100);
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

        // Build variation options with transparent outline buttons
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
            
            // Build HTML with transparent outline buttons
            let html = '';
            for (const [type, variations] of Object.entries(groupedVariations)) {
                html += `
                    <div class="variation-group mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select ${type}</label>
                        <div class="variation-buttons-container flex gap-2 flex-wrap">
                `;
                
                variations.forEach(variation => {
                    const isSelected = currentVariation && currentVariation.id === variation.id;
                    // Use available_stock instead of variant_stock
                    const availableStock = variation.available_stock !== undefined ? variation.available_stock : variation.variant_stock;
                    const isOutOfStock = availableStock <= 0;
                    
                    // Get stock text for tooltip
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
                        
                        // Update active state
                        document.querySelectorAll('.variation-option-btn').forEach(btn => {
                            btn.classList.remove('active', 'selected');
                        });
                        this.classList.add('active', 'selected');
                        
                        // Update price and stock
                        updatePriceAndTotal();
                        // Use available_stock for stock display
                        const availableStock = selectedVariation.available_stock !== undefined 
                            ? selectedVariation.available_stock 
                            : selectedVariation.variant_stock;
                        updateStockDisplay(availableStock);
                        
                        // CHANGE IMAGE BASED ON SELECTED VARIATION
                        updateProductImageForVariation(selectedVariation);
                        
                        // Reset quantity if it exceeds available stock
                        if (currentQuantity > availableStock && availableStock > 0) {
                            currentQuantity = availableStock;
                            document.getElementById('modalQuantity').innerText = currentQuantity;
                            updatePriceAndTotal();
                        }
                    }
                });
            });
        }

        // NEW FUNCTION: Update product image based on selected variation
        function updateProductImageForVariation(variation) {
            const imgElement = document.getElementById('modalProductImage');
            
            // Add loading effect
            imgElement.style.opacity = '0.5';
            imgElement.style.transform = 'scale(0.98)';
            
            // Determine new image source
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
            
            // Load new image with transition
            const tempImage = new Image();
            tempImage.onload = function() {
                imgElement.src = newImageSrc;
                imgElement.style.opacity = '1';
                imgElement.style.transform = 'scale(1)';
            };
            tempImage.onerror = function() {
                // Fallback to main product image if variant image fails to load
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
            if (currentVariation && currentQuantity < currentVariation.variant_stock) {
                currentQuantity++;
                document.getElementById('modalQuantity').innerText = currentQuantity;
                updatePriceAndTotal();
            } else if (currentVariation && currentQuantity >= currentVariation.variant_stock) {
                showToast('Maximum stock reached', 'warning');
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

        function buyNow(productId) {
            window.location.href = "/checkout?product=" + productId;
        }

        // Add to cart function
        function confirmAddToCart() {
            if (!currentVariation) {
                showToast('Please select a variation', 'error');
                return;
            }
            
            // Use available_stock instead of variant_stock
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

        function updateCartCount(count) {
            const cartCountElement = document.getElementById('cartCount');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.classList.remove('hidden');
            }
        }

        function fetchCartCount() {
            fetch('{{ route("cart.realtime.count") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cart_count);
                }
            })
            .catch(error => console.error('Error fetching cart count:', error));
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            if (!toast) return;
            
            const toastMessage = document.getElementById('toastMessage');
            const icon = toast.querySelector('i');
            
            toastMessage.innerText = message;
            
            if (type === 'error') {
                icon.className = 'fas fa-exclamation-circle text-red-400 mr-2';
            } else if (type === 'warning') {
                icon.className = 'fas fa-exclamation-triangle text-yellow-400 mr-2';
            } else if (type === 'info') {
                icon.className = 'fas fa-info-circle text-blue-400 mr-2';
            } else {
                icon.className = 'fas fa-check-circle text-green-400 mr-2';
            }
            
            toast.classList.remove('opacity-0', 'pointer-events-none');
            toast.classList.add('opacity-100', 'pointer-events-auto');
            
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'pointer-events-auto');
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 3000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            fetchCartCount();
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>

    <style>
        /* Add blur effect to whole body when modal is open */
        body.modal-blur {
            overflow: hidden !important;
        }
        
        body.modal-blur main {
            filter: blur(8px);
            -webkit-filter: blur(8px);
            transition: filter 0.3s ease;
            pointer-events: none;
        }
        
        body.modal-blur header,
        body.modal-blur footer,
        body.modal-blur .navbar,
        body.modal-blur .top-bar {
            filter: blur(8px);
            -webkit-filter: blur(8px);
            transition: filter 0.3s ease;
            pointer-events: none;
        }
        
        #productModal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4) !important;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9999;
            display: none;
            overflow-y: hidden;
            padding: 20px;
        }
        
        .modal-responsive {
            max-width: 90%;
            width: 100%;
            margin: 60px auto 40px auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(0, 0, 0, 0.05);
            animation: modalSlideDown 0.4s cubic-bezier(0.34, 1.2, 0.64, 1);
            transform-origin: top;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
            overflow-y: auto !important;
            max-height: 85vh;
        }
        
        .modal-responsive::-webkit-scrollbar {
            display: none !important;
        }
        
        @keyframes modalSlideDown {
            0% {
                opacity: 0;
                transform: translateY(-100px) scale(0.95);
            }
            60% {
                opacity: 1;
                transform: translateY(10px) scale(1.02);
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
            animation: fadeIn 0.3s ease-out;
        }
        
        .modal-responsive .sticky {
            background: white;
            border-radius: 24px 24px 0 0;
        }
        
        #modalProductImage {
            transition: transform 0.3s ease;
        }
        
        #modalProductImage:hover {
            transform: scale(1.02);
        }
        
        .add-cart-btn, .buy-now-btn {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .add-cart-btn:active, .buy-now-btn:active {
            transform: scale(0.97);
        }
        
        .add-cart-btn::after, .buy-now-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .add-cart-btn:active::after, .buy-now-btn:active::after {
            width: 300px;
            height: 300px;
        }
        
        .size-btn {
            transition: all 0.2s cubic-bezier(0.34, 1.2, 0.64, 1);
            position: relative;
        }
        
        .size-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .size-btn:active {
            transform: translateY(0);
        }
        
        .quantity-btn {
            transition: all 0.2s ease;
        }
        
        .quantity-btn:active {
            transform: scale(0.9);
        }
        
        button[onclick="closeModal()"]:hover i {
            transform: rotate(90deg);
        }
        
        button[onclick="closeModal()"] i {
            transition: transform 0.3s ease;
        }
        
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
        
        @keyframes slideInDown {
            from {
                transform: translate(-50%, 100px);
                opacity: 0;
            }
            to {
                transform: translate(-50%, 0);
                opacity: 1;
            }
        }
        
        #toast {
            animation: slideInDown 0.3s ease-out;
        }
        
        @media (min-width: 640px) {
            .modal-responsive {
                max-width: 85%;
            }
        }
        
        @media (min-width: 768px) {
            .modal-responsive {
                max-width: 700px;
                margin: 80px auto 40px auto;
            }
        }
        
        @media (min-width: 1024px) {
            .modal-responsive {
                max-width: 800px;
            }
        }
        
        @media (max-width: 640px) {
            .modal-responsive {
                margin: 40px auto 20px auto;
            }
            
            .size-btn {
                min-height: 44px;
                font-size: 14px;
            }
            
            .quantity-btn {
                min-width: 44px;
                min-height: 44px;
            }
            
            .add-cart-btn {
                min-height: 48px;
            }
        }
        
        .active-size {
            border-color: black !important;
            background-color: black !important;
            color: white !important;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        main, header, footer, .navbar {
            transition: filter 0.3s ease;
        }
        
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
        
        body.modal-blur::before {
            content: '';
            position: fixed;
            top: 20px;
            right: 20px;
            width: 12px;
            height: 12px;
            background: #4ade80;
            border-radius: 50%;
            z-index: 10000;
            animation: pulse 2s infinite;
            box-shadow: 0 0 8px rgba(74, 222, 128, 0.6);
        }
        
        .variation-option-btn {
            padding: 8px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
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

        /* Stock warning badge */
        .stock-warning {
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 12px;
            background: #fef3c7;
            color: #d97706;
            margin-left: 8px;
        }
        
        @media (max-width: 640px) {
            .variation-option-btn {
                padding: 6px 14px;
                font-size: 12px;
                min-height: 36px;
            }
        }
        
        #modalProductPrice {
            color: #1f2937;
        }
        
        #modalStock .text-success {
            color: #10b981;
        }
        
        #modalStock .text-warning {
            color: #f59e0b;
        }
        
        #modalStock .text-danger {
            color: #ef4444;
        }

        /* Smooth image transition */
        #modalProductImage {
            transition: all 0.3s ease-in-out;
        }

        /* Add a subtle fade effect when image changes */
        .image-changing {
            opacity: 0.5;
            transform: scale(0.98);
        }
    </style>
@endsection