<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ShopLink | Admin Login</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('uilibs/images/favicon.svg') }}">

    <link rel="stylesheet" href="{{ asset('uilibs/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/toastr/toastr.min.css') }}">

    <style>
        /* shadcn-style tuning on top of the main.css kit (monochrome) */
        body {
            background-color: #fafafa;
        }
        .auth-card {
            border: 1px solid #e5e7eb !important;
            border-radius: 12px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        }
        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -0.025em;
        }
        .auth-form .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.375rem;
            color: #111;
        }
        .auth-form .form-control {
            min-height: 2.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }
        .auth-form .form-control:focus {
            border-color: #000;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
        }
        .btn-auth {
            min-height: 2.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .eye-btn {
            border: 0;
            background: transparent;
            color: #71717a;
            padding: 0.375rem 0.625rem;
        }
        .eye-btn:hover {
            color: #000;
        }
        .muted-link {
            color: #71717a;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .muted-link:hover {
            color: #000;
            text-decoration: underline;
        }
        .toast-top-center {
            margin-top: 24px;
        }
        @media (max-width: 576px) {
            .login-wrap {
                padding-left: 16px;
                padding-right: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container login-wrap min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="w-100" style="max-width: 380px;">
            <div class="card auth-card bg-white">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column align-items-center text-center mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-black text-white mb-3" style="width: 40px; height: 40px; font-size: 18px;">
                            <i class="ti ti-shopping-cart"></i>
                        </span>
                        <h1 class="auth-title mb-1">Welcome back</h1>
                        <p class="text-muted small mb-0">Sign in to your admin account to continue.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger py-2 small" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form class="auth-form" action="{{ route('postLogin') }}" method="post" autocomplete="off">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="logEmail">Username</label>
                            <input type="text" name="username" class="form-control" id="logEmail"
                                   placeholder="Enter your username" value="{{ old('username') }}"
                                   autocomplete="username" autofocus required />
                        </div>

                        <div class="mb-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <label class="form-label mb-0" for="logPassword">Password</label>
                                <a href="#" class="muted-link" style="font-size: 0.8rem;">Forgot password?</a>
                            </div>
                            <div class="position-relative mt-2">
                                <input type="password" name="password" class="form-control pe-5" id="logPassword"
                                       placeholder="Enter your password" autocomplete="current-password" required />
                                <button type="button" class="eye-btn position-absolute top-50 end-0 translate-middle" id="togglePw" aria-label="Show password">
                                    <i class="ti ti-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <input type="submit" class="btn btn-dark btn-auth w-100 mt-4" value="Sign In" />
                    </form>
                </div>
            </div>

            <p class="text-center text-muted small mt-4 mb-1">Maintained by ShopLink Developer</p>
            <p class="text-center mt-0">
                <a href="{{ route('dashboard.index') }}" class="muted-link">← Back to shop</a>
            </p>
        </div>
    </div>

    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('uilibs/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        document.getElementById('togglePw').addEventListener('click', function () {
            var input = document.getElementById('logPassword');
            var icon = document.getElementById('eyeIcon');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.classList.toggle('ti-eye', !show);
            icon.classList.toggle('ti-eye-off', show);
            this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    </script>
    <script>
        $(document).ready(function() {
            @if(session('error'))
                toastr.error("{{ session('error') }}", "Error", {
                    closeButton: false,
                    progressBar: true,
                    positionClass: "toast-top-center",
                    timeOut: 10000
                });
            @endif

            @if(session('success'))
                toastr.success("{{ session('success') }}", "Success", {
                    closeButton: false,
                    progressBar: true,
                    positionClass: "toast-top-center",
                    timeOut: 10000
                });
            @endif
        });
    </script>
</body>

</html>
