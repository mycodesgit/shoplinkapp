@php
    $curr_route = request()->route()->getName();

    $dashActive = in_array($curr_route, ['dashboard.index']) ? 'text-black' : 'text-gray-500';
    $aboutActive = in_array($curr_route, ['dashboard.about']) ? 'text-black' : 'text-gray-500';
    $itemsActive = in_array($curr_route, ['dashboard.items', 'itemdetails.index']) ? 'text-black' : 'text-gray-500';
    $cartActive = in_array($curr_route, ['cart.index']) ? 'text-black' : 'text-gray-500';
    $profileActive = in_array($curr_route, ['profile.account']) ? 'text-black' : 'text-gray-500';
@endphp

<header class="sticky top-0 z-30 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14 md:h-16">
            <div class="flex items-center gap-2">
                <img src="{{ asset('uilibs/images/logonobg.png') }}" alt="ShopLink Logo" class="h-25 w-auto">
                {{-- <span class="font-bold text-xl md:text-2xl tracking-tight">SHOPLINK</span> --}}
            </div>
            
            <div class="hidden md:flex items-center gap-8 text-gray-700 font-medium">
                <a href="{{ route('dashboard.index') }}" class="{{ $dashActive }}">Home</a>
                <a href="{{ route('dashboard.items') }}" class="{{ $itemsActive }}">Shop</a>
                @if(Auth::guard('customer')->check())
                    <a href="wishlist.html" class="hover:text-gray-600">Wishlist</a>
                    <a href="{{ route('cart.index') }}" class="{{ $cartActive }}">Cart</a>
                    <a href="{{ route('profile.account') }}" class="{{ $profileActive }}">Profile</a>
                @else
                    <a href="{{ route('dashboard.about') }}" class="{{ $aboutActive }}">About</a>
                @endif
            </div>

            @if(Auth::guard('customer')->check())
                <div class="flex items-center gap-4">
                    <span class="text-gray-700 text-sm">Hello, {{ Auth::guard('customer')->user()->fname }}</span>
                    <a href="{{ route('cart.index') }}" class="relative">
                        <i class="fas fa-shopping-bag text-gray-700 text-lg"></i>
                        <span id="cartCount" class="absolute -top-2 -right-3 bg-orange-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">0</span>
                    </a>
                </div>
            @else
                <div class="flex items-center gap-4">
                    <a href="{{ route('shop.login') }}" 
                    class="flex items-center gap-2 bg-transparent hover:bg-amber-500 text-amber-600 hover:text-white border-2 border-amber-500 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                        <i class="fas fa-sign-in-alt text-sm"></i>
                        Sign In
                    </a>
                </div>
            @endif
        </div>
    </div>
</header>