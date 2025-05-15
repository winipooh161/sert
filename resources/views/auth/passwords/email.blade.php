@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card bg-white shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-transparent border-0 pt-4 text-center">
                    <h4 class="gradient-text fw-bold mb-1">{{ __('Сброс пароля') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
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

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary py-2 rounded-pill hover-lift">
                                {{ __('Отправить ссылку для сброса пароля') }}
                            </button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none text-primary">
                                <i class="fa-solid fa-arrow-left me-1"></i>
                                {{ __('Вернуться к входу') }}
                            </a>
                        </div>
                    </form>
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
