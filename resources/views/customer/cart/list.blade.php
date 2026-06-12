@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade">
        <div class="mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Shopping Cart</h1>
            <p class="text-sm text-gray-500 mt-1">Select items to checkout</p>
        </div>

        <div id="toast" class="fixed top-20 md:top-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-2.5 rounded-full shadow-lg z-50 transition-all duration-300 opacity-0 pointer-events-none text-sm whitespace-nowrap">
            <i class="fas" id="toastIcon"></i>
            <span id="toastMessage"></span>
        </div>

        @if($cartItems->isEmpty())
            <div class="text-center py-12 md:py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-shopping-cart text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-gray-500 mb-6">Looks like you haven't added anything to your cart yet</p>
                <a href="{{ route('dashboard.auth.items') }}" class="inline-flex items-center gap-2 bg-black text-white px-6 py-3 rounded-full font-semibold hover:bg-gray-800 transition-all duration-200">
                    <i class="fas fa-arrow-left text-sm"></i>
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex-1 space-y-3">
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="selectAll" class="w-5 h-5 rounded border-gray-300 text-black focus:ring-black">
                            <span class="font-medium text-gray-700">Select All Items</span>
                            <span class="text-sm text-gray-500" id="selectedCount">(0 items selected)</span>
                        </label>
                    </div>

                    <div class="hidden md:grid md:grid-cols-6 gap-4 px-4 py-2 text-sm text-gray-500 border-b border-gray-200">
                        <div class="w-12">Select</div>
                        <div class="col-span-2">Product</div>
                        <div class="text-center">Price</div>
                        <div class="text-center">Quantity</div>
                        <div class="text-right">Total</div>
                    </div>

                    @foreach($cartItems as $item)
                        @php
                            $itemTotal = $item->price * $item->quantity;
                            $itemPrice = $item->price;
                            $itemQuantity = $item->quantity;
                            $itemId = $item->id;
                            
                            $imagePath = null;
                            
                            // PRIORITY 1: Check if variation has its own image
                            if ($item->variation && $item->variation->variant_image && $item->variation->variant_image !== 'null' && $item->variation->variant_image !== '') {
                                $variantImage = $item->variation->variant_image;
                                
                                // Check if image exists in product-variations folder
                                if (strpos($variantImage, 'product-variations/') !== false) {
                                    $imagePath = asset('storage/' . $variantImage);
                                } 
                                // Check if image exists in storage/product-variations
                                else if (strpos($variantImage, 'storage/product-variations/') !== false) {
                                    $imagePath = asset($variantImage);
                                }
                                // If it's just the filename without path
                                else if (!strpos($variantImage, '/')) {
                                    $imagePath = asset('storage/product-variations/' . $variantImage);
                                }
                                // Default for other formats
                                else {
                                    $imagePath = asset('storage/' . $variantImage);
                                }
                            }
                            
                            // PRIORITY 2: If no variation image, use product's main image
                            if (!$imagePath && $item->product && $item->product->prdctimage) {
                                $images = $item->product->prdctimage;
                                if (is_string($images)) {
                                    $images = json_decode($images, true);
                                }
                                
                                if (is_array($images) && count($images) > 0) {
                                    $firstImage = $images[0];
                                    
                                    // Check if image is in products folder
                                    if (strpos($firstImage, 'products/') !== false) {
                                        $imagePath = asset('storage/' . $firstImage);
                                    } 
                                    // Check if image is in storage/products
                                    else if (strpos($firstImage, 'storage/products/') !== false) {
                                        $imagePath = asset($firstImage);
                                    }
                                    // If it's just the filename without path
                                    else if (!strpos($firstImage, '/')) {
                                        $imagePath = asset('storage/products/' . $firstImage);
                                    }
                                    // Default for other formats
                                    else {
                                        $imagePath = asset('storage/' . $firstImage);
                                    }
                                }
                            }
                            
                            // PRIORITY 3: Fallback placeholder image
                            if (!$imagePath) {
                                $imagePath = 'https://images.unsplash.com/photo-1534030347209-467a5b0ad3e6?w=120&h=120&fit=crop';
                            }
                            
                            // Get variation display text
                            $variationDisplay = '';
                            if ($item->variation) {
                                $variationDisplay = $item->variation->variation_name . ': ' . $item->variation->variation_value;
                            }
                        @endphp
                        
                        <div class="cart-item bg-white rounded-2xl border border-gray-200 p-5 shadow-xs hover:shadow-md transition-all duration-200" data-id="{{ $itemId }}" data-price="{{ $itemPrice }}" data-quantity="{{ $itemQuantity }}">
                            <!-- Mobile Layout -->
                            <div class="block md:hidden">
                                <!-- Top row: Checkbox + Image + Product Name -->
                                <div class="flex gap-3">
                                    <div class="flex items-start">
                                        <input type="checkbox" class="item-checkbox w-5 h-5 rounded border-gray-300 mt-2" data-id="{{ $itemId }}">
                                    </div>
                                    <img src="{{ $imagePath }}" class="w-20 h-20 rounded-lg object-cover" alt="{{ $item->product->prdctname ?? 'Product' }}">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 text-base">
                                            {{ Str::limit($item->product->prdctname ?? 'Product', 12) }}
                                        </h3>
                                        <p class="text-gray-600 text-sm mt-1">₱{{ number_format($itemPrice, 2) }}</p>
                                    </div>
                                </div>
                                
                                <!-- Bottom row: Quantity controls + Item Total + Remove -->
                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <button class="cart-dec w-8 h-8 rounded-full border border-gray-300 bg-white hover:bg-gray-50 active:scale-95 transition-all duration-150 flex items-center justify-center text-gray-600" 
                                                data-id="{{ $itemId }}">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span class="w-8 text-center font-medium quantity-display text-gray-800">{{ $itemQuantity }}</span>
                                        <button class="cart-inc w-8 h-8 rounded-full border border-gray-300 bg-white hover:bg-gray-50 active:scale-95 transition-all duration-150 flex items-center justify-center text-gray-600" 
                                                data-id="{{ $itemId }}">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Item Total - Prominently displayed here -->
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 text-lg item-total-mobile">₱{{ number_format($itemTotal, 2) }}</p>
                                    </div>
                                    
                                    <button class="cart-remove text-red-500 text-sm hover:text-red-700 transition-colors" data-id="{{ $itemId }}" data-name="{{ $item->product->prdctname ?? 'Product' }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Desktop Layout -->
                            <div class="hidden md:flex md:items-center md:gap-4">
                                <div class="w-12">
                                    <input type="checkbox" class="item-checkbox-desktop w-5 h-5 rounded border-gray-300" data-id="{{ $itemId }}">
                                </div>
                                <div class="flex-shrink-0 w-24">
                                    <img src="{{ $imagePath }}" class="w-20 h-20 rounded-lg object-cover" alt="{{ $item->product->prdctname ?? 'Product' }}">
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $item->product->prdctname ?? 'Product' }}</h3>
                                </div>
                                <div class="w-24 text-center">
                                    <p class="font-semibold text-gray-900">₱{{ number_format($itemPrice, 2) }}</p>
                                </div>
                                <div class="w-32">
                                    <div class="flex items-center gap-3">
                                        <button class="cart-decdesktop w-8 h-8 rounded-full border border-gray-300 bg-white hover:bg-gray-50 flex items-center justify-center" data-id="{{ $itemId }}">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span class="w-8 text-center font-medium quantity-display-desktop">{{ $itemQuantity }}</span>
                                        <button class="cart-incdesktop w-8 h-8 rounded-full border border-gray-300 bg-white hover:bg-gray-50 flex items-center justify-center" data-id="{{ $itemId }}">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="w-24 text-right">
                                    <p class="font-bold text-gray-900 item-total-desktop">₱{{ number_format($itemTotal, 2) }}</p>
                                </div>
                                <div class="w-12 text-right">
                                    <button class="cart-remove text-red-500 hover:text-red-700" data-id="{{ $itemId }}" data-name="{{ $item->product->prdctname ?? 'Product' }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Order Summary Sidebar -->
                <div class="lg:w-96">
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs sticky top-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-4">Order Summary</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Selected Subtotal</span>
                                <span class="font-medium selected-subtotal">₱0.00</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="text-green-600">Free</span>
                            </div>
                            <div class="flex justify-between text-gray-600 border-b border-gray-200 pb-3">
                                <span>Tax (12% VAT)</span>
                                <span class="font-medium selected-tax">₱0.00</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg text-gray-900 pt-2">
                                <span>Selected Total</span>
                                <span class="selected-total">₱0.00</span>
                            </div>
                        </div>
                        
                        <form id="checkoutForm" action="{{ route('cart.checkout') }}" method="get">
                            @csrf
                            <input type="hidden" name="selected_items" id="selectedItemsInput">
                            <button type="submit" id="checkoutBtn" class="w-full bg-black text-white text-center py-3 rounded-full mt-6 font-semibold hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                Proceed to Checkout →
                            </button>
                        </form>
                        
                        <a href="{{ route('dashboard.auth.items') }}" class="block text-center text-sm text-gray-500 hover:text-black transition-colors mt-4">
                            ← Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <script>
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toastIcon');
            const toastMessage = document.getElementById('toastMessage');
            if (!toast) return;
            
            toastIcon.className = type === 'success' ? 'fas fa-check-circle text-green-400 mr-2' : 
                                 (type === 'error' ? 'fas fa-exclamation-circle text-red-400 mr-2' : 'fas fa-info-circle text-blue-400 mr-2');
            toastMessage.innerText = message;
            toast.classList.remove('opacity-0', 'pointer-events-none');
            toast.classList.add('opacity-100');
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 3000);
        }
        
        // Update the order summary based on selected checkboxes
        function updateOrderSummary() {
            let subtotal = 0;
            let selectedIds = [];
            
            // IMPORTANT: Use a Set to track unique cart IDs to prevent double counting
            let processedIds = new Set();
            
            // Only process ONE set of checkboxes (prefer desktop, fallback to mobile)
            let checkboxes = document.querySelectorAll('.item-checkbox-desktop:checked');
            
            // If no desktop checkboxes found, use mobile
            if (checkboxes.length === 0) {
                checkboxes = document.querySelectorAll('.item-checkbox:checked');
            }
            
            checkboxes.forEach(checkbox => {
                const cartId = checkbox.getAttribute('data-id');
                
                // Skip if already processed
                if (processedIds.has(cartId)) return;
                processedIds.add(cartId);
                
                const cartItem = document.querySelector(`.cart-item[data-id="${cartId}"]`);
                
                if (cartItem) {
                    const price = parseFloat(cartItem.getAttribute('data-price'));
                    const quantity = parseInt(cartItem.getAttribute('data-quantity'));
                    
                    if (!isNaN(price) && !isNaN(quantity)) {
                        const itemTotal = price * quantity;  // This is correct: price × quantity
                        subtotal += itemTotal;
                        selectedIds.push(cartId);
                        
                        console.log(`Item ${cartId}: ${price} × ${quantity} = ${itemTotal}`); // Debug
                    }
                }
            });
            
            const tax = subtotal * 0.12;
            const total = subtotal + tax;
            
            document.querySelector('.selected-subtotal').innerText = `₱${subtotal.toFixed(2)}`;
            document.querySelector('.selected-tax').innerText = `₱${tax.toFixed(2)}`;
            document.querySelector('.selected-total').innerText = `₱${total.toFixed(2)}`;
            document.getElementById('selectedCount').innerText = `(${selectedIds.length} item${selectedIds.length !== 1 ? 's' : ''} selected)`;
            document.getElementById('selectedItemsInput').value = JSON.stringify(selectedIds);
            document.getElementById('checkoutBtn').disabled = selectedIds.length === 0;
        }
        
        // Sync checkboxes between mobile and desktop
        function syncCheckboxes(cartId, isChecked) {
            const mobileCb = document.querySelector(`.item-checkbox[data-id="${cartId}"]`);
            const desktopCb = document.querySelector(`.item-checkbox-desktop[data-id="${cartId}"]`);
            
            // Only update if they are different to prevent infinite loops
            if (mobileCb && mobileCb.checked !== isChecked) {
                mobileCb.checked = isChecked;
            }
            if (desktopCb && desktopCb.checked !== isChecked) {
                desktopCb.checked = isChecked;
            }
        }
        
        // Update UI for a specific cart item
        function updateItemUI(cartId, price, quantity) {
            const cartItem = document.querySelector(`.cart-item[data-id="${cartId}"]`);
            if (!cartItem) return;
            
            // Update data attributes
            cartItem.setAttribute('data-price', price);
            cartItem.setAttribute('data-quantity', quantity);
            
            // Update quantity displays
            const qtyMobile = cartItem.querySelector('.quantity-display');
            const qtyDesktop = cartItem.querySelector('.quantity-display-desktop');
            if (qtyMobile) qtyMobile.innerText = quantity;
            if (qtyDesktop) qtyDesktop.innerText = quantity;
            
            // Update item total displays
            const totalMobile = cartItem.querySelector('.item-total-mobile');
            const totalDesktop = cartItem.querySelector('.item-total-desktop');
            const itemTotal = price * quantity;
            if (totalMobile) totalMobile.innerText = `₱${itemTotal.toFixed(2)}`;
            if (totalDesktop) totalDesktop.innerText = `₱${itemTotal.toFixed(2)}`;
            
            // Update checkboxes' data attributes
            const mobileCb = document.querySelector(`.item-checkbox[data-id="${cartId}"]`);
            const desktopCb = document.querySelector(`.item-checkbox-desktop[data-id="${cartId}"]`);
            if (mobileCb) {
                mobileCb.setAttribute('data-price', price);
                mobileCb.setAttribute('data-quantity', quantity);
            }
            if (desktopCb) {
                desktopCb.setAttribute('data-price', price);
                desktopCb.setAttribute('data-quantity', quantity);
            }
        }
        
        // Update quantity via AJAX
        async function updateQuantity(cartId, newQuantity, button) {
            if (button.disabled) return;
            button.disabled = true;
            
            // Store current selection state
            const wasSelected = document.querySelector(`.item-checkbox[data-id="${cartId}"]`)?.checked || 
                                document.querySelector(`.item-checkbox-desktop[data-id="${cartId}"]`)?.checked;
            
            try {
                const response = await fetch("{{ route('cart.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ cart_id: cartId, quantity: newQuantity })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update UI with server data
                    updateItemUI(cartId, data.price, data.quantity);
                    
                    // Restore selection if it was selected
                    if (wasSelected) {
                        syncCheckboxes(cartId, true);
                    }
                    
                    // Update order summary
                    updateOrderSummary();
                    showToast('Cart updated!', 'success');
                } else {
                    showToast(data.message || 'Update failed', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Network error. Please try again.', 'error');
            } finally {
                button.disabled = false;
            }
        }
        
        // Remove item from cart
        async function removeItem(cartId, button) {
            const cartItem = document.querySelector(`.cart-item[data-id="${cartId}"]`);
            if (!cartItem) return;
            
            cartItem.style.transition = 'all 0.3s ease';
            cartItem.style.opacity = '0';
            cartItem.style.transform = 'translateX(-20px)';
            
            try {
                const response = await fetch("{{ route('cart.remove') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ cart_id: cartId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    setTimeout(() => {
                        cartItem.remove();
                        updateOrderSummary();
                        
                        // Update select all state
                        const allCheckboxes = document.querySelectorAll('.item-checkbox, .item-checkbox-desktop');
                        const selectAll = document.getElementById('selectAll');
                        if (selectAll && allCheckboxes.length > 0) {
                            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked, .item-checkbox-desktop:checked');
                            selectAll.checked = allCheckboxes.length === checkedBoxes.length;
                        } else if (selectAll) {
                            selectAll.checked = false;
                        }
                        
                        // Update cart count badges
                        const cartCount = document.getElementById('cartCount');
                        const mobileBadge = document.getElementById('mobileCartBadge');
                        if (cartCount) cartCount.innerText = data.cart_count;
                        if (mobileBadge) mobileBadge.innerText = data.cart_count;
                        
                        showToast('Item removed from cart', 'success');
                        
                        if (data.cart_count === 0) {
                            setTimeout(() => location.reload(), 1000);
                        }
                    }, 300);
                } else {
                    cartItem.style.opacity = '1';
                    cartItem.style.transform = 'translateX(0)';
                    showToast(data.message || 'Failed to remove item', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                cartItem.style.opacity = '1';
                cartItem.style.transform = 'translateX(0)';
                showToast('Error removing item', 'error');
            }
        }
        
        // Confirm dialog for removal
        function showConfirmDialog(message, onConfirm) {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm';
            
            const modal = document.createElement('div');
            modal.className = 'bg-white rounded-2xl p-6 max-w-sm w-full mx-4 transform transition-all';
            modal.innerHTML = `
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Remove Item</h3>
                    <p class="text-gray-600 mb-6">${message}</p>
                    <div class="flex gap-3">
                        <button class="flex-1 px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 cancel-btn">Cancel</button>
                        <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 confirm-btn">Remove</button>
                    </div>
                </div>
            `;
            
            overlay.appendChild(modal);
            document.body.appendChild(overlay);
            document.body.style.overflow = 'hidden';
            
            modal.querySelector('.cancel-btn').onclick = () => {
                document.body.style.overflow = '';
                overlay.remove();
            };
            modal.querySelector('.confirm-btn').onclick = () => {
                document.body.style.overflow = '';
                overlay.remove();
                onConfirm();
            };
            overlay.onclick = (e) => {
                if (e.target === overlay) {
                    document.body.style.overflow = '';
                    overlay.remove();
                }
            };
        }
        
        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Select All
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    document.querySelectorAll('.item-checkbox, .item-checkbox-desktop').forEach(cb => {
                        cb.checked = isChecked;
                    });
                    updateOrderSummary();
                });
            }
            
            // Individual checkboxes
            document.querySelectorAll('.item-checkbox, .item-checkbox-desktop').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const cartId = this.getAttribute('data-id');
                    syncCheckboxes(cartId, this.checked);
                    updateOrderSummary();
                    
                    // Update select all state
                    const allCheckboxes = document.querySelectorAll('.item-checkbox, .item-checkbox-desktop');
                    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked, .item-checkbox-desktop:checked');
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = allCheckboxes.length === checkedBoxes.length && allCheckboxes.length > 0;
                    }
                });
            });
            
            // Decrement buttons
            document.querySelectorAll('.cart-dec, .cart-decdesktop').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cartId = this.getAttribute('data-id');
                    const cartItem = document.querySelector(`.cart-item[data-id="${cartId}"]`);
                    if (cartItem) {
                        let currentQty = parseInt(cartItem.getAttribute('data-quantity'));
                        if (currentQty > 1) {
                            updateQuantity(cartId, currentQty - 1, this);
                        } else if (currentQty === 1) {
                            const productName = this.closest('.cart-item')?.querySelector('.cart-remove')?.getAttribute('data-name') || 'item';
                            showConfirmDialog(`Remove "${productName}" from your cart?`, () => {
                                removeItem(cartId, this);
                            });
                        }
                    }
                });
            });
            
            // Increment buttons
            document.querySelectorAll('.cart-inc, .cart-incdesktop').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cartId = this.getAttribute('data-id');
                    const cartItem = document.querySelector(`.cart-item[data-id="${cartId}"]`);
                    if (cartItem) {
                        let currentQty = parseInt(cartItem.getAttribute('data-quantity'));
                        updateQuantity(cartId, currentQty + 1, this);
                    }
                });
            });
            
            // Remove buttons
            document.querySelectorAll('.cart-remove').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cartId = this.getAttribute('data-id');
                    const productName = this.getAttribute('data-name') || 'this item';
                    showConfirmDialog(`Are you sure you want to remove "${productName}" from your cart?`, () => {
                        removeItem(cartId, this);
                    });
                });
            });
            
            // Initial summary calculation
            updateOrderSummary();
        });
    </script>

    <style>
        button:disabled { opacity: 0.6; cursor: not-allowed; }
        .cart-dec, .cart-inc, .cart-decdesktop, .cart-incdesktop { cursor: pointer; transition: transform 0.1s; }
        .cart-dec:active, .cart-inc:active, .cart-decdesktop:active, .cart-incdesktop:active { transform: scale(0.95); }
    </style>
@endsection