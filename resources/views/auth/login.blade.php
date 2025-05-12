@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Декоративные элементы -->
            <div class="position-relative">
                <div class="position-absolute top-0 start-0 translate-middle">
                    <div class="blob" style="width: 250px; height: 250px; background: linear-gradient(45deg, #4e73df, #6610f2); opacity: 0.1;"></div>
                </div>
                <div class="position-absolute bottom-0 end-0 translate-middle-x">
                    <div class="blob" style="width: 200px; height: 200px; background: linear-gradient(45deg, #fd7e14, #e83e8c); opacity: 0.1;"></div>
                </div>
                
                <div class="card bg-white shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0 text-center">
                        <h4 class="gradient-text fw-bold mb-1">{{ __('Вход в аккаунт') }}</h4>
                        <p class="text-muted small">Войдите для доступа к личному кабинету</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-medium">{{ __('Email адрес') }}</label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-regular fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="your.email@example.com">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="password" class="form-label fw-medium mb-0">{{ __('Пароль') }}</label>
                                    
                                    @if (Route::has('password.request'))
                                        <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                                            {{ __('Забыли пароль?') }}
                                        </a>
                                    @endif
                                </div>

                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-lock text-muted"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label small" for="remember">
                                        {{ __('Запомнить меня') }}
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary py-2 rounded-pill hover-lift">
                                    <i class="fa-solid fa-right-to-bracket me-2"></i>{{ __('Войти') }}
                                </button>
                            </div>
                            
                            <div class="text-center mt-4">
                                <p class="text-muted small mb-0">
                                    Ещё нет аккаунта? 
                                    <a href="{{ route('register') }}" class="text-decoration-none text-primary">Зарегистрироваться</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
