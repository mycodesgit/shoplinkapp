<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ShopLink | Login</title>
    
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('template/assets/css/poppins.css') }}"> --}}
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/slogo.png') }}">

    <style>
        /* @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"); */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            /* background: url("images/bg.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #303336; */
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .box {
            display: flex;
            flex-direction: row;
            position: relative;
            padding: 60px 20px 30px 20px;
            height: 500px;
            width: 350px;
            background-color: rgba(224, 255, 255, 0.4);
            border-radius: 30px;
            -webkit-backdrop-filter: blur(15px);
            backdrop-filter: blur(15px);
            border: 3px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .box-login {
            position: absolute;
            width: 85%;
            left: 27px;
            transition: 0.5s ease-in-out;
        }

        .box-register {
            position: absolute;
            width: 85%;
            right: -350px;
            transition: 0.5s ease-in-out;
        }

        .top-header {
            text-align: center;
            margin: 10px 0;
        }

        .top-header h3 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .input-group {
            width: 100%;
        }

        .input-field {
            margin: 12px 0;
            position: relative;
        }

        .input-box {
            width: 100%;
            height: 50px;
            font-size: 15px;
            color: #040404;
            border: none;
            border-radius: 10px;
            padding: 7px 45px 0 20px;
            background: rgba(224, 223, 223, 0.6);
            backdrop-filter: blur(2px);
            outline: none;
        }

        .input-field label {
            position: absolute;
            left: 20px;
            top: 15px;
            font-size: 15px;
            transition: 0.3s ease-in-out;
        }

        .input-box:focus~label,
        .input-box:valid~label {
            top: 2px;
            font-size: 10px;
            color: #000000;
            font-weight: 500;
        }

        .eye-area {
            position: absolute;
            top: 25px;
            right: 25px;
        }

        .eye-box {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        i {
            position: absolute;
            color: #444444;
            cursor: pointer;
        }

        #eye,
        #eye-2 {
            opacity: 1;
        }

        #eye-slash,
        #eye-slash-2 {
            opacity: 0;
        }

        .remember {
            display: flex;
            font-size: 13px;
            margin: 12px 0 30px 0;
            color: #000;
        }

        .check {
            margin-right: 8px;
            width: 14px;
        }

        .input-submit {
            width: 100%;
            height: 50px;
            font-size: 15px;
            font-weight: 500;
            border: none;
            border-radius: 10px;
            background: #3aa069;
            color: #fff;
            box-shadow: 0px 4px 20px rgba(62, 9, 9, 0.145);
            cursor: pointer;
            transition: 0.4s;
        }

        .input-submit:hover {
            background: #3aa069;
            box-shadow: 0px 4px 20px rgba(62, 9, 9, 0.32);
        }

        .forgot {
            text-align: center;
            font-size: 13px;
            font: 500;
            margin-top: 40px;
        }

        .forgot a {
            text-decoration: none;
            color: #000;
        }

        .switch {
            display: flex;
            position: absolute;
            bottom: 50px;
            left: 25px;
            width: 85%;
            height: 50px;
            background: rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .switch a {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            color: #000;
            text-decoration: none;
            width: 50%;
            border-radius: 10px;
            z-index: 10;
        }

        #btn {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 145px;
            height: 50px;
            background: #cccccd;
            border-radius: 10px;
            box-shadow: 2px 0px 12px rgba(0, 0, 0, 0.1);
            transition: 0.5s ease-in-out;
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: #607a6c;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 100%;
            z-index: -1;
        }
        .toast-top-center {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="box">
            <!------------------ Login Box --------------------->
            <div class="box-login" id="login">
				<div style="display: flex; justify-content: center; align-items: center; margin-top: -30px;">
                    <a href="./">
					    <img src="{{ asset('template/img/slogo.png') }}" alt="" width="100" height="100">
                    </a>
				</div>
                <div class="top-header">
                    <h3>ShopLink</h3>
                    <small>Sign in to start session.</small>
                </div>
                <form action="{{ route('postLogin') }}" method="post">
                    @csrf
                    <div class="input-group">
                        <div class="input-field">
                            <input type="text" name="username" class="input-box" id="logEmail" required />
                            <label for="logEmail">Username</label>
                        </div>
                        <div class="input-field">
                            <input type="password" name="password" class="input-box" id="logPassword" required />
                            <label for="logPassword">Password</label>
                            <div class="eye-area">
                                <div class="eye-box" onclick="myLogPassword()">
                                    <i class="fa-regular fa-eye" id="eye"></i>
                                    <i class="fa-regular fa-eye-slash" id="eye-slash"></i>
                                </div>
                            </div>
                        </div>
                        <div class="input-field">
                            <input type="submit" class="input-submit" value="Sign In" />
                        </div>
                        <div class="forgot">
                            <a href="#">Forgot password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div style="position: fixed; left: 50%; transform: translateX(-50%); top: calc(50% + 250px); z-index: 100; font-size: 8pt; margin-top: 5px; color: #353131; text-align: center;">
			Maintained and Managed by <br> ShopLink Developer.
		</div>
    </div>

    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('particles/particles.js') }}"></script>
    <script src="{{ asset('particles/app.js') }}"></script>
    {{-- <script src="{{ asset('template/js/contextmenu.js') }}"></script> --}}
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        var x = document.getElementById("login");
        var y = document.getElementById("register");
        var z = document.getElementById("btn");

        function login() {
            x.style.left = "27px";
            y.style.right = "-350px";
            z.style.left = "0px";
        }

        function register() {
            x.style.left = "-350px";
            y.style.right = "25px";
            z.style.left = "150px";
        }

        // view password codes

        function myLogPassword() {
            var a = document.getElementById("logPassword");
            var b = document.getElementById("eye");
            var c = document.getElementById("eye-slash");

            if (a.type === "password") {
                a.type = "text";
                b.style.opacity = "0";
                c.style.opacity = "1";
            } else {
                a.type = "password";
                b.style.opacity = "1";
                c.style.opacity = "0";
            }
        }

        function myRegPassword() {
            var d = document.getElementById("regPassword");
            var b = document.getElementById("eye-2");
            var c = document.getElementById("eye-slash-2");

            if (d.type === "password") {
                d.type = "text";
                b.style.opacity = "0";
                c.style.opacity = "1";
            } else {
                d.type = "password";
                b.style.opacity = "1";
                c.style.opacity = "0";
            }
        }
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
    <script>
        document.body.style.zoom = "90%"; 
    </script>
</body>

</html>