@extends('layouts.lk')

@section('content')
<div class="container-fluid py-4">
    <!-- Хлебные крошки -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Панель администратора</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.templates.index') }}">Шаблоны сертификатов</a></li>
            <li class="breadcrumb-item active" aria-current="page">Создание шаблона</li>
        </ol>
    </nav>
    
    <h1 class="mb-4">Создание нового шаблона сертификата</h1>
    
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-4">
                    <!-- Основная информация -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Основная информация</h4>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Название шаблона *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение предпросмотра</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                id="image" name="image" accept="image/*">
                            <div class="form-text">Рекомендуемый размер: 800x600px, максимум 2MB.</div>
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_premium" name="is_premium" 
                                    {{ old('is_premium') ? 'checked' : '' }} value="1">
                                <label class="form-check-label" for="is_premium">Премиум шаблон</label>
                            </div>
                            <div class="form-text">Премиум шаблоны доступны только клиентам с премиум подпиской.</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                    {{ old('is_active', true) ? 'checked' : '' }} value="1">
                                <label class="form-check-label" for="is_active">Активен</label>
                            </div>
                            <div class="form-text">Неактивные шаблоны не будут показываться клиентам.</div>
                        </div>
                    </div>
                    
                    <!-- HTML шаблон -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Шаблон HTML</h4>
                        
                        <div class="mb-3">
                            <label for="html_template" class="form-label">HTML код шаблона *</label>
                            <textarea class="form-control @error('html_template') is-invalid @enderror" 
                                id="html_template" name="html_template" rows="14" required>{{ old('html_template') }}</textarea>
                            <div class="form-text">
                                Используйте переменные в формате {имя_переменной} для динамических данных.
                                Например: {recipient_name}, {amount}, {certificate_number}
                            </div>
                            @error('html_template')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary me-2">Отмена</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-1"></i> Сохранить шаблон
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
