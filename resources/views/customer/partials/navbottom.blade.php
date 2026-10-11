@php
    $curr_route = request()->route()->getName();

    $dashActive = in_array($curr_route, ['dashboard.index', 'dashboard.auth.index']);
    $aboutActive = in_array($curr_route, ['dashboard.about', 'dashboard.auth.about']);
    $itemsActive = in_array($curr_route, ['dashboard.items', 'dashboard.auth.items', 'itemdetails.index', 'itemdetails.auth.index']);
    $cartActive = in_array($curr_route, ['cart.index', 'cart.auth.index']);
    $orderActive = in_array($curr_route, ['orders.auth.index']);
    $profileActive = in_array($curr_route, ['profile.account', 'profile.auth.account']);

    $tabs = Auth::guard('customer')->check()
        ? [
            ['label' => 'Home', 'icon' => 'fa-home', 'url' => route('dashboard.auth.index'), 'active' => $dashActive, 'badge' => false],
            ['label' => 'Shop', 'icon' => 'fa-store', 'url' => route('dashboard.auth.items'), 'active' => $itemsActive, 'badge' => false],
            ['label' => 'Orders', 'icon' => 'fa-box', 'url' => route('orders.auth.index'), 'active' => $orderActive, 'badge' => false],
            ['label' => 'Cart', 'icon' => 'fa-shopping-cart', 'url' => route('cart.auth.index'), 'active' => $cartActive, 'badge' => true],
            ['label' => 'Profile', 'icon' => 'fa-user', 'url' => route('profile.auth.account'), 'active' => $profileActive, 'badge' => false],
          ]
        : [
            ['label' => 'Home', 'icon' => 'fa-home', 'url' => route('dashboard.index'), 'active' => $dashActive, 'badge' => false],
            ['label' => 'Shop', 'icon' => 'fa-store', 'url' => route('dashboard.items'), 'active' => $itemsActive, 'badge' => false],
            ['label' => 'About', 'icon' => 'fa-info-circle', 'url' => route('dashboard.about'), 'active' => $aboutActive, 'badge' => false],
          ];
@endphp

<!-- Mobile Bottom Navigation -->
<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-100 bg-white/95 pb-[env(safe-area-inset-bottom)] shadow-[0_-4px_20px_rgba(0,0,0,0.06)] backdrop-blur md:hidden" aria-label="Mobile">
    <div class="flex items-stretch justify-around px-2 pt-1.5">
        @foreach($tabs as $tab)
            <a href="{{ $tab['url'] }}"
               @if($tab['active']) aria-current="page" @endif
               class="relative flex min-h-14 min-w-16 flex-col items-center justify-center rounded-2xl px-3 py-2 text-[10px] font-medium transition-all duration-200 active:scale-95 {{ $tab['active'] ? 'font-semibold text-black' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-700' }}">
                @if($tab['active'])
                    <span class="absolute -top-1.5 h-1 w-8 rounded-full bg-black"></span>
                @endif
                <span class="relative leading-none">
                    <i class="fas {{ $tab['icon'] }} text-xl leading-none {{ $tab['active'] ? 'scale-110' : '' }}"></i>
                    @if($tab['badge'])
                        <span id="mobileCartBadge" class="absolute -right-3 -top-2 flex h-4 min-w-4 items-center justify-center rounded-full bg-black px-1 text-[9px] font-bold text-white ring-2 ring-white">0</span>
                    @endif
                </span>
                <span class="mt-1 leading-none">{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
