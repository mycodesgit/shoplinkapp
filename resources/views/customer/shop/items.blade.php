@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade"> 
        <!-- Toast Notification -->
        <div id="toast" class="fixed top-20 md:top-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-2.5 rounded-full shadow-lg z-50 transition-all duration-300 opacity-0 pointer-events-none text-sm whitespace-nowrap">
            <i class="fas fa-check-circle text-green-400 mr-2"></i>
            <span id="toastMessage"></span>
        </div>

        <!-- Modal Popup - Clean, No Overlay, Preserves Background -->
        <!-- Modal Popup - Always Centered -->
        <div id="productModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 md:p-6">
            <div class="absolute inset-0" onclick="closeModal()"></div>
            
            <!-- Modal Content - Always Centered Vertically & Horizontally -->
            <div class="bg-white rounded-2xl w-full modal-responsive shadow-2xl border border-gray-200 relative max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                <div class="sticky top-0 bg-white border-b border-gray-100 px-4 sm:px-5 py-3 sm:py-4 flex justify-between items-center rounded-t-2xl z-10">
                    <h3 class="font-semibold text-base sm:text-lg text-gray-900">Add to Cart</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                        <i class="fas fa-times text-lg sm:text-xl"></i>
                    </button>
                </div>
                
                <div class="p-4 sm:p-5 md:p-6">
                    <div class="flex flex-col sm:flex-row sm:gap-5 md:gap-6">
                        <!-- Product Image -->
                        <div class="sm:w-2/5 lg:w-1/3 mb-4 sm:mb-0">
                            <img id="modalProductImage" src="" alt="Product" class="w-full h-40 sm:h-44 md:h-48 object-cover rounded-xl">
                        </div>
                        
                        <!-- Product Details -->
                        <div class="flex-1">
                            <h4 id="modalProductName" class="font-semibold text-gray-900 text-base sm:text-lg mb-2 line-clamp-2"></h4>
                            
                            <div class="flex items-center gap-2 mb-4">
                                <span id="modalProductPrice" class="text-xl sm:text-2xl font-bold text-gray-900"></span>
                                <span class="text-xs sm:text-sm text-gray-400 line-through">₱200.00</span>
                            </div>

                            <!-- Size Options -->
                            <div class="mb-5">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Size</label>
                                <div class="flex gap-2 flex-wrap" id="sizeOptions">
                                    <button data-size="S" class="size-btn px-3 sm:px-4 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm font-medium hover:border-black transition bg-white">S</button>
                                    <button data-size="M" class="size-btn px-3 sm:px-4 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm font-medium hover:border-black transition bg-white">M</button>
                                    <button data-size="L" class="size-btn px-3 sm:px-4 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm font-medium hover:border-black transition bg-white">L</button>
                                    <button data-size="XL" class="size-btn px-3 sm:px-4 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm font-medium hover:border-black transition bg-white">XL</button>
                                    <button data-size="XXL" class="size-btn px-3 sm:px-4 py-1.5 sm:py-2 border border-gray-300 rounded-lg text-sm font-medium hover:border-black transition bg-white">XXL</button>
                                </div>
                            </div>

                            <!-- Quantity Selector -->
                            <div class="mb-5 sm:mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <div class="flex items-center gap-3">
                                    <button onclick="decrementQuantity()" class="quantity-btn w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition bg-white">
                                        <i class="fas fa-minus text-xs sm:text-sm"></i>
                                    </button>
                                    <span id="modalQuantity" class="text-lg sm:text-xl font-semibold w-10 sm:w-12 text-center">1</span>
                                    <button onclick="incrementQuantity()" class="quantity-btn w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-gray-300 flex items-center justify-center hover:border-black transition bg-white">
                                        <i class="fas fa-plus text-xs sm:text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <button onclick="confirmAddToCart()" class="add-cart-btn w-full py-3 sm:py-3.5 mt-4 sm:mt-5">
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

        <!-- Category Tabs -->
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
                <div class="product-card group card-item" data-product-id="{{ $item->id }}">
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
                            @if(Auth::guard('customer')->check())
                                <a href="{{ route('itemdetails.auth.index', $item->id) }}">
                            @else
                                <a href="{{ route('itemdetails.index', $item->id) }}">
                            @endif
                                <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}" class="product-image" alt="{{ $item->prdctname }}">
                            </a>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-semibold text-gray-900 text-base cursor-pointer" onclick="window.location.href='{{ Auth::guard('customer')->check() ? route('itemdetails.auth.index', $item->id) : route('itemdetails.index', $item->id) }}'">
                                {{ strlen($item->prdctname) > 25 ? substr($item->prdctname, 0, 25) . '...' : $item->prdctname }}
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
                                <button class="add-cart-btn flex-1" onclick="openModal({{ $item->id }}, '{{ addslashes($item->prdctname) }}', {{ $item->prdctprice }}, '{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}')">
                                    <i class="fas fa-shopping-cart text-sm"></i>
                                    Add Cart
                                </button>
                                <button class="buy-now-btn flex-1" onclick="buyNow({{ $item->id }})">
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
                <div class="col-span-full text-center py-16"><i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i><p class="text-gray-500">No products found</p></div>
            @endforelse
        </div>
    </main>

    <script>
        // Modal variables
        let currentProductId = null;
        let currentQuantity = 1;
        let selectedSize = 'M';

        function redirectToLogin() {
            const currentUrl = window.location.href;
            window.location.href = "{{ route('shop.login') }}?redirect=" + encodeURIComponent(currentUrl);
        }

        // Auto-scroll to modal when opened (for better UX on mobile)
        function openModal(productId, productName, productPrice, productImage) {
            currentProductId = productId;
            currentQuantity = 1;
            
            // Set modal content
            document.getElementById('modalProductImage').src = productImage;
            document.getElementById('modalProductName').innerText = productName;
            document.getElementById('modalProductPrice').innerText = '₱' + parseFloat(productPrice).toFixed(2);
            document.getElementById('modalQuantity').innerText = currentQuantity;
            
            // Reset size selection
            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.classList.remove('active-size');
            });
            const defaultSizeBtn = document.querySelector('[data-size="M"]');
            if (defaultSizeBtn) {
                defaultSizeBtn.classList.add('active-size');
                selectedSize = 'M';
            }
            
            // Show modal
            const modal = document.getElementById('productModal');
            modal.style.display = 'block';

            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            // Auto-scroll to modal on mobile (so user sees it)
            setTimeout(() => {
                const modalContent = document.querySelector('.modal-responsive');
                if (modalContent && window.innerWidth <= 768) {
                    modalContent.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
            }, 100);
        }

        function closeModal() {
            const modal = document.getElementById('productModal');
            modal.style.display = 'none';
            modal.classList.add('hidden');
            
            // Remove blur from body content
            document.body.classList.remove('modal-blur');
            
            // Restore body scroll
            document.body.style.overflow = '';
        }

        function incrementQuantity() {
            currentQuantity++;
            document.getElementById('modalQuantity').innerText = currentQuantity;
        }

        function decrementQuantity() {
            if (currentQuantity > 1) {
                currentQuantity--;
                document.getElementById('modalQuantity').innerText = currentQuantity;
            }
        }

        // Size selection handler
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.size-btn').forEach(b => {
                    b.classList.remove('active-size');
                });
                this.classList.add('active-size');
                selectedSize = this.getAttribute('data-size');
            });
        });

        // Function to update cart count in navbar
        function updateCartCount(count) {
            const cartCountElement = document.getElementById('cartCount');
            if (cartCountElement) {
                if (count > 0) {
                    cartCountElement.textContent = count;
                    cartCountElement.classList.remove('hidden');
                    // Add a small animation
                    cartCountElement.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        cartCountElement.style.transform = 'scale(1)';
                    }, 200);
                } else {
                    cartCountElement.textContent = '0';
                }
            }
        }

        // Function to fetch current cart count from server
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
            .catch(error => {
                console.error('Error fetching cart count:', error);
            });
        }

        // Updated confirmAddToCart function
        function confirmAddToCart() {
            const addBtn = document.querySelector('#productModal .add-cart-btn');
            const originalHtml = addBtn.innerHTML;
            addBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm mr-2"></i> Adding...';
            addBtn.disabled = true;
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: currentProductId,
                    quantity: currentQuantity,
                    size: selectedSize  // Use 'size' not 'options'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    showToast(data.message || 'Added to cart successfully!');
                    
                    if (data.cart_count !== undefined) {
                        if (window.cartManager) {
                            window.cartManager.updateCartCountUI(data.cart_count);
                        }
                    }
                } else if (data.require_login) {
                    closeModal();
                    showToast('Please login to add items to cart', 'warning');
                    setTimeout(() => {
                        redirectToLogin();
                    }, 1500);
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

        // Call fetchCartCount when page loads to initialize cart count
        document.addEventListener('DOMContentLoaded', function() {
            fetchCartCount();
        });

        function buyNow(productId) {
            window.location.href = "#" + productId;
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const icon = toast.querySelector('i');
            
            toastMessage.innerText = message;
            
            if (type === 'error') {
                icon.className = 'fas fa-exclamation-circle text-red-400 mr-2';
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

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
        // Ensure modal is centered on window resize
        window.addEventListener('resize', function() {
            const modal = document.getElementById('productModal');
            if (modal.style.display === 'flex') {
                // Re-center modal (already centered by CSS, this just ensures)
                modal.style.display = 'flex';
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
        
        /* Keep navbar/header also blurred */
        body.modal-blur header,
        body.modal-blur footer,
        body.modal-blur .navbar,
        body.modal-blur .top-bar {
            filter: blur(8px);
            -webkit-filter: blur(8px);
            transition: filter 0.3s ease;
            pointer-events: none;
        }
        
        /* Modal Container - covers entire screen but allows scrolling to find modal */

        /* #productModal .bg-white {
            animation: slideUp 0.3s ease-out;
        } */
        
        /* Modal background styling */
        #productModal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4) !important;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            /* transition: all 0.3s ease; */
            z-index: 9999;
            display: none;
            overflow-y: auto;
            padding: 20px;
        }
        
        /* Modal Content - positioned at top but with better visibility */
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
        }
        
        /* Animation - Slide down with bounce effect */
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
        
        /* Animation - Fade in for backdrop */
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
        
        /* Modal header sticky */
        .modal-responsive .sticky {
            background: white;
            border-radius: 24px 24px 0 0;
        }
        
        /* Product image hover effect */
        #modalProductImage {
            transition: transform 0.3s ease;
        }
        
        #modalProductImage:hover {
            transform: scale(1.02);
        }
        
        /* Button animations */
        .add-cart-btn, .buy-now-btn {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .add-cart-btn:active, .buy-now-btn:active {
            transform: scale(0.97);
        }
        
        /* Ripple effect for buttons */
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
        
        /* Size button animations */
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
        
        /* Quantity button animations */
        .quantity-btn {
            transition: all 0.2s ease;
        }
        
        .quantity-btn:active {
            transform: scale(0.9);
        }
        
        /* Close button animation */
        button[onclick="closeModal()"]:hover i {
            transform: rotate(90deg);
        }
        
        button[onclick="closeModal()"] i {
            transition: transform 0.3s ease;
        }
        
        /* Loading animation for add to cart */
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
        
        /* Toast notification animation */
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
        
        /* Responsive adjustments */
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
        
        /* Mobile specific */
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
        
        /* Active Size Button */
        .active-size {
            border-color: black !important;
            background-color: black !important;
            color: white !important;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Modal content scrollbar */
        .modal-responsive {
            scrollbar-width: thin;
            max-height: 85vh;
            overflow-y: auto;
        }
        
        .modal-responsive::-webkit-scrollbar {
            width: 6px;
        }
        
        .modal-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .modal-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
            transition: background 0.3s;
        }
        
        .modal-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Line clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Smooth transitions */
        main, header, footer, .navbar {
            transition: filter 0.3s ease;
        }
        
        /* Product card hover effect remains */
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Pulse animation for modal open indicator */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
        
        /* Add a subtle indicator that modal is open */
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
    </style>
@endsection