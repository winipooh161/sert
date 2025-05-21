<div class="quiz-step" id="quizStep4">
    <div class="text-center mb-4">
        <div class="quiz-step-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-3">
            <i class="fa-solid fa-image text-primary fs-3"></i>
        </div>
        <h3 class="quiz-step-title">Обложка сертификата</h3>
        <p class="text-muted">Выберите изображение для карточки сертификата</p>
    </div>
    
    <div class="mb-4">
        <div class="cover-upload-container">
            <div class="mb-3">
                <label for="cover_image" class="form-label fw-medium">Загрузите изображение обложки:</label>
                <input type="file" class="form-control form-control-lg @error('cover_image') is-invalid @enderror" 
                    id="cover_image" name="cover_image" accept="image/*" data-file-uploaded="false" required>
                <div class="form-text">Рекомендуемый размер: 1200 x 630 пикселей. Максимальный размер файла: 20MB.</div>
                <div id="cover_upload_status" class="mt-2 d-none">
                    <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Файл выбран</span>
                </div>
            </div>
          
            @error('cover_image')
                <div class="invalid-feedback d-block mt-2 text-center">{{ $message }}</div>
            @enderror
            <div id="cover_error_message" class="text-danger small mt-2 d-none"></div>
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
    // Элементы для обложки
    const coverInput = document.getElementById('cover_image');
    const coverErrorMessage = document.getElementById('cover_error_message');
    const coverUploadStatus = document.getElementById('cover_upload_status');
    
    // Элементы для логотипа
    const logoTypeRadios = document.querySelectorAll('input[name="logo_type"]');
    const customLogoContainer = document.getElementById('custom_logo_container');
    const logoInput = document.getElementById('custom_logo');
    const logoPreviewContainer = document.getElementById('logo_preview_container');
    const logoPreviewImage = document.getElementById('logo_preview_image');
    const logoUploadPlaceholder = document.getElementById('logo_upload_placeholder');
    const removeLogoButton = document.getElementById('remove_logo');
    const logoLabel = document.querySelector('.logo-upload-label');
    
    // Обработчик изменения обложки - упрощенная версия без предпросмотра
    if (coverInput) {
        coverInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Проверка размера файла (до 20MB)
                const maxSize = 20 * 1024 * 1024; // 20MB в байтах
                if (file.size > maxSize) {
                    if (coverErrorMessage) {
                        coverErrorMessage.textContent = 'Файл слишком большой. Максимальный размер 20MB.';
                        coverErrorMessage.classList.remove('d-none');
                    }
                    coverInput.value = ''; // Сбрасываем выбранный файл
                    
                    // Скрываем статус успешной загрузки
                    if (coverUploadStatus) coverUploadStatus.classList.add('d-none');
                    return;
                }
                
                // Устанавливаем флаг, что файл загружен
                coverInput.setAttribute('data-file-uploaded', 'true');
                
                // Показываем статус успешной загрузки
                if (coverUploadStatus) coverUploadStatus.classList.remove('d-none');
                
                // Прячем сообщение об ошибке если оно есть
                if (coverErrorMessage) {
                    coverErrorMessage.classList.add('d-none');
                }
                
                // Удаляем класс ошибки
                coverInput.classList.remove('is-invalid');
                
                // Обновляем информацию в итоговом шаге
                const summaryCover = document.getElementById('summary_cover');
                if (summaryCover) {
                    summaryCover.innerHTML = `<span class="badge bg-success">Выбрана</span>`;
                }
                
                // Тактильная обратная связь
                if (window.safeVibrate) {
                    window.safeVibrate(50);
                }
            } else {
                // Скрываем статус успешной загрузки если файл не выбран
                if (coverUploadStatus) coverUploadStatus.classList.add('d-none');
                
                // Сбрасываем флаг загрузки файла
                coverInput.setAttribute('data-file-uploaded', 'false');
            }
        });
    }
    
    // Обработка удаления обложки
    if (removeCoverButton) {
        removeCoverButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Останавливаем всплытие события
            
            // Сбрасываем input и флаг загрузки
            if (coverInput) {
                coverInput.value = '';
                coverInput.setAttribute('data-file-uploaded', 'false');
            }
            
            // Скрываем превью и показываем плейсхолдер
            if (coverPreviewContainer) coverPreviewContainer.classList.add('d-none');
            if (coverUploadPlaceholder) coverUploadPlaceholder.classList.remove('d-none');
            
            // Обновляем информацию в итоговом шаге
            const summaryCover = document.getElementById('summary_cover');
            if (summaryCover) {
                summaryCover.innerHTML = `<span class="badge bg-secondary">Не выбрана</span>`;
            }
            
            // Тактильная обратная связь
            if (window.safeVibrate) {
                window.safeVibrate(50);
            }
        });
    }
    
    // Переключение типа логотипа
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
    
    // Обработчик изменения логотипа
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const reader = new FileReader();
                
                // Устанавливаем флаг, что файл загружен
                logoInput.setAttribute('data-file-uploaded', 'true');
                
                reader.onload = function(e) {
                    // Обновляем превью
                    logoPreviewImage.src = e.target.result;
                    logoPreviewContainer.classList.remove('d-none');
                    logoUploadPlaceholder.classList.add('d-none');
                    
                    // Удаляем класс ошибки
                    logoInput.classList.remove('is-invalid');
                    
                    // Отправляем файл на сервер для временного хранения
                    const formData = new FormData();
                    formData.append('logo', file);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    fetch('{{ route('entrepreneur.certificates.temp-logo') }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && window.updatePreview && typeof window.updatePreview === 'function') {
                            window.logoUrl = data.logo_url;
                            window.updatePreview();
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при загрузке логотипа:', error);
                    });
                    
                    // Тактильная обратная связь
                    if (window.safeVibrate) {
                        window.safeVibrate(50);
                    }
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Обработка удаления логотипа
    if (removeLogoButton) {
        removeLogoButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Очень важно остановить всплытие
            
            if (logoInput) {
                logoInput.value = '';
                logoInput.setAttribute('data-file_uploaded', 'false');
            }
            
            if (logoPreviewContainer) logoPreviewContainer.classList.add('d-none');
            if (logoUploadPlaceholder) logoUploadPlaceholder.classList.remove('d-none');
            
            // Тактильная обратная связь
            if (window.safeVibrate) {
                window.safeVibrate(50);
            }
        });
    }
});
</script>
