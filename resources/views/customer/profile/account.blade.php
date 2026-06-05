@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade"> 
        
        <!-- Mobile View -->
        <div class="block md:hidden">
            <div class="bg-white rounded-2xl card-item">
                <!-- Profile Header -->
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <i class="fas fa-user-circle text-6xl text-gray-500"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ Auth::guard('customer')->user()->fname }} {{ Auth::guard('customer')->user()->lname }}</h2>
                    <p class="text-gray-500 text-sm">{{ Auth::guard('customer')->user()->email }}</p>
                </div>

                <!-- Premium Banner -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-400 rounded-2xl p-4 mb-6 text-white">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-gift text-2xl"></i>
                        <div class="flex-1">
                            <p class="text-sm font-semibold mb-1">🎉 {{ Auth::guard('customer')->user()->fname }}, join Runna Premium</p>
                            <p class="text-xs opacity-90">Subscribe to unlock the rest of your weeks and reach your full potential</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info Section -->
                <div class="border-t border-gray-100 pt-6 mt-4 mb-4 space-y-4">
                    <!-- Member Since -->
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-500 text-sm">Member Since</span>
                        <span class="text-gray-900 text-sm font-medium">{{ Auth::guard('customer')->user()->created_at->format('F d, Y') }}</span>
                    </div>
                    
                    <!-- My Orders Button -->
                    <a href="orders.html" class="flex items-center justify-between py-3 px-4 bg-gray-200 rounded-xl">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-box text-gray-500"></i>
                            <span class="text-gray-900 font-medium">My Orders</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </a>
                    
                    <!-- Continue Shopping Button -->
                    <a href="shop.html" class="block w-full text-center bg-black text-white py-3 rounded-xl font-medium">
                        Continue Shopping
                    </a>
                </div>

                <!-- Menu Items -->
                <div class="space-y-1 mb-8">
                    <div class="flex items-center justify-between py-3 px-2">
                        <span class="text-gray-900 font-semibold text-sm">CONTENT</span>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-mobile-alt w-5 text-gray-400"></i>
                        <span class="flex-1 text-sm">Connected Apps and Watches</span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-shoe-prints w-5 text-gray-400"></i>
                        <span class="flex-1 text-sm">My Shoes</span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-sliders-h w-5 text-gray-400"></i>
                        <span class="flex-1 text-sm">Workout settings</span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-bell w-5 text-gray-400"></i>
                        <span class="flex-1 text-sm">Notification Settings</span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-users w-5 text-gray-400"></i>
                        <span class="flex-1 text-sm">Community Settings</span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </div>
                </div>

                <!-- Subscribe Buttons -->
                <div class="flex gap-3 mb-8">
                    <form action="{{ route('logout.customer') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="flex-1 bg-blue-100 text-gray-800 py-3 rounded-xl font-semibold text-sm text-center w-full">
                            LOGOUT
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tablet View (2 columns) -->
        <div class="hidden md:block lg:hidden">
            <div class="grid grid-cols-2 gap-6 card-item">
                <!-- Left Column -->
                <div>
                    <!-- Profile -->
                    <div class="bg-white rounded-2xl p-6 text-center mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
                            <i class="fas fa-user-circle text-6xl text-gray-500"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ Auth::guard('customer')->user()->fname }} {{ Auth::guard('customer')->user()->lname }}</h2>
                        <p class="text-gray-500 text-sm">{{ Auth::guard('customer')->user()->email }}</p>
                    </div>
                    
                    <!-- Premium Banner -->
                    <div class="bg-gradient-to-r from-black to-gray-400 rounded-2xl p-4 mb-6 text-white">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-gift text-2xl"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold mb-1">🎉 Join Runna Premium</p>
                                <p class="text-xs opacity-90">Subscribe to unlock the rest of your weeks</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex gap-3 mb-6">
                        <button class="flex-1 bg-black text-white py-3 rounded-xl font-semibold text-sm">SUBSCRIBE</button>
                        <button class="flex-1 bg-gray-100 text-gray-800 py-3 rounded-xl font-semibold text-sm">RESTORE</button>
                    </div>
                    
                    <!-- Member Since & Orders -->
                    <div class="bg-white rounded-2xl p-4 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Member Since</span>
                            <span class="text-gray-900 text-sm font-medium">January 2025</span>
                        </div>
                        <a href="orders.html" class="flex items-center justify-between py-2">
                            <span class="text-gray-900 font-medium">My Orders</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="shop.html" class="block w-full text-center bg-black text-white py-3 rounded-xl font-medium">
                            Continue Shopping
                        </a>
                    </div>
                </div>
                
                <!-- Right Column - Menu Items -->
                <div class="bg-white rounded-2xl p-4">
                    <div class="mb-3">
                        <span class="text-gray-900 font-semibold text-sm">CONTENT</span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-mobile-alt text-gray-400 w-5"></i>
                                <span class="text-sm">Connected Apps and Watches</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        </div>
                        
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-shoe-prints text-gray-400 w-5"></i>
                                <span class="text-sm">My Shoes</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        </div>
                        
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-sliders-h text-gray-400 w-5"></i>
                                <span class="text-sm">Workout settings</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        </div>
                        
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-bell text-gray-400 w-5"></i>
                                <span class="text-sm">Notification Settings</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        </div>
                        
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-users text-gray-400 w-5"></i>
                                <span class="text-sm">Community Settings</span>
                            </div>
                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop View (3 columns) -->
        <div class="hidden lg:block">
            <div class="grid grid-cols-3 gap-6 card-item">
                <!-- Column 1: Profile & Actions -->
                <div>
                    <div class="bg-white rounded-2xl p-6 text-center mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
                            <i class="fas fa-user-circle text-6xl text-gray-500"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ Auth::guard('customer')->user()->fname }} {{ Auth::guard('customer')->user()->lname }}</h2>
                        <p class="text-gray-500 text-sm">{{ Auth::guard('customer')->user()->email }}</p>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-4 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Member Since</span>
                            <span class="text-gray-900 text-sm font-medium">{{ Auth::guard('customer')->user()->created_at->format('F d, Y') }}</span>
                        </div>
                        <a href="orders.html" class="flex items-center justify-between py-2">
                            <span class="text-gray-900 font-medium">My Orders</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="shop.html" class="block w-full text-center bg-black text-white py-3 rounded-xl font-medium">
                            Continue Shopping
                        </a>
                    </div>
                </div>
                
                <!-- Column 2: Premium & Menu -->
                <div class="col-span-2">
                    <div class="bg-gradient-to-r from-black to-gray-400 rounded-2xl p-4 mb-6 text-white">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-gift text-2xl"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold mb-1">🎉 {{ Auth::guard('customer')->user()->fname }}, join Runna Premium</p>
                                <p class="text-xs opacity-90">Subscribe to unlock the rest of your weeks and reach your full potential</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 mb-6">
                        <button class="flex-1 bg-black text-white py-3 rounded-xl font-semibold text-sm">SUBSCRIBE</button>
                        <button class="flex-1 bg-gray-100 text-gray-800 py-3 rounded-xl font-semibold text-sm">LOGOUT</button>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6">
                        <div class="mb-4">
                            <span class="text-gray-900 font-semibold">CONTENT</span>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-mobile-alt text-gray-400 w-5"></i>
                                    <span>Connected Apps and Watches</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                            
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-shoe-prints text-gray-400 w-5"></i>
                                    <span>My Shoes</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                            
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-sliders-h text-gray-400 w-5"></i>
                                    <span>Workout settings</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                            
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-bell text-gray-400 w-5"></i>
                                    <span>Notification Settings</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                            
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-users text-gray-400 w-5"></i>
                                    <span>Community Settings</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection