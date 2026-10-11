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
                <div class="flex items-center gap-1 sm:gap-2 min-w-0">
                    <span class="hidden sm:block text-sm text-gray-600 truncate max-w-32 lg:max-w-48">Hello, {{ Auth::guard('customer')->user()->fname }}</span>
                    <div class="relative">
                        <button id="custBellBtn" type="button" class="relative p-2 rounded-full hover:bg-gray-100 transition" aria-label="Notifications" aria-haspopup="true">
                            <i class="fas fa-bell text-gray-700 text-lg"></i>
                            <span id="custBellBadge" class="hidden absolute top-0.5 right-0.5 bg-black text-white text-[10px] font-bold rounded-full min-w-4 h-4 px-1 items-center justify-center">0</span>
                        </button>
                        <div id="custBellDropdown" class="hidden absolute right-0 top-full mt-2 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-2xl ring-1 ring-black/10 z-50 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-100">
                                <strong class="text-sm text-black">Notifications</strong>
                                <button id="custMarkRead" type="button" class="text-xs text-gray-500 hover:text-black transition">Mark all read</button>
                            </div>
                            <ul id="custBellList" class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                                @php
                                    $tabOf = function ($status) {
                                        if (in_array($status, ['accepted', 'preparing'], true)) return 'accepted';
                                        if (in_array($status, ['ready_to_claim', 'ready_to_deliver', 'out_for_delivery'], true)) return 'ready';
                                        if (in_array($status, ['pending', 'delivered'], true)) return $status;
                                        return null;
                                    };
                                @endphp
                                @forelse($bellOrders ?? [] as $bellOrder)
                                    @php $bellTab = $tabOf($bellOrder->status); @endphp
                                    <li>
                                        <a href="{{ $bellTab ? route('orders.auth.index') . '?tab=' . $bellTab : route('myorder.show', Crypt::encryptString($bellOrder->id)) }}" data-id="{{ $bellOrder->id }}" data-updated="{{ $bellOrder->updated_at }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-gray-50 transition">
                                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-500"><i class="fas fa-box text-xs"></i></span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block truncate text-xs font-semibold text-black">{{ $bellOrder->order_number }}</span>
                                                <span class="mt-0.5 block text-[11px] text-gray-500">{{ ucwords(str_replace('_', ' ', $bellOrder->status)) }} · {{ $bellOrder->updated_at ? $bellOrder->updated_at->format('M d, h:i A') : '' }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @empty
                                    <li class="px-4 py-4 text-center text-sm text-gray-400">No orders yet — your status updates will appear here.</li>
                                @endforelse
                            </ul>
                            <a href="{{ route('orders.auth.index') }}" class="block text-center text-xs font-medium text-gray-500 hover:text-black transition py-2.5 border-t border-gray-100">View my orders</a>
                        </div>
                    </div>
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
