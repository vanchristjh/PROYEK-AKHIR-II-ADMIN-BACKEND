@extends('layouts.app')

@section('title', 'Login Admin SMA')

@section('content')
<div class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 fade-in">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <div class="mb-3 d-inline-block p-3 rounded-circle bg-white shadow-sm">
                            <img src="https://ui-avatars.com/api/?name=SMA&background=4361ee&color=fff&bold=true&size=60" alt="Logo SMA" class="img-fluid rounded-circle" style="width: 60px;">
                        </div>
                        <h2 class="fw-bold text-primary">Admin Dashboard</h2>
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
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="nama@sma.sch.id" required autofocus>
                                <div class="invalid-feedback">
                                    Silakan masukkan email yang valid.
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <a href="#" class="text-decoration-none small text-primary">Lupa password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggle-password">
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
                            <button type="submit" class="btn btn-primary py-2 btn-login">
                                <span class="d-flex align-items-center justify-content-center">
                                    <i class="bx bx-log-in-circle me-2"></i>
                                    Masuk
                                </span>
                            </button>
                        </div>
                    </form>
                    <div class="text-center">
                        <small class="text-muted">© {{ date('Y') }} SMA - All Rights Reserved</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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