<!DOCTYPE html>
<html lang="id">

<head>
    <title>Verifikasi Email - Lalulalanglelang</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Verifikasi Email Anda - Lalulalanglelang" />
    <meta name="keywords" content="Verifikasi, OTP, Email, Lalulalanglelang" />
    <meta name="author" content="Lalulalanglelang" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('vendor/dashboard') }}/assets/images/favicon.svg" type="image/x-icon" />

    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/inter/inter.css" id="main-font-link" />
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
        }

        .auth-wrapper {
            flex: 1;
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
            max-width: 500px;
            width: 100%;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: slideUp 0.6s ease-out;
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

        .btn-verify {
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
            cursor: pointer;
        }

        .btn-verify:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .btn-verify:active {
            transform: translateY(0);
        }

        .btn-verify:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-resend {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-resend:hover:not(:disabled) {
            background: var(--primary-color);
            color: white;
        }

        .btn-resend:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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

        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-info {
            background-color: #f0f8ff;
            border-color: #b3d9ff;
            color: #004085;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .timer-text {
            color: #e74c3c;
            font-weight: 600;
            margin-top: 15px;
        }

        .attempts-text {
            color: #f39c12;
            font-weight: 600;
            margin-top: 15px;
        }

        .otp-input {
            font-size: 24px;
            letter-spacing: 8px;
            text-align: center;
            font-weight: bold;
            font-family: 'Courier New', monospace;
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

        footer {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            padding: 20px;
            text-align: center;
            margin-top: auto;
        }

        footer p {
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-container {
                max-width: 400px;
                padding: 40px 30px;
            }

            .form-title {
                font-size: 28px;
            }
        }

        @media (max-width: 480px) {
            .auth-wrapper {
                padding: 10px;
            }

            .auth-container {
                padding: 30px 20px;
            }

            .form-title {
                font-size: 24px;
            }
        }

        /* Accessibility */
        .form-control:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .btn-verify:focus-visible {
            outline: 2px solid white;
            outline-offset: 2px;
        }

        /* Animation for form appearance */
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

        .d-none {
            display : none;
        }
    </style>
</head>

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
                <div class="form-header">
                    <h2 class="form-title">Verifikasi Email</h2>
                    <p class="form-subtitle">Masukkan kode OTP yang telah dikirim ke email Anda</p>
                </div>

                @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach ($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                <!-- User Info -->
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-envelope" style="font-size: 20px; margin-right: 12px;"></i>
                        <div>
                            <strong>Email Anda:</strong> {{ Auth::user()->email }}<br>
                            <small>Kode OTP telah dikirim ke alamat email ini</small>
                        </div>
                    </div>
                </div>

                <!-- OTP Form -->
                <form action="{{ route('otp.verify.submit') }}" method="POST" id="otpForm" novalidate>
                    @csrf

                    <!-- OTP Input -->
                    <div class="form-group">
                        <label for="otp_code" class="form-label">
                            <i class="fas fa-key me-1"></i>Kode OTP (6 Digit)
                        </label>
                        <input
                            type="text"
                            class="form-control otp-input @error('otp_code') is-invalid @enderror"
                            id="otp_code"
                            name="otp_code"
                            placeholder="000000"
                            maxlength="6"
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                            required
                            autofocus
                        >
                        @error('otp_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" class="btn-verify" id="verifyBtn">
                            <i class="fas fa-check-circle me-2"></i>Verifikasi Email
                        </button>
                    </div>
                </form>

                <div class="divider">
                    <span>Belum menerima kode?</span>
                </div>

                <!-- Resend OTP Section -->
                <div class="text-center" style="margin-bottom:1rem;text-align: center;">
                    <button
                        type="button"
                        class="btn-resend"
                        id="resendBtn"
                        onclick="resendOtp()"
                    >
                        <i class="fas fa-redo me-1"></i>  Kirim Ulang Kode OTP
                    </button>

                    <!-- Cooldown Timer -->
                    <div id="cooldownTimer" class="timer-text" style="display: none;">
                        ⏱️ Tunggu <span id="cooldownSeconds">60</span> detik sebelum meminta kode baru
                    </div>

                    <!-- Remaining Attempts -->
                    <div id="remainingAttempts" class="attempts-text" style="display: none;">
                        📊 Sisa permintaan: <span id="attemptsCount">3</span> kali
                    </div>

                    <!-- Max Attempts Reached -->
                    <div id="maxAttemptsReached" class="alert alert-danger" style="display: none; margin-top: 15px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Batas permintaan tercapai</strong><br>
                        Silakan coba lagi dalam <span id="resetSeconds">3600</span> detik
                    </div>
                </div>

                <!-- Info Box -->
                <div class="alert alert-warning mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi Penting:</strong>
                    <ul class="mb-0 mt-2" style="padding-left: 20px;">
                        <li>Kode OTP berlaku selama 10 menit</li>
                        <li>Anda memiliki 5 kesempatan untuk memasukkan kode yang benar</li>
                        <li>Anda dapat meminta kode baru maksimal 3 kali dalam 1 jam</li>
                    </ul>
                </div>

                <div class="form-links">
                    <p class="mb-0">
                        Tidak bisa login? <a href="{{ route('login') }}">Kembali ke Login</a>
                    </p>
                </div>
        </div>
    </div>

    <!-- Footer with Copyright -->
    <footer>
        <p>&copy; 2025 Lalulalanglelang. Hak Cipta Dilindungi.</p>
    </footer>

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
        // Auto-focus and format OTP input
        const otpInput = document.getElementById('otp_code');

        otpInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');

            // Limit to 6 digits
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });

        // Resend OTP function
        function resendOtp() {
            const btn = document.getElementById('resendBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengirim...';

            fetch('{{ route("otp.resend") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('✅ Kode OTP baru telah dikirim ke email Anda!');

                    // Update remaining attempts
                    document.getElementById('attemptsCount').textContent = data.remaining_attempts;

                    // Show remaining attempts if any
                    if (data.remaining_attempts > 0) {
                        document.getElementById('remainingAttempts').style.display = 'block';
                        document.getElementById('maxAttemptsReached').style.display = 'none';
                    }

                    // Start cooldown timer
                    startCooldownTimer(60);
                } else {
                    // Handle error
                    if (data.cooldown_seconds) {
                        alert(`⏱️ Silakan tunggu ${data.cooldown_seconds} detik sebelum meminta kode baru.`);
                        startCooldownTimer(data.cooldown_seconds);
                    } else if (data.reset_seconds) {
                        alert(`⚠️ Anda telah mencapai batas permintaan. Silakan coba lagi dalam ${data.reset_seconds} detik.`);
                        document.getElementById('maxAttemptsReached').style.display = 'block';
                        document.getElementById('remainingAttempts').style.display = 'none';
                        startResetTimer(data.reset_seconds);
                    } else {
                        alert('❌ ' + data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan. Silakan coba lagi.');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-redo me-1"></i>Kirim Ulang Kode OTP';
            });
        }

        // Cooldown timer
        function startCooldownTimer(seconds) {
            document.getElementById('cooldownTimer').style.display = 'block';
            document.getElementById('resendBtn').disabled = true;

            let remaining = seconds;
            const timerInterval = setInterval(() => {
                document.getElementById('cooldownSeconds').textContent = remaining;
                remaining--;

                if (remaining < 0) {
                    clearInterval(timerInterval);
                    document.getElementById('cooldownTimer').style.display = 'none';
                    document.getElementById('resendBtn').disabled = false;
                }
            }, 1000);
        }

        // Reset timer
        function startResetTimer(seconds) {
            let remaining = seconds;
            const timerInterval = setInterval(() => {
                document.getElementById('resetSeconds').textContent = remaining;
                remaining--;

                if (remaining < 0) {
                    clearInterval(timerInterval);
                    document.getElementById('maxAttemptsReached').style.display = 'none';
                    document.getElementById('resendBtn').disabled = false;
                }
            }, 1000);
        }

        // Check initial state on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Focus on OTP input
            otpInput.focus();
        });
    </script>
</body>

</html>