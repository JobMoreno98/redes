@extends('layouts.app')

@section('content')
<div class="row justify-content-center" style="min-height: 80vh; align-items: flex-start; padding-top: 50px;">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg login-card" style="background-color: #192853; color: white; border-radius: 15px; padding: 30px;">
            <div class="text-center mb-4">
                <h2>Iniciar Sesión</h2>
            </div>

            @if (session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3 position-relative">
    <label class="form-label">Código</label>
    <input 
        type="text" 
        name="codigo" 
        value="{{ old('codigo') }}" 
        class="form-control rounded-pill ps-5 @error('codigo') is-invalid @enderror" 
        placeholder="Código de usuario" 
        required 
        autofocus
        pattern="\d*" 
        inputmode="numeric">
    <i class="bi bi-person position-absolute" style="top: 38px; left: 20px; font-size: 1.1rem; color: #79bd55;"></i>
    @error('codigo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                <div class="mb-3 position-relative">
                    <label class="form-label">Contraseña</label>
                    <input 
                    type="password" 
                    name="password" 
                    id="password"
                    class="form-control rounded-pill ps-5 pe-4 @error('password') is-invalid @enderror" 
                    placeholder="********" 
                    required>
                    <i class="bi bi-lock position-absolute" style="top: 38px; left: 20px; font-size: 1.1rem; color: #79bd55;"></i>
                    <i class="bi bi-eye position-absolute" id="toggleEye" 
                    style="top: 38px; right: 20px; font-size: 1.1rem; cursor: pointer; color: #79bd55;"
                    onclick="togglePassword()"></i>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label for="remember" class="form-check-label">Recuérdame</label>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success rounded-pill px-5">
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('toggleEye');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
@endpush

@push('styles')
<style>
   .index-main .card.login-card:hover {
        transform: none;
    }

    .login-card .form-control {
        background-color: #1e305f;
        border: none;
        color: white;
    }

    .login-card .form-control::placeholder {
        color: #adb5bd;
    }

    .login-card .form-control:focus {
        background-color: #1e305f;
        box-shadow: 0 0 0 0.2rem rgba(121, 189, 85, 0.25);
        color: white;
    }

    .login-card .btn-outline-light i {
        font-size: 1.1rem;
    }

    .invalid-feedback {
        display: block;
    }
</style>
@endpush
@endsection

