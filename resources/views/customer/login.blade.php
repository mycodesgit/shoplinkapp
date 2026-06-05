<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>ShopLink | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: white;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Custom input field styling */
        .input-field {
            width: 100%;
            padding: 12px 16px 12px 45px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
            background: white;
        }
        
        .input-field:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
        }
        
        .relative {
            position: relative;
        }
        
        /* Custom checkbox styling */
        .checkbox-custom {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
            position: relative;
            transition: all 0.2s ease;
        }
        
        input[type="checkbox"]:checked + .checkbox-custom {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        
        input[type="checkbox"]:checked + .checkbox-custom::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 10px;
        }
        
        /* Login button styling */
        .login-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        /* Social button styling */
        .social-btn {
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
            background: white;
        }
        
        .social-btn:hover {
            border-color: #4f46e5;
            background: #f8fafc;
        }
        
        /* Remove any default card shadow */
        .auth-container {
            background: white;
            border-radius: 0;
            box-shadow: none;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-container {
            animation: fadeIn 0.5s ease-out;
        }

        /* Floating Shopping Icons Styles */
        .floating-icon {
            position: fixed;
            font-size: 24px;
            opacity: 0.15;
            z-index: 0;
            pointer-events: auto;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: float 6s ease-in-out infinite;
        }

        .floating-icon:hover {
            opacity: 0.4;
            transform: scale(1.3) translateY(-8px);
            animation-play-state: paused;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        /* Individual icon positions and animations */
        .icon-1 { top: 10%; left: 5%; animation-delay: 0s; }
        .icon-2 { top: 20%; right: 5%; animation-delay: 1s; }
        .icon-3 { bottom: 15%; left: 3%; animation-delay: 2s; }
        .icon-4 { bottom: 25%; right: 4%; animation-delay: 0.5s; }
        .icon-5 { top: 50%; left: 2%; animation-delay: 1.5s; }
        .icon-6 { top: 60%; right: 3%; animation-delay: 2.5s; }
        .icon-7 { top: 30%; left: 8%; animation-delay: 0.8s; }
        .icon-8 { bottom: 40%; right: 7%; animation-delay: 1.8s; }

        /* Different hover effects for each icon type */
        .floating-icon:hover .fa-bag-shopping {
            animation: shake 0.5s ease-in-out;
        }
        
        .floating-icon:hover .fa-cart-shopping {
            animation: bounce 0.5s ease;
        }
        
        .floating-icon:hover .fa-tag {
            animation: spin 0.5s ease;
        }
        
        .floating-icon:hover .fa-gift {
            animation: pulse 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(10deg); }
            75% { transform: rotate(-10deg); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* Make sure form stays above floating icons */
        .auth-container {
            position: relative;
            z-index: 1;
            background: white;
        }

        /* Responsive - hide some icons on mobile */
        @media (max-width: 768px) {
            .floating-icon {
                font-size: 18px;
                opacity: 0.1;
            }
            .icon-5, .icon-6, .icon-7, .icon-8 {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .floating-icon {
                display: none;
            }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4" style="background: white;">
    
    <!-- Floating Shopping Icons -->
    <div class="floating-icon icon-1">
        <i class="fas fa-bag-shopping"></i>
    </div>
    <div class="floating-icon icon-2">
        <i class="fas fa-cart-shopping"></i>
    </div>
    <div class="floating-icon icon-3">
        <i class="fas fa-tag"></i>
    </div>
    <div class="floating-icon icon-4">
        <i class="fas fa-gift"></i>
    </div>
    <div class="floating-icon icon-5">
        <i class="fas fa-tshirt"></i>
    </div>
    <div class="floating-icon icon-6">
        <i class="fas fa-shoe-prints"></i>
    </div>
    <div class="floating-icon icon-7">
        <i class="fas fa-mobile-alt"></i>
    </div>
    <div class="floating-icon icon-8">
        <i class="fas fa-box"></i>
    </div>
    
    <div class="auth-container w-full max-w-md mx-auto p-8">
        <!-- Back Button -->
        <div class="mb-4 sm:mb-5 md:mb-2">
            <a href="{{ route('dashboard.index') }}" 
            class="back-btn group flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                <i class="fas fa-arrow-left text-sm sm:text-base"></i>
                <span class="text-sm sm:text-base font-medium">Back</span>
            </a>
        </div>

        <!-- Logo -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center">
                <img src="{{ asset('uilibs/images/logonobg.webp') }}" alt="ShopLink Logo" class="h-35 w-auto">
            </div>
            <h4 class="text-md font-bold text-gray-800">Sign in to enjoy</h4>
            <p class="text-gray-500 text-sm">to start shopping</p>
        </div>
        
        <!-- Form -->
        <form action="{{ route('shop.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div class="relative">
                <input type="email" name="email" class="input-field" placeholder="E-mail ID" required>
                <i class="fas fa-envelope input-icon"></i>
            </div>
            
            <div class="relative">
                <input type="password" name="password" class="input-field" placeholder="Password" required>
                <i class="fas fa-lock input-icon"></i>
            </div>
            
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="hidden" id="remember">
                    <span class="checkbox-custom inline-block"></span>
                    <span class="text-sm text-gray-600">Remember me</span>
                </label>
                <a href="#" class="text-sm text-indigo-600 font-medium">Forget Password?</a>
            </div>
            
            <button type="submit" class="login-btn w-full py-3 rounded-2xl text-white font-semibold text-base shadow-md mt-4">
                Login
            </button>
        </form>
        
        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-400">Or</span>
            </div>
        </div>
        
        <!-- Social Login (Commented - kept as requested) -->
        {{-- <div class="grid grid-cols-2 gap-3">
            <button class="social-btn flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white">
                <i class="fab fa-google text-red-500"></i>
                <span class="text-sm font-medium text-gray-700">Google</span>
            </button>
            <button class="social-btn flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white">
                <i class="fab fa-apple text-black"></i>
                <span class="text-sm font-medium text-gray-700">Apple</span>
            </button>
        </div> --}}
        
        <!-- Register Link -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('shop.register') }}" class="text-indigo-600 font-semibold ml-1">Register →</a>
            </p>
        </div>
    </div>
    
</body>
</html>