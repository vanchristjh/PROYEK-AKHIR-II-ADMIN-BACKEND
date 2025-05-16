<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Emergency Login - SMAN 1 Girsip</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            margin-top: 100px;
            max-width: 450px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #dc3545;
            color: white;
            font-weight: 600;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px;
        }
        .form-control {
            padding: 12px;
            border-radius: 5px;
        }
        .btn-danger {
            padding: 10px 20px;
            border-radius: 5px;
        }
        .alert-warning {
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">Emergency Login System</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-warning">
                            <strong>Warning!</strong> This is an emergency login page to bypass redirect loops. Use only when the regular login page is inaccessible.
                        </div>

                        <form method="POST" action="{{ route('emergency.login.submit') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autofocus>
                                
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <strong>Emergency Login</strong>
                                </button>
                            </div>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <p>
                                <a href="{{ route('login') }}" class="text-secondary">
                                    Back to regular login
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-muted">
                        <!-- Current time for timestamp -->
                        {{ now() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
