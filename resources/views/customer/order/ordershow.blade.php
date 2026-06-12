@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('orders.auth.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-black transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
                <span>Back to My Orders</span>
            </a>
        </div>

        <!-- Order Header -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    @php $statusBadge = $order->status_badge; @endphp
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $statusBadge['class'] }}">
                        <i class="fas {{ $statusBadge['icon'] }} mr-2"></i>
                        {{ $statusBadge['text'] }}
                    </span>
                    @if($order->canCancel())
                    <button onclick="cancelOrder({{ $order->id }})" class="px-4 py-1.5 border border-red-500 text-red-500 rounded-full text-sm font-medium hover:bg-red-50 transition">
                        Cancel Order
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Column - Order Items -->
            <div class="flex-1 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-box text-gray-400"></i>
                        Order Items
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            @php
                                $imagePath = null;
                                
                                // PRIORITY 1: Check if variation has its own image
                                if ($item->variation && $item->variation->variant_image && $item->variation->variant_image !== 'null' && $item->variation->variant_image !== '') {
                                    $variantImage = $item->variation->variant_image;
                                    
                                    if (strpos($variantImage, 'product-variations/') !== false) {
                                        $imagePath = asset('storage/' . $variantImage);
                                    } elseif (strpos($variantImage, 'storage/product-variations/') !== false) {
                                        $imagePath = asset($variantImage);
                                    } elseif (!strpos($variantImage, '/')) {
                                        $imagePath = asset('storage/product-variations/' . $variantImage);
                                    } else {
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
                                        
                                        if (strpos($firstImage, 'products/') !== false) {
                                            $imagePath = asset('storage/' . $firstImage);
                                        } elseif (strpos($firstImage, 'storage/products/') !== false) {
                                            $imagePath = asset($firstImage);
                                        } elseif (!strpos($firstImage, '/')) {
                                            $imagePath = asset('storage/products/' . $firstImage);
                                        } else {
                                            $imagePath = asset('storage/' . $firstImage);
                                        }
                                    }
                                }
                                if (!$imagePath) {
                                    $imagePath = 'https://images.unsplash.com/photo-1534030347209-467a5b0ad3e6?w=80&h=80&fit=crop';
                                }
                            @endphp
                            
                            <!-- Desktop Layout (hidden on mobile) -->
                            <div class="hidden md:flex gap-4 py-3 border-b border-gray-100 last:border-0">
                                <img src="{{ $imagePath }}" class="w-20 h-20 rounded-xl object-cover" alt="{{ $item->product_name }}">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $item->product_name }}</h4>
                                    @if($item->variation_name)
                                        <p class="text-sm text-gray-500 mt-0.5">{{ $item->variation_name }}</p>
                                    @endif
                                    @if($item->special_instructions)
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-pen mr-1"></i> Note: {{ $item->special_instructions }}
                                        </p>
                                    @endif
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</span>
                                            <span class="text-gray-300">|</span>
                                            <span class="text-sm text-gray-600">₱{{ number_format($item->price, 2) }} each</span>
                                        </div>
                                        <p class="font-bold text-gray-900">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mobile Layout (visible only on mobile) -->
                            <div class="md:hidden bg-gray-50 rounded-xl p-3 mb-3 last:mb-0">
                                <!-- Top row: Image and Product Name -->
                                <div class="flex gap-3">
                                    <img src="{{ $imagePath }}" class="w-16 h-16 rounded-lg object-cover" alt="{{ $item->product_name }}">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 text-sm">{{ $item->product_name }}</h4>
                                        @if($item->variation_name)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->variation_name }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Special Instructions -->
                                @if($item->special_instructions)
                                    <div class="mt-2 bg-yellow-50 rounded-lg p-2">
                                        <p class="text-xs text-yellow-700">
                                            <i class="fas fa-pen mr-1"></i> Note: {{ $item->special_instructions }}
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Price and Quantity Row -->
                                <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-200">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Price per item</span>
                                        <span class="text-sm font-semibold text-gray-800">₱{{ number_format($item->price, 2) }}</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs text-gray-500">Quantity</span>
                                        <span class="text-sm font-semibold text-gray-800">{{ $item->quantity }}</span>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="text-xs text-gray-500">Total</span>
                                        <span class="text-base font-bold text-gray-900">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Timeline / Status Tracker -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-line text-gray-400"></i>
                        Order Status
                    </h3>
                    
                    <div class="relative">
                        <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        
                        <div class="space-y-6 relative">
                            <!-- Order Placed -->
                            <div class="flex gap-4">
                                <div class="relative z-10">
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center">
                                        <i class="fas fa-check text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 pb-4">
                                    <h4 class="font-semibold text-gray-900">Order Placed</h4>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Your order has been received</p>
                                </div>
                            </div>
                            
                            <!-- Accepted -->
                            <div class="flex gap-4">
                                <div class="relative z-10">
                                    <div class="w-10 h-10 rounded-full {{ $order->accepted_at ? 'bg-green-500' : 'bg-gray-200' }} flex items-center justify-center">
                                        <i class="fas {{ $order->accepted_at ? 'fa-check' : 'fa-clock' }} text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 pb-4">
                                    <h4 class="font-semibold {{ $order->accepted_at ? 'text-gray-900' : 'text-gray-400' }}">Order Accepted</h4>
                                    @if($order->accepted_at)
                                        <p class="text-sm text-gray-500">{{ $order->accepted_at->format('F d, Y \a\t h:i A') }}</p>
                                    @else
                                        <p class="text-sm text-gray-400">Waiting for restaurant to accept</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Preparing / Ready -->
                            <div class="flex gap-4">
                                <div class="relative z-10">
                                    <div class="w-10 h-10 rounded-full {{ $order->ready_at ? 'bg-green-500' : 'bg-gray-200' }} flex items-center justify-center">
                                        <i class="fas {{ $order->ready_at ? 'fa-check' : 'fa-utensils' }} text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 pb-4">
                                    <h4 class="font-semibold {{ $order->ready_at ? 'text-gray-900' : 'text-gray-400' }}">
                                        @if($order->delivery_method === 'pickup')
                                            Ready for Pickup
                                        @else
                                            Ready for Delivery
                                        @endif
                                    </h4>
                                    @if($order->ready_at)
                                        <p class="text-sm text-gray-500">{{ $order->ready_at->format('F d, Y \a\t h:i A') }}</p>
                                    @else
                                        <p class="text-sm text-gray-400">Being prepared</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Delivered / Completed -->
                            <div class="flex gap-4">
                                <div class="relative z-10">
                                    <div class="w-10 h-10 rounded-full {{ $order->delivered_at ? 'bg-green-500' : 'bg-gray-200' }} flex items-center justify-center">
                                        <i class="fas {{ $order->delivered_at ? 'fa-check-double' : 'fa-truck' }} text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold {{ $order->delivered_at ? 'text-gray-900' : 'text-gray-400' }}">
                                        @if($order->delivery_method === 'pickup')
                                            Order Picked Up
                                        @else
                                            Order Delivered
                                        @endif
                                    </h4>
                                    @if($order->delivered_at)
                                        <p class="text-sm text-gray-500">{{ $order->delivered_at->format('F d, Y \a\t h:i A') }}</p>
                                    @elseif($order->status === 'cancelled')
                                        <p class="text-sm text-red-500">Order Cancelled</p>
                                    @else
                                        <p class="text-sm text-gray-400">In progress</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary & Details -->
            <div class="lg:w-96 space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 sticky top-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">Order Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Delivery Fee</span>
                            <span class="{{ $order->delivery_fee > 0 ? 'text-gray-900' : 'text-green-600' }}">
                                @if($order->delivery_fee > 0)
                                    ₱{{ number_format($order->delivery_fee, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax (12% VAT)</span>
                            <span>₱{{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600 border-t border-gray-200 pt-3">
                            <span>Discount</span>
                            <span>-₱{{ number_format($order->discount, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-gray-900 pt-2">
                            <span>Total</span>
                            <span>₱{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Delivery / Pickup Information -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                        {{ $order->delivery_method === 'pickup' ? 'Pickup Information' : 'Delivery Information' }}
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Method:</span>
                            <span class="font-medium text-gray-900">
                                {{ $order->delivery_method === 'pickup' ? 'Store Pickup' : 'Home Delivery' }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Customer:</span>
                            <span class="font-medium text-gray-900">{{ $order->customer_name }}</span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Phone:</span>
                            <span class="font-medium text-gray-900">{{ $order->customer_phone }}</span>
                        </div>
                        
                        @if($order->delivery_method === 'delivery' && $order->delivery_address)
                        <div class="pt-2 border-t border-gray-100">
                            <span class="text-gray-500 text-sm block mb-1">Delivery Address:</span>
                            <p class="text-gray-900 text-sm">{{ $order->delivery_address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-gray-400"></i>
                        Payment Information
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Method:</span>
                            <span class="font-medium text-gray-900">
                                @php $paymentBadge = $order->payment_method_badge; @endphp
                                <i class="fas {{ $paymentBadge['icon'] }} mr-1"></i>
                                {{ $paymentBadge['text'] }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Status:</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->notes)
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <h3 class="font-bold text-lg text-gray-900 mb-2 flex items-center gap-2">
                        <i class="fas fa-pen text-gray-400"></i>
                        Order Notes
                    </h3>
                    <p class="text-gray-600 text-sm">{{ $order->notes }}</p>
                </div>
                @endif

                <!-- Reorder Button -->
                @if($order->status === 'delivered')
                <button onclick="reorder({{ $order->id }})" class="w-full bg-black text-white py-3 rounded-full font-semibold hover:bg-gray-800 transition-all duration-200">
                    <i class="fas fa-redo-alt mr-2"></i>
                    Reorder Items
                </button>
                @endif
            </div>
        </div>
    </main>

    <script>
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

        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                const cancelUrl = "{{ route('myorder.cancel', '') }}/" + orderId;
                
                fetch(cancelUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Order cancelled successfully', 'success');
                        setTimeout(() => {
                            window.location.href = "{{ route('orders.auth.index') }}";
                        }, 1500);
                    } else {
                        showToast(data.message || 'Failed to cancel order', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Network error. Please try again.', 'error');
                });
            }
        }

        function reorder(orderId) {
            const reorderUrl = "{{ route('myorder.reorder', '') }}/" + orderId;
            
            fetch(reorderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Items added to cart!', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('cart.auth.index') }}";
                    }, 1500);
                } else {
                    showToast(data.message || 'Failed to reorder', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error. Please try again.', 'error');
            });
        }
    </script>

    <style>
        .page-fade {
            animation: fadeSlideUp 0.35s ease-out;
        }
        
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection