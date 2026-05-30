<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Auction System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .reset-password-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            margin: 20px;
        }
        
        .reset-password-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .reset-password-header i {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .reset-password-body {
            padding: 40px 30px;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .password-requirements {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <div class="reset-password-container">
        <div class="reset-password-header">
            <i class="fas fa-lock"></i>
            <h2>Reset Password</h2>
            <p class="mb-0">Enter your new password</p>
        </div>
        
        <div class="reset-password-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="mb-4">
                    <label for="phone_number" class="form-label">
                        <i class="fas fa-phone me-2"></i>Phone Number
                    </label>
                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                           id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                           placeholder="Enter your phone number" required pattern="^(\+62|62|0)8[1-9][0-9]{7,10}$">
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Format: 08xxxxxxxxx, 628xxxxxxxxx, or +628xxxxxxxxx</small>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>New Password
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="Enter new password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="password-requirements">
                        <ul>
                            <li>Minimal 6 karakter</li>
                            <li>Kombinasi huruf dan angka direkomendasikan</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock me-2"></i>Confirm New Password
                    </label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" 
                           placeholder="Confirm new password" required>
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Reset Password
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-muted">
                        <i class="fas fa-arrow-left me-2"></i>Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (password !== confirmation) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>

</html>
