<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

class CustomerBroadcastController extends Controller
{
    /**
     * Pusher-compatible channel authorization for customers.
     * The default /broadcasting/auth endpoint resolves users through the
     * default (admin) guard, so customer-guard sessions get 403 there.
     * This endpoint switches resolution to the customer guard first.
     */
    public function authenticate(Request $request)
    {
        Auth::shouldUse('customer');

        return Broadcast::auth($request);
    }
}
