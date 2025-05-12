@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Декоративные элементы -->
            <div class="position-relative">
                <div class="position-absolute top-0 end-0 translate-middle-x">
                    <div class="blob" style="width: 250px; height: 250px; background: linear-gradient(45deg, #20c997, #0dcaf0); opacity: 0.1;"></div>
                </div>
                <div class="position-absolute bottom-0 start-0 translate-middle">
                    <div class="blob" style="width: 200px; height: 200px; background: linear-gradient(45deg, #6f42c1, #4e73df); opacity: 0.1;"></div>
                </div>
                
                <div class="card bg-white shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0 text-center">
                        <h4 class="gradient-text fw-bold mb-1">{{ __('Создать аккаунт') }}</h4>
                        <p class="text-muted small">Зарегистрируйтесь для полного доступа к сервису</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label fw-medium">{{ __('Имя') }}</label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-regular fa-user text-muted"></i>
                                    </span>
                                    <input id="name" type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Иван Иванов">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-medium">{{ __('Email адрес') }}</label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-regular fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="your.email@example.com">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-medium">{{ __('Пароль') }}</label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-lock text-muted"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Минимум 8 символов">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label fw-medium">{{ __('Подтверждение пароля') }}</label>

                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-shield text-muted"></i>
                                    </span>
                                    <input id="password-confirm" type="password" class="form-control border-start-0" name="password_confirmation" required autocomplete="new-password" placeholder="Повторите пароль">
                                </div>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary py-2 rounded-pill hover-lift">
                                    <i class="fa-solid fa-user-plus me-2"></i>{{ __('Зарегистрироваться') }}
                                </button>
                            </div>
                            
                            <div class="text-center mt-4">
                                <p class="text-muted small mb-0">
                                    Уже есть аккаунт? 
                                    <a href="{{ route('login') }}" class="text-decoration-none text-primary">Войти</a>
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
