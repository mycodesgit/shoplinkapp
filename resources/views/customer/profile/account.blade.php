@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade"> 
        
        <!-- Mobile View -->
        <div class="block md:hidden">
            <div class="bg-white rounded-2xl card-item">
                <!-- Profile Header -->
                <div class="text-center mb-6">
                    @if(Auth::guard('customer')->user()->avatar)
                        <div class="w-24 h-24 rounded-full mx-auto mb-3 overflow-hidden">
                            <img src="{{ Storage::url(Auth::guard('customer')->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
                            <i class="fas fa-user-circle text-6xl text-gray-500"></i>
                        </div>
                    @endif
                    <h2 class="text-xl font-bold text-gray-900">{{ Auth::guard('customer')->user()->fname }} {{ Auth::guard('customer')->user()->lname }}</h2>
                    <p class="text-gray-500 text-sm">{{ Auth::guard('customer')->user()->email }}</p>
                    <button type="button" onclick="openEditModal()" class="mt-3 text-sm text-blue-600 hover:text-blue-700">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
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
                    <a href="{{ route('orders.auth.index') }}" class="flex items-center justify-between py-3 px-4 bg-gray-200 rounded-xl">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-box text-gray-500"></i>
                            <span class="text-gray-900 font-medium">My Orders</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </a>
                    
                    <!-- Continue Shopping Button -->
                    <a href="{{ route('dashboard.auth.items') }}" class="block w-full text-center bg-black text-white py-3 rounded-xl font-medium">
                        Continue Shopping
                    </a>
                </div>

                <!-- Personal Information Section -->
                <div class="space-y-1 mb-8">
                    <div class="flex items-center justify-between py-3 px-2">
                        <span class="text-gray-900 font-semibold text-sm">PERSONAL INFORMATION</span>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-user w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Full Name</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->fname }} {{ Auth::guard('customer')->user()->mname ? Auth::guard('customer')->user()->mname . ' ' : '' }}{{ Auth::guard('customer')->user()->lname }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-venus-mars w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Gender</span>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst(Auth::guard('customer')->user()->gender ?? 'Not specified') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-birthday-cake w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Birthday</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->bday ? date('F d, Y', strtotime(Auth::guard('customer')->user()->bday)) : 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-phone w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Mobile Number</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->mobileno ?? 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-envelope w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Email Address</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="space-y-1 mb-8">
                    <div class="flex items-center justify-between py-3 px-2">
                        <span class="text-gray-900 font-semibold text-sm">ADDRESS INFORMATION</span>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-home w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">House/Unit #</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->hnum ?? 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Barangay</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->brgy ?? 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-city w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">City/Municipality</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->city ?? 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-landmark w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Province</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->province ?? 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-globe w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Region</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->region ?? 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-mail-bulk w-5 text-gray-400"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Postal Code</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->zcode ?? 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 py-3 px-2 text-gray-700">
                        <i class="fas fa-address-card w-5 text-gray-400 mt-1"></i>
                        <div class="flex-1">
                            <span class="text-sm text-gray-500">Complete Address</span>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->address ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <div class="flex gap-3 mb-8">
                    <form action="{{ route('logout.customer') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-xl font-semibold text-sm text-center w-full">
                            <i class="fas fa-sign-out"></i> LOGOUT
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tablet View -->
        <div class="hidden md:block lg:hidden">
            <!-- Keep your existing tablet view code but update the edit button -->
            <div class="grid grid-cols-2 gap-6 card-item">
                <!-- Left Column -->
                <div>
                    <div class="bg-white rounded-2xl p-6 text-center mb-6">
                        @if(Auth::guard('customer')->user()->avatar)
                            <div class="w-24 h-24 rounded-full mx-auto mb-3 overflow-hidden">
                                <img src="{{ Storage::url(Auth::guard('customer')->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <i class="fas fa-user-circle text-6xl text-gray-500"></i>
                            </div>
                        @endif
                        <h2 class="text-xl font-bold text-gray-900">{{ Auth::guard('customer')->user()->fname }} {{ Auth::guard('customer')->user()->lname }}</h2>
                        <p class="text-gray-500 text-sm">{{ Auth::guard('customer')->user()->email }}</p>
                        <button type="button" onclick="openEditModal()" class="mt-3 text-sm text-blue-600 hover:text-blue-700">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                    
                    <!-- Rest of tablet view content -->
                    <div class="bg-gradient-to-r from-black to-gray-400 rounded-2xl p-4 mb-6 text-white">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-gift text-2xl"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold mb-1">🎉 Join Runna Premium</p>
                                <p class="text-xs opacity-90">Subscribe to unlock the rest of your weeks</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 mb-6">
                        <form action="{{ route('logout.customer') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-xl font-semibold text-sm">
                                <i class="fas fa-sign-out"></i> LOGOUT
                            </button>
                        </form>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-4 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Member Since</span>
                            <span class="text-gray-900 text-sm font-medium">{{ Auth::guard('customer')->user()->created_at->format('F d, Y') }}</span>
                        </div>
                        <a href="{{ route('orders.auth.index') }}" class="flex items-center justify-between py-2">
                            <span class="text-gray-900 font-medium">My Orders</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('dashboard.auth.items') }}" class="block w-full text-center bg-black text-white py-3 rounded-xl font-medium">
                            Continue Shopping
                        </a>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="bg-white rounded-2xl p-4">
                    <div class="mb-3">
                        <span class="text-gray-900 font-semibold text-sm">PERSONAL INFORMATION</span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-user text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Full Name</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->fname }} {{ Auth::guard('customer')->user()->mname ? Auth::guard('customer')->user()->mname . ' ' : '' }}{{ Auth::guard('customer')->user()->lname }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-venus-mars text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Gender</span>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst(Auth::guard('customer')->user()->gender ?? 'Not specified') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-birthday-cake text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Birthday</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->bday ? date('F d, Y', strtotime(Auth::guard('customer')->user()->bday)) : 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-phone text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Mobile Number</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->mobileno ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-envelope text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Email Address</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 my-4"></div>
                    
                    <div class="mb-3">
                        <span class="text-gray-900 font-semibold text-sm">ADDRESS INFORMATION</span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-home text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">House/Unit #</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->hnum ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Barangay</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->brgy ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-city text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">City/Municipality</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->city ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-landmark text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Province</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->province ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-globe text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Region</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->region ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-mail-bulk text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Postal Code</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->zcode ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 py-2">
                            <i class="fas fa-address-card text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Complete Address</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->address ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop View -->
        <div class="hidden lg:block">
            <!-- Keep your existing desktop view code but update the edit button -->
            <div class="grid grid-cols-3 gap-6 card-item">
                <!-- Column 1 -->
                <div>
                    <div class="bg-white rounded-2xl p-6 text-center mb-6">
                        @if(Auth::guard('customer')->user()->avatar)
                            <div class="w-24 h-24 rounded-full mx-auto mb-3 overflow-hidden">
                                <img src="{{ Storage::url(Auth::guard('customer')->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <i class="fas fa-user-circle text-6xl text-gray-500"></i>
                            </div>
                        @endif
                        <h2 class="text-xl font-bold text-gray-900">{{ Auth::guard('customer')->user()->fname }} {{ Auth::guard('customer')->user()->lname }}</h2>
                        <p class="text-gray-500 text-sm">{{ Auth::guard('customer')->user()->email }}</p>
                        <button type="button" onclick="openEditModal()" class="mt-3 text-sm text-blue-600 hover:text-blue-700">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-4 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Member Since</span>
                            <span class="text-gray-900 text-sm font-medium">{{ Auth::guard('customer')->user()->created_at->format('F d, Y') }}</span>
                        </div>
                        <a href="{{ route('orders.auth.index') }}" class="flex items-center justify-between py-2">
                            <span class="text-gray-900 font-medium">My Orders</span>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('dashboard.auth.items') }}" class="block w-full text-center bg-black text-white py-3 rounded-xl font-medium">
                            Continue Shopping
                        </a>
                    </div>
                </div>
                
                <!-- Column 2 -->
                <div class="bg-white rounded-2xl p-6">
                    <div class="mb-4">
                        <span class="text-gray-900 font-semibold">PERSONAL INFORMATION</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-user text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Full Name</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->fname }} {{ Auth::guard('customer')->user()->mname ? Auth::guard('customer')->user()->mname . ' ' : '' }}{{ Auth::guard('customer')->user()->lname }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-venus-mars text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Gender</span>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst(Auth::guard('customer')->user()->gender ?? 'Not specified') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-birthday-cake text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Birthday</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->bday ? date('F d, Y', strtotime(Auth::guard('customer')->user()->bday)) : 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-phone text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Mobile Number</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->mobileno ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3">
                            <i class="fas fa-envelope text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Email Address</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 mb-4">
                        <span class="text-gray-900 font-semibold">ADDRESS INFORMATION</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-home text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">House/Unit #</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->hnum ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Barangay</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->brgy ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-city text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">City/Municipality</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->city ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-landmark text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Province</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->province ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-globe text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Region</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->region ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100">
                            <i class="fas fa-mail-bulk text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Postal Code</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->zcode ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <i class="fas fa-address-card text-gray-400 w-5 mt-1"></i>
                            <div class="flex-1">
                                <span class="text-xs text-gray-500">Complete Address</span>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::guard('customer')->user()->address ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Column 3 -->
                <div>
                    <div class="bg-white rounded-2xl p-6 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Account Settings</h3>
                        <div class="space-y-3">
                            <a href="#" class="flex items-center justify-between py-2 hover:bg-gray-50 px-2 rounded-lg transition">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-key text-gray-400"></i>
                                    <span>Change Password</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                            </a>
                            
                            <a href="#" class="flex items-center justify-between py-2 hover:bg-gray-50 px-2 rounded-lg transition">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-bell text-gray-400"></i>
                                    <span>Notification Settings</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6">
                        <form action="{{ route('logout.customer') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-gray-200 text-gray-800 py-3 rounded-xl font-semibold text-sm">
                                <i class="fas fa-sign-out"></i> LOGOUT
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; overflow-y: auto;">
        <div style="background: white; border-radius: 1rem; max-width: 42rem; width: 100%; margin: auto; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            
            <form id="editProfileForm" action="#" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
                @csrf
                @method('PUT')
                
                <!-- Header inside form -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: bold; color: #111827; margin: 0;">Edit Profile</h3>
                    <button onclick="closeEditModal()" type="button" style="color: #6b7280; background: none; border: none; font-size: 1.5rem; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">&times;</button>
                </div>
                
                <!-- Avatar Upload -->
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="position: relative; display: inline-block;">
                        <div id="avatarPreview" style="width: 6rem; height: 6rem; border-radius: 50%; margin: 0 auto; overflow: hidden; background: #f3f4f6; border: 2px solid #e5e7eb;">
                            @if(Auth::guard('customer')->user()->avatar)
                                <img src="{{ Storage::url(Auth::guard('customer')->user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;" id="avatarImg">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(to bottom right, #e5e7eb, #d1d5db);">
                                    <i class="fas fa-user-circle" style="font-size: 3rem; color: #6b7280;"></i>
                                </div>
                            @endif
                        </div>
                        <label for="avatar" style="position: absolute; bottom: 0; right: 0; background: #2563eb; border-radius: 9999px; padding: 0.5rem; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                            <i class="fas fa-camera" style="color: white; font-size: 0.75rem;"></i>
                        </label>
                        <input type="file" name="avatar" id="avatar" style="display: none;" accept="image/*" onchange="previewAvatar(this)">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">First Name *</label>
                        <input type="text" name="fname" value="{{ Auth::guard('customer')->user()->fname }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Middle Name</label>
                        <input type="text" name="mname" value="{{ Auth::guard('customer')->user()->mname }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Last Name *</label>
                        <input type="text" name="lname" value="{{ Auth::guard('customer')->user()->lname }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Email *</label>
                        <input type="email" name="email" value="{{ Auth::guard('customer')->user()->email }}" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Gender</label>
                        <select name="gender" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                            <option value="">Select Gender</option>
                            <option value="male" {{ Auth::guard('customer')->user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ Auth::guard('customer')->user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ Auth::guard('customer')->user()->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Birthday</label>
                        <input type="date" name="bday" value="{{ Auth::guard('customer')->user()->bday }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Mobile Number</label>
                        <input type="tel" name="mobileno" value="{{ Auth::guard('customer')->user()->mobileno }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                    </div>
                </div>
                
                <div style="border-top: 1px solid #e5e7eb; margin-top: 1rem; padding-top: 1rem;">
                    <h4 style="font-weight: 600; color: #111827; margin-bottom: 1rem; font-size: 1.125rem;">Address Information</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">House/Unit #</label>
                            <input type="text" name="hnum" value="{{ Auth::guard('customer')->user()->hnum }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Barangay</label>
                            <input type="text" name="brgy" value="{{ Auth::guard('customer')->user()->brgy }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">City/Municipality</label>
                            <input type="text" name="city" value="{{ Auth::guard('customer')->user()->city }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Province</label>
                            <input type="text" name="province" value="{{ Auth::guard('customer')->user()->province }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Region</label>
                            <input type="text" name="region" value="{{ Auth::guard('customer')->user()->region }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Postal Code</label>
                            <input type="text" name="zcode" value="{{ Auth::guard('customer')->user()->zcode }}" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; box-sizing: border-box;">
                        </div>
                        
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Complete Address</label>
                            <textarea name="address" rows="3" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; background: white; color: #1f2937; resize: vertical; box-sizing: border-box;">{{ Auth::guard('customer')->user()->address }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    <button type="button" onclick="closeEditModal()" style="flex: 1; background: #f3f4f6; color: #374151; padding: 0.625rem; border-radius: 0.5rem; font-weight: 500; border: none; cursor: pointer;">
                        Cancel
                    </button>
                    <button type="submit" style="flex: 1; background: #2563eb; color: white; padding: 0.625rem; border-radius: 0.5rem; font-weight: 500; border: none; cursor: pointer;">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal() {
            const modal = document.getElementById('editProfileModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        }
        
        function closeEditModal() {
            const modal = document.getElementById('editProfileModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }
        
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarPreview = document.getElementById('avatarPreview');
                    if (avatarPreview) {
                        avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;" id="avatarImg">`;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('editProfileModal');
            if (modal && modal.style.display === 'flex') {
                if (event.target === modal) {
                    closeEditModal();
                }
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('editProfileModal');
                if (modal && modal.style.display === 'flex') {
                    closeEditModal();
                }
            }
        });
    </script>
@endsection