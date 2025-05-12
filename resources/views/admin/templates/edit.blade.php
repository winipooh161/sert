@extends('layouts.lk')

@section('content')
<div class="container-fluid py-4">
    <!-- Хлебные крошки -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Панель администратора</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.templates.index') }}">Шаблоны сертификатов</a></li>
            <li class="breadcrumb-item active" aria-current="page">Редактирование шаблона</li>
        </ol>
    </nav>
    
    <h1 class="mb-4">Редактирование шаблона сертификата</h1>
    
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.templates.update', $template) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <!-- Основная информация -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Основная информация</h4>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Название шаблона *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $template->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3">{{ old('description', $template->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение предпросмотра</label>
                            
                            @if ($template->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $template->image) }}" class="img-thumbnail" style="max-height: 150px;" alt="{{ $template->name }}">
                                </div>
                            @endif
                            
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                id="image" name="image" accept="image/*">
                            <div class="form-text">Оставьте пустым, чтобы сохранить текущее изображение. Рекомендуемый размер: 800x600px, максимум 2MB.</div>
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_premium" name="is_premium" 
                                    {{ old('is_premium', $template->is_premium) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_premium">Премиум шаблон</label>
                            </div>
                            <div class="form-text">Премиум шаблоны доступны только клиентам с премиум подпиской.</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                    {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Активен</label>
                            </div>
                            <div class="form-text">Неактивные шаблоны не будут показываться клиентам.</div>
                        </div>
                    </div>
                    
                    <!-- HTML шаблон и поля -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Шаблон HTML</h4>
                        
                        <div class="mb-3">
                            <label for="html_template" class="form-label">HTML код шаблона *</label>
                            <textarea class="form-control @error('html_template') is-invalid @enderror" 
                                id="html_template" name="html_template" rows="10" required>{{ old('html_template', $template->html_template) }}</textarea>
                            <div class="form-text">
                                Используйте переменные в формате {имя_переменной} для динамических данных.
                                Например: {recipient_name}, {amount}, {certificate_number}
                            </div>
                            @error('html_template')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <h4 class="mb-3 mt-4">Настраиваемые поля</h4>
                        <div id="fields-container">
                            @if ($template->fields && count($template->fields) > 0)
                                @foreach ($template->fields as $key => $field)
                                    <div class="field-row mb-3">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">Поле</span>
                                            <input type="text" class="form-control" name="fields[keys][]" value="{{ $key }}" placeholder="Имя поля">
                                            <span class="input-group-text">Метка</span>
                                            <input type="text" class="form-control" name="fields[labels][]" value="{{ $field['label'] ?? '' }}" placeholder="Метка поля">
                                            <div class="input-group-text">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="fields[required][]" value="1" {{ isset($field['required']) && $field['required'] ? 'checked' : '' }}>
                                                    <label class="form-check-label">Обязательное</label>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger remove-field">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="field-row mb-3">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">Поле</span>
                                        <input type="text" class="form-control" name="fields[keys][]" placeholder="Имя поля, например: company_name">
                                        <span class="input-group-text">Метка</span>
                                        <input type="text" class="form-control" name="fields[labels][]" placeholder="Метка поля, например: Название компании">
                                        <div class="input-group-text">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="fields[required][]" value="1">
                                                <label class="form-check-label">Обязательное</label>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-danger remove-field">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary mb-4" id="add-field">
                            <i class="fa-solid fa-plus me-1"></i> Добавить поле
                        </button>
                    </div>
                </div>
                
                <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary me-2">Отмена</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-1"></i> Сохранить изменения
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Опасная зона -->
    <div class="card border-0 rounded-4 shadow-sm mt-4 border-danger border-top border-4">
        <div class="card-body p-4">
            <h5 class="card-title text-danger mb-3">Опасная зона</h5>
            <p class="text-muted mb-3">Если вы удалите этот шаблон, он будет недоступен для всех пользователей. Если шаблон используется в существующих сертификатах, удаление может привести к ошибкам отображения.</p>
            
            <form method="POST" action="{{ route('admin.templates.destroy', $template) }}" id="deleteTemplateForm">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-outline-danger" onclick="confirmTemplateDeletion()">
                    <i class="fa-solid fa-trash me-1"></i> Удалить шаблон
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Добавление нового поля
    document.getElementById('add-field').addEventListener('click', function() {
        const fieldsContainer = document.getElementById('fields-container');
        const fieldRow = document.querySelector('.field-row').cloneNode(true);
        
        // Очистка введенных значений
        fieldRow.querySelectorAll('input[type="text"]').forEach(input => {
            input.value = '';
        });
        fieldRow.querySelector('input[type="checkbox"]').checked = false;
        
        // Добавление обработчика на кнопку удаления
        fieldRow.querySelector('.remove-field').addEventListener('click', function() {
            const fieldRows = document.querySelectorAll('.field-row');
            if (fieldRows.length > 1) {
                this.closest('.field-row').remove();
            } else {
                alert('Должно остаться минимум одно поле');
            }
        });
        
        fieldsContainer.appendChild(fieldRow);
    });
    
    // Обработчики для кнопок удаления уже существующих полей
    document.querySelectorAll('.remove-field').forEach(button => {
        button.addEventListener('click', function() {
            const fieldRows = document.querySelectorAll('.field-row');
            if (fieldRows.length > 1) {
                this.closest('.field-row').remove();
            } else {
                alert('Должно остаться минимум одно поле');
            }
        });
    });
});

function confirmTemplateDeletion() {
    if (confirm('Вы уверены, что хотите удалить этот шаблон? Это действие может привести к ошибкам в существующих сертификатах.')) {
        document.getElementById('deleteTemplateForm').submit();
    }
}
</script>
@endsection
