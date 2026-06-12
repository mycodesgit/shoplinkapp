@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade">
        <!-- Page Title -->
        <div class="mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">My Orders</h1>
            <p class="text-sm text-gray-500 mt-1">Review and track your orders</p>
        </div>

        <!-- Toast Notification -->
        <div id="toast" class="fixed top-20 md:top-6 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-5 py-2.5 rounded-full shadow-lg z-50 transition-all duration-300 opacity-0 pointer-events-none text-sm whitespace-nowrap">
            <i class="fas" id="toastIcon"></i>
            <span id="toastMessage"></span>
        </div>

        @if($orders->isEmpty())
            <!-- Empty Orders State -->
            <div class="text-center py-12 md:py-20 bg-white rounded-2xl border border-gray-200">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-shopping-bag text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
                <p class="text-gray-500 mb-6">Looks like you haven't placed any orders yet</p>
                <a href="{{ route('dashboard.auth.items') }}" class="inline-flex items-center gap-2 bg-black text-white px-6 py-3 rounded-full font-semibold hover:bg-gray-800 transition-all duration-200">
                    <i class="fas fa-shopping-cart text-sm"></i>
                    Start Shopping
                </a>
            </div>
        @else
            @php
                $pendingCount = $orders->where('status', 'pending')->count();
                $acceptedCount = $orders->whereIn('status', ['accepted', 'preparing'])->count();
                $readyCount = $orders->whereIn('status', ['ready_to_claim', 'ready_to_deliver', 'out_for_delivery'])->count();
                $deliveredCount = $orders->where('status', 'delivered')->count();
            @endphp

            <!-- ========== 4 TABS NAVIGATION ========== -->
            <div class="mb-8 border-b border-gray-200 overflow-x-auto scrollbar-hide">
                <div class="flex space-x-6 sm:space-x-8 min-w-max md:min-w-0">
                    <button data-tab="pending" class="tab-btn py-3 px-1 text-sm md:text-base font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200">
                        <i class="fas fa-clock mr-2"></i> Pending
                        <span class="ml-1.5 bg-gray-100 text-gray-700 text-xs rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                    </button>
                    <button data-tab="accepted" class="tab-btn py-3 px-1 text-sm md:text-base font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200">
                        <i class="fas fa-check-circle mr-2"></i> Accepted
                        <span class="ml-1.5 bg-gray-100 text-gray-700 text-xs rounded-full px-2 py-0.5">{{ $acceptedCount }}</span>
                    </button>
                    <button data-tab="ready" class="tab-btn py-3 px-1 text-sm md:text-base font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200">
                        <i class="fas fa-box-open mr-2"></i> Ready to Claim/Deliver
                        <span class="ml-1.5 bg-gray-100 text-gray-700 text-xs rounded-full px-2 py-0.5">{{ $readyCount }}</span>
                    </button>
                    <button data-tab="delivered" class="tab-btn py-3 px-1 text-sm md:text-base font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200">
                        <i class="fas fa-truck mr-2"></i> Delivered
                        <span class="ml-1.5 bg-gray-100 text-gray-700 text-xs rounded-full px-2 py-0.5">{{ $deliveredCount }}</span>
                    </button>
                </div>
            </div>

            <!-- ========== PENDING PANEL ========== -->
            <div id="pendingPanel" class="tab-panel">
                @php $pendingOrders = $orders->where('status', 'pending'); @endphp
                @if($pendingOrders->isEmpty())
                    <div class="text-center py-12 bg-white rounded-2xl border border-gray-200">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No pending orders</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($pendingOrders as $order)
                        @php
                            // Get the first item's product image
                            $firstItem = $order->items->first();
                            
                            $imagePath = null;
                            
                            if ($firstItem) {
                                // PRIORITY 1: Check if variation has its own image (using firstItem)
                                if ($firstItem->variation && $firstItem->variation->variant_image && $firstItem->variation->variant_image !== 'null' && $firstItem->variation->variant_image !== '') {
                                    $variantImage = $firstItem->variation->variant_image;
                                    
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
                                
                                // PRIORITY 2: If no variation image, use product's main image (using firstItem)
                                if (!$imagePath && $firstItem->product && $firstItem->product->prdctimage) {
                                    $images = $firstItem->product->prdctimage;
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
                            }
                            
                            if (!$imagePath) {
                                $imagePath = 'https://images.unsplash.com/photo-1534030347209-467a5b0ad3e6?w=60&h=60&fit=crop';
                            }
                        @endphp

                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200">
                            <div class="p-4 sm:p-5">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0">
                                            <img src="{{ $imagePath }}" class="w-full h-full object-cover" alt="{{ $firstItem->product_name ?? 'Product' }}">
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-800 text-base md:text-lg">{{ $order->order_number }}</h3>
                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('M d, Y') }}</span>
                                                <span><i class="fas fa- peso-sign mr-1"></i> ₱{{ number_format($order->total, 2) }}</span>
                                                <span><i class="fas fa-box mr-1"></i> {{ $order->items->count() }} item(s)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 ml-auto sm:ml-0">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            <i class="fas fa-hourglass-half mr-1 text-xs"></i> Pending
                                        </span>
                                        <button onclick="viewOrder('{{ $order->encrypted_id }}')" class="text-blue-600 text-sm font-medium hover:text-blue-800 transition">View Details <i class="fas fa-arrow-right ml-1 text-xs"></i></button>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap justify-between items-center gap-2">
                                    <div class="text-xs text-gray-500">
                                        <i class="far fa-clock mr-1"></i> 
                                        Estimated: {{ $order->estimated_time_display ?? 'Ready in 25-35 min' }}
                                    </div>
                                    <div class="flex gap-2">
                                        @if($order->canCancel())
                                        <button onclick="cancelOrder({{ $order->id }})" class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="far fa-trash-alt mr-1"></i> Cancel
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- ========== ACCEPTED PANEL ========== -->
            <div id="acceptedPanel" class="tab-panel hidden">
                @php $acceptedOrders = $orders->whereIn('status', ['accepted', 'preparing']); @endphp
                @if($acceptedOrders->isEmpty())
                    <div class="text-center py-12 bg-white rounded-2xl border border-gray-200">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No accepted orders</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($acceptedOrders as $order)
                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200">
                            <div class="p-4 sm:p-5">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                            <i class="fas fa-thumbs-up text-blue-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-800 text-base md:text-lg">{{ $order->order_number }}</h3>
                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('M d, Y') }}</span>
                                                <span><i class="fas fa- peso-sign mr-1"></i> ₱{{ number_format($order->total, 2) }}</span>
                                                <span><i class="fas fa-box mr-1"></i> {{ $order->items->count() }} item(s)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-check-circle mr-1 text-xs"></i> {{ ucfirst($order->status) }}
                                        </span>
                                        <button onclick="viewOrder({{ $order->id }})" class="text-blue-600 text-sm font-medium hover:text-blue-800 transition">Track <i class="fas fa-map-marker-alt ml-1 text-xs"></i></button>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="text-xs text-green-700">
                                        <i class="fas fa-utensils mr-1"></i> 
                                        @if($order->status === 'accepted') Order accepted, preparing soon
                                        @else Chef is preparing your order @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- ========== READY PANEL ========== -->
            <div id="readyPanel" class="tab-panel hidden">
                @php $readyOrders = $orders->whereIn('status', ['ready_to_claim', 'ready_to_deliver', 'out_for_delivery']); @endphp
                @if($readyOrders->isEmpty())
                    <div class="text-center py-12 bg-white rounded-2xl border border-gray-200">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No ready orders</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($readyOrders as $order)
                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200 {{ $order->delivery_method === 'pickup' ? 'border-l-4 border-l-green-500' : '' }}">
                            <div class="p-4 sm:p-5">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                                            @if($order->status === 'ready_to_claim')
                                                <i class="fas fa-store text-green-600 text-xl"></i>
                                            @elseif($order->status === 'ready_to_deliver')
                                                <i class="fas fa-truck-fast text-green-600 text-xl"></i>
                                            @else
                                                <i class="fas fa-motorcycle text-green-600 text-xl"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-800 text-base md:text-lg">{{ $order->order_number }}</h3>
                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('M d, Y') }}</span>
                                                <span><i class="fas fa- peso-sign mr-1"></i> ₱{{ number_format($order->total, 2) }}</span>
                                                <span><i class="fas fa-box mr-1"></i> {{ $order->items->count() }} item(s)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-box-open mr-1 text-xs"></i> 
                                            {{ $order->status === 'ready_to_claim' ? 'Ready to Claim' : ($order->status === 'ready_to_deliver' ? 'Ready to Deliver' : 'Out for Delivery') }}
                                        </span>
                                        <button onclick="viewOrder({{ $order->id }})" class="text-blue-600 text-sm font-medium hover:text-blue-800 transition">Track <i class="fas fa-arrow-right ml-1 text-xs"></i></button>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap justify-between items-center gap-2">
                                    <div class="text-xs text-gray-600">
                                        @if($order->status === 'ready_to_claim')
                                            <i class="fas fa-location-dot text-green-600 mr-1"></i> Ready for pickup at store
                                        @elseif($order->status === 'ready_to_deliver')
                                            <i class="fas fa-truck text-green-600 mr-1"></i> Waiting for delivery partner
                                        @else
                                            <i class="fas fa-motorcycle text-green-600 mr-1"></i> Delivery partner is on the way
                                        @endif
                                    </div>
                                    @if($order->status === 'ready_to_claim')
                                    <button onclick="showQRCode({{ $order->id }})" class="bg-green-600 hover:bg-green-700 text-white text-xs md:text-sm font-medium px-3 py-1.5 rounded-xl transition shadow-sm">
                                        <i class="fas fa-qrcode mr-1"></i> Show QR
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- ========== DELIVERED PANEL ========== -->
            <div id="deliveredPanel" class="tab-panel hidden">
                @php $deliveredOrders = $orders->where('status', 'delivered'); @endphp
                @if($deliveredOrders->isEmpty())
                    <div class="text-center py-12 bg-white rounded-2xl border border-gray-200">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No delivered orders yet</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($deliveredOrders as $order)
                        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs hover:shadow-md transition-all duration-200">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-check-double text-gray-500"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $order->order_number }}</h4>
                                        <p class="text-xs text-gray-400">
                                            Delivered {{ $order->delivered_at ? $order->delivered_at->format('M d, Y') : $order->updated_at->format('M d, Y') }} 
                                            • ₱{{ number_format($order->total, 2) }}
                                        </p>
                                    </div>
                                </div>
                                <button onclick="reorder({{ $order->id }})" class="text-sm text-blue-600 font-medium hover:text-blue-800 transition">
                                    <i class="fas fa-redo-alt mr-1"></i> Reorder
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
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

        function viewOrder(orderId) {
            window.location.href = "{{ route('myorder.show', '') }}/" + orderId;
        }

        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                fetch("{{ url('/customer/orders') }}/" + orderId + "/cancel", {
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
                        setTimeout(() => location.reload(), 1500);
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

        function showQRCode(orderId) {
            showToast('QR Code: Scan at pickup counter', 'info');
        }

        function reorder(orderId) {
            fetch("{{ url('/customer/orders') }}/" + orderId + "/reorder", {
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

        // TABS LOGIC
        (function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const panels = {
                pending: document.getElementById('pendingPanel'),
                accepted: document.getElementById('acceptedPanel'),
                ready: document.getElementById('readyPanel'),
                delivered: document.getElementById('deliveredPanel')
            };

            function setActiveTab(activeId) {
                tabs.forEach(btn => {
                    const tabValue = btn.getAttribute('data-tab');
                    if (tabValue === activeId) {
                        btn.classList.add('tab-active');
                        btn.classList.remove('text-gray-500', 'border-transparent');
                        btn.classList.add('text-blue-600', 'border-blue-600');
                    } else {
                        btn.classList.remove('text-blue-600', 'border-blue-600', 'tab-active');
                        btn.classList.add('text-gray-500', 'border-transparent');
                    }
                });
                
                Object.keys(panels).forEach(key => {
                    if (panels[key]) {
                        if (key === activeId) {
                            panels[key].classList.remove('hidden');
                        } else {
                            panels[key].classList.add('hidden');
                        }
                    }
                });
            }

            tabs.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tabId = btn.getAttribute('data-tab');
                    if (tabId && panels[tabId]) {
                        setActiveTab(tabId);
                    }
                });
            });

            // Set default active tab
            setActiveTab('pending');
        })();
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .tab-active {
            position: relative;
        }
        .tab-active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #2563eb;
            border-radius: 4px;
        }
        @media (max-width: 640px) {
            .tab-btn {
                font-size: 0.85rem;
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }
            .tab-btn i {
                margin-right: 0.25rem;
            }
        }
        button {
            cursor: pointer;
        }
    </style>
@endsection