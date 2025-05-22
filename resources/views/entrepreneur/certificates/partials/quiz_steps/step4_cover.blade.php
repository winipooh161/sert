<div class="quiz-step active" id="quizStep4">
    <div class="text-center mb-4">
        <div class="quiz-step-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-3">
            <i class="fa-solid fa-image text-primary fs-3"></i>
        </div>
        <h3 class="quiz-step-title">Обложка сертификата</h3>
        <p class="text-muted">Создайте изображение для карточки сертификата</p>
    </div>
    
    <div class="mb-4">
        <div class="cover-upload-container">
            <!-- Если есть временное изображение из фоторедактора, добавляем скрытое поле -->
            @if(session('temp_certificate_cover'))
                <input type="hidden" name="temp_cover_path" value="{{ session('temp_certificate_cover') }}">
            @endif
            
            <div class="mb-3">
                <label class="form-label fw-medium">Изображение обложки:</label>
                
                @if(session('temp_certificate_cover'))
                <!-- Показать предпросмотр загруженного изображения -->
                <div id="cover_upload_status" class="mt-2">
                    <div class="alert alert-success py-2">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-check-circle me-2"></i>
                            <div>
                                <div>Изображение успешно создано в редакторе</div>
                                <div class="small text-muted">Файл уже сохранен на сервере</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <img src="{{ Storage::url(session('temp_certificate_cover')) }}" style="max-height: 150px; border-radius: 4px;" class="img-thumbnail">
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('photo.editor') }}?template={{ request()->route('template')->id }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-pencil me-2"></i>Изменить изображение
                        </a>
                    </div>
                </div>
                @else
                <!-- Показать кнопку для перехода в фоторедактор -->
                <div class="text-center p-4 border rounded">
                    <div class="mb-3">
                        <i class="fa-solid fa-image fa-3x text-muted"></i>
                    </div>
                    <p>Для создания изображения воспользуйтесь встроенным редактором</p>
                    <div class="form-text mb-3">Рекомендуемый размер: 1200 x 630 пикселей. Максимальный размер файла: 20MB.</div>
                    <a href="{{ route('photo.editor') }}?template={{ request()->route('template')->id }}" class="btn btn-primary">
                        <i class="fa-solid fa-camera me-2"></i>Открыть фоторедактор
                    </a>
                </div>
                @endif
            </div>
            
            @error('temp_cover_path')
                <div class="invalid-feedback d-block mt-2 text-center">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="mt-4">
        <h6 class="fw-medium mb-3">Логотип на сертификате:</h6>
        <div class="logo-options">
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="logo_type" id="logo_default" value="default" checked>
                <label class="form-check-label d-flex align-items-center" for="logo_default">
                    <span class="me-2">Использовать из профиля</span>
                    <div class="small-logo-preview border rounded p-1">
                        <img src="{{ Auth::user()->company_logo ? asset('storage/' . Auth::user()->company_logo) : asset('images/default-logo.png') }}" 
                             style="max-height: 24px; max-width: 80px;" alt="Логотип">
                    </div>
                </label>
            </div>
            
            <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="logo_type" id="logo_custom" value="custom">
                <label class="form-check-label" for="logo_custom">
                    Загрузить новый логотип
                </label>
            </div>
            
            <div class="form-check">
                <input class="form-check-input" type="radio" name="logo_type" id="logo_none" value="none">
                <label class="form-check-label" for="logo_none">
                    Без логотипа
                </label>
            </div>
            
            <!-- Контейнер для загрузки пользовательского логотипа (скрыт по умолчанию) -->
            <div id="custom_logo_container" class="mt-3 d-none">
                <div class="logo-upload-container">
                    <!-- Используем hidden вместо position-absolute -->
                    <input type="file" class="form-control form-control-lg @error('custom_logo') is-invalid @enderror" 
                        id="custom_logo" name="custom_logo" accept="image/*" data-file-uploaded="false"
                        style="display: none;">
                    
                    <label for="custom_logo" class="logo-upload-label">
                        <div id="logo_upload_placeholder" class="d-flex flex-column align-items-center justify-content-center p-3 border rounded">
                            <i class="fa-solid fa-cloud-arrow-up mb-2 text-primary"></i>
                            <span class="text-center small">Загрузить логотип</span>
                        </div>
                        
                        <div id="logo_preview_container" class="d-none">
                            <img src="#" id="logo_preview_image" class="img-fluid rounded w-100" alt="Предпросмотр логотипа">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" id="remove_logo">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </label>
                    
                    @error('custom_logo')
                        <div class="invalid-feedback d-block mt-2 text-center">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cover-upload-container {
    position: relative;
    width: 100%;
}

.cover-upload-label {
    display: block;
    position: relative;
    width: 100%;
    min-height: 180px;
    border: 2px dashed #dee2e6;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
}

.cover-upload-label:hover {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05);
}

#cover_preview_container {
    width: 100%;
    height: 100%;
    position: relative;
}

#cover_preview_image {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.logo-upload-label {
    display: block;
    position: relative;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
}

#logo_preview_container {
    width: 100%;
    height: 100%;
    position: relative;
}

#logo_preview_image {
    max-height: 60px;
    width: auto;
    object-fit: contain;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, есть ли временное изображение из фоторедактора
    @if(session('temp_certificate_cover'))
        // Обновляем информацию в итоговом шаге
        const summaryCover = document.getElementById('summary_cover');
        if (summaryCover) {
            summaryCover.innerHTML = `<span class="badge bg-success">Загружена из редактора</span>`;
        }
    @endif
    
    // Переключение типа логотипа
    const logoTypeRadios = document.querySelectorAll('input[name="logo_type"]');
    const customLogoContainer = document.getElementById('custom_logo_container');
    
    logoTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'custom') {
                if (customLogoContainer) customLogoContainer.classList.remove('d-none');
            } else {
                if (customLogoContainer) customLogoContainer.classList.add('d-none');
            }
            
            // Вибрация для обратной связи
            if (window.safeVibrate) {
                window.safeVibrate(30);
            }
        });
    });
    
    // Обработка логотипа (оставляем без изменений)
    // ...existing code...
});
</script>
