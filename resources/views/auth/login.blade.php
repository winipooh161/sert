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
                                <label for="phone" class="form-label fw-medium">{{ __('Номер телефона') }}</label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-phone text-muted"></i>
                                    </span>
                                    <input id="phone" type="tel" class="form-control border-start-0 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel" autofocus placeholder="+7 (___) ___-__-__">

                                    @error('phone')
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Подключаем маску для телефона
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            // Простая функция для форматирования телефона
            phoneInput.addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                if (x[1] != '') {
                    e.target.value = '+' + x[1] + (x[2] ? ' (' + x[2] + ')' : '') + (x[3] ? ' ' + x[3] : '') + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
                } else {
                    e.target.value = '';
                }
            });
        }
    });
</script>
@endpush
@endsection
