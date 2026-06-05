@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade">
        <!-- Page Title -->
        <div class="mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Shopping Cart</h1>
            <p class="text-sm text-gray-500 mt-1">Review and manage your items</p>
        </div>

        <!-- Toast Notification -->
        <div id="toast" class="fixed top-20 md:top-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-2.5 rounded-full shadow-lg z-50 transition-all duration-300 opacity-0 pointer-events-none text-sm whitespace-nowrap">
            <i class="fas" id="toastIcon"></i>
            <span id="toastMessage"></span>
        </div>

        @if($cartItems->isEmpty())
            <!-- Empty Cart State -->
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
                <!-- Cart Items Section -->
                <div class="flex-1 space-y-3">
                    <!-- Desktop Table Header -->
                    <div class="hidden md:grid md:grid-cols-5 gap-4 px-4 py-2 text-sm text-gray-500 border-b border-gray-200">
                        <div class="col-span-2">Product</div>
                        <div class="text-center">Price</div>
                        <div class="text-center">Quantity</div>
                        <div class="text-right">Total</div>
                    </div>

                    @foreach($cartItems as $index => $item)
                        @php
                            $itemTotal = $item->price * $item->quantity;
                            
                            // Handle image array - get first image
                            $imagePath = null;
                            if ($item->product->prdctimage) {
                                $images = $item->product->prdctimage;
                                if (is_string($images)) {
                                    $images = json_decode($images, true);
                                }
                                
                                if (is_array($images) && count($images) > 0) {
                                    $firstImage = $images[0];
                                    if (strpos($firstImage, 'products/') !== false) {
                                        $imagePath = asset('storage/' . $firstImage);
                                    } else {
                                        $imagePath = asset('storage/products/' . $firstImage);
                                    }
                                }
                            }
                            
                            if (!$imagePath) {
                                $imagePath = 'https://images.unsplash.com/photo-1534030347209-467a5b0ad3e6?w=120&h=120&fit=crop';
                            }
                        @endphp
                        
                        <div class="cart-item bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200" data-id="{{ $item->id }}">
                            <!-- Mobile Layout -->
                            <div class="block md:hidden">
                                <div class="flex gap-4">
                                    <img src="{{ $imagePath }}" 
                                         class="w-24 h-24 rounded-lg object-cover" 
                                         alt="{{ $item->product->name ?? 'Product' }}">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $item->product->name ?? 'Product' }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">Size: {{ $item->size ?? 'M' }}</p>
                                        <p class="text-black font-bold mt-2">${{ number_format($item->price, 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 item-total" data-id="{{ $item->id }}">${{ number_format($itemTotal, 2) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <button class="cart-dec w-8 h-8 rounded-full border border-gray-300 bg-white hover:bg-gray-50 active:scale-95 transition-all duration-150 flex items-center justify-center text-gray-600" 
                                                data-id="{{ $item->id }}" 
                                                aria-label="Decrease quantity">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span class="w-8 text-center font-medium quantity-display" data-id="{{ $item->id }}">{{ $item->quantity }}</span>
                                        <button class="cart-inc w-8 h-8 rounded-full border border-gray-300 bg-white hover:bg-gray-50 active:scale-95 transition-all duration-150 flex items-center justify-center text-gray-600" 
                                                data-id="{{ $item->id }}" 
                                                aria-label="Increase quantity">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                    <button class="cart-remove text-red-500 text-sm hover:text-red-700 transition-colors flex items-center gap-1" data-id="{{ $item->id }}" data-name="{{ $item->product->name ?? 'Product' }}">
                                        <i class="fas fa-trash-alt text-xs"></i> Remove
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Desktop/Tablet Layout -->
                            <div class="hidden md:flex md:items-center md:gap-4">
                                <div class="flex-shrink-0 w-24">
                                    <img src="{{ $imagePath }}" 
                                         class="w-20 h-20 rounded-lg object-cover" 
                                         alt="{{ $item->product->name ?? 'Product' }}">
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $item->product->name ?? 'Product' }}</h3>
                                    <p class="text-sm text-gray-500">Size: {{ $item->size ?? 'M' }}</p>
                                </div>
                                <div class="w-24 text-center">
                                    <p class="font-semibold text-gray-900">${{ number_format($item->price, 2) }}</p>
                                </div>
                                <div class="w-32">
                                    <div class="flex items-center gap-3">
                                        <button class="cart-decdesktop w-8 h-8 rounded-full border border-gray-300 bg-white hover:bg-gray-50 active:scale-95 transition-all duration-150 flex items-center justify-center text-gray-600" 
                                                data-id="{{ $item->id }}" 
                                                aria-label="Decrease quantity">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span class="w-8 text-center font-medium quantity-displaydesktop" data-id="{{ $item->id }}">{{ $item->quantity }}</span>
                                        <button class="cart-incdesktop w-8 h-8 rounded-full border border-gray-300 bg-white hover:bg-gray-50 active:scale-95 transition-all duration-150 flex items-center justify-center text-gray-600" 
                                                data-id="{{ $item->id }}" 
                                                aria-label="Increase quantity">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="w-24 text-right">
                                    <p class="font-bold text-gray-900 item-totaldesktop" data-id="{{ $item->id }}">${{ number_format($itemTotal, 2) }}</p>
                                </div>
                                <div class="w-12 text-right">
                                    <button class="cart-remove text-red-500 hover:text-red-700 transition-colors" data-id="{{ $item->id }}" data-name="{{ $item->product->name ?? 'Product' }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Order Summary Sidebar -->
                <div class="lg:w-96">
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 sticky top-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-4">Order Summary</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-medium subtotal-amount">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="text-green-600">Free</span>
                            </div>
                            <div class="flex justify-between text-gray-600 border-b border-gray-200 pb-3">
                                <span>Tax (12% VAT)</span>
                                <span class="font-medium tax-amount">${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg text-gray-900 pt-2">
                                <span>Total</span>
                                <span class="total-amount">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        
                        <a href="#" class="block w-full bg-black text-white text-center py-3 rounded-full mt-6 font-semibold hover:bg-gray-800 transition-all duration-200 transform hover:scale-[1.02]">
                            Proceed to Checkout →
                        </a>
                        
                        <a href="{{ route('dashboard.auth.items') }}" class="block text-center text-sm text-gray-500 hover:text-black transition-colors mt-4">
                            ← Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <script>
        // Show Toast Notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toastIcon');
            const toastMessage = document.getElementById('toastMessage');
            
            if (!toast) return;
            
            if (type === 'success') {
                toastIcon.className = 'fas fa-check-circle text-green-400 mr-2';
            } else if (type === 'error') {
                toastIcon.className = 'fas fa-exclamation-circle text-red-400 mr-2';
            } else if (type === 'info') {
                toastIcon.className = 'fas fa-info-circle text-blue-400 mr-2';
            }
            
            toastMessage.innerText = message;
            toast.classList.remove('opacity-0', 'pointer-events-none');
            toast.classList.add('opacity-100');
            
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 3000);
        }
        
        // Custom confirmation dialog with blur background
        function showConfirmDialog(message, onConfirm) {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 z-50 flex items-center justify-center';
            
            // Create backdrop with blur effect
            overlay.style.animation = 'fadeIn 0.2s ease-out';
            overlay.style.background = 'rgba(0, 0, 0, 0.4)';
            overlay.style.backdropFilter = 'blur(8px)';
            overlay.style.WebkitBackdropFilter = 'blur(8px)'; // For Safari support
            
            const modal = document.createElement('div');
            modal.className = 'bg-white rounded-2xl p-6 max-w-sm w-full mx-4 transform transition-all';
            modal.style.animation = 'scaleIn 0.2s ease-out';
            modal.innerHTML = `
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Remove Item</h3>
                    <p class="text-gray-600 mb-6">${message}</p>
                    <div class="flex gap-3">
                        <button class="flex-1 px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 transition-colors cancel-btn">Cancel</button>
                        <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors confirm-btn">Remove</button>
                    </div>
                </div>
            `;
            
            overlay.appendChild(modal);
            document.body.appendChild(overlay);
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
            
            modal.querySelector('.cancel-btn').addEventListener('click', () => {
                document.body.style.overflow = '';
                overlay.remove();
            });
            
            modal.querySelector('.confirm-btn').addEventListener('click', () => {
                document.body.style.overflow = '';
                overlay.remove();
                onConfirm();
            });
            
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    document.body.style.overflow = '';
                    overlay.remove();
                }
            });
        }
        
        // Update cart quantity instantly (REAL-TIME)
        function updateQuantity(cartId, action, button) {
            // Prevent multiple rapid clicks
            if (button.disabled) return;
            
            // Find the parent cart item
            const cartItem = button.closest('.cart-item');
            if (!cartItem) return;
            
            // Find quantity display and item total elements
            const quantitySpan = cartItem.querySelector(`.quantity-display[data-id="${cartId}"]`);
            const quantitySpanDesktop = cartItem.querySelector(`.quantity-displaydesktop[data-id="${cartId}"]`);
            const itemTotalSpan = cartItem.querySelector(`.item-total[data-id="${cartId}"]`);
            const itemTotalSpanDesktop = cartItem.querySelector(`.item-totaldesktop[data-id="${cartId}"]`);
            
            if (!quantitySpan && !quantitySpanDesktop) return;
            
            // Get current quantity
            let currentQty = parseInt(quantitySpan ? quantitySpan.innerText : quantitySpanDesktop.innerText);
            let newQty = currentQty;
            
            // Determine new quantity based on action
            if (action === 'increment') {
                newQty = currentQty + 1;
            } else if (action === 'decrement') {
                newQty = currentQty - 1;
            }
            
            // If quantity becomes 0, ask for confirmation to remove
            if (newQty < 1) {
                const removeBtn = cartItem.querySelector(`.cart-remove[data-id="${cartId}"]`);
                const productName = removeBtn ? removeBtn.getAttribute('data-name') : 'item';
                showConfirmDialog(`Remove "${productName}" from your cart?`, () => {
                    removeItem(cartId);
                });
                return;
            }
            
            // === REAL-TIME UI UPDATE (Immediate visual feedback) ===
            // Add visual feedback to button
            button.style.transform = 'scale(0.95)';
            setTimeout(() => {
                button.style.transform = '';
            }, 150);
            
            // Update quantity display instantly
            if (quantitySpan) {
                quantitySpan.innerText = newQty;
            }
            if (quantitySpanDesktop) {
                quantitySpanDesktop.innerText = newQty;
            }
            
            // Add highlight effect to quantity
            if (quantitySpan) {
                quantitySpan.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    quantitySpan.style.transform = '';
                }, 200);
            }
            if (quantitySpanDesktop) {
                quantitySpanDesktop.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    quantitySpanDesktop.style.transform = '';
                }, 200);
            }
            
            // Calculate and update item total instantly
            if (itemTotalSpan) {
                // Get price per item
                let pricePerItem = parseFloat(itemTotalSpan.getAttribute('data-price'));
                if (isNaN(pricePerItem)) {
                    // Calculate from current total and quantity
                    const currentTotal = parseFloat(itemTotalSpan.innerText.replace('$', '').replace(',', ''));
                    pricePerItem = currentTotal / currentQty;
                    itemTotalSpan.setAttribute('data-price', pricePerItem);
                }
                
                const newItemTotal = pricePerItem * newQty;
                itemTotalSpan.innerText = `$${newItemTotal.toFixed(2)}`;
            }
            
            if (itemTotalSpanDesktop) {
                // Get price per item
                let pricePerItem = parseFloat(itemTotalSpanDesktop.getAttribute('data-price'));
                if (isNaN(pricePerItem)) {
                    // Calculate from current total and quantity
                    const currentTotal = parseFloat(itemTotalSpanDesktop.innerText.replace('$', '').replace(',', ''));
                    pricePerItem = currentTotal / currentQty;
                    itemTotalSpanDesktop.setAttribute('data-price', pricePerItem);
                }
                
                const newItemTotal = pricePerItem * newQty;
                itemTotalSpanDesktop.innerText = `$${newItemTotal.toFixed(2)}`;
            }
            
            // Update all totals instantly (subtotal, tax, total)
            updateTotalsInstantly();
            
            // Disable button to prevent spam
            button.disabled = true;
            
            // Send update to server in background
            fetch("{{ route('cart.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    quantity: newQty
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Sync with server data to ensure accuracy
                    if (quantitySpan) {
                        quantitySpan.innerText = data.quantity;
                    }
                    
                    if (itemTotalSpan) {
                        itemTotalSpan.innerText = `$${data.item_total.toFixed(2)}`;
                        // Store price per item for future calculations
                        itemTotalSpan.setAttribute('data-price', data.item_total / data.quantity);
                    }
                    
                    // Update summary with server data
                    const subtotalEl = document.querySelector('.subtotal-amount');
                    const taxEl = document.querySelector('.tax-amount');
                    const totalEl = document.querySelector('.total-amount');
                    
                    if (subtotalEl) subtotalEl.innerText = `$${data.subtotal.toFixed(2)}`;
                    if (taxEl) taxEl.innerText = `$${data.tax.toFixed(2)}`;
                    if (totalEl) totalEl.innerText = `$${data.total.toFixed(2)}`;
                    
                    // Update cart count badges
                    const cartCount = document.getElementById('cartCount');
                    const mobileBadge = document.getElementById('mobileCartBadge');
                    if (cartCount) cartCount.innerText = data.cart_count;
                    if (mobileBadge) mobileBadge.innerText = data.cart_count;
                    
                    showToast('Cart updated!', 'success');
                } else {
                    // Revert UI if server update failed
                    quantitySpan.innerText = currentQty;
                    if (itemTotalSpan) {
                        const revertTotal = pricePerItem * currentQty;
                        itemTotalSpan.innerText = `$${revertTotal.toFixed(2)}`;
                    }
                    updateTotalsInstantly();
                    showToast(data.message || 'Update failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert UI on error
                quantitySpan.innerText = currentQty;
                if (itemTotalSpan) {
                    const revertTotal = pricePerItem * currentQty;
                    itemTotalSpan.innerText = `$${revertTotal.toFixed(2)}`;
                }
                updateTotalsInstantly();
                showToast('Network error. Please try again.', 'error');
            })
            .finally(() => {
                // Re-enable button after 400ms
                setTimeout(() => {
                    button.disabled = false;
                }, 400);
            });
        }
        
        // Update subtotal, tax, and total instantly based on current item totals
        function updateTotalsInstantly() {
            let subtotal = 0;
            
            // Sum all item totals
            document.querySelectorAll('.item-total').forEach(itemTotal => {
                const totalText = itemTotal.innerText.replace('$', '').replace(',', '');
                const total = parseFloat(totalText);
                if (!isNaN(total)) {
                    subtotal += total;
                }
            });
            
            document.querySelectorAll('.item-totaldesktop').forEach(itemTotal => {
                const totalText = itemTotal.innerText.replace('$', '').replace(',', '');
                const total = parseFloat(totalText);
                if (!isNaN(total)) {
                    subtotal += total;
                }
            });
            
            // Calculate tax (12%) and total
            const tax = subtotal * 0.12;
            const total = subtotal + tax;
            
            // Update the summary section
            const subtotalEl = document.querySelector('.subtotal-amount');
            const taxEl = document.querySelector('.tax-amount');
            const totalEl = document.querySelector('.total-amount');
            
            if (subtotalEl) subtotalEl.innerText = `$${subtotal.toFixed(2)}`;
            if (taxEl) taxEl.innerText = `$${tax.toFixed(2)}`;
            if (totalEl) totalEl.innerText = `$${total.toFixed(2)}`;
        }
        
        // Remove item from cart
        function removeItem(cartId) {
            const cartItem = document.querySelector(`.cart-item[data-id="${cartId}"]`);
            if (!cartItem) return;
            
            // Add fade out animation
            cartItem.style.transition = 'all 0.3s ease';
            cartItem.style.opacity = '0';
            cartItem.style.transform = 'translateX(-20px)';
            
            fetch("{{ route('cart.remove') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    cart_id: cartId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    setTimeout(() => {
                        cartItem.remove();
                        
                        // Update totals
                        const subtotalEl = document.querySelector('.subtotal-amount');
                        const taxEl = document.querySelector('.tax-amount');
                        const totalEl = document.querySelector('.total-amount');
                        
                        if (subtotalEl) subtotalEl.innerText = `$${data.subtotal.toFixed(2)}`;
                        if (taxEl) taxEl.innerText = `$${data.tax.toFixed(2)}`;
                        if (totalEl) totalEl.innerText = `$${data.total.toFixed(2)}`;
                        
                        // Update cart badges
                        const cartCount = document.getElementById('cartCount');
                        const mobileBadge = document.getElementById('mobileCartBadge');
                        if (cartCount) cartCount.innerText = data.cart_count;
                        if (mobileBadge) mobileBadge.innerText = data.cart_count;
                        
                        showToast('Item removed from cart', 'success');
                        
                        // Reload page if cart becomes empty
                        if (data.cart_count === 0) {
                            setTimeout(() => location.reload(), 1000);
                        }
                    }, 300);
                } else {
                    // Revert animation
                    cartItem.style.opacity = '1';
                    cartItem.style.transform = 'translateX(0)';
                    showToast(data.message || 'Failed to remove item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                cartItem.style.opacity = '1';
                cartItem.style.transform = 'translateX(0)';
                showToast('Error removing item', 'error');
            });
        }
        
        // Initialize all event listeners when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Store price per item for each cart item
            document.querySelectorAll('.cart-item').forEach(item => {
                const itemTotal = item.querySelector('.item-total');
                const itemTotalDesktop = item.querySelector('.item-totaldesktop');
                const quantitySpan = item.querySelector('.quantity-display');
                const quantitySpanDesktop = item.querySelector('.quantity-displaydesktop');

                if (itemTotal && quantitySpan) {
                    const totalText = itemTotal.innerText.replace('$', '').replace(',', '');
                    const total = parseFloat(totalText);
                    const quantity = parseInt(quantitySpan.innerText);
                    
                    if (!isNaN(total) && !isNaN(quantity) && quantity > 0) {
                        const pricePerItem = total / quantity;
                        itemTotal.setAttribute('data-price', pricePerItem);
                    }
                }
            });
            
            // Handle decrement buttons (minus)
            document.querySelectorAll('.cart-dec, .cart-decdesktop').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cartId = this.getAttribute('data-id');
                    updateQuantity(cartId, 'decrement', this);
                });
            });
            
            // Handle increment buttons (plus)
            document.querySelectorAll('.cart-inc, .cart-incdesktop').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cartId = this.getAttribute('data-id');
                    updateQuantity(cartId, 'increment', this);
                });
            });
            
            // Handle remove buttons
            document.querySelectorAll('.cart-remove').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cartId = this.getAttribute('data-id');
                    const productName = this.getAttribute('data-name') || 'this item';
                    showConfirmDialog(`Are you sure you want to remove "${productName}" from your cart?`, () => {
                        removeItem(cartId);
                    });
                });
            });
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Optional: For a more subtle blur effect on different browsers */
        @supports (backdrop-filter: blur(8px)) {
            .modal-overlay {
                background: rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(8px);
            }
        }
        
        /* Fallback for browsers that don't support backdrop-filter */
        @supports not (backdrop-filter: blur(8px)) {
            .modal-overlay {
                background: rgba(0, 0, 0, 0.7);
            }
        }
        
        button:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .quantity-display {
            transition: transform 0.2s ease;
            display: inline-block;
        }
        .quantity-displaydesktop {
            transition: transform 0.2s ease;
            display: inline-block;
        }
        
        .cart-dec, .cart-inc {
            transition: transform 0.1s ease;
            cursor: pointer;
        }

        .cart-decdesktop, .cart-incdesktop {
            transition: transform 0.1s ease;
            cursor: pointer;
        }
        
        .cart-dec:active, .cart-inc:active {
            transform: scale(0.95);
        }
    </style>
@endsection