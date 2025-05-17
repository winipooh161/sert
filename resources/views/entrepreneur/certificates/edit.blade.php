@extends('layouts.lk')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <!-- Хлебные крошки - адаптивный вариант -->
    <nav aria-label="breadcrumb" class="mb-2 mb-md-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('entrepreneur.certificates.index') }}">Мои сертификаты</a></li>
            <li class="breadcrumb-item d-none d-md-inline"><a href="{{ route('entrepreneur.certificates.show', $certificate) }}">Просмотр сертификата</a></li>
            <li class="breadcrumb-item active" aria-current="page">Редактирование</li>
        </ol>
    </nav>
    
    <h1 class="fs-4 fs-md-3 fw-bold mb-3 mb-md-4">Редактирование сертификата</h1>
    
    <div class="row g-3 g-md-4">
        <div class="col-12 col-lg-8 order-2 order-lg-1">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <h5 class="card-title fs-6 fs-md-5 mb-3 mb-md-4">Данные сертификата</h5>
                    
                    <form method="POST" action="{{ route('entrepreneur.certificates.update', $certificate) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Получатель -->
                        <div class="mb-3 mb-md-4">
                            <h6 class="fw-bold mb-2 mb-md-3 fs-7 fs-md-6">Информация о получателе</h6>
                            <div class="row g-2 g-md-3">
                                <div class="col-12">
                                    <label for="recipient_name" class="form-label small">Имя получателя *</label>
                                    <input type="text" class="form-control form-control-sm @error('recipient_name') is-invalid @enderror" 
                                        id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $certificate->recipient_name) }}" required>
                                    @error('recipient_name')
                                        <span class="invalid-feedback small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="recipient_phone" class="form-label small">Телефон получателя *</label>
                                    <input type="tel" class="form-control form-control-sm bg-light @error('recipient_phone') is-invalid @enderror" 
                                        id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone', $certificate->recipient_phone) }}" readonly>
                                    <div class="form-text small text-muted mt-1">Номер телефона нельзя изменить после создания сертификата</div>
                                    @error('recipient_phone')
                                        <span class="invalid-feedback small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="recipient_email" class="form-label small">Email получателя</label>
                                    <input type="email" class="form-control form-control-sm @error('recipient_email') is-invalid @enderror" 
                                        id="recipient_email" name="recipient_email" value="{{ old('recipient_email', $certificate->recipient_email) }}">
                                    @error('recipient_email')
                                        <span class="invalid-feedback small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Информация о сертификате -->
                        <div class="mb-3 mb-md-4">
                            <h6 class="fw-bold mb-2 mb-md-3 fs-7 fs-md-6">Параметры сертификата</h6>
                            <div class="row g-2 g-md-3">
                                <div class="col-sm-6">
                                    <label for="amount" class="form-label small">Сумма сертификата (руб.) *</label>
                                    <input type="number" class="form-control form-control-sm @error('amount') is-invalid @enderror" 
                                        id="amount" name="amount" value="{{ old('amount', $certificate->amount) }}" min="0" step="100" required>
                                    @error('amount')
                                        <span class="invalid-feedback small">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="col-sm-6">
                                    <label for="status" class="form-label small">Статус *</label>
                                    <select class="form-select form-select-sm @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="active" {{ (old('status', $certificate->status) == 'active') ? 'selected' : '' }}>Активен</option>
                                        <option value="expired" {{ (old('status', $certificate->status) == 'expired') ? 'selected' : '' }}>Истек</option>
                                        <option value="canceled" {{ (old('status', $certificate->status) == 'canceled') ? 'selected' : '' }}>Отменен</option>
                                        @if($certificate->status == 'used')
                                        <option value="used" selected disabled>Использован</option>
                                        @endif
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback small">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="col-sm-6">
                                    <label for="valid_from" class="form-label small">Действителен с *</label>
                                    <input type="date" class="form-control form-control-sm @error('valid_from') is-invalid @enderror" 
                                        id="valid_from" name="valid_from" value="{{ old('valid_from', $certificate->valid_from->format('Y-m-d')) }}" required>
                                    @error('valid_from')
                                        <span class="invalid-feedback small">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="col-sm-6">
                                    <label for="valid_until" class="form-label small">Действителен до *</label>
                                    <input type="date" class="form-control form-control-sm @error('valid_until') is-invalid @enderror" 
                                        id="valid_until" name="valid_until" value="{{ old('valid_until', $certificate->valid_until->format('Y-m-d')) }}" required>
                                    @error('valid_until')
                                        <span class="invalid-feedback small">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form-label small">Сообщение/пожелания</label>
                                    <textarea class="form-control form-control-sm @error('message') is-invalid @enderror" 
                                        id="message" name="message" rows="2">{{ old('message', $certificate->message) }}</textarea>
                                    @error('message')
                                        <span class="invalid-feedback small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Обложка сертификата -->
                        <div class="mb-3 mb-md-4">
                            <h6 class="fw-bold mb-2 mb-md-3 fs-7 fs-md-6">Обложка сертификата</h6>
                            
                            @if($certificate->cover_image)
                                <div class="mb-3">
                                    <label class="form-label small">Текущая обложка</label>
                                    <div class="current-cover-image p-2 border rounded text-center">
                                        <img src="{{ asset('storage/' . $certificate->cover_image) }}" 
                                             class="img-fluid rounded" style="max-height: 150px;" alt="Обложка сертификата">
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="cover_image" class="form-label small">Загрузить новую обложку</label>
                                <input type="file" class="form-control form-control-sm @error('cover_image') is-invalid @enderror" 
                                    id="cover_image" name="cover_image" accept="image/*">
                                <div class="form-text small">Оставьте поле пустым, чтобы сохранить текущую обложку.</div>
                                @error('cover_image')
                                    <span class="invalid-feedback small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Логотип компании - компактный вариант для мобильных -->
                        <div class="mb-3 mb-md-4">
                            <h6 class="fw-bold mb-2 mb-md-3 fs-7 fs-md-6">Логотип компании</h6>
                            <div class="mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="logo_type" id="logo_current" value="current" checked>
                                    <label class="form-check-label small" for="logo_current">
                                        Текущий логотип
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="logo_type" id="logo_default" value="default">
                                    <label class="form-check-label small" for="logo_default">
                                        Из профиля
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="logo_type" id="logo_none" value="none">
                                    <label class="form-check-label small" for="logo_none">
                                        Без логотипа
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Секции для предпросмотра и загрузки логотипов - можно добавить здесь при необходимости -->
                        </div>
                        
                        <!-- Дополнительные поля шаблона -->
                        @if (is_array($template->fields) && count($template->fields) > 0)
                        <div class="mb-3 mb-md-4">
                            <h6 class="fw-bold mb-2 mb-md-3 fs-7 fs-md-6">Дополнительные поля</h6>
                            <div class="row g-2 g-md-3">
                                @foreach ($template->fields as $key => $field)
                                    <div class="col-sm-6">
                                        <label for="custom_{{ $key }}" class="form-label small">
                                            {{ $field['label'] ?? $key }} 
                                            @if (isset($field['required']) && $field['required'])
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                        <input type="text" class="form-control form-control-sm @error('custom_fields.'.$key) is-invalid @enderror" 
                                            id="custom_{{ $key }}" name="custom_fields[{{ $key }}]" 
                                            value="{{ old('custom_fields.'.$key, isset($certificate->custom_fields[$key]) ? $certificate->custom_fields[$key] : '') }}"
                                            {{ isset($field['required']) && $field['required'] ? 'required' : '' }}>
                                        @error('custom_fields.'.$key)
                                            <span class="invalid-feedback small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Кнопки управления формой -->
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end mt-3 pt-3 border-top">
                            <a href="{{ route('entrepreneur.certificates.show', $certificate) }}" class="btn btn-sm btn-outline-secondary order-2 order-sm-1">
                                <i class="fa-solid fa-times me-1 d-none d-sm-inline"></i> Отмена
                            </a>
                            <button type="submit" class="btn btn-sm btn-primary order-1 order-sm-2">
                                <i class="fa-solid fa-save me-1 d-none d-sm-inline"></i> Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            @if($certificate->status != 'canceled')
            <div class="card border-0 rounded-4 shadow-sm mt-3 border-danger border-top border-4">
                <div class="card-body p-3 p-md-4">
                    <h5 class="card-title text-danger fs-6 fs-md-5 mb-2 mb-md-3">Опасная зона</h5>
                    <p class="text-muted mb-3 small">Если вы хотите отменить сертификат, нажмите кнопку ниже. Это действие нельзя будет отменить.</p>
                    
                    <form method="POST" action="{{ route('entrepreneur.certificates.destroy', $certificate) }}" id="cancelForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmCancellation()">
                            <i class="fa-solid fa-ban me-1"></i> Отменить сертификат
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-12 col-lg-4 order-1 order-lg-2 mb-3 mb-lg-0">
            <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 70px; z-index: 10;">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="card-title fs-6 fs-md-5 mb-0">Информация о шаблоне</h5>
                        <a href="{{ route('entrepreneur.certificates.show', $certificate) }}" class="btn btn-sm btn-outline-primary d-lg-none">
                            <i class="fa-solid fa-eye"></i> Просмотр
                        </a>
                    </div>
                    
                    @if ($template->image)
                        <div class="d-none d-md-block mb-3">
                            <img src="{{ asset('storage/' . $template->image) }}" class="card-img-top rounded-4" alt="{{ $template->name }}">
                        </div>
                        <div class="d-md-none mb-2">
                            <img src="{{ asset('storage/' . $template->image) }}" class="img-fluid rounded-4" style="max-height:120px; object-fit:cover;" alt="{{ $template->name }}">
                        </div>
                    @endif
                    
                    <h6 class="fw-bold small">{{ $template->name }}</h6>
                    <p class="text-muted small mb-3">{{ Str::limit($template->description, 100) }}</p>
                    
                    <hr class="my-2">
                    
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">Номер:</dt>
                        <dd class="col-7">{{ $certificate->certificate_number }}</dd>
                        
                        <dt class="col-5 text-muted">Дата создания:</dt>
                        <dd class="col-7">{{ $certificate->created_at->format('d.m.Y') }}</dd>
                        
                        <dt class="col-5 text-muted">Статус:</dt>
                        <dd class="col-7">
                            @if($certificate->status == 'active')
                                <span class="badge bg-success">Активен</span>
                            @elseif($certificate->status == 'used')
                                <span class="badge bg-secondary">Использован</span>
                            @elseif($certificate->status == 'expired')
                                <span class="badge bg-warning">Истек</span>
                            @elseif($certificate->status == 'canceled')
                                <span class="badge bg-danger">Отменен</span>
                            @endif
                        </dd>
                    </dl>
                </div>
                <div class="card-footer bg-transparent p-3 d-none d-lg-block">
                    <a href="{{ route('entrepreneur.certificates.show', $certificate) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fa-solid fa-eye me-1"></i> Просмотр сертификата
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Дополнительные стили для улучшения адаптивности */
@media (max-width: 767.98px) {
    .form-control-sm, .form-select-sm {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem !important;
    }
    
    .form-check-inline {
        margin-right: 0.75rem;
    }
    
    .fs-7 {
        font-size: 0.85rem !important;
    }
    
    .form-label.small {
        font-size: 0.75rem;
        margin-bottom: 0.25rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem;
    }
}

@media (max-width: 575.98px) {
    .form-check-inline {
        display: block;
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
    
    .breadcrumb {
        font-size: 0.75rem;
    }
    
    .sticky-top {
        position: relative !important;
        top: 0 !important;
    }
}
</style>

<script>
function confirmCancellation() {
    if (confirm('Вы уверены, что хотите отменить этот сертификат? Это действие необратимо.')) {
        document.getElementById('cancelForm').submit();
    }
}
</script>
@endsection
