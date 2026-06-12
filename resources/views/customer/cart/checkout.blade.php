@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade">
        <!-- Page Title -->
        <div class="mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Checkout</h1>
            <p class="text-sm text-gray-500 mt-1">Complete your order</p>
        </div>

        <!-- Toast Notification -->
        <div id="toast" class="fixed top-20 md:top-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-2.5 rounded-full shadow-lg z-50 transition-all duration-300 opacity-0 pointer-events-none text-sm whitespace-nowrap">
            <i class="fas" id="toastIcon"></i>
            <span id="toastMessage"></span>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Checkout Form Section -->
            <div class="flex-1 space-y-5">
                <!-- Delivery Information -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                        Delivery Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="full_name" id="fullName" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-black focus:border-black transition" placeholder="John Doe" value="{{ Auth::guard('customer')->user()->name ?? '' }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" name="phone" id="phone" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-black focus:border-black transition" placeholder="0912 345 6789" value="{{ Auth::guard('customer')->user()->phone ?? '' }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-black focus:border-black transition" placeholder="john@example.com" value="{{ Auth::guard('customer')->user()->email ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Delivery Method -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-truck text-gray-400"></i>
                        Delivery Method
                    </h3>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-black has-[:checked]:bg-gray-50">
                            <input type="radio" name="delivery_method" value="pickup" class="w-4 h-4 text-black focus:ring-black" checked>
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Pickup</span>
                                <p class="text-sm text-gray-500">Pick up your order from the store</p>
                            </div>
                            <i class="fas fa-store text-gray-400 text-xl"></i>
                        </label>
                        
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-black has-[:checked]:bg-gray-50">
                            <input type="radio" name="delivery_method" value="delivery" class="w-4 h-4 text-black focus:ring-black">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">Delivery</span>
                                <p class="text-sm text-gray-500">Delivered to your address</p>
                            </div>
                            <i class="fas fa-truck-fast text-gray-400 text-xl"></i>
                        </label>
                    </div>
                    
                    <!-- Delivery Address (hidden by default) -->
                    <div id="deliveryAddressContainer" class="mt-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Address *</label>
                        <textarea name="delivery_address" id="deliveryAddress" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-black focus:border-black transition" placeholder="House/Unit No., Street, Barangay, City"></textarea>
                        <p class="text-xs text-gray-400 mt-1">We'll deliver to this address</p>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-gray-400"></i>
                        Payment Method
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-black">
                            <input type="radio" name="payment_method" value="cash" class="w-4 h-4 text-black focus:ring-black" checked>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                                <span class="font-medium">Cash on Pickup/Delivery</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-black">
                            <input type="radio" name="payment_method" value="gcash" class="w-4 h-4 text-black focus:ring-black">
                            <div class="flex items-center gap-2">
                                <i class="fab fa-cc-visa text-blue-700 text-xl"></i>
                                <span class="font-medium">GCash / Card</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-pen text-gray-400"></i>
                        Order Notes (Optional)
                    </h3>
                    <textarea name="notes" id="orderNotes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-black focus:border-black transition" placeholder="Special instructions, delivery notes, etc."></textarea>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:w-96">
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs sticky top-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">Order Summary</h3>
                    
                    <!-- Selected Items List -->
                    <div class="max-h-64 overflow-y-auto space-y-3 mb-4 border-b border-gray-100 pb-4">
                        <div id="selectedItemsList" class="space-y-2">
                            <!-- Items will be populated via JavaScript -->
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-medium" id="checkoutSubtotal">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Delivery Fee</span>
                            <span id="deliveryFeeDisplay" class="text-green-600">Free</span>
                        </div>
                        <div class="flex justify-between text-gray-600 border-b border-gray-200 pb-3">
                            <span>Tax (12% VAT)</span>
                            <span class="font-medium" id="checkoutTax">₱0.00</span>
                        </div>
                        <div class="flex justify-between font-bold text-xl text-gray-900 pt-2">
                            <span>Total</span>
                            <span class="text-black" id="checkoutTotal">₱0.00</span>
                        </div>
                    </div>
                    
                    <button id="placeOrderBtn" class="w-full bg-black text-white text-center py-3 rounded-full mt-6 font-semibold hover:bg-gray-800 transition-all duration-200 transform hover:scale-[1.02]">
                        <i class="fas fa-check-circle mr-2"></i>
                        Place Order
                    </button>
                    
                    <a href="{{ route('cart.auth.index') }}" class="block text-center text-sm text-gray-500 hover:text-black transition-colors mt-4">
                        ← Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        let selectedCartItems = [];
        
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
        
        // Load selected items from sessionStorage or passed data
        function loadSelectedItems() {
            const storedItems = sessionStorage.getItem('checkout_items');
            if (storedItems) {
                selectedCartItems = JSON.parse(storedItems);
                renderSelectedItems();
                updateCheckoutTotals();
            } else {
                // Fallback: fetch from server
                fetchSelectedItems();
            }
        }
        
        function fetchSelectedItems() {
            fetch("#", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ selected_items: JSON.parse(document.getElementById('selectedItemsInput')?.value || '[]') })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectedCartItems = data.items;
                    renderSelectedItems();
                    updateCheckoutTotals();
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        function renderSelectedItems() {
            const container = document.getElementById('selectedItemsList');
            if (!container) return;
            
            if (selectedCartItems.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-sm text-center py-4">No items selected</p>';
                return;
            }
            
            container.innerHTML = selectedCartItems.map(item => `
                <div class="flex justify-between items-start text-sm">
                    <div class="flex-1">
                        <span class="font-medium text-gray-800">${item.name}</span>
                        ${item.variation ? `<p class="text-xs text-gray-400">${item.variation}</p>` : ''}
                        <p class="text-xs text-gray-500">Qty: ${item.quantity}</p>
                    </div>
                    <span class="font-semibold text-gray-900">₱${(item.price * item.quantity).toFixed(2)}</span>
                </div>
            `).join('');
        }
        
        function updateCheckoutTotals() {
            let subtotal = selectedCartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.12;
            const total = subtotal + tax;
            
            document.getElementById('checkoutSubtotal').innerText = `₱${subtotal.toFixed(2)}`;
            document.getElementById('checkoutTax').innerText = `₱${tax.toFixed(2)}`;
            document.getElementById('checkoutTotal').innerText = `₱${total.toFixed(2)}`;
        }
        
        // Toggle delivery address based on selected method
        function setupDeliveryMethodToggle() {
            const pickupRadio = document.querySelector('input[value="pickup"]');
            const deliveryRadio = document.querySelector('input[value="delivery"]');
            const addressContainer = document.getElementById('deliveryAddressContainer');
            const deliveryFeeDisplay = document.getElementById('deliveryFeeDisplay');
            
            function updateDeliveryOption() {
                if (deliveryRadio.checked) {
                    addressContainer.classList.remove('hidden');
                    deliveryFeeDisplay.innerHTML = '₱50.00';
                    deliveryFeeDisplay.classList.remove('text-green-600');
                    deliveryFeeDisplay.classList.add('text-gray-900');
                } else {
                    addressContainer.classList.add('hidden');
                    deliveryFeeDisplay.innerHTML = 'Free';
                    deliveryFeeDisplay.classList.remove('text-gray-900');
                    deliveryFeeDisplay.classList.add('text-green-600');
                }
                updateTotalWithDelivery();
            }
            
            pickupRadio.addEventListener('change', updateDeliveryOption);
            deliveryRadio.addEventListener('change', updateDeliveryOption);
            updateDeliveryOption();
        }
        
        function updateTotalWithDelivery() {
            const deliveryRadio = document.querySelector('input[value="delivery"]');
            const deliveryFee = deliveryRadio && deliveryRadio.checked ? 50 : 0;
            
            let subtotal = selectedCartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.12;
            const total = subtotal + tax + deliveryFee;
            
            document.getElementById('checkoutTotal').innerText = `₱${total.toFixed(2)}`;
        }
        
        // Place Order
        function setupPlaceOrder() {
            const placeBtn = document.getElementById('placeOrderBtn');
            if (!placeBtn) return;
            
            placeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Validate required fields
                const fullName = document.getElementById('fullName')?.value.trim();
                const phone = document.getElementById('phone')?.value.trim();
                const deliveryMethod = document.querySelector('input[name="delivery_method"]:checked')?.value;
                
                if (!fullName) {
                    showToast('Please enter your full name', 'error');
                    return;
                }
                
                if (!phone) {
                    showToast('Please enter your phone number', 'error');
                    return;
                }
                
                if (deliveryMethod === 'delivery') {
                    const address = document.getElementById('deliveryAddress')?.value.trim();
                    if (!address) {
                        showToast('Please enter your delivery address', 'error');
                        return;
                    }
                }
                
                if (selectedCartItems.length === 0) {
                    showToast('No items selected for checkout', 'error');
                    return;
                }
                
                // Disable button to prevent double submission
                placeBtn.disabled = true;
                placeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                
                // Prepare order data
                const orderData = {
                    full_name: fullName,
                    phone: phone,
                    email: document.getElementById('email')?.value.trim(),
                    delivery_method: deliveryMethod,
                    delivery_address: document.getElementById('deliveryAddress')?.value.trim(),
                    payment_method: document.querySelector('input[name="payment_method"]:checked')?.value,
                    notes: document.getElementById('orderNotes')?.value.trim(),
                    items: selectedCartItems.map(item => ({
                        cart_id: item.cart_id,
                        product_id: item.product_id,
                        variation_id: item.variation_id,
                        quantity: item.quantity,
                        price: item.price
                    }))
                };
                
                fetch("#", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Order placed successfully!', 'success');
                        sessionStorage.removeItem('checkout_items');
                        setTimeout(() => {
                            window.location.href = "{{ route('orders.auth.index') }}";
                        }, 1500);
                    } else {
                        showToast(data.message || 'Failed to place order', 'error');
                        placeBtn.disabled = false;
                        placeBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Place Order';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Network error. Please try again.', 'error');
                    placeBtn.disabled = false;
                    placeBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Place Order';
                });
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            loadSelectedItems();
            setupDeliveryMethodToggle();
            setupPlaceOrder();
        });
    </script>
@endsection