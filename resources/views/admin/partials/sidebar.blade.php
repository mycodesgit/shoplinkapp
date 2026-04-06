@php
    $current_route = request()->route()->getName();
@endphp

<ul class="nav flex-column">
    <li class="px-4 py-2">
        <small class="nav-text text-muted">Main</small>
    </li>
    <li>
        <a class="nav-link {{ request()->routeIs('dash-index') ? 'active' : '' }}" href="{{ route('dash-index') }}">
            <i class="ti ti-layout-grid"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}" href="{{ route('category.index') }}">
            <i class="ti ti-server"></i><span class="nav-text">Category</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{ request()->routeIs('product.index') ? 'active' : '' }}" href="{{ route('product.index') }}">
            <i class="ti ti-shopping-cart"></i><span class="nav-text">Items</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}" href="{{ route('customer.index') }}">
            <i class="ti ti-users"></i><span class="nav-text">Customers</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}" href="{{ route('user.index') }}">
            <i class="ti ti-user-cog"></i><span class="nav-text">Users</span>
        </a>
    </li>

    <li class="px-4 py-2">
        <small class="nav-text text-muted">Manage</small>
    </li>
    <li>
        <a class="nav-link {{ request()->routeIs('welcomebanner.index') ? 'active' : '' }}" href="{{ route('welcomebanner.index') }}">
            <i class="ti ti-photo"></i><span class="nav-text">Banner</span>
        </a>
    </li>
</ul>