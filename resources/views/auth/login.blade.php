@extends('layouts.app')

@section('title', 'Login Admin SMA')

@section('content')
<div class="login-wrapper">
    <div class="login-bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
        <div class="shape-3"></div>
        <div class="shape-4"></div>
    </div>
    
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-9 my-4">
                <div class="login-container">
                    <!-- Left side - Informational -->
                    <div class="login-info d-none d-lg-block">
                        <div class="school-branding">
                            <div class="school-logo-wrapper">
                                <img src="{{ asset('images/logo.jpg') }}" alt="Logo SMA" class="school-logo">
                            </div>
                            <h1 class="school-name">SMA NEGERI 1</h1>
                            <h2 class="school-location">GIRSANG SIPANGAN BOLON</h2>
                        </div>
                        
                        <div class="info-content">
                            <div class="info-item">
                                <div class="icon-wrapper">
                                    <i class="bx bx-book-reader"></i>
                                </div>
                                <div class="text">
                                    <h4>Portal Akademik</h4>
                                    <p>Kelola data siswa, guru, dan kelas dengan mudah</p>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="icon-wrapper">
                                    <i class="bx bx-calendar-check"></i>
                                </div>
                                <div class="text">
                                    <h4>Jadwal & Kehadiran</h4>
                                    <p>Pantau jadwal pelajaran dan absensi secara realtime</p>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="icon-wrapper">
                                    <i class="bx bx-line-chart"></i>
                                </div>
                                <div class="text">
                                    <h4>Pantau Perkembangan</h4>
                                    <p>Analisis data akademik dengan visualisasi interaktif</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="decoration-elements">
                            <div class="element-1"></div>
                            <div class="element-2"></div>
                            <div class="element-3"></div>
                        </div>
                    </div>
                    
                    <!-- Right side - Login Form -->
                    <div class="login-form-container">
                        <div class="login-header">
                            <div class="d-block d-lg-none text-center mb-4">
                                <img src="{{ asset('images/logo.jpg') }}" alt="Logo SMA" class="mobile-logo">
                                <h3 class="school-name-mobile">SMA NEGERI 1 GIRSANG SIPANGAN BOLON</h3>
                            </div>
                            
                            <h2 class="welcome-text">Selamat Datang</h2>
                            <p class="login-subtext">Masuk ke dashboard admin untuk mengelola sistem akademik</p>
                        </div>
                        
                        @if ($errors->any())
                        <div class="alert custom-alert-danger" role="alert">
                            <div class="alert-icon">
                                <i class="bx bx-error-circle"></i>
                            </div>
                            <div class="alert-content">
                                <h6 class="alert-heading">Gagal Masuk</h6>
                                <p class="mb-0">@foreach ($errors->all() as $error) {{ $error }} @endforeach</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        
                        <form method="POST" action="{{ route('login') }}" class="login-form needs-validation" novalidate>
                            @csrf
                            <div class="form-floating mb-4">
                                <input type="email" class="form-control custom-input" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required autofocus>
                                <label for="email"><i class="bx bx-envelope"></i> Email</label>
                                <div class="invalid-feedback">
                                    Silakan masukkan email yang valid.
                                </div>
                            </div>
                            
                            <div class="form-floating mb-4 password-field">
                                <input type="password" class="form-control custom-input" id="password" name="password" placeholder="Masukkan password" required>
                                <label for="password"><i class="bx bx-lock-alt"></i> Password</label>
                                <button class="btn password-toggle" type="button" id="toggle-password">
                                    <i class="bx bx-hide"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Password tidak boleh kosong.
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="form-check custom-checkbox">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Ingat saya</label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary login-btn w-100">
                                <span class="btn-text"><i class="bx bx-log-in-circle me-1"></i> Masuk</span>
                                <span class="btn-loader"><i class="bx bx-loader-alt bx-spin"></i></span>
                            </button>
                        </form>
                        
                        <div class="login-footer">
                            <p class="system-info">Sistem Informasi Akademik v2.0</p>
                            <p class="copyright">&copy; {{ date('Y') }} SMA Negeri 1 Girsang Sipangan Bolon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Base Styles & Animation */
