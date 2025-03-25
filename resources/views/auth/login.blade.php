@extends('layouts.app')

@section('title', 'Login Admin SMA')

@section('content')
<div class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 fade-in">
                <div class="login-card shadow-lg rounded-4 p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="mb-3 d-inline-block p-3 rounded-circle bg-white shadow">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Logo SMA" class="img-fluid" style="width: 80px; height: 80px;">
                        </div>
                        <h2 class="fw-bold text-primary">SMA NEGERI 1 GIRSANG SIPANGAN BOLON</h2>
                        <p class="text-muted">Selamat datang kembali! Silakan masuk ke akun Anda.</p>
                    </div>

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bx bx-error-circle me-2"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light"><i class="bx bx-envelope text-primary"></i></span>
                                <input type="email" class="form-control form-control-lg border-start-0" id="email" name="email" value="{{ old('email') }}" placeholder="nama@sma.sch.id" required autofocus>
                                <div class="invalid-feedback">
                                    Silakan masukkan email yang valid.
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light"><i class="bx bx-lock-alt text-primary"></i></span>
                                <input type="password" class="form-control form-control-lg border-start-0" id="password" name="password" placeholder="Masukkan password" required>
                                <button class="btn btn-light border" type="button" id="toggle-password">
                                    <i class="bx bx-hide"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Password tidak boleh kosong.
                                </div>
                            </div>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3 btn-login position-relative overflow-hidden">
                                <span class="d-flex align-items-center justify-content-center">
                                    <i class="bx bx-log-in-circle me-2"></i>
                                    Masuk
                                </span>
                            </button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <small class="text-muted">© {{ date('Y') }} SMA Negeri 1 Girsang Sipangan Bolon. All Rights Reserved</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
}

.login-card {
    background-color: #ffffff;
    padding: 2.5rem;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.btn-login {
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
}

.btn-loading::after {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-top: -10px;
    margin-left: -10px;
    border-radius: 50%;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top-color: #fff;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.fade-in {
    animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.input-group-text {
    border-right: 0;
}

.form-control:focus {
    box-shadow: none;
    border-color: #4361ee;
}
</style>
@endsection

@section('scripts')
<script>
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
    (function () {
        'use strict'
        
        // Fetch all forms we want to apply validation to
        const forms = document.querySelectorAll('.needs-validation');
        
        // Loop over them and prevent submission
        Array.from(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Button animation on click
    document.querySelector('.btn-login').addEventListener('click', function() {
        this.classList.add('btn-loading');
    });
</script>
@endsection