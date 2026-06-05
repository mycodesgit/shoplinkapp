<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shoplink | Home</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">

    <!-- Header -->
    @include('customer.partials.navtop')

    <!-- Main Content -->
    @yield('content')

    @include('customer.partials.navbottom')

    <script>
        function loadCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const total = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCount = document.getElementById('cartCount');
            const mobileBadge = document.getElementById('mobileCartBadge');
            if (cartCount) cartCount.innerText = total;
            if (mobileBadge) mobileBadge.innerText = total;
        }
        loadCartCount();
    </script>
    
    <script>
        // Cart count manager (only for logged-in users)
        class CartCountManager {
            constructor() {
                this.updateInterval = null;
                this.pollingInterval = 5000;
                this.isUpdating = false;
            }
            
            // Fetch latest cart count from server
            async fetchCartCount() {
                if (this.isUpdating) return;
                
                this.isUpdating = true;
                
                try {
                    const response = await fetch('{{ route("cart.realtime.count") }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.updateCartCountUI(data.cart_count);
                    }
                } catch (error) {
                    console.error('Error fetching cart count:', error);
                } finally {
                    this.isUpdating = false;
                }
            }
            
            // Update both desktop and mobile badges
            updateCartCountUI(count) {
                // Update desktop badge (navtop)
                const cartCountElement = document.getElementById('cartCount');
                if (cartCountElement) {
                    const currentCount = parseInt(cartCountElement.textContent) || 0;
                    
                    if (currentCount !== count) {
                        cartCountElement.textContent = count;
                        this.animateBadge(cartCountElement);
                    }
                }
                
                // Update mobile badge (navbottom)
                const mobileBadge = document.getElementById('mobileCartBadge');
                if (mobileBadge) {
                    const currentCount = parseInt(mobileBadge.textContent) || 0;
                    
                    if (currentCount !== count) {
                        mobileBadge.textContent = count;
                        this.animateBadge(mobileBadge);
                    }
                }
            }
            
            // Animate badge
            animateBadge(element) {
                element.style.transform = 'scale(1.3)';
                element.style.transition = 'transform 0.2s ease';
                element.classList.add('cart-pulse');
                
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                    element.classList.remove('cart-pulse');
                }, 200);
                
                // Change opacity based on count
                const count = parseInt(element.textContent);
                if (count === 0) {
                    element.style.opacity = '0.5';
                } else {
                    element.style.opacity = '1';
                }
            }
            
            // Start real-time polling
            startRealTimeUpdates() {
                this.fetchCartCount();
                this.updateInterval = setInterval(() => {
                    this.fetchCartCount();
                }, this.pollingInterval);
            }
            
            // Stop real-time updates
            stopRealTimeUpdates() {
                if (this.updateInterval) {
                    clearInterval(this.updateInterval);
                    this.updateInterval = null;
                }
            }
            
            // Manual refresh
            manualRefresh() {
                this.fetchCartCount();
            }
        }
                
                // Initialize cart count manager
                const cartManager = new CartCountManager();
                
                // Start real-time updates when page loads (only if logged in)
                document.addEventListener('DOMContentLoaded', function() {
            const isLoggedIn = {{ Auth::guard('customer')->check() ? 'true' : 'false' }};
            
            if (isLoggedIn) {
                cartManager.startRealTimeUpdates();
                
                // Initial fetch to set both badges
                cartManager.fetchCartCount();
            } else {
                // Set both badges to 0 for guests
                const cartCountElement = document.getElementById('cartCount');
                const mobileBadge = document.getElementById('mobileCartBadge');
                
                if (cartCountElement) cartCountElement.textContent = '0';
                if (mobileBadge) mobileBadge.textContent = '0';
            }
            
            window.cartManager = cartManager;
        });
    </script>
    
</body>
</html>