@extends('admin.layouts.master')

@section('title')
    Shoplink || Dashboard
@endsection

@push('styles')
<style>
    .stat-tile {
        width: 44px;
        height: 44px;
        font-size: 20px;
        flex-shrink: 0;
    }
    .dash-chart {
        min-height: 300px;
    }
    .dash-chart-fallback {
        min-height: 300px;
    }
    /* React-like mount animation: staggered rise + fade on every load */
    .dash-anim {
        opacity: 0;
        animation: dashRise 0.55s cubic-bezier(0.21, 0.9, 0.35, 1) forwards;
        animation-delay: var(--d, 0s);
        will-change: transform, opacity;
    }
    @keyframes dashRise {
        from {
            opacity: 0;
            transform: translateY(14px) scale(0.99);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    @media (prefers-reduced-motion: reduce) {
        .dash-anim {
            animation: none;
            opacity: 1;
            transform: none;
        }
    }
    .count-chip {
        min-width: 0;
    }
    .count-chip .num {
        font-size: 1.375rem;
        line-height: 1.1;
    }
</style>
@endpush

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                    <div>
                        <h1 class="fs-3 fw-bold mb-0">Dashboard</h1>
                        <small class="text-muted">{{ now()->format('l, F d, Y') }}</small>
                    </div>
                    <div class="d-none d-md-block">
                        <span class="badge bg-dark">
                            <i class="ti ti-clock me-1"></i>{{ $stats['pendingOrders'] }} pending orders
                        </span>
                    </div>
                </div>

                <div class="card rounded-2 mb-3 dash-anim" style="--d: 0s;">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-tile d-inline-flex align-items-center justify-content-center rounded-2 bg-black text-white">
                                <i class="ti ti-user"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fs-4 fw-bold">Welcome back, {{ Auth::guard('web')->user()->fname ?? 'Admin' }}</h2>
                                <p class="text-muted mb-0 small">Here's what's happening in your store today.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stat cards --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6 col-xl-3 dash-anim" style="--d: 0.05s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="stat-tile d-inline-flex align-items-center justify-content-center rounded-2 bg-black text-white">
                                    <i class="ti ti-wallet"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Total Revenue</div>
                                    <div class="fs-4 fw-bold">₱{{ number_format($stats['revenue'], 2) }}</div>
                                    <small class="text-muted">₱{{ number_format($stats['revenueToday'], 2) }} today</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 dash-anim" style="--d: 0.1s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="stat-tile d-inline-flex align-items-center justify-content-center rounded-2 bg-black text-white">
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Total Orders</div>
                                    <div class="fs-4 fw-bold">{{ number_format($stats['orders']) }}</div>
                                    <small class="text-muted">{{ $stats['todayOrders'] }} today · {{ $stats['yesterdayOrders'] }} yesterday</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 dash-anim" style="--d: 0.15s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="stat-tile d-inline-flex align-items-center justify-content-center rounded-2 bg-black text-white">
                                    <i class="ti ti-package"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Items Sold</div>
                                    <div class="fs-4 fw-bold">{{ number_format($stats['itemsSold']) }}</div>
                                    <small class="text-muted">{{ $stats['products'] }} products · {{ $stats['categories'] }} categories</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 dash-anim" style="--d: 0.2s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="stat-tile d-inline-flex align-items-center justify-content-center rounded-2 bg-black text-white">
                                    <i class="ti ti-users"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Customers</div>
                                    <div class="fs-4 fw-bold">{{ number_format($stats['customers']) }}</div>
                                    <small class="text-muted">{{ $stats['users'] }} staff user(s)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order status + cancellation counts --}}
                @php $preparing = max(0, $stats['activeOrders'] - $stats['readyOrders']); @endphp
                <div class="row g-3 mb-3">
                    <div class="col-lg-8 dash-anim" style="--d: 0.25s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1">Orders Snapshot</h6>
                                <small class="text-muted">Live counts straight from your orders table</small>
                                <div class="d-flex flex-wrap gap-4 mt-3">
                                    <div class="count-chip">
                                        <div class="num fw-bold">{{ number_format($stats['pendingOrders']) }}</div>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                    <div class="count-chip">
                                        <div class="num fw-bold">{{ number_format($preparing) }}</div>
                                        <small class="text-muted">Preparing</small>
                                    </div>
                                    <div class="count-chip">
                                        <div class="num fw-bold">{{ number_format($stats['readyOrders']) }}</div>
                                        <small class="text-muted">Ready</small>
                                    </div>
                                    <div class="count-chip">
                                        <div class="num fw-bold">{{ number_format($stats['deliveredOrders']) }}</div>
                                        <small class="text-muted">Delivered</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 dash-anim" style="--d: 0.3s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1">Cancellations</h6>
                                <small class="text-muted">Cancelled order counts</small>
                                <ul class="list-group list-group-flush mt-2">
                                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span class="small">Today</span>
                                        <span class="badge bg-dark">{{ $stats['cancelledToday'] }}</span>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span class="small">Yesterday</span>
                                        <span class="badge bg-dark">{{ $stats['cancelledYesterday'] }}</span>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span class="small">This Month</span>
                                        <span class="badge bg-dark">{{ $stats['cancelledMonth'] }}</span>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span class="small">All Time</span>
                                        <span class="badge bg-dark">{{ $stats['cancelledTotal'] }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts --}}
                <div class="row g-3 mb-3">
                    <div class="col-lg-8 dash-anim" style="--d: 0.35s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body">
                                <div class="mb-2">
                                    <h6 class="fw-bold mb-0">Sales Overview</h6>
                                    <small class="text-muted">Revenue · last 7 days · ₱{{ number_format(array_sum($salesTotals), 2) }} total</small>
                                </div>
                                <div id="revenueChart" class="dash-chart"></div>
                                <div id="revenueChartFallback" class="dash-chart-fallback d-none align-items-center justify-content-center text-muted small">
                                    Chart unavailable offline — showing totals above.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 dash-anim" style="--d: 0.4s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-0">Orders by Status</h6>
                                <small class="text-muted">Only statuses in your database</small>
                                <div id="statusChart" class="dash-chart"></div>
                                <div id="statusChartFallback" class="dash-chart-fallback d-none align-items-center justify-content-center text-muted small">
                                    Chart unavailable offline.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tables --}}
                <div class="row g-3 mb-3">
                    <div class="col-lg-8 dash-anim" style="--d: 0.45s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold mb-0">Recent Orders</h6>
                                    <small class="text-muted">Latest 5</small>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Customer</th>
                                                <th>Status</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $statusPill = [
                                                    'pending' => ['Pending', 'bg-light text-dark border'],
                                                    'accepted' => ['Accepted', 'bg-secondary text-white'],
                                                    'preparing' => ['Preparing', 'bg-secondary text-white'],
                                                    'ready_to_claim' => ['Ready to Claim', 'bg-dark text-white'],
                                                    'ready_to_deliver' => ['Ready to Deliver', 'bg-dark text-white'],
                                                    'out_for_delivery' => ['Out for Delivery', 'bg-dark text-white'],
                                                    'delivered' => ['Delivered', 'bg-dark text-white'],
                                                    'cancelled' => ['Cancelled', 'bg-light text-dark border'],
                                                ];
                                            @endphp
                                            @forelse($recentOrders as $order)
                                                @php $pill = $statusPill[$order->status] ?? [ucfirst($order->status), 'bg-light text-dark border']; @endphp
                                                <tr>
                                                    <td class="fw-semibold">{{ $order->order_number }}</td>
                                                    <td>{{ $order->customer->fname ?? $order->customer_name ?? '—' }} {{ $order->customer->lname ?? '' }}</td>
                                                    <td><span class="badge {{ $pill[1] }}">{{ $pill[0] }}</span></td>
                                                    <td class="text-end fw-semibold">₱{{ number_format($order->total, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">No orders yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 dash-anim" style="--d: 0.5s;">
                        <div class="card rounded-2 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold mb-0">Low Stock Alert</h6>
                                    <span class="badge bg-dark">{{ $stats['lowStock'] }}</span>
                                </div>
                                <ul class="list-group list-group-flush">
                                    @forelse($lowStockItems as $variation)
                                        <li class="list-group-item px-0 d-flex align-items-center justify-content-between gap-2">
                                            <div class="text-truncate">
                                                <div class="fw-semibold text-truncate">{{ $variation->product->prdctname ?? 'Unknown product' }}</div>
                                                <small class="text-muted">{{ $variation->variation_name }}: {{ $variation->variation_value }}</small>
                                            </div>
                                            <span class="badge {{ $variation->variant_stock <= 0 ? 'bg-dark text-white' : 'bg-light text-dark border' }} flex-shrink-0">
                                                {{ $variation->variant_stock }} left
                                            </span>
                                        </li>
                                    @empty
                                        <li class="list-group-item px-0 text-muted small">All stocked up. Nice.</li>
                                    @endforelse
                                </ul>
                                @if($stats['outOfStock'] > 0)
                                    <small class="text-muted">{{ $stats['outOfStock'] }} variation(s) completely out of stock.</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        (function () {
            var salesLabels = @json($salesLabels);
            var salesTotals = @json($salesTotals);
            var statusCounts = @json($statusCounts);

            var statusLabels = Object.keys(statusCounts).map(function (s) {
                return s.replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
            });
            var statusSeries = Object.keys(statusCounts).map(function (s) { return statusCounts[s]; });

            if (!window.ApexCharts) {
                document.getElementById('revenueChartFallback').classList.remove('d-none');
                document.getElementById('revenueChartFallback').classList.add('d-flex');
                document.getElementById('statusChartFallback').classList.remove('d-none');
                document.getElementById('statusChartFallback').classList.add('d-flex');
                return;
            }

            new ApexCharts(document.querySelector('#revenueChart'), {
                chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'Poppins, sans-serif' },
                series: [{ name: 'Revenue (₱)', data: salesTotals }],
                xaxis: { categories: salesLabels, labels: { style: { fontSize: '11px' } } },
                yaxis: { labels: { formatter: function (v) { return '₱' + Number(v).toLocaleString(); } } },
                colors: ['#000000'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 0.4, opacityFrom: 0.35, opacityTo: 0.02 } },
                stroke: { width: 2 },
                markers: { size: 0 },
                dataLabels: { enabled: false },
                grid: { borderColor: '#e5e7eb' },
                tooltip: { y: { formatter: function (v) { return '₱' + Number(v).toLocaleString(undefined, { minimumFractionDigits: 2 }); } } }
            }).render();

            if (statusSeries.length) {
                new ApexCharts(document.querySelector('#statusChart'), {
                    chart: { type: 'donut', height: 300, fontFamily: 'Poppins, sans-serif' },
                    series: statusSeries,
                    labels: statusLabels,
                    colors: ['#000000', '#3f3f46', '#71717a', '#a1a1aa', '#d4d4d8', '#52525b', '#18181b', '#e4e4e7'],
                    legend: { position: 'bottom', fontSize: '12px' },
                    dataLabels: { enabled: false },
                    stroke: { width: 2, colors: ['#ffffff'] }
                }).render();
            } else {
                document.getElementById('statusChart').innerHTML = '<p class="text-muted small text-center pt-5">No orders yet.</p>';
            }
        })();
    </script>
@endpush
