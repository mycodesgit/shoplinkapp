@php
    $curr_route = request()->route()->getName();

    $dashActive = in_array($curr_route, ['dashboard.index', 'dashboard.auth.index']);
    $aboutActive = in_array($curr_route, ['dashboard.about', 'dashboard.auth.about']);
    $itemsActive = in_array($curr_route, ['dashboard.items', 'dashboard.auth.items', 'itemdetails.index', 'itemdetails.auth.index']);
    $cartActive = in_array($curr_route, ['cart.index', 'cart.auth.index']);
    $orderActive = in_array($curr_route, ['orders.auth.index']);
    $profileActive = in_array($curr_route, ['profile.account', 'profile.auth.account']);

    $linkBase = 'relative px-1 py-1 text-sm font-medium transition-colors hover:text-black';
@endphp

<header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14 md:h-16 gap-3">
            <a href="{{ Auth::guard('customer')->check() ? route('dashboard.auth.index') : route('dashboard.index') }}" class="flex shrink-0 items-center gap-2" aria-label="ShopLink home">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-black text-base text-white md:h-10 md:w-10">
                    <i class="fas fa-shopping-bag"></i>
                </span>
                <span class="text-lg font-extrabold tracking-tight text-black md:text-xl">ShopLink</span>
            </a>

            <nav class="hidden md:flex items-center gap-7 text-gray-500" aria-label="Primary">
                @if(Auth::guard('customer')->check())
                    <a href="{{ route('dashboard.auth.index') }}" aria-current="{{ $dashActive ? 'page' : 'false' }}" class="{{ $linkBase }} {{ $dashActive ? 'text-black font-semibold after:absolute after:-bottom-[3px] after:left-0 after:h-0.5 after:w-full after:rounded after:bg-black' : '' }}">Home</a>
                    <a href="{{ route('dashboard.auth.items') }}" aria-current="{{ $itemsActive ? 'page' : 'false' }}" class="{{ $linkBase }} {{ $itemsActive ? 'text-black font-semibold after:absolute after:-bottom-[3px] after:left-0 after:h-0.5 after:w-full after:rounded after:bg-black' : '' }}">Shop</a>
                    <a href="{{ route('orders.auth.index') }}" aria-current="{{ $orderActive ? 'page' : 'false' }}" class="{{ $linkBase }} {{ $orderActive ? 'text-black font-semibold after:absolute after:-bottom-[3px] after:left-0 after:h-0.5 after:w-full after:rounded after:bg-black' : '' }}">Orders</a>
                    <a href="{{ route('cart.auth.index') }}" aria-current="{{ $cartActive ? 'page' : 'false' }}" class="{{ $linkBase }} {{ $cartActive ? 'text-black font-semibold after:absolute after:-bottom-[3px] after:left-0 after:h-0.5 after:w-full after:rounded after:bg-black' : '' }}">Cart</a>
                    <a href="{{ route('profile.auth.account') }}" aria-current="{{ $profileActive ? 'page' : 'false' }}" class="{{ $linkBase }} {{ $profileActive ? 'text-black font-semibold after:absolute after:-bottom-[3px] after:left-0 after:h-0.5 after:w-full after:rounded after:bg-black' : '' }}">Profile</a>
                @else
                    <a href="{{ route('dashboard.index') }}" class="{{ $linkBase }} {{ $dashActive ? 'text-black font-semibold' : '' }}">Home</a>
                    <a href="{{ route('dashboard.items') }}" class="{{ $linkBase }} {{ $itemsActive ? 'text-black font-semibold' : '' }}">Shop</a>
                    <a href="{{ route('dashboard.about') }}" class="{{ $linkBase }} {{ $aboutActive ? 'text-black font-semibold' : '' }}">About</a>
                @endif
            </nav>

            @if(Auth::guard('customer')->check())
                <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                    <span class="hidden sm:block text-sm text-gray-600 truncate max-w-32 lg:max-w-48">Hello, {{ Auth::guard('customer')->user()->fname }}</span>
                    <a href="{{ route('cart.auth.index') }}" class="relative p-2 -m-1 rounded-full hover:bg-gray-100 transition" aria-label="Cart">
                        <i class="fas fa-shopping-bag text-gray-700 text-lg"></i>
                        <span id="cartCount" class="absolute top-0 right-0 bg-black text-white text-[10px] font-bold rounded-full min-w-4 h-4 px-1 flex items-center justify-center">0</span>
                    </a>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('shop.login') }}"
                       class="inline-flex items-center gap-2 bg-transparent hover:bg-black text-black hover:text-white border-2 border-black px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200">
                        <i class="fas fa-sign-in-alt text-xs sm:text-sm"></i>
                        Sign In
                    </a>
                </div>
            @endif
        </div>
    </div>
</header>
