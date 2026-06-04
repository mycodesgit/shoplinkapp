@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade">
        
        <!-- About Us Section -->
        <div class="max-w-4xl mx-auto">
            
            <!-- Header Section -->
            <div class="text-center mb-8 sm:mb-12 md:mb-16">
                <h4 class="text-lg sm:text-4xl md:text-5xl font-bold text-gray-900 mb-3 sm:mb-4">
                    About <span class="text-orange-500">ShopLink</span>
                </h4>
                <div class="w-20 h-1 bg-orange-500 mx-auto mb-4 sm:mb-6"></div>
                <p class="text-gray-600 text-sm sm:text-base md:text-lg max-w-2xl mx-auto">
                    Your trusted shopping companion for the best deals and seamless experience
                </p>
            </div>

            <!-- Mission & Vision Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 mb-12 sm:mb-16">
                <!-- Mission Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-xl transition-shadow duration-300">
                    <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mb-4 mx-auto md:mx-0">
                        <i class="fas fa-bullseye text-2xl text-orange-500"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 text-center md:text-left">Our Mission</h3>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed text-center md:text-left">
                        To provide a seamless, secure, and enjoyable online shopping experience, 
                        connecting customers with quality products at the best prices.
                    </p>
                </div>

                <!-- Vision Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 hover:shadow-xl transition-shadow duration-300">
                    <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mb-4 mx-auto md:mx-0">
                        <i class="fas fa-eye text-2xl text-orange-500"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 text-center md:text-left">Our Vision</h3>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed text-center md:text-left">
                        To become the leading e-commerce platform that revolutionizes online shopping 
                        with innovation, trust, and customer-centric values.
                    </p>
                </div>
            </div>

            <!-- Story Section -->
            <div class="mb-12 sm:mb-16">
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl p-6 sm:p-8 md:p-10">
                    <div class="flex flex-col md:flex-row items-center gap-6 md:gap-10">
                        <div class="flex-1 text-center md:text-left">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">Our Story</h2>
                            <p class="text-gray-700 text-sm sm:text-base leading-relaxed mb-4">
                                Founded in 2024, ShopLink started with a simple idea: to make online shopping 
                                accessible, affordable, and enjoyable for everyone. What began as a small 
                                startup has grown into a trusted platform serving thousands of happy customers.
                            </p>
                            <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                                We believe in putting our customers first, offering quality products, 
                                secure transactions, and exceptional customer service.
                            </p>
                        </div>
                        <div class="flex-1">
                            <div class="bg-white rounded-xl p-4 shadow-md">
                                <i class="fas fa-shopping-bag text-6xl text-orange-500 w-full text-center block"></i>
                                <div class="grid grid-cols-2 gap-4 mt-4 text-center">
                                    <div>
                                        <div class="text-2xl sm:text-3xl font-bold text-orange-500">10K+</div>
                                        <div class="text-xs sm:text-sm text-gray-600">Happy Customers</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl sm:text-3xl font-bold text-orange-500">50K+</div>
                                        <div class="text-xs sm:text-sm text-gray-600">Products Sold</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl sm:text-3xl font-bold text-orange-500">500+</div>
                                        <div class="text-xs sm:text-sm text-gray-600">Brands</div>
                                    </div>
                                    <div>
                                        <div class="text-2xl sm:text-3xl font-bold text-orange-500">4.8</div>
                                        <div class="text-xs sm:text-sm text-gray-600">Rating</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Core Values -->
            <div class="mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 text-center mb-8 sm:mb-10">Our Core Values</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <div class="text-center p-4 sm:p-5 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-shield-alt text-xl text-orange-500"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-2">Trust & Security</h4>
                        <p class="text-gray-600 text-xs sm:text-sm">Safe and secure transactions</p>
                    </div>
                    <div class="text-center p-4 sm:p-5 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-star text-xl text-orange-500"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-2">Quality First</h4>
                        <p class="text-gray-600 text-xs sm:text-sm">Premium products guaranteed</p>
                    </div>
                    <div class="text-center p-4 sm:p-5 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-headset text-xl text-orange-500"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-2">24/7 Support</h4>
                        <p class="text-gray-600 text-xs sm:text-sm">Always here to help you</p>
                    </div>
                    <div class="text-center p-4 sm:p-5 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-rocket text-xl text-orange-500"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-2">Fast Delivery</h4>
                        <p class="text-gray-600 text-xs sm:text-sm">Quick shipping worldwide</p>
                    </div>
                </div>
            </div>

            <!-- Team Section -->
            <div class="mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 text-center mb-8 sm:mb-10">Meet Our Team</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <div class="text-center group">
                        <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-r from-orange-400 to-orange-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg group-hover:scale-105 transition-transform duration-300">
                            JD
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">John Doe</h4>
                        <p class="text-orange-500 text-sm mb-2">Founder & CEO</p>
                        <p class="text-gray-600 text-xs sm:text-sm">Visionary leader with 10+ years in e-commerce</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-r from-orange-400 to-orange-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg group-hover:scale-105 transition-transform duration-300">
                            JS
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Jane Smith</h4>
                        <p class="text-orange-500 text-sm mb-2">Operations Manager</p>
                        <p class="text-gray-600 text-xs sm:text-sm">Ensuring smooth operations daily</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-r from-orange-400 to-orange-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg group-hover:scale-105 transition-transform duration-300">
                            MR
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Mike Ross</h4>
                        <p class="text-orange-500 text-sm mb-2">Tech Lead</p>
                        <p class="text-gray-600 text-xs sm:text-sm">Innovative tech solutions expert</p>
                    </div>
                </div>
            </div>

            <!-- Simple CTA -->
            <div class="text-center p-6 sm:p-8 rounded-xl" style="background-color: #23273e;">
                <h2 class="text-xl sm:text-2xl font-semibold text-white mb-2">Start Shopping Today</h2>
                <p class="text-white text-opacity-80 text-sm mb-4">Join thousands of happy customers</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="#" class="inline-flex items-center justify-center gap-2 bg-white px-6 py-2 rounded-lg text-sm font-medium transition hover:scale-105" style="color: #23273e;">
                        <i class="fas fa-user-plus"></i>
                        Sign Up
                    </a>
                    <a href="#" class="inline-flex items-center justify-center gap-2 border border-white text-white px-6 py-2 rounded-lg text-sm font-medium transition hover:bg-white hover:text-[#23273e]">
                        <i class="fas fa-shopping-cart"></i>
                        Shop Now
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection