@extends('layouts.lk')

@section('content')
<div class="certificate-editor">
    <div class="editor-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between py-3">
                <h1 class="h4 fw-bold mb-0">Создание сертификата</h1>
                <a href="{{ route('entrepreneur.certificates.select-template') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i>Вернуться к шаблонам
                </a>
            </div>
        </div>
    </div>
    
    <div class="editor-body">
        <div class="container">
            <div class="row">
                <!-- Форма редактирования сертификата -->
                <div class="col-lg-2">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-3">
                            <h5 class="fw-bold mb-0">Параметры сертификата</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('entrepreneur.certificates.store', $template) }}" id="certificateForm">
                                @csrf
                                
                                <!-- Информация о шаблоне -->
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">{{ $template->name }}</h6>
                                     
                                    </div>
                                </div>
                                
                                <hr class="mb-3">
                                
                                <!-- Основные параметры сертификата -->
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Номинал сертификата</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                            id="amount" name="amount" value="{{ old('amount', 3000) }}" min="100" step="100" required>
                                        <span class="input-group-text">₽</span>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                </div>
                                
                                <div class="mb-3">
                                    <label for="valid_until" class="form-label">Срок действия</label>
                                    <input type="date" class="form-control @error('valid_until') is-invalid @enderror" 
                                        id="valid_until" name="valid_until" 
                                        value="{{ old('valid_until', now()->addMonths(3)->format('Y-m-d')) }}" 
                                        min="{{ now()->format('Y-m-d') }}" required>
                                    @error('valid_until')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Сертификат будет действителен до указанной даты</div>
                                   
                                </div>
                                
                                <input type="hidden" name="valid_from" id="valid_from" value="{{ now()->format('Y-m-d') }}">
                                
                                <!-- Информация о получателе -->
                                <div class="mb-3">
                                    <label for="recipient_name" class="form-label">Имя получателя</label>
                                    <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" 
                                        id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" required>
                                    @error('recipient_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                   
                                </div>
                                
                                <div class="mb-3">
                                    <label for="recipient_email" class="form-label">Email получателя</label>
                                    <input type="email" class="form-control @error('recipient_email') is-invalid @enderror" 
                                        id="recipient_email" name="recipient_email" value="{{ old('recipient_email') }}">
                                    @error('recipient_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Необязательно. Для отправки сертификата по email</div>
                                </div>
                                
                                <!-- Сообщение -->
                                <div class="mb-3">
                                    <label for="message" class="form-label">Сообщение или пожелание</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                        id="message" name="message" rows="3">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                   
                                </div>
                                
                                <!-- Кнопки управления формой -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-plus me-2"></i>Создать сертификат
                                    </button>
                                    <a href="{{ route('entrepreneur.certificates.select-template') }}" class="btn btn-outline-secondary">
                                        Отмена
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Визуальный предпросмотр сертификата -->
                <div class="col-lg-10 mb-4 mb-lg-0">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-transparent border-0 pt-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <h5 class="fw-bold mb-0 me-3">Предпросмотр сертификата</h5>
                                <div class="badge bg-primary-subtle text-primary">Шаблон: {{ $template->name }}</div>
                            </div>
                            <div class="device-toggle btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary active" data-device="desktop">
                                    <i class="fa-solid fa-desktop"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-device="tablet">
                                    <i class="fa-solid fa-tablet-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-device="mobile">
                                    <i class="fa-solid fa-mobile-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                Это точная копия шаблона из базы данных. Введите данные слева, чтобы увидеть их в сертификате.
                            </div>
                            <div class="certificate-preview-container" data-current-device="desktop">
                                <div class="certificate-preview-wrapper device-frame">
                                    <iframe id="certificatePreview" src="{{ route('template.preview', $template) }}" class="certificate-preview" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3 text-center">
                            <div class="d-flex justify-content-center">
                                <div class="btn-group me-3">
                                    <button type="button" class="btn btn-primary" id="zoomInButton">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary" id="zoomOutButton">
                                        <i class="fa-solid fa-magnifying-glass-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="resetZoomButton">
                                        <i class="fa-solid fa-arrows-to-circle"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary" id="rotateViewButton">
                                        <i class="fa-solid fa-rotate"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для скрытия бокового меню на этой странице */
aside, .sidebar-nav, .navbar-toggler {
    display: none !important;
}

/* Расширяем main-контент на всю ширину */
.main-content {
    margin-left: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    padding:0 !important;
}

/* Переопределяем стили для container чтобы дать больше места */
.container {
    max-width: 100% !important;
}

.certificate-editor {
    min-height: calc(100vh - 100px);
    background-color: #f8f9fa;
    padding-bottom: 50px;
}

.editor-header {
    background-color: white;
    border-bottom: 1px solid #e9ecef;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Элементы DOM
    const previewFrame = document.getElementById('certificatePreview');
    const formInputs = document.querySelectorAll('#certificateForm input, #certificateForm textarea');
    const previewContainer = document.querySelector('.certificate-preview-container');
    let scale = 1;
    
    // Функция обновления предпросмотра
    const updatePreview = () => {
        // Получаем значения из полей формы
        const recipientName = document.getElementById('recipient_name').value || 'Имя получателя';
        const amount = document.getElementById('amount').value || '3000';
        const message = document.getElementById('message').value || 'Ваше сообщение или пожелание';
        
        // Устанавливаем текущую дату и срок действия
        const validFrom = new Date().toLocaleDateString('ru-RU');
        const validUntil = new Date(document.getElementById('valid_until').value || Date.now())
            .toLocaleDateString('ru-RU');
        
        // Компания
        const companyName = '{{ Auth::user()->company ?? config('app.name') }}';
        
        // Обновляем iframe с новыми параметрами
        const params = new URLSearchParams({
            recipient_name: recipientName,
            amount: `${Number(amount).toLocaleString('ru-RU')} ₽`,
            valid_from: validFrom,
            valid_until: validUntil,
            message: message,
            certificate_number: 'CERT-PREVIEW',
            company_name: companyName
        });
        
        previewFrame.src = `{{ route('template.preview', $template) }}?${params.toString()}`;
    };
    
    // Устанавливаем обработчики событий на все поля ввода для мгновенного обновления
    formInputs.forEach(input => {
        // Используем несколько событий для максимальной отзывчивости
        ['input', 'change', 'keyup', 'paste'].forEach(eventType => {
            input.addEventListener(eventType, updatePreview);
        });
    });
    
    // Управление масштабом предпросмотра
    document.getElementById('zoomInButton').addEventListener('click', function() {
        scale *= 1.1;
        previewFrame.style.transform = `scale(${scale})`;
    });
    
    document.getElementById('zoomOutButton').addEventListener('click', function() {
        scale *= 0.9;
        previewFrame.style.transform = `scale(${scale})`;
    });
    
    document.getElementById('resetZoomButton').addEventListener('click', function() {
        scale = 1;
        previewFrame.style.transform = 'scale(1)';
    });
    
    // Переключение между устройствами (desktop, tablet, mobile)
    const deviceButtons = document.querySelectorAll('.device-toggle button');
    deviceButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Удаляем активный класс у всех кнопок и добавляем текущей
            deviceButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Устанавливаем текущее устройство
            const device = this.getAttribute('data-device');
            previewContainer.setAttribute('data-current-device', device);
            
            // Сбрасываем масштаб при переключении устройства
            scale = 1;
            previewFrame.style.transform = 'scale(1)';
        });
    });
    
    // Поворот устройства (только для планшета и мобильного)
    document.getElementById('rotateViewButton').addEventListener('click', function() {
        const currentDevice = previewContainer.getAttribute('data-current-device');
        if (currentDevice !== 'desktop') {
            previewContainer.classList.toggle('landscape');
        }
    });
    
    // Инициализация предпросмотра
    updatePreview();
});
</script>
@endsection
