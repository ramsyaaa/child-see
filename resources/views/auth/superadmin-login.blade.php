<!DOCTYPE html>
<html lang="en">

<head>
    <title>Superadmin Login - InkluSyncID</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/inter/inter.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/tabler-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/fonts/fontawesome.css" />
    <link rel="stylesheet" href="{{ asset('vendor/dashboard') }}/assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        :root {
            --inklu-coral: #FF6F51;
            --inklu-sage: #97B5A9;
            --inklu-rose: #E3B7B4;
            --inklu-yellow: #F5DD89;
            --inklu-cream: #FFF6DF;
        }

        body {
            background: linear-gradient(135deg, var(--inklu-coral) 0%, var(--inklu-rose) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .auth-header {
            background: var(--inklu-coral);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .auth-header h2 {
            color: white;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .auth-body {
            padding: 40px 30px;
        }

        .btn-primary {
            background-color: var(--inklu-coral);
            border-color: var(--inklu-coral);
            padding: 12px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #e65a3d;
            border-color: #e65a3d;
        }

        .form-control:focus {
            border-color: var(--inklu-coral);
            box-shadow: 0 0 0 0.2rem rgba(255, 111, 81, 0.25);
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .logo-circle i {
            font-size: 40px;
            color: var(--inklu-coral);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-card">
                    <div class="auth-header">
                        <div class="logo-circle">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h2>Superadmin Portal</h2>
                        <p class="mb-0">InkluSyncID Management</p>
                    </div>
                    <div class="auth-body">
                        <form method="POST" action="{{ route('superadmin.login.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email or Username</label>
                                <input type="text" class="form-control @error('login') is-invalid @enderror" 
                                       name="login" value="{{ old('login') }}" required autofocus>
                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @if(session('success'))
        <script>toastr.success("{{ session('success') }}");</script>
    @endif
    @if(session('error'))
        <script>toastr.error("{{ session('error') }}");</script>
    @endif
</body>
</html>

