<!DOCTYPE html>
<html lang="en">

<head>
    <title>Masuk - Lalulalanglelang</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Masuk ke Platform Lelang Lalulalanglelang" />
    <meta name="keywords" content="Lelang, Bidding, Login, Autentikasi, Lalulalanglelang" />
    <meta name="author" content="Lalulalanglelang" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('vendor/dashboard') }}/assets/images/favicon.svg" type="image/x-icon" />

    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/inter/inter.css" id="main-font-link" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/tabler-icons.min.css" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/feather.css" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/fontawesome.css" />

    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/css/style.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/css/style-preset.css" />

    <!-- Custom Auction System Styles -->
    <link rel="stylesheet" href="{{ asset('css/auction-admin-custom.css') }}">

    <style>
        :root {
            --primary-color: #721c24;
            --primary-dark: #5a161c;
            --primary-light: #8b2635;
            --secondary-color: #f8f9fa;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --border-color: #e9ecef;
            --shadow-light: 0 2px 10px rgba(114, 28, 36, 0.1);
            --shadow-medium: 0 4px 20px rgba(114, 28, 36, 0.15);
            --shadow-heavy: 0 8px 30px rgba(114, 28, 36, 0.2);
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-heavy);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        .auth-brand {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateX(0) translateY(0);
            }

            100% {
                transform: translateX(-50px) translateY(-50px);
            }
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 2;
        }

        .brand-logo i {
            font-size: 36px;
            color: white;
        }

        .brand-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .brand-subtitle {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }

        .auth-form {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .form-subtitle {
            color: var(--text-muted);
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafbfc;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(114, 28, 36, 0.25);
            background: white;
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .btn-login {
            background: var(--primary-color);
            border: none;
            padding: 16px 30px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .form-links {
            text-align: center;
            margin-top: 30px;
        }

        .form-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .form-links a:hover {
            color: var(--primary-dark);
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            background: white;
            padding: 0 20px;
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
                max-width: 400px;
            }

            .auth-brand {
                padding: 40px 30px;
                min-height: 200px;
            }

            .auth-form {
                padding: 40px 30px;
            }

            .form-title {
                font-size: 28px;
            }

            .brand-title {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .auth-wrapper {
                padding: 10px;
            }

            .auth-form {
                padding: 30px 20px;
            }

            .auth-brand {
                padding: 30px 20px;
            }
        }

        /* Accessibility */
        .form-control:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .btn-login:focus-visible {
            outline: 2px solid white;
            outline-offset: 2px;
        }

        /* Animation for form appearance */
        .auth-container {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head><!-- [Head] end -->
<!-- [Body] Start -->

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-wrapper">
        <div class="auth-container">
            <!-- Brand Section -->
            <div class="auth-brand">
                <div class="brand-logo">
                    @if(file_exists(public_path('logo-dark.png')))
                    <img src="{{ asset('logo-dark.png') }}" alt="Lalulalanglelang"
                        style="width: 50px; height: 50px; object-fit: contain;">
                    @else
                    <i class="fas fa-gavel"></i>
                    @endif
                </div>
                <h1 class="brand-title">Lalulalanglelang</h1>
                <p class="brand-subtitle">
                    Selamat datang di platform lelang premium kami. Temukan barang-barang unik, berikan penawaran
                    kompetitif, dan bergabunglah dengan komunitas kolektor dan penggemar.
                </p>
            </div>

            <!-- Login Form Section -->
            <div class="auth-form">
                <div class="form-header">
                    <h2 class="form-title">Selamat Datang Kembali</h2>
                    <p class="form-subtitle">Silakan masuk ke akun Anda untuk melanjutkan</p>
                </div>

                @if ($errors->any())
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST" id="loginForm" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Alamat Email
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="Masukkan alamat email Anda" required
                            autocomplete="email" autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i>Kata Sandi
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="Masukkan kata sandi Anda" required
                            autocomplete="current-password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-login" id="loginBtn">
                            <span class="loading-spinner" id="loadingSpinner"></span>
                            <span id="loginText">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk
                            </span>
                        </button>
                    </div>
                </form>

                <div class="divider">
                    <span>Baru di platform kami?</span>
                </div>

                <div class="form-links">
                    <p class="mb-0">
                        Belum punya akun?
                        <a href="{{ route('register') }}">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/popper.min.js"></script>
    <script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/dashboard') }}/assets/js/fonts/custom-font.js"></script>
    <script src="{{ asset('vendor/dashboard') }}/assets/js/pcoded.js"></script>
    <script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/feather.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert') }}/sweetalert.min.js"></script>
    @include('vendor.sweet.alert')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const loginText = document.getElementById('loginText');

            // Form validation
            loginForm.addEventListener('submit', function(e) {
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                let isValid = true;

                // Reset previous validation states
                email.classList.remove('is-invalid');
                password.classList.remove('is-invalid');

                // Email validation
                if (!email.value.trim()) {
                    showFieldError(email, 'Alamat email wajib diisi');
                    isValid = false;
                } else if (!isValidEmail(email.value)) {
                    showFieldError(email, 'Silakan masukkan alamat email yang valid');
                    isValid = false;
                }

                // Password validation
                if (!password.value.trim()) {
                    showFieldError(password, 'Kata sandi wajib diisi');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    return;
                }

                // Show loading state
                showLoadingState();
            });

            function showFieldError(field, message) {
                field.classList.add('is-invalid');
                let feedback = field.parentNode.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    field.parentNode.appendChild(feedback);
                }
                feedback.textContent = message;
            }

            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function showLoadingState() {
                loginBtn.disabled = true;
                loadingSpinner.style.display = 'inline-block';
                loginText.innerHTML = 'Sedang Masuk...';
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });

            // Focus management for accessibility
            const firstInput = document.getElementById('email');
            if (firstInput && !firstInput.value) {
                firstInput.focus();
            }
        });
    </script>
</body>

</html>