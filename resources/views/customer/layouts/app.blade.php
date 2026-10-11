<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="ShopLink — quality products at the best prices.">
    <meta name="theme-color" content="#ffffff">

    <title>@yield('title', 'Shoplink | Home')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <!-- Header -->
    @include('customer.partials.navtop')

    <!-- Main Content -->
    <div class="flex-1 w-full">
        @yield('content')
    </div>

    <!-- Footer (desktop / tablet only — mobile uses bottom nav) -->
    <footer class="hidden md:block bg-white border-t border-gray-100 mt-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-black text-sm text-white">
                    <i class="fas fa-shopping-bag"></i>
                </span>
                <span class="text-base font-extrabold tracking-tight text-black">ShopLink</span>
                <span class="text-sm text-gray-500">© {{ date('Y') }}. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-6 text-sm text-gray-500">
                <a href="{{ Auth::guard('customer')->check() ? route('dashboard.auth.items') : route('dashboard.items') }}" class="hover:text-black transition">Shop</a>
                <a href="{{ Auth::guard('customer')->check() ? route('dashboard.auth.index') : route('dashboard.index') }}" class="hover:text-black transition">Home</a>
                <a href="#" class="hover:text-black transition">About</a>
            </div>
        </div>
    </footer>

    @include('customer.partials.navbottom')
    @include('customer.partials.confirm-modal')

    {{-- Pairing-style notification card (AirPods-like popup, top-center on all devices) --}}
    <div id="pairToast" class="pointer-events-none fixed left-1/2 top-16 z-[10000] w-[calc(100%-2rem)] max-w-sm -translate-x-1/2 opacity-0 transition-all duration-300 md:top-20" aria-live="polite">
        <div id="pairToastCard" class="flex -translate-y-3 scale-95 items-center gap-3 rounded-3xl bg-white p-4 shadow-2xl ring-1 ring-black/10 transition-all duration-300">
            <div id="pairToastIcon" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-black text-base text-white">
                <i class="fas fa-check"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold tracking-tight text-black">ShopLink</p>
                <p id="pairToastMessage" class="truncate text-xs text-gray-500"></p>
            </div>
        </div>
    </div>

    {{-- Global cart-badge sync (logged-in users only, polled). Page scripts go via @push('scripts'). --}}
    <script>
        window.ShopLink = window.ShopLink || {};
        window.ShopLink.csrf = '{{ csrf_token() }}';
        window.ShopLink.isCustomer = {{ Auth::guard('customer')->check() ? 'true' : 'false' }};
        window.ShopLink.customerId = {{ Auth::guard('customer')->check() ? (int) Auth::guard('customer')->id() : 'null' }};
        window.ShopLink.cartCountUrl = '{{ route("cart.realtime.count") }}';
        window.ShopLink.orderFeedUrl = '{{ Auth::guard('customer')->check() ? route('orders.auth.feed') : '' }}';
        window.ShopLink.ordersUrl = '{{ Auth::guard('customer')->check() ? route('orders.auth.index') : '' }}';
        window.ShopLink.reverb = {
            key: '{{ config('broadcasting.connections.reverb.key') }}',
            host: '{{ config('broadcasting.connections.reverb.options.host', 'localhost') }}',
            port: {{ (int) config('broadcasting.connections.reverb.options.port', 8080) }},
            tls: {{ config('broadcasting.connections.reverb.options.scheme', 'http') === 'https' ? 'true' : 'false' }}
        };

        window.ShopLink.setCartCount = function (count) {
            ['cartCount', 'mobileCartBadge'].forEach(function (id) {
                var el = document.getElementById(id);
                if (!el) return;
                var current = parseInt(el.textContent, 10) || 0;
                if (current === count) return;
                el.textContent = count;
                el.style.opacity = count === 0 ? '0.5' : '1';
                el.classList.remove('cart-pulse');
                void el.offsetWidth;
                el.classList.add('cart-pulse');
            });
        };

        window.ShopLink.redirectToLogin = function () {
            var loginUrl = '{{ route("shop.login") }}';
            window.location.href = loginUrl + '?redirect=' + encodeURIComponent(window.location.href);
        };

        /* Custom verification popup — opts: { title, message, confirmText, cancelText, onConfirm } */
        window.ShopLink.confirm = function (opts) {
            var modal = document.getElementById('confirmModal');
            var title = document.getElementById('confirmModalTitle');
            var message = document.getElementById('confirmModalMessage');
            var okBtn = document.getElementById('confirmModalOk');
            var cancelBtn = document.getElementById('confirmModalCancel');
            if (!modal || !okBtn || !cancelBtn) return;
            opts = opts || {};

            if (title) title.textContent = opts.title || 'Are you sure?';
            if (message) message.textContent = opts.message || 'This action cannot be undone.';
            okBtn.textContent = opts.confirmText || 'Confirm';
            cancelBtn.textContent = opts.cancelText || 'Cancel';

            var close = function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
                document.removeEventListener('keydown', onKey);
            };
            var onKey = function (e) {
                if (e.key === 'Escape') close();
            };

            okBtn.onclick = function () {
                close();
                if (typeof opts.onConfirm === 'function') opts.onConfirm();
            };
            cancelBtn.onclick = close;
            modal.onclick = function (e) {
                if (e.target === modal) close();
            };
            document.addEventListener('keydown', onKey);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        };

        window.ShopLink.showToast = function (message, type) {
            var wrap = document.getElementById('pairToast');
            var card = document.getElementById('pairToastCard');
            var msg = document.getElementById('pairToastMessage');
            var icon = document.getElementById('pairToastIcon');
            if (!wrap || !card || !msg || !icon) return;
            msg.textContent = message;
            msg.title = message;
            var glyph = 'fa-check';
            var bg = 'bg-black';
            if (type === 'error') {
                glyph = 'fa-exclamation';
                bg = 'bg-red-600';
            } else if (type === 'warning') {
                glyph = 'fa-exclamation';
            } else if (type === 'info') {
                glyph = 'fa-info';
            }
            icon.className = 'flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-base text-white ' + bg;
            icon.innerHTML = '<i class="fas ' + glyph + '"></i>';
            wrap.classList.remove('opacity-0', 'pointer-events-none');
            wrap.classList.add('opacity-100');
            card.classList.remove('-translate-y-3', 'scale-95');
            card.classList.add('translate-y-0', 'scale-100');
            clearTimeout(window.ShopLink._toastTimer);
            window.ShopLink._toastTimer = setTimeout(function () {
                wrap.classList.remove('opacity-100');
                wrap.classList.add('opacity-0', 'pointer-events-none');
                card.classList.remove('translate-y-0', 'scale-100');
                card.classList.add('-translate-y-3', 'scale-95');
            }, 2600);
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Bell dropdown works standalone — never blocked by feed/socket issues
            var bellBtn = document.getElementById('custBellBtn');
            var bellDrop = document.getElementById('custBellDropdown');
            if (bellBtn && bellDrop && !bellBtn.dataset.wired) {
                bellBtn.dataset.wired = '1';
                bellBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    bellDrop.classList.toggle('hidden');
                });
                // Mark as read on click, then follow the link (exact tab / details)
                document.getElementById('custBellList').addEventListener('click', function (e) {
                    var link = e.target.closest('a[data-id]');
                    if (!link) return;
                    try {
                        var seen = JSON.parse(localStorage.getItem('custNotifSeen') || '{}');
                        seen[link.getAttribute('data-id')] = link.getAttribute('data-updated');
                        localStorage.setItem('custNotifSeen', JSON.stringify(seen));
                    } catch (err) {}
                });
                document.addEventListener('click', function (e) {
                    if (!bellDrop.classList.contains('hidden') && !bellDrop.contains(e.target) && e.target !== bellBtn) {
                        bellDrop.classList.add('hidden');
                    }
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') bellDrop.classList.add('hidden');
                });
                var markRead = document.getElementById('custMarkRead');
                if (markRead) {
                    markRead.addEventListener('click', function () {
                        var seen = {};
                        custFeedCache.forEach(function (o) { seen[o.id] = o.updated_at; });
                        try { localStorage.setItem('custNotifSeen', JSON.stringify(seen)); } catch (err) {}
                        paintCustBell(custFeedCache);
                    });
                }
            }

            if (!window.ShopLink.isCustomer) {
                window.ShopLink.setCartCount(0);
                return;
            }
            var fetchCount = function () {
                fetch(window.ShopLink.cartCountUrl, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.ShopLink.csrf }
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data && data.success) window.ShopLink.setCartCount(data.cart_count);
                })
                .catch(function (e) { console.error('Cart count error:', e); });
            };
            fetchCount();
            setInterval(fetchCount, 15000);
            window.cartManager = { manualRefresh: fetchCount };

            startOrderNotifications();
        });

        /* Live order-status notifications (Reverb socket + AJAX fallback) */
        function handleStatusUpdate(order) {
            var label = (order.order_number || ('#' + order.order_id)) + ': ' + (order.message || 'Your order status changed.');
            window.ShopLink.showToast(label, order.status === 'cancelled' ? 'error' : 'success');
            // Let pills/tables on order pages reflect the new status
            if (/myorder/.test(window.location.pathname)) {
                setTimeout(function () { window.location.reload(); }, 3000);
            }
        }

        var statusBaseline = {};
        function pollOrderStatuses() {
            if (!window.ShopLink.isCustomer || !window.ShopLink.orderFeedUrl) return;
            fetch(window.ShopLink.orderFeedUrl, {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.ShopLink.csrf }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data || !data.success) return;
                var firstRun = Object.keys(statusBaseline).length === 0;
                (data.orders || []).forEach(function (o) {
                    if (!firstRun && statusBaseline[o.id] && statusBaseline[o.id] !== o.status) {
                        handleStatusUpdate({ order_id: o.id, order_number: o.order_number, status: o.status, message: statusMessage(o.status, o.order_number) });
                    }
                    statusBaseline[o.id] = o.status;
                });
                paintCustBell(data.orders || []);
            })
            .catch(function (e) { console.error('Status feed error:', e); });
        }

        function statusMessage(status, number) {
            var map = {
                accepted: 'Your order was accepted and is now in queue.',
                preparing: 'Your order is being prepared.',
                ready_to_claim: 'Your order is ready for pickup!',
                ready_to_deliver: 'Your order is ready and waiting for delivery.',
                out_for_delivery: 'Your order is out for delivery!',
                delivered: 'Your order has been delivered. Enjoy!',
                cancelled: 'Your order was cancelled.'
            };
            return map[status] || ('Order ' + number + ' is now ' + status + '.');
        }

        /* Customer notification bell: per-order status history */
        var custFeedCache = [];
        function getCustSeen() {
            try { return JSON.parse(localStorage.getItem('custNotifSeen') || '{}'); }
            catch (e) { return {}; }
        }
        function statusPill(status) {
            var label = (status || '').replace(/_/g, ' ');
            var cls = 'bg-gray-100 text-gray-800';
            if (['accepted', 'preparing'].indexOf(status) !== -1) cls = 'bg-gray-200 text-gray-900';
            else if (['ready_to_claim', 'ready_to_deliver', 'out_for_delivery', 'delivered'].indexOf(status) !== -1) cls = 'bg-black text-white';
            else if (status === 'cancelled') cls = 'bg-gray-100 text-gray-500';
            return '<span class="inline-block rounded-full px-2 py-0.5 text-[10px] font-semibold ' + cls + '">' + label + '</span>';
        }
        function paintCustBell(orders) {
            var badge = document.getElementById('custBellBadge');
            var list = document.getElementById('custBellList');
            if (!badge || !list) return;
            custFeedCache = orders || [];
            var seen = getCustSeen();
            var unread = custFeedCache.filter(function (o) { return seen[o.id] !== o.updated_at; });

            if (unread.length) {
                badge.textContent = unread.length > 9 ? '9+' : unread.length;
                badge.classList.remove('hidden');
                badge.classList.add('flex');
            } else {
                badge.classList.add('hidden');
                badge.classList.remove('flex');
            }

            if (!custFeedCache.length) {
                list.innerHTML = '<li class="px-4 py-4 text-center text-sm text-gray-400">No orders yet — your status updates will appear here.</li>';
                return;
            }
            list.innerHTML = custFeedCache.map(function (o) {
                var isNew = seen[o.id] !== o.updated_at;
                var when = o.updated_at ? new Date(o.updated_at).toLocaleString(undefined, { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' }) : '';
                return '<li>' +
                    '<a href="' + (o.url || window.ShopLink.ordersUrl) + '" data-id="' + o.id + '" data-updated="' + (o.updated_at || '') + '" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-gray-50 transition ' + (isNew ? 'bg-gray-50' : '') + '">' +
                        '<span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full ' + (isNew ? 'bg-black text-white' : 'bg-gray-100 text-gray-500') + '"><i class="fas fa-box text-xs"></i></span>' +
                        '<span class="min-w-0 flex-1">' +
                            '<span class="block truncate text-xs font-semibold text-black">' + (o.order_number || ('#' + o.id)) + '</span>' +
                            '<span class="mt-0.5 block">' + statusPill(o.status) + '</span>' +
                            '<span class="mt-0.5 block text-[11px] text-gray-400">' + when + '</span>' +
                        '</span>' +
                        (isNew ? '<span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-black"></span>' : '') +
                    '</a>' +
                '</li>';
            }).join('');
        }

        function startOrderNotifications() {
            if (!window.ShopLink.isCustomer) return;
            pollOrderStatuses();
            setInterval(pollOrderStatuses, 25000);

            if (!window.Pusher || !window.Echo) return;
            try {
                var EchoCtor = window.Echo.default || window.Echo;
                if (typeof EchoCtor !== 'function') throw new Error('Echo constructor not found');
                var echo = new EchoCtor({
                    broadcaster: 'reverb',
                    key: window.ShopLink.reverb.key,
                    wsHost: window.ShopLink.reverb.host,
                    wsPort: window.ShopLink.reverb.port,
                    wssPort: window.ShopLink.reverb.port,
                    forceTLS: window.ShopLink.reverb.tls,
                    enabledTransports: ['ws', 'wss'],
                    authEndpoint: '/broadcasting/customer-auth',
                    auth: { headers: { 'X-CSRF-TOKEN': window.ShopLink.csrf } }
                });
                echo.private('customer.orders.' + window.ShopLink.customerId).listen('.order.status', function (e) {
                    handleStatusUpdate(e);
                    if (e && e.order_id) statusBaseline[e.order_id] = e.status;
                    pollOrderStatuses();
                });
                window.customerEcho = echo;
            } catch (e) {
                console.error('Reverb connection failed, using AJAX polling:', e);
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@2.6.1/dist/echo.iife.js"></script>

    @stack('scripts')

</body>
</html>