:root {
    --primary-color: #0066b3;
    --primary-dark: #004c87;
    --primary-light: #e6f2ff;
    --secondary-color: #ffc107;
    --text-color: #333333;
    --text-muted: #6c757d;
    --light-bg: #f8f9fa;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --white: #ffffff;
    --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
    --transition: all 0.3s ease;
    --border-radius: 10px;
}

.login-wrapper {
    min-height: 100vh;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
}

/* Background Shapes Animation */
.login-bg-shapes div {
    position: absolute;
    border-radius: 50%;
    filter: blur(50px);
    opacity: 0.5;
    z-index: 0;
}

.shape-1 {
    width: 300px;
    height: 300px;
    background: rgba(0, 102, 179, 0.15);
    top: -100px;
    left: -100px;
    animation: float 15s infinite ease-in-out;
}

.shape-2 {
    width: 200px;
    height: 200px;
    background: rgba(255, 193, 7, 0.15);
    top: 60%;
    right: -50px;
    animation: float 20s infinite ease-in-out reverse;
}

.shape-3 {
    width: 250px;
    height: 250px;
    background: rgba(0, 102, 179, 0.1);
    bottom: -80px;
    left: 30%;
    animation: float 18s infinite ease-in-out 2s;
}

.shape-4 {
    width: 150px;
    height: 150px;
    background: rgba(255, 193, 7, 0.1);
    top: 20%;
    right: 20%;
    animation: float 12s infinite ease-in-out 1s;
}

@keyframes float {
    0% { transform: translate(0, 0) rotate(0deg); }
    25% { transform: translate(10px, 15px) rotate(5deg); }
    50% { transform: translate(5px, -10px) rotate(0deg); }
    75% { transform: translate(-10px, 15px) rotate(-5deg); }
    100% { transform: translate(0, 0) rotate(0deg); }
}

/* Login Container */
.login-container {
    display: flex;
    background-color: var(--white);
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--card-shadow);
    position: relative;
    z-index: 10;
    animation: fadeInUp 0.8s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Info Side */
.login-info {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: var(--white);
    padding: 3rem 2rem;
    width: 40%;
    position: relative;
    overflow: hidden;
}

.school-branding {
    text-align: center;
    position: relative;
    margin-bottom: 2.5rem;
    animation: fadeIn 1s ease-out 0.3s forwards;
    opacity: 0;
}

.school-logo-wrapper {
    width: 90px;
    height: 90px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    padding: 5px;
    margin-bottom: 1rem;
}

.school-logo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.5);
}

