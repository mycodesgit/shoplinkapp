@php
    $curr_route = request()->route()->getName();

    $dashActive = in_array($curr_route, ['dashboard.index', 'dashboard.auth.index']) ? 'text-black' : 'text-gray-500';
    $aboutActive = in_array($curr_route, ['dashboard.about', 'dashboard.auth.about']) ? 'text-black' : 'text-gray-500';
    $itemsActive = in_array($curr_route, ['dashboard.items', 'dashboard.auth.items', 'itemdetails.index', 'itemdetails.auth.index']) ? 'text-black' : 'text-gray-500';
    $cartActive = in_array($curr_route, ['cart.index', 'cart.auth.index']) ? 'text-black' : 'text-gray-500';
    $profileActive = in_array($curr_route, ['profile.account', 'profile.auth.account']) ? 'text-black' : 'text-gray-500';
@endphp

<!-- Mobile Bottom Navigation -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-lg z-40">
    <div class="flex justify-around items-center py-2">
        
        @if(Auth::guard('customer')->check())
            <a href="{{ route('dashboard.auth.index') }}" class="flex flex-col items-center py-1 px-3 {{ $dashActive }}">
                <i class="fas fa-home text-xl"></i>
                <span class="text-[10px] mt-0.5">Home</span>
            </a>
            <a href="{{ route('dashboard.auth.items') }}" class="flex flex-col items-center py-1 px-3 {{ $itemsActive }}">
                <i class="fas fa-store text-xl"></i>
                <span class="text-[10px] mt-0.5">Shop</span>
            </a>
            <a href="wishlist.html" class="flex flex-col items-center py-1 px-3 text-gray-500">
                <i class="far fa-heart text-xl"></i>
                <span class="text-[10px] mt-0.5">Wishlist</span>
            </a>
            <a href="{{ route('cart.auth.index') }}" class="flex flex-col items-center py-1 px-3 {{ $cartActive }} relative">
                <i class="fas fa-shopping-cart text-xl"></i>
                <span class="text-[10px] mt-0.5">Cart</span>
                <span id="mobileCartBadge" class="absolute -top-1 -right-1 bg-black text-white text-[8px] font-bold rounded-full w-3.5 h-3.5 flex items-center justify-center">0</span>
            </a>
            <a href="{{ route('profile.auth.account') }}" class="flex flex-col items-center py-1 px-3 {{ $profileActive }}">
                <i class="fas fa-user text-xl"></i>
                <span class="text-[10px] mt-0.5">Profile</span>
            </a>
        @else
            <a href="{{ route('dashboard.index') }}" class="flex flex-col items-center py-1 px-3 {{ $dashActive }}">
                <i class="fas fa-home text-xl"></i>
                <span class="text-[10px] mt-0.5">Home</span>
            </a>
            <a href="{{ route('dashboard.items') }}" class="flex flex-col items-center py-1 px-3 {{ $itemsActive }}">
                <i class="fas fa-store text-xl"></i>
                <span class="text-[10px] mt-0.5">Shop</span>
            </a>
            <a href="{{ route('dashboard.about') }}" class="flex flex-col items-center py-1 px-3 {{ $aboutActive }}">
                <i class="fas fa-info-circle text-xl"></i>
                <span class="text-[10px] mt-0.5">About</span>
            </a>
        @endif
    </div>
</div>