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
        window.ShopLink.cartCountUrl = '{{ route("cart.realtime.count") }}';

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
        });
    </script>

    @stack('scripts')

</body>
</html>
