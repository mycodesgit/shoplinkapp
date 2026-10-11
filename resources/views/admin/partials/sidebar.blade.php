@php
    $isActive = fn ($pattern) => request()->routeIs($pattern) ? 'active' : '';
@endphp

<ul class="nav flex-column gap-1">
    <li class="px-4 pt-2 pb-1">
        <small class="nav-text text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.08em;">Main</small>
    </li>
    <li>
        <a class="nav-link d-flex align-items-center gap-3 {{ $isActive('dash-index') }}" href="{{ route('dash-index') }}" @if(request()->routeIs('dash-index')) aria-current="page" @endif>
            <i class="ti ti-layout-grid"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li>
        <a class="nav-link d-flex align-items-center gap-3 {{ $isActive('category.index') }}" href="{{ route('category.index') }}" @if(request()->routeIs('category.index')) aria-current="page" @endif>
            <i class="ti ti-server"></i><span class="nav-text">Category</span>
        </a>
    </li>
    <li>
        <a class="nav-link d-flex align-items-center gap-3 {{ $isActive('product.index') }}" href="{{ route('product.index') }}" @if(request()->routeIs('product.index')) aria-current="page" @endif>
            <i class="ti ti-shopping-cart"></i><span class="nav-text">Items</span>
        </a>
    </li>
    <li>
        <a class="nav-link d-flex align-items-center gap-3 {{ $isActive('customer.index') }}" href="{{ route('customer.index') }}" @if(request()->routeIs('customer.index')) aria-current="page" @endif>
            <i class="ti ti-users"></i><span class="nav-text">Customers</span>
        </a>
    </li>
    <li>
        <a class="nav-link d-flex align-items-center gap-3 {{ $isActive('order.index') }}" href="{{ route('order.index') }}" @if(request()->routeIs('order.index')) aria-current="page" @endif>
            <i class="ti ti-shopping-bag"></i><span class="nav-text">Orders</span>
        </a>
    </li>
    <li>
        <a class="nav-link d-flex align-items-center gap-3 {{ $isActive('user.index') }}" href="{{ route('user.index') }}" @if(request()->routeIs('user.index')) aria-current="page" @endif>
            <i class="ti ti-user-cog"></i><span class="nav-text">Users</span>
        </a>
    </li>

    <li class="px-4 pt-3 pb-1">
        <small class="nav-text text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.08em;">Manage</small>
    </li>
    <li>
        <a class="nav-link d-flex align-items-center gap-3 {{ $isActive('welcomebanner.index') }}" href="{{ route('welcomebanner.index') }}" @if(request()->routeIs('welcomebanner.index')) aria-current="page" @endif>
            <i class="ti ti-photo"></i><span class="nav-text">Banner</span>
        </a>
    </li>
</ul>
