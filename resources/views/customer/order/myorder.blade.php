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

        <!-- ========== 4 TABS NAVIGATION ========== -->
        <div class="mb-8 border-b border-gray-200 overflow-x-auto scrollbar-hide">
            <div class="flex space-x-6 sm:space-x-8 min-w-max md:min-w-0">
                <button data-tab="pending" class="tab-btn py-3 px-1 text-sm md:text-base font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200">
                    <i class="fas fa-clock mr-2"></i> Pending
                    <span class="ml-1.5 bg-gray-100 text-gray-700 text-xs rounded-full px-2 py-0.5">3</span>
                </button>
                <button data-tab="accepted" class="tab-btn py-3 px-1 text-sm md:text-base font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200">
                    <i class="fas fa-check-circle mr-2"></i> Accepted
                    <span class="ml-1.5 bg-gray-100 text-gray-700 text-xs rounded-full px-2 py-0.5">2</span>
                </button>
                <button data-tab="ready" class="tab-btn py-3 px-1 text-sm md:text-base font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200">
                    <i class="fas fa-box-open mr-2"></i> Ready to Claim
                    <span class="ml-1.5 bg-gray-100 text-gray-700 text-xs rounded-full px-2 py-0.5">4</span>
                </button>
                <button data-tab="delivered" class="tab-btn py-3 px-1 text-sm md:text-base font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200">
                    <i class="fas fa-truck mr-2"></i> Delivered
                    <span class="ml-1.5 bg-gray-100 text-gray-700 text-xs rounded-full px-2 py-0.5">2</span>
                </button>
            </div>
        </div>

        <!-- ========== TAB PANELS ========== -->
        <div id="pendingPanel" class="tab-panel">
            <!-- Pending Orders List -->
            <div class="space-y-4">
                <!-- Order Item 1 -->
                <div class="bg-white rounded-2xl border border-gray-200 p-0 shadow-xs overflow-hidden hover:shadow-md transition-all duration-200 order-card">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-receipt text-amber-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base md:text-lg">Order #ORD-9823</h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                        <span><i class="far fa-calendar-alt mr-1"></i> Apr 12, 2025</span>
                                        <span><i class="fas fa-dollar-sign mr-1"></i> $48.90</span>
                                        <span><i class="fas fa-box mr-1"></i> 3 items</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 ml-auto sm:ml-0">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-hourglass-half mr-1 text-xs"></i> Pending
                                </span>
                                <button class="text-blue-600 text-sm font-medium hover:text-blue-800 transition">Track Order <i class="fas fa-arrow-right ml-1 text-xs"></i></button>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-50 flex flex-wrap justify-between items-center gap-2">
                            <div class="text-xs text-gray-500">🕒 Estimated: Ready in 25-35 min</div>
                            <div class="flex gap-2">
                                <button class="text-gray-500 hover:text-gray-700 text-sm"><i class="far fa-clock mr-1"></i> Remind me</button>
                                <button class="text-red-500 hover:text-red-700 text-sm"><i class="far fa-trash-alt mr-1"></i> Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Order Item 2 -->
                <div class="bg-white rounded-2xl border border-gray-200 p-0 shadow-xs overflow-hidden hover:shadow-md transition-all duration-200 order-card">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-pizza-slice text-amber-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base md:text-lg">Order #ORD-9741</h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                        <span><i class="far fa-calendar-alt mr-1"></i> Apr 12, 2025</span>
                                        <span><i class="fas fa-dollar-sign mr-1"></i> $27.50</span>
                                        <span><i class="fas fa-box mr-1"></i> 2 items</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 ml-auto sm:ml-0">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-hourglass-half mr-1 text-xs"></i> Pending
                                </span>
                                <button class="text-blue-600 text-sm font-medium hover:text-blue-800 transition">Track Order <i class="fas fa-arrow-right ml-1 text-xs"></i></button>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-50 flex flex-wrap justify-between items-center gap-2">
                            <div class="text-xs text-gray-500">🕒 Estimated: Ready in 15-20 min</div>
                            <div class="flex gap-2">
                                <button class="text-gray-500 hover:text-gray-700 text-sm"><i class="far fa-clock mr-1"></i> Remind me</button>
                                <button class="text-red-500 hover:text-red-700 text-sm"><i class="far fa-trash-alt mr-1"></i> Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Order Item 3 -->
                <div class="bg-white rounded-2xl border border-gray-200 p-0 shadow-xs overflow-hidden hover:shadow-md transition-all duration-200 order-card">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-mug-hot text-amber-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base md:text-lg">Order #ORD-9652</h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                        <span><i class="far fa-calendar-alt mr-1"></i> Apr 11, 2025</span>
                                        <span><i class="fas fa-dollar-sign mr-1"></i> $12.30</span>
                                        <span><i class="fas fa-box mr-1"></i> 1 item</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 ml-auto sm:ml-0">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-hourglass-half mr-1 text-xs"></i> Pending
                                </span>
                                <button class="text-blue-600 text-sm font-medium hover:text-blue-800 transition">Track Order <i class="fas fa-arrow-right ml-1 text-xs"></i></button>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-50 flex flex-wrap justify-between items-center gap-2">
                            <div class="text-xs text-gray-500">🕒 Estimated: Ready in 10-15 min</div>
                            <div class="flex gap-2">
                                <button class="text-gray-500 hover:text-gray-700 text-sm"><i class="far fa-clock mr-1"></i> Remind me</button>
                                <button class="text-red-500 hover:text-red-700 text-sm"><i class="far fa-trash-alt mr-1"></i> Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="acceptedPanel" class="tab-panel hidden">
            <div class="space-y-4">
                <!-- Accepted Order 1 -->
                <div class="bg-white rounded-2xl border border-gray-200 p-0 shadow-xs overflow-hidden hover:shadow-md transition-all duration-200 order-card">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-thumbs-up text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base md:text-lg">Order #ORD-9910</h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                        <span><i class="far fa-calendar-alt mr-1"></i> Apr 12, 2025</span>
                                        <span><i class="fas fa-dollar-sign mr-1"></i> $64.20</span>
                                        <span><i class="fas fa-box mr-1"></i> 4 items</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i> Accepted
                                </span>
                                <button class="text-blue-600 text-sm font-medium hover:text-blue-800 transition">Track <i class="fas fa-map-marker-alt ml-1 text-xs"></i></button>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-50 flex flex-wrap justify-between items-center gap-2">
                            <div class="text-xs text-green-700"><i class="fas fa-utensils mr-1"></i> Chef is preparing your order</div>
                            <button class="text-indigo-500 text-sm hover:text-indigo-700"><i class="fas fa-comment-dots mr-1"></i> Contact kitchen</button>
                        </div>
                    </div>
                </div>
                <!-- Accepted Order 2 -->
                <div class="bg-white rounded-2xl border border-gray-200 p-0 shadow-xs overflow-hidden hover:shadow-md transition-all duration-200 order-card">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-burger text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base md:text-lg">Order #ORD-9887</h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                        <span><i class="far fa-calendar-alt mr-1"></i> Apr 11, 2025</span>
                                        <span><i class="fas fa-dollar-sign mr-1"></i> $33.45</span>
                                        <span><i class="fas fa-box mr-1"></i> 2 items</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i> Accepted
                                </span>
                                <button class="text-blue-600 text-sm font-medium hover:text-blue-800 transition">Track <i class="fas fa-map-marker-alt ml-1 text-xs"></i></button>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-50 flex flex-wrap justify-between items-center gap-2">
                            <div class="text-xs text-green-700"><i class="fas fa-clock mr-1"></i> Preparation in progress</div>
                            <button class="text-indigo-500 text-sm hover:text-indigo-700"><i class="fas fa-phone-alt mr-1"></i> Call store</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="readyPanel" class="tab-panel hidden">
            <div class="space-y-4">
                <!-- Ready to Claim - to deliver style also pickup -->
                <div class="bg-white rounded-2xl shadow-sm border-l-4 border-l-green-500 overflow-hidden hover:shadow-md transition-all duration-200 order-card">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-store text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base md:text-lg">Order #ORD-9993</h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                        <span><i class="far fa-calendar-alt mr-1"></i> Apr 12, 2025</span>
                                        <span><i class="fas fa-dollar-sign mr-1"></i> $52.75</span>
                                        <span><i class="fas fa-box mr-1"></i> 3 items</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-box-open mr-1 text-xs"></i> Ready to Claim
                                </span>
                                <button class="bg-green-600 hover:bg-green-700 text-white text-xs md:text-sm font-medium px-3 py-1.5 rounded-xl transition shadow-sm"><i class="fas fa-qrcode mr-1"></i> Show QR</button>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-50 flex flex-wrap justify-between items-center gap-2">
                            <div class="text-xs text-gray-600"><i class="fas fa-location-dot text-green-600 mr-1"></i> Pickup counter B - order ready</div>
                            <button class="text-green-700 text-sm font-medium"><i class="fas fa-directions mr-1"></i> Get directions</button>
                        </div>
                    </div>
                </div>
                <!-- Ready for delivery style (if delivery service) -->
                <div class="bg-white rounded-2xl shadow-sm border-l-4 border-l-green-500 overflow-hidden hover:shadow-md transition-all duration-200 order-card">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                                    <i class="fas fa-truck-fast text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base md:text-lg">Order #ORD-9982</h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 mt-1">
                                        <span><i class="far fa-calendar-alt mr-1"></i> Apr 12, 2025</span>
                                        <span><i class="fas fa-dollar-sign mr-1"></i> $78.20</span>
                                        <span><i class="fas fa-box mr-1"></i> 5 items</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-box-open mr-1 text-xs"></i> Ready to Deliver
                                </span>
                                <button class="border border-green-600 text-green-700 hover:bg-green-50 text-xs md:text-sm font-medium px-3 py-1.5 rounded-xl transition"><i class="fas fa-share-alt mr-1"></i> Share ETA</button>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-50 flex flex-wrap justify-between items-center gap-2">
                            <div class="text-xs text-gray-600"><i class="fas fa-motorcycle text-green-600 mr-1"></i> Delivery partner assigned</div>
                            <button class="text-green-700 text-sm"><i class="fas fa-map-marked-alt mr-1"></i> Live tracking</button>
                        </div>
                    </div>
                </div>
                <!-- More ready items 3 & 4 -->
                <div class="bg-white rounded-2xl border border-gray-200 p-0 shadow-xs overflow-hidden order-card">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex gap-3">
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center"><i class="fas fa-cake-candles text-emerald-600 text-xl"></i></div>
                                <div><h3 class="font-bold">Order #ORD-9955</h3><div class="text-xs text-gray-500">$19.99 • 1 item • Apr 12</div></div>
                            </div>
                            <div class="mt-2 sm:mt-0 flex flex-wrap items-center gap-2"><span class="badge-status bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full">Ready to Claim</span><button class="text-blue-600 text-sm">View code <i class="fas fa-chevron-right ml-1 text-xs"></i></button></div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 p-0 shadow-xs overflow-hidden order-card">
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex gap-3"><div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center"><i class="fas fa-drumstick-bite text-emerald-600 text-xl"></i></div><div><h3 class="font-bold">Order #ORD-9941</h3><div class="text-xs text-gray-500">$43.50 • 3 items • Apr 11</div></div></div>
                            <div class="mt-2 sm:mt-0"><span class="badge-status bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full">Ready to Deliver</span><button class="ml-3 text-blue-600 text-sm">Track <i class="fas fa-location-dot ml-1"></i></button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="deliveredPanel" class="tab-panel hidden">
            <div class="space-y-3">
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs flex flex-wrap items-center justify-between gap-3 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center"><i class="fas fa-check-double text-gray-500"></i></div><div><h4 class="font-semibold">Order #ORD-9820</h4><p class="text-xs text-gray-400">Delivered Apr 10, 2025 • $34.90</p></div></div><button class="text-sm text-blue-600 font-medium">Reorder <i class="fas fa-redo-alt ml-1"></i></button>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs flex flex-wrap items-center justify-between gap-3 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center"><i class="fas fa-check-double text-gray-500"></i></div><div><h4 class="font-semibold">Order #ORD-9775</h4><p class="text-xs text-gray-400">Delivered Apr 8, 2025 • $61.25</p></div></div><button class="text-sm text-blue-600 font-medium">Reorder <i class="fas fa-redo-alt ml-1"></i></button>
                </div>
                <div class="mt-5 text-center text-gray-400 text-sm"><i class="far fa-smile-wink mr-1"></i> That's all for delivered orders</div>
            </div>
        </div>

        <!-- empty state (optional but design consistent) -->
    </main>

    <script>
        // TABS LOGIC with dynamic panel switching & toast demo + smooth UI
        (function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const panels = {
                pending: document.getElementById('pendingPanel'),
                accepted: document.getElementById('acceptedPanel'),
                ready: document.getElementById('readyPanel'),
                delivered: document.getElementById('deliveredPanel')
            };

            // toast handling function (demo of existing toast element)
            function showToast(message, icon = 'fa-info-circle') {
                const toast = document.getElementById('toast');
                const toastIcon = document.getElementById('toastIcon');
                const toastMessage = document.getElementById('toastMessage');
                if (!toast || !toastIcon || !toastMessage) return;
                toastIcon.className = `fas ${icon} mr-2`;
                toastMessage.innerText = message;
                toast.classList.remove('opacity-0', 'pointer-events-none');
                toast.classList.add('opacity-100', 'pointer-events-auto');
                setTimeout(() => {
                    toast.classList.remove('opacity-100', 'pointer-events-auto');
                    toast.classList.add('opacity-0', 'pointer-events-none');
                }, 2500);
            }

            // set active tab style
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
                // show/hide panels
                Object.keys(panels).forEach(key => {
                    if (panels[key]) {
                        if (key === activeId) {
                            panels[key].classList.remove('hidden');
                            panels[key].style.opacity = '0';
                            setTimeout(() => { if (panels[key]) panels[key].style.opacity = '1'; }, 20);
                        } else {
                            panels[key].classList.add('hidden');
                            panels[key].style.opacity = '';
                        }
                    }
                });
            }

            // attach click handlers to each tab
            tabs.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tabId = btn.getAttribute('data-tab');
                    if (tabId && panels[tabId]) {
                        setActiveTab(tabId);
                        // optional toast feedback for better UX
                        let tabLabel = '';
                        if (tabId === 'pending') tabLabel = 'Pending orders';
                        else if (tabId === 'accepted') tabLabel = 'Accepted orders';
                        else if (tabId === 'ready') tabLabel = 'Ready orders';
                        else if (tabId === 'delivered') tabLabel = 'Delivered orders';
                        showToast(`Showing ${tabLabel}`, 'fa-clipboard-list');
                    }
                });
            });

            // default active: pending tab
            setActiveTab('pending');

            // Additional interactive demo for Track Order, Cancel, QR, etc with toast for UI only
            document.querySelectorAll('.order-card button, .order-card .text-blue-600, .order-card .bg-green-600, .order-card .border-green-600').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const btnText = btn.innerText.trim().toLowerCase();
                    if (btnText.includes('track') || btnText.includes('track order') || (btn.innerHTML.includes('Track'))) {
                        showToast('🔍 Live tracking demo: order is being prepared', 'fa-map-marked-alt');
                    } else if (btnText.includes('cancel')) {
                        showToast('❌ Cancel requested — demo mode', 'fa-ban');
                    } else if (btnText.includes('qr') || btnText.includes('show qr')) {
                        showToast('📱 QR code would open to claim your order at counter', 'fa-qrcode');
                    } else if (btnText.includes('remind me')) {
                        showToast('⏰ We will remind you in 15 minutes', 'fa-bell');
                    } else if (btnText.includes('reorder')) {
                        showToast('🔄 Reorder added to cart (demo)', 'fa-cart-plus');
                    } else if (btnText.includes('directions') || btnText.includes('get directions')) {
                        showToast('📍 Opening maps for store location', 'fa-location-dot');
                    } else if (btnText.includes('live tracking')) {
                        showToast('🚴‍♂️ Delivery partner is 0.8km away', 'fa-truck-fast');
                    } else if (btnText.includes('contact') || btnText.includes('call')) {
                        showToast('📞 Connecting to store support', 'fa-phone-alt');
                    } else {
                        // fallback gentle feedback
                        showToast('✨ Feature coming soon', 'fa-sparkles');
                    }
                });
            });
        })();
    </script>
    <style>
        /* Hide scrollbar for clean tab overflow on mobile */
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
        .order-card button:active {
            transform: scale(0.97);
        }
        button {
            cursor: pointer;
        }
        .badge-status {
            white-space: nowrap;
        }
    </style>
@endsection