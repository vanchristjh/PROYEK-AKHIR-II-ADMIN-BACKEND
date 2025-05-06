<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMA Negeri 1 Girsang Sipangan Bolon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative;
        }
        
        .login-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            height: 600px;
            margin: 20px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .login-left {
            width: 50%;
            background: #4338ca;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(99, 102, 241, 0.3) 0%, rgba(67, 56, 202, 0) 70%);
        }
        
        .school-logo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: contain;
            background-color: white;
            padding: 10px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border: 5px solid rgba(255, 255, 255, 0.2);
        }
        
        .school-name {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            text-align: center;
            margin-bottom: 10px;
            z-index: 1;
        }
        
        .school-tagline {
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            margin-bottom: 40px;
            font-size: 16px;
            line-height: 1.5;
            font-weight: 300;
            z-index: 1;
            max-width: 300px;
        }
        
        .feature-list {
            width: 100%;
            z-index: 1;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: #ffffff;
        }
        
        .feature-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .feature-text {
            font-size: 14px;
            font-weight: 400;
        }
        
        .login-right {
            width: 50%;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
        }
        
        .welcome-text {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }
        
        .login-subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
        }
        
        .login-form {
            width: 100%;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #f9fafb;
        }
        
        .form-group input:focus {
            border-color: #4338ca;
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .form-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        
        .login-button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(to right, #4f46e5, #4338ca);
            color: white;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .login-button:hover {
            background: linear-gradient(to right, #4338ca, #3730a3);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
        }
        
        .login-button i {
            font-size: 18px;
        }
        
        .login-button-loading {
            display: none;
        }
        
        .footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            color: #888;
            font-size: 12px;
        }
        
        .error-alert {
            background-color: rgba(254, 226, 226, 0.5);
            color: #b91c1c;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            border-left: 4px solid #ef4444;
        }
        
        .error-alert i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
            }
            
            .login-left, .login-right {
                width: 100%;
                padding: 30px;
            }
            
            .login-left {
                padding-top: 40px;
                padding-bottom: 40px;
            }
            
            .school-logo {
                width: 100px;
                height: 100px;
                margin-bottom: 20px;
            }
            
            .feature-list {
                display: none;
            }
            
            .school-name {
                font-size: 20px;
            }
            
            .school-tagline {
                font-size: 14px;
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="SMA Negeri 1 Girsang Sipangan Bolon Logo" class="school-logo">
            <h1 class="school-name">SMA Negeri 1<br>Girsang Sipangan Bolon</h1>
            <p class="school-tagline">Sistem Informasi Akademik Terpadu untuk Kemajuan Pendidikan</p>
            
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap text-white"></i>
                    </div>
                    <span class="feature-text">Manajemen Akademik Terpadu</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <span class="feature-text">Kolaborasi Guru dan Siswa</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <span class="feature-text">Monitoring Prestasi Real-time</span>
                </div>
            </div>
        </div>
        
        <div class="login-right">
            <h2 class="welcome-text">Selamat Datang Kembali!</h2>
            <p class="login-subtitle">Silahkan login untuk mengakses sistem</p>
            
            @if ($errors->any())
                <div class="error-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Username atau password yang Anda masukkan salah.</span>
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST" id="loginForm" class="login-form">
                @csrf
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required value="{{ old('username') }}" autocomplete="username">
                </div>
                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required id="passwordField">
                </div>
                <button type="submit" id="loginButton" class="login-button">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>
            
            <div class="footer">
                &copy; {{ date('Y') }} SMA Negeri 1 Girsang Sipangan Bolon - Sistem Informasi Akademik
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animated form submission
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const button = document.getElementById('loginButton');
                    button.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';
                    button.disabled = true;
                });
            }
            
            // Input field focus effects
            const inputFields = document.querySelectorAll('.form-group input');
            inputFields.forEach(field => {
                field.addEventListener('focus', function() {
                    this.parentElement.querySelector('i').style.color = '#4338ca';
                });
                
                field.addEventListener('blur', function() {
                    this.parentElement.querySelector('i').style.color = '#888';
                });
            });
        });
    </script>
</body>
</html>