.school-name {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.school-location {
    font-size: 0.9rem;
    font-weight: 500;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-content {
    position: relative;
    z-index: 1;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    backdrop-filter: blur(4px);
    transform: translateX(-20px);
    opacity: 0;
    animation: slideInRight 0.5s ease-out forwards;
}

.info-item:nth-child(1) { animation-delay: 0.5s; }
.info-item:nth-child(2) { animation-delay: 0.7s; }
.info-item:nth-child(3) { animation-delay: 0.9s; }

.icon-wrapper {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    margin-right: 1rem;
    flex-shrink: 0;
}

.icon-wrapper i {
    font-size: 1.5rem;
    color: var(--white);
}

.info-item .text h4 {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.info-item .text p {
    font-size: 0.8rem;
    margin-bottom: 0;
    opacity: 0.8;
}

.decoration-elements div {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
}

.element-1 {
    width: 100px;
    height: 100px;
    bottom: -50px;
    left: -50px;
}

.element-2 {
    width: 80px;
    height: 80px;
    top: 30%;
    right: -40px;
}

.element-3 {
    width: 60px;
    height: 60px;
    top: 10%;
    left: 20%;
}

/* Form Side */
.login-form-container {
    padding: 3rem 2.5rem;
    width: 60%;
    display: flex;
    flex-direction: column;
}

.login-header {
    margin-bottom: 2rem;
    animation: fadeIn 0.8s ease-out 0.2s forwards;
    opacity: 0;
}

.mobile-logo {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    margin-bottom: 1rem;
    border: 3px solid var(--primary-light);
}

.school-name-mobile {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0;
}

.welcome-text {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    text-align: center;
}

.login-subtext {
    color: var(--text-muted);
    margin-bottom: 0;
    text-align: center;
}

/* Custom Form Styling */
.login-form {
    animation: fadeIn 0.8s ease-out 0.4s forwards;
    opacity: 0;
}

.custom-input {
    height: 60px;
    border-radius: var(--border-radius);
    border: 1px solid #e2e8f0;
    padding-left: 2.5rem;
    font-size: 1rem;
    transition: var(--transition);
}

.custom-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(0, 102, 179, 0.15);
}

.form-floating label {
    padding-left: 2.5rem;
    font-weight: 500;
}

.form-floating label i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.password-field {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: var(--text-muted);
    z-index: 5;
}

.password-toggle:hover {
    color: var(--primary-color);
}

.custom-checkbox .form-check-input {
    width: 1.1rem;
    height: 1.1rem;
    margin-top: 0.2rem;
    cursor: pointer;
}

.custom-checkbox .form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.custom-checkbox .form-check-label {
    font-size: 0.9rem;
    color: var(--text-muted);
    cursor: pointer;
}

/* Login Button */
.login-btn {
    height: 55px;
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-weight: 600;
    background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
    border: none;
    box-shadow: 0 4px 15px rgba(0, 102, 179, 0.3);
    position: relative;
    overflow: hidden;
    transition: var(--transition);
}

.login-btn::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
    transition: var(--transition);
}

.login-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 102, 179, 0.4);
    background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
}

.login-btn:hover::after {
    left: 100%;
}

.login-btn:active {
    transform: translateY(-1px);
}

.btn-loader {
    display: none;
}

.login-btn.loading .btn-text {
    display: none;
}

.login-btn.loading .btn-loader {
    display: inline-block;
}

/* Custom Alert */
.custom-alert-danger {
    display: flex;
    align-items: flex-start;
    background-color: rgba(220, 53, 69, 0.1);
    border: none;
    border-left: 3px solid var(--danger-color);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    animation: shake 0.5s ease-in-out;
}

.alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background-color: rgba(220, 53, 69, 0.2);
    border-radius: 50%;
    margin-right: 1rem;
    flex-shrink: 0;
}

.alert-icon i {
    font-size: 1.25rem;
    color: var(--danger-color);
}

.alert-content {
    flex: 1;
}

.alert-heading {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--danger-color);
    margin-bottom: 0.25rem;
}

.alert-content p {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-bottom: 0;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* Login Footer */
.login-footer {
    margin-top: auto;
    text-align: center;
    padding-top: 2rem;
    animation: fadeIn 0.8s ease-out 0.6s forwards;
    opacity: 0;
}

.system-info {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.copyright {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 0;
}

/* Animation Keyframes */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .login-container {
        flex-direction: column;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .login-info, .login-form-container {
        width: 100%;
    }
    
    .login-info {
        display: none;
    }
    
    .login-form-container {
        padding: 2rem 1.5rem;
    }
}

@media (max-width: 575.98px) {
    .login-form-container {
        padding: 1.5rem 1rem;
    }
    
    .welcome-text {
        font-size: 1.5rem;
    }
    
    .school-name-mobile {
        font-size: 1rem;
    }
    
    .login-subtext {
        font-size: 0.85rem;
    }
    
    .custom-input {
        height: 55px;
    }
    
    .login-btn {
        height: 50px;
    }
}
</style>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide password
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        });
        
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    const loginBtn = this.querySelector('.login-btn');
                    if (loginBtn) {
                        loginBtn.classList.add('loading');
                    }
                }
                
                form.classList.add('was-validated');
            }, false);
        });
        
        // Form input focus effect
        const inputs = document.querySelectorAll('.custom-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focused');
            });
        });
    });
</script>
@endsection