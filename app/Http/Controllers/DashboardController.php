<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use \App\Traits\CartTrait;

    public function index()
    {
        $cartCount = $this->getCartCount();

        $validOrders = Order::where('status', '!=', 'cancelled');
        $cancelledOrders = Order::where('status', 'cancelled');

        $stats = [
            'revenue' => (clone $validOrders)->sum('total'),
            'revenueToday' => (clone $validOrders)->whereDate('created_at', Carbon::today())->sum('total'),
            'orders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'activeOrders' => Order::whereIn('status', ['accepted', 'preparing', 'ready_to_claim', 'ready_to_deliver', 'out_for_delivery'])->count(),
            'readyOrders' => Order::whereIn('status', ['ready_to_claim', 'ready_to_deliver', 'out_for_delivery'])->count(),
            'deliveredOrders' => Order::where('status', 'delivered')->count(),
            'todayOrders' => Order::whereDate('created_at', Carbon::today())->count(),
            'yesterdayOrders' => Order::whereDate('created_at', Carbon::yesterday())->count(),
            'cancelledToday' => (clone $cancelledOrders)->whereDate('created_at', Carbon::today())->count(),
            'cancelledYesterday' => (clone $cancelledOrders)->whereDate('created_at', Carbon::yesterday())->count(),
            'cancelledMonth' => (clone $cancelledOrders)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count(),
            'cancelledTotal' => (clone $cancelledOrders)->count(),
            'itemsSold' => OrderItem::whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))->sum('quantity'),
            'products' => Product::count(),
            'categories' => Category::count(),
            'customers' => Customer::count(),
            'users' => User::count(),
            'lowStock' => ProductVariation::where('variant_stock', '<=', 10)->count(),
            'outOfStock' => ProductVariation::where('variant_stock', '<=', 0)->count(),
        ];

        // Sales per day, last 7 days (excludes cancelled orders)
        $salesRaw = (clone $validOrders)
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('SUM(total) as total'))
            ->where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $salesLabels = [];
        $salesTotals = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $key = $date->format('Y-m-d');
            $salesLabels[] = $date->format('M d');
            $salesTotals[] = (float) ($salesRaw[$key]->total ?? 0);
        }

        // Orders grouped by status — only statuses present in the DB
        $statusCounts = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        $lowStockItems = ProductVariation::with('product')
            ->where('variant_stock', '<=', 10)
            ->orderBy('variant_stock')
            ->take(6)
            ->get();

        return view('admin.home.dashboard', compact(
            'cartCount',
            'stats',
            'salesLabels',
            'salesTotals',
            'statusCounts',
            'recentOrders',
            'lowStockItems'
        ));
    }

    public function logout(Request $request)
    {
        if (\Auth::guard('web')->check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('getLogin')->with('success', 'You have been Successfully Logged Out');

        } elseif (\Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('dashboard.index')->with('success', 'You have been Successfully Logged Out');
        }

    }
}
