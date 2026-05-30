<!DOCTYPE html>
<html lang="en">

<head>
    <title>Member Registration - InkluSyncID</title>
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
            background: linear-gradient(135deg, var(--inklu-rose) 0%, var(--inklu-cream) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
            max-width: 550px;
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="auth-card">
                    <div class="auth-header">
                        <div class="logo-circle">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h2>Join InkluSyncID</h2>
                        <p class="mb-0">Start your wellness journey today</p>
                    </div>
                    <div class="auth-body">
                        <form method="POST" action="{{ route('member.register.submit') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                           name="full_name" value="{{ old('full_name') }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                           name="username" value="{{ old('username') }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </button>
                        </form>
                        <div class="login-link">
                            <p class="mb-0">Already have an account? <a href="{{ route('member.login') }}" style="color: var(--inklu-coral); font-weight: 500;">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('vendor/dashboard') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>

