<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Server-rendered bell notifications: the dropdown always has content
        // even if the live feed fails. JS enhances it with unread state.
        View::composer('customer.partials.navtop', function ($view) {
            $bellOrders = collect();
            try {
                if (Auth::guard('customer')->check()) {
                    $bellOrders = Order::where('customer_id', Auth::guard('customer')->id())
                        ->orderBy('updated_at', 'desc')
                        ->take(6)
                        ->get(['id', 'order_number', 'status', 'updated_at']);
                }
            } catch (\Exception $e) {
                // DB down etc. — leave the bell empty rather than breaking the page.
            }
            $view->with('bellOrders', $bellOrders);
        });
    }
}
