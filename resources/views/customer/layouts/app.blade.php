<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
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
</body>
</html>