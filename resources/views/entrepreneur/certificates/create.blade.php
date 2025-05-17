@extends('layouts.lk')

@section('content')
<div class="certificate-editor">
    <div class="editor-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between py-2 py-sm-3">
                <h1 class="h4 h5-sm fw-bold mb-0">Создание сертификата</h1>
                <a href="{{ route('entrepreneur.certificates.select-template') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1 me-sm-2"></i><span class="d-none d-sm-inline">Вернуться к шаблонам</span>
                </a>
            </div>
        </div>
    </div>
    
    <div class="editor-body">
        <div class="container">
            <div class="row">
                <!-- Форма редактирования сертификата - колонка будет полной шириной на мобильных -->
                <div class="col-lg-3 order-2 order-lg-1 mt-3 mt-lg-0">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-3">
                            <h5 class="fw-bold mb-0 fs-6">Параметры сертификата</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('entrepreneur.certificates.store', $template) }}" id="certificateForm" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Информация о шаблоне -->
                                <div class="d-flex align-items-center mb-2 mb-sm-3">
                                    <div>
                                        <h6 class="mb-0">{{ $template->name }}</h6>
                                     
                                    </div>
                                </div>
                                
                                <hr class="my-2">
                                
                                <!-- Основные параметры сертификата -->
                                <div class="mb-2 mb-sm-3">
                                    <label for="amount" class="form-label small">Номинал сертификата</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control form-control-sm @error('amount') is-invalid @enderror" 
                                            id="amount" name="amount" value="{{ old('amount', 3000) }}" min="100" step="100" required>
                                        <span class="input-group-text small">₽</span>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                </div>
                                
                                <div class="mb-2 mb-sm-3">
                                    <label for="valid_until" class="form-label small">Срок действия</label>
                                    <input type="date" class="form-control form-control-sm @error('valid_until') is-invalid @enderror" 
                                        id="valid_until" name="valid_until" 
                                        value="{{ old('valid_until', now()->addMonths(3)->format('Y-m-d')) }}" 
                                        min="{{ now()->format('Y-m-d') }}" required>
                                    @error('valid_until')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text small">Сертификат будет действителен до указанной даты</div>
                                   
                                </div>
                                
                                <input type="hidden" name="valid_from" id="valid_from" value="{{ now()->format('Y-m-d') }}">
                                
                                <!-- Информация о получателе -->
                                <div class="mb-2 mb-sm-3">
                                    <label for="recipient_name" class="form-label small">Имя получателя</label>
                                    <input type="text" class="form-control form-control-sm @error('recipient_name') is-invalid @enderror" 
                                        id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" required>
                                    @error('recipient_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                   
                                </div>
                                
                                <div class="mb-2 mb-sm-3">
                                    <label for="recipient_phone" class="form-label small">Телефон получателя *</label>
                                    <input type="tel" class="form-control form-control-sm @error('recipient_phone') is-invalid @enderror" 
                                        id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone') }}" required>
                                    @error('recipient_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text small">Обязательно. Используется для идентификации получателя. На один номер можно создать только один активный сертификат.</div>
                                </div>
                                
                                <div class="mb-2 mb-sm-3">
                                    <label for="recipient_email" class="form-label small">Email получателя</label>
                                    <input type="email" class="form-control form-control-sm @error('recipient_email') is-invalid @enderror" 
                                        id="recipient_email" name="recipient_email" value="{{ old('recipient_email') }}">
                                    @error('recipient_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text small">Необязательно. Для отправки сертификата по email</div>
                                </div>
                                
                                <!-- Сообщение -->
                                <div class="mb-2 mb-sm-3">
                                    <label for="message" class="form-label small">Сообщение или пожелание</label>
                                    <textarea class="form-control form-control-sm @error('message') is-invalid @enderror" 
                                        id="message" name="message" rows="2">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                   
                                </div>
                                
                                <!-- Логотип компании -->
                                <div class="mb-2 mb-sm-3">
                                    <label for="logo" class="form-label small">Логотип компании</label>
                                    <div class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="logo_type" id="logo_default" value="default" checked>
                                            <label class="form-check-label small" for="logo_default">
                                                Использовать из профиля
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="logo_type" id="logo_custom" value="custom">
                                            <label class="form-check-label small" for="logo_custom">
                                                Загрузить новый
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="logo_type" id="logo_none" value="none">
                                            <label class="form-check-label small" for="logo_none">
                                                Не использовать логотип
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div id="default_logo_preview" class="mb-2 text-center p-2 border rounded">
                                        <img src="{{ Auth::user()->company_logo ? asset('storage/' . Auth::user()->company_logo) : asset('images/default-logo.png') }}" 
                                             class="img-thumbnail" style="max-height: 60px;" alt="Текущий логотип">
                                        <div class="small text-muted mt-1 fs-7">Текущий логотип</div>
                                    </div>
                                    
                                    <div id="custom_logo_container" class="d-none">
                                        <input type="file" class="form-control form-control-sm @error('custom_logo') is-invalid @enderror" 
                                            id="custom_logo" name="custom_logo" accept="image/*">
                                        <div class="form-text small">Рекомендуемый размер: 300x100px, PNG или JPG</div>
                                        
                                        <div id="custom_logo_preview" class="mt-2 text-center"></div>
                                    </div>
                                    
                                    @error('custom_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Добавляем блок загрузки обложки сертификата -->
                                <div class="mb-2 mb-sm-3">
                                    <label for="cover_image" class="form-label small">Обложка сертификата *</label>
                                    <input type="file" class="form-control form-control-sm @error('cover_image') is-invalid @enderror" 
                                        id="cover_image" name="cover_image" accept="image/*" required>
                                    @error('cover_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text small">Обязательное поле. Загрузите изображение, которое будет отображаться в карточке сертификата. Рекомендуемый размер: 500x300px.</div>
                                    
                                    <div id="cover_image_preview" class="mt-2 text-center"></div>
                                </div>

                                <!-- Кнопки управления формой -->
                                <div class="d-grid gap-1 gap-sm-2 mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-plus me-1 me-sm-2"></i>Создать сертификат
                                    </button>
                                    <a href="{{ route('entrepreneur.certificates.select-template') }}" class="btn btn-outline-secondary btn-sm">
                                        Отмена
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Визуальный предпросмотр сертификата - колонка первая на мобильных -->
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-transparent border-0 pt-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                            <div class="d-flex align-items-center mb-2 mb-sm-0">
                                <h5 class="fw-bold mb-0 me-2 fs-6">Предпросмотр</h5>
                                <span class="badge bg-primary-subtle text-primary small">{{ $template->name }}</span>
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
                        <div class="card-body p-2 p-sm-3">
                            <div class="alert alert-info mb-2 mb-sm-3 py-2 small">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Заполните форму слева, чтобы увидеть изменения в сертификате
                            </div>
                            <div class="certificate-preview-container" data-current-device="desktop">
                                <div class="certificate-preview-wrapper device-frame">
                                    <iframe id="certificatePreview" src="{{ route('template.preview', $template) }}" class="certificate-preview" frameborder="0" loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3 text-center">
                            <div class="btn-toolbar justify-content-center">
                                <div class="btn-group me-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="zoomInButton">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary" id="zoomOutButton">
                                        <i class="fa-solid fa-magnifying-glass-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="resetZoomButton">
                                        <i class="fa-solid fa-arrows-to-circle"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="rotateViewButton">
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
    padding-bottom: 20px;
}

.editor-header {
    background-color: white;
    border-bottom: 1px solid #e9ecef;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    margin-bottom: 15px;
}

/* Адаптивные стили для мобильных устройств */
@media (max-width: 991.98px) {
    .certificate-preview-container {
        min-height: 350px;
    }
}

@media (max-width: 767.98px) {
    .editor-body {
        padding-bottom: 15px;
    }
    
    .certificate-preview-wrapper {
        max-height: 50vh !important;
    }
    
    .certificate-preview-iframe {
        height: 50vh !important;
    }
    
    .fs-7 {
        font-size: 0.8rem !important;
    }
    
    .form-text {
        margin-top: 0.15rem;
        font-size: 0.7rem !important;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem !important;
    }
    
    .form-control-sm, .form-select-sm {
        padding: 0.2rem 0.5rem !important;
        font-size: 0.75rem !important;
    }
    
    .input-group-text.small {
        padding: 0.2rem 0.5rem !important;
        font-size: 0.75rem !important;
    }
    
    .form-check-label.small {
        font-size: 0.75rem !important;
    }
}

/* Улучшенный стиль для мобильного iframe */
@media (max-width: 575.98px) {
    .certificate-preview-container {
        min-height: 300px;
    }
    
    .certificate-preview-wrapper {
        max-height: 40vh !important;
    }
    
    #certificatePreview {
        min-height: auto !important;
        height: 40vh !important;
    }
    
    .device-toggle .btn {
        padding: 0.2rem 0.4rem !important;
    }
}

/* Фикс для iPhone SE и других маленьких устройств */
@media (max-width: 375px) {
    .container {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }
    
    .card-body, .card-header, .card-footer {
        padding: 0.5rem !important;
    }
    
    .certificate-preview-container {
        min-height: 250px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Элементы DOM
    const previewFrame = document.getElementById('certificatePreview');
    const formInputs = document.querySelectorAll('#certificateForm input, #certificateForm textarea');
    const previewContainer = document.querySelector('.certificate-preview-container');
    let scale = 1;
    let logoUrl = '{{ Auth::user()->company_logo ? asset('storage/' . Auth::user()->company_logo) : asset('images/default-logo.png') }}';
    
    // Переключение типа логотипа
    const logoDefault = document.getElementById('logo_default');
    const logoCustom = document.getElementById('logo_custom');
    const logoNone = document.getElementById('logo_none');
    const defaultLogoPreview = document.getElementById('default_logo_preview');
    const customLogoContainer = document.getElementById('custom_logo_container');
    const customLogoInput = document.getElementById('custom_logo');
    const customLogoPreview = document.getElementById('custom_logo_preview');
    
    logoDefault.addEventListener('change', function() {
        if (this.checked) {
            defaultLogoPreview.classList.remove('d-none');
            customLogoContainer.classList.add('d-none');
            logoUrl = '{{ Auth::user()->company_logo ? asset('storage/' . Auth::user()->company_logo) : asset('images/default-logo.png') }}';
            console.log("Установлен логотип по умолчанию:", logoUrl);
            updatePreview();
        }
    });
    
    logoCustom.addEventListener('change', function() {
        if (this.checked) {
            defaultLogoPreview.classList.add('d-none');
            customLogoContainer.classList.remove('d-none');
            // Если уже есть загруженный пользовательский логотип
            if (customLogoPreview.querySelector('img')) {
                logoUrl = customLogoPreview.querySelector('img').src;
                console.log("Установлен пользовательский логотип:", logoUrl);
                updatePreview();
            }
        }
    });
    
    // Предпросмотр загруженного логотипа
    customLogoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const tempLogoUrl = e.target.result;
                
                customLogoPreview.innerHTML = `
                    <img src="${tempLogoUrl}" class="img-thumbnail" style="max-height: 60px;" alt="Загруженный логотип">
                    <div class="small text-muted mt-1">Новый логотип</div>
                `;
                
                // Сразу обновляем логотип в предпросмотре с временным локальным URL
                logoUrl = tempLogoUrl;
                updatePreview();
                
                // Отправляем файл на сервер для временного хранения
                const formData = new FormData();
                formData.append('logo', customLogoInput.files[0]);
                formData.append('_token', '{{ csrf_token() }}');
                
                console.log("Отправка логотипа на сервер...");
                
                fetch('{{ route('entrepreneur.certificates.temp-logo') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Сохраняем URL логотипа с сервера
                        logoUrl = data.logo_url;
                        console.log("Логотип успешно загружен на сервер:", logoUrl);
                        // Обновляем превью с серверным URL логотипа
                        updatePreview();
                    } else {
                        console.error('Ошибка загрузки логотипа:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Произошла ошибка:', error);
                });
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Улучшенная функция обновления предпросмотра
    const updatePreview = () => {
        // Получаем значения из полей формы
        const recipientName = document.getElementById('recipient_name').value || 'Имя получателя';
        const amount = document.getElementById('amount').value || '3000';
        const message = document.getElementById('message').value || '';
        
        // Устанавливаем текущую дату и срок действия
        const validFrom = document.getElementById('valid_from').value 
            ? new Date(document.getElementById('valid_from').value).toLocaleDateString('ru-RU')
            : new Date().toLocaleDateString('ru-RU');
            
        const validUntil = document.getElementById('valid_until').value 
            ? new Date(document.getElementById('valid_until').value).toLocaleDateString('ru-RU')
            : new Date(Date.now() + 90*24*60*60*1000).toLocaleDateString('ru-RU');
        
        // Компания
        const companyName = '{{ Auth::user()->company ?? config('app.name') }}';
        
        // Создаем параметры для запроса - БЕЗ логотипа
        const params = new URLSearchParams({
            recipient_name: recipientName,
            amount: `${Number(amount).toLocaleString('ru-RU')} ₽`,
            valid_from: validFrom,
            valid_until: validUntil,
            message: message,
            certificate_number: 'CERT-PREVIEW',
            company_name: companyName
        });
        
        // Обновляем iframe с новыми параметрами
        const iframeSrc = `{{ route('template.preview', $template) }}?${params.toString()}`;
        
        // Проверяем, нужно ли обновлять iframe
        if (previewFrame.src.split('?')[0] === iframeSrc.split('?')[0]) {
            // Только обновляем параметры для существующего iframe
            previewFrame.src = iframeSrc;
        } else {
            // Полностью меняем src, если изменился базовый URL
            previewFrame.src = iframeSrc;
        }
        
        // После загрузки iframe отправляем логотип через postMessage
        previewFrame.onload = function() {
            // Оптимизированная отправка логотипа
            setTimeout(() => {
                try {
                    previewFrame.contentWindow.postMessage({
                        type: 'update_logo',
                        logo_url: logoUrl
                    }, '*');
                } catch (error) {
                    console.error("Ошибка при отправке сообщения в iframe:", error);
                }
            }, 300);
        };
    };
    
    // Устанавливаем обработчики событий для полей ввода с троттлингом
    let updateTimeout;
    formInputs.forEach(input => {
        ['input', 'change', 'keyup', 'paste'].forEach(eventType => {
            input.addEventListener(eventType, function() {
                if (input.id !== 'custom_logo') {
                    clearTimeout(updateTimeout);
                    updateTimeout = setTimeout(updatePreview, 300); // Задержка для улучшения производительности
                }
            });
        });
    });
    
    // Управление масштабом предпросмотра с адаптивным шагом
    document.getElementById('zoomInButton').addEventListener('click', function() {
        const zoomStep = window.innerWidth < 768 ? 1.05 : 1.1;
        scale *= zoomStep;
        previewFrame.style.transform = `scale(${scale})`;
    });
    
    document.getElementById('zoomOutButton').addEventListener('click', function() {
        const zoomStep = window.innerWidth < 768 ? 0.95 : 0.9;
        scale *= zoomStep;
        previewFrame.style.transform = `scale(${scale})`;
    });
    
    document.getElementById('resetZoomButton').addEventListener('click', function() {
        scale = 1;
        previewFrame.style.transform = 'scale(1)';
    });
    
    // Переключение между устройствами с учетом размера экрана
    const deviceButtons = document.querySelectorAll('.device-toggle button');
    deviceButtons.forEach(button => {
        button.addEventListener('click', function() {
            deviceButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const device = this.getAttribute('data-device');
            previewContainer.setAttribute('data-current-device', device);
            
            // Автоматически сбрасываем масштаб при переключении устройства
            scale = 1;
            previewFrame.style.transform = 'scale(1)';
            
            // Для мобильных устройств, если выбран desktop, переключаем на tablet
            if (window.innerWidth < 576 && device === 'desktop') {
                setTimeout(() => {
                    const tabletButton = document.querySelector('[data-device="tablet"]');
                    if (tabletButton) tabletButton.click();
                }, 100);
            }
        });
    });
    
    // Поворот устройства с улучшенной адаптивностью
    document.getElementById('rotateViewButton').addEventListener('click', function() {
        const currentDevice = previewContainer.getAttribute('data-current-device');
        if (currentDevice !== 'desktop') {
            previewContainer.classList.toggle('landscape');
            // Сбрасываем масштаб при повороте
            scale = 1;
            previewFrame.style.transform = 'scale(1)';
        }
    });
    
    // Добавляем обработчик для опции "Не использовать логотип"
    logoNone.addEventListener('change', function() {
        if (this.checked) {
            defaultLogoPreview.classList.add('d-none');
            customLogoContainer.classList.add('d-none');
            logoUrl = 'none';
            updatePreview();
        }
    });
    
    // Адаптивные настройки при изменении размера окна
    window.addEventListener('resize', function() {
        // Для мобильных устройств принудительно выбираем tablet или mobile
        if (window.innerWidth < 576) {
            const currentDevice = previewContainer.getAttribute('data-current-device');
            if (currentDevice === 'desktop') {
                const tabletButton = document.querySelector('[data-device="tablet"]');
                if (tabletButton) tabletButton.click();
            }
        }
    });
    
    // Предпросмотр изображения обложки сертификата
    const coverImageInput = document.getElementById('cover_image');
    const coverImagePreview = document.getElementById('cover_image_preview');
    
    coverImageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                coverImagePreview.innerHTML = `
                    <div class="card shadow-sm">
                        <div class="card-body p-2">
                            <h6 class="card-title small mb-2">Предпросмотр обложки</h6>
                            <img src="${e.target.result}" class="img-fluid rounded mb-2" style="max-height: 200px;" alt="Предпросмотр обложки">
                            <p class="text-muted mb-0 small">Так обложка будет выглядеть в карточке сертификата</p>
                        </div>
                    </div>
                `;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Инициализация предпросмотра
    updatePreview();
});
</script>
@endsection
