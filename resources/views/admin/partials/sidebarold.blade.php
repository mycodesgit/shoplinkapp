@php
    $current_route = request()->route()->getName();
@endphp

<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header text-gray">Main Navigation</li>

        <li class="nav-item ripple-effect">
            <a href="{{ route('dash-index') }}"
                class="nav-link {{ request()->routeIs('dash-index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-th"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <li class="nav-item ripple-effect">
            <a href="{{ route('category.index') }}"
                class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-layer-group"></i>
                <p>Category</p>
            </a>
        </li>

        <li class="nav-item ripple-effect">
            <a href="{{ route('product.index') }}"
                class="nav-link {{ request()->routeIs('product.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-sitemap"></i>
                <p>Products</p>
            </a>
        </li>

        <li class="nav-item ripple-effect">
            <a href="{{ route('customer.index') }}"
                class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>Customer</p>
            </a>
        </li>

        <li class="nav-header" style="color: gray">User Management</li>
        <li class="nav-item ripple-effect">
            <a href="{{ route('user.index') }}"
                class="nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users-gear"></i>
                <p>Users</p>
            </a>
        </li>

    </ul>
</nav>
