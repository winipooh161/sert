<div class="card border-0 shadow-sm rounded-4 certificate-quiz-container">
    <div class="card-header bg-transparent border-0 pt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0 fs-6">Создание сертификата</h5>
            <span class="quiz-progress">Шаг <span id="currentStep">1</span> из <span id="totalSteps">5</span></span>
        </div>
        
        <!-- Индикатор прогресса -->
        <div class="progress" style="height: 5px;">
            <div class="progress-bar" id="quizProgressBar" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
    
    <div class="card-body">
        <form method="POST" action="{{ route('entrepreneur.certificates.store', $template) }}" id="mobileCertificateForm" class="mobile-form" enctype="multipart/form-data">
            @csrf

            <!-- Контейнер для шагов квиза -->
            <div class="quiz-steps-container">
                <!-- Шаг 1: Номинал сертификата -->
                @include('entrepreneur.certificates.partials.quiz_steps.step1_amount')
                
                <!-- Шаг 2: Информация о получателе -->
                @include('entrepreneur.certificates.partials.quiz_steps.step2_recipient')
                
                <!-- Шаг 3: Срок действия -->
                @include('entrepreneur.certificates.partials.quiz_steps.step3_validity')
                
                <!-- Шаг 4: Обложка сертификата -->
                @include('entrepreneur.certificates.partials.quiz_steps.step4_cover')
                
                <!-- Шаг 5: Дополнительные функции -->
                @include('entrepreneur.certificates.partials.quiz_steps.step5_extras')
            </div>

            <!-- Кнопки навигации -->
            <div class="quiz-navigation mt-4">
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-outline-secondary w-100" id="prevStepBtn" disabled>
                            <i class="fa-solid fa-chevron-left me-2"></i>Назад
                        </button>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-primary w-100" id="nextStepBtn">
                            Далее<i class="fa-solid fa-chevron-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-success w-100 d-none" id="submitQuizBtn">
                            <i class="fa-solid fa-check me-2"></i>Создать 
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.certificate-quiz-container {
    position: relative;
    border-radius: 1rem;
}

.quiz-progress {
    font-size: 0.8rem;
    color: #6c757d;
}

.quiz-step {
    display: none;
}

.quiz-step.active {
    display: block;
    animation: fadeIn 0.3s ease-in-out;
}

.quiz-step-title {
    font-weight: 600;
    margin-bottom: 1rem;
    color: #0d6efd;
}

.quiz-navigation {
    margin-top: 1.5rem;
}

/* Анимация для плавного появления шагов */
@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(10px); }
    100% { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Элементы квиза
    const quizSteps = document.querySelectorAll('.quiz-step');
    const prevBtn = document.getElementById('prevStepBtn');
    const nextBtn = document.getElementById('nextStepBtn');
    const submitBtn = document.getElementById('submitQuizBtn');
    const currentStepIndicator = document.getElementById('currentStep');
    const progressBar = document.getElementById('quizProgressBar');
    const quizForm = document.getElementById('mobileCertificateForm');
    
    let currentStep = 0;
    const totalSteps = quizSteps.length;
    
    // Инициализация квиза
    function initQuiz() {
        // Показываем первый шаг
        showStep(currentStep);
        
        // Обновляем общее количество шагов в индикаторе
        document.getElementById('totalSteps').textContent = totalSteps;
    }
    
    // Функция для отображения определенного шага
    function showStep(stepIndex) {
        // Скрываем все шаги
        quizSteps.forEach(step => {
            step.classList.remove('active');
        });
        
        // Показываем нужный шаг
        quizSteps[stepIndex].classList.add('active');
        
        // Обновляем индикатор текущего шага
        currentStepIndicator.textContent = stepIndex + 1;
        
        // Обновляем прогресс-бар
        const progressPercentage = ((stepIndex + 1) / totalSteps) * 100;
        progressBar.style.width = `${progressPercentage}%`;
        progressBar.setAttribute('aria-valuenow', progressPercentage);
        
        // Управление состоянием кнопок
        prevBtn.disabled = stepIndex === 0;
        
        // На последнем шаге показываем кнопку отправки формы
        if (stepIndex === totalSteps - 1) {
            nextBtn.classList.add('d-none');
            submitBtn.classList.remove('d-none');
        } else {
            nextBtn.classList.remove('d-none');
            submitBtn.classList.add('d-none');
        }
        
        // Прокручиваем страницу вверх для лучшего UX
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        
        // Эффект вибрации при переходе к следующему шагу (только если устройство поддерживает)
        if (window.safeVibrate && stepIndex > 0) {
            window.safeVibrate(50);
        }
        
        // Обновляем предпросмотр сертификата
        if (window.updatePreview && typeof window.updatePreview === 'function') {
            setTimeout(window.updatePreview, 100);
        }
    }
    
    // Обновленная функция проверки перед переходом к следующему шагу
    function nextStep() {
        // Получаем текущий шаг
        const currentQuizStep = quizSteps[currentStep];
        const requiredFields = currentQuizStep.querySelectorAll('[required]');
        let isValid = true;
        
        // Удаляем старые сообщения об ошибках
        const oldErrorMessages = currentQuizStep.querySelectorAll('.quiz-error, .feedback-text');
        oldErrorMessages.forEach(msg => msg.remove());
        
        // Проверяем все обязательные поля
        requiredFields.forEach(field => {
            // Пропускаем скрытые поля (кроме file inputs, которые могут быть скрыты)
            if (field.offsetParent === null && field.type !== 'file') {
                return;
            }
            
            // Проверка для полей типа file
            if (field.type === 'file') {
                const fileUploaded = field.files && field.files.length > 0;
                const wasUploaded = field.getAttribute('data-file-uploaded') === 'true';
                
                console.log(`Проверка поля ${field.id}:`, {
                    fileUploaded,
                    wasUploaded,
                    isRequired: field.required,
                    dataAttr: field.getAttribute('data-file-uploaded')
                });
                
                if (field.required && !fileUploaded && !wasUploaded) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Добавляем сообщение об ошибке рядом с полем
                    const fieldContainer = field.closest('.cover-upload-container') || field.closest('.logo-upload-container');
                    if (fieldContainer) {
                        const errorMsg = fieldContainer.querySelector('#cover_error_message') || 
                                       document.createElement('div');
                        
                        if (!fieldContainer.querySelector('#cover_error_message')) {
                            errorMsg.id = field.id + '_error_message';
                            errorMsg.classList.add('text-danger', 'small', 'mt-2');
                            fieldContainer.appendChild(errorMsg);
                        }
                        
                        errorMsg.textContent = field.id === 'cover_image' 
                            ? 'Пожалуйста, выберите изображение для обложки' 
                            : 'Пожалуйста, выберите логотип';
                        errorMsg.classList.remove('d-none');
                    }
                } else {
                    field.classList.remove('is-invalid');
                    
                    // Удаляем сообщение об ошибке если оно есть
                    const fieldContainer = field.closest('.cover-upload-container') || field.closest('.logo_upload-container');
                    if (fieldContainer) {
                        const errorMsg = fieldContainer.querySelector(`#${field.id}_error_message`) || 
                                       fieldContainer.querySelector('.feedback-text');
                        if (errorMsg) errorMsg.classList.add('d-none');
                    }
                }
            }
            // Обычная проверка для других типов полей
            else if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            // Показываем общее сообщение об ошибке
            const errorMsg = document.createElement('div');
            errorMsg.classList.add('alert', 'alert-danger', 'mt-3', 'mb-0', 'py-2', 'quiz-error');
            errorMsg.innerHTML = '<i class="fa-solid fa-exclamation-circle me-2"></i>Пожалуйста, заполните все обязательные поля';
            currentQuizStep.appendChild(errorMsg);
            
            // Вибрация для обратной связи об ошибке
            if (window.safeVibrate) {
                window.safeVibrate([30, 50, 30]);
            }
            
            // Прокрутка к первому невалидному полю
            const firstInvalidField = currentQuizStep.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            return; // Останавливаем переход к следующему шагу
        }
        
        // Если валидация прошла успешно, переходим к следующему шагу
        if (currentStep < totalSteps - 1) {
            currentStep++;
            showStep(currentStep);
        }
    }
    
    // Переход к предыдущему шагу
    function prevStep() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    }
    
    // Назначение обработчиков событий для кнопок
    nextBtn.addEventListener('click', nextStep);
    prevBtn.addEventListener('click', prevStep);
    
    // Инициализация при загрузке страницы
    initQuiz();
    
    // Синхронизация значений между шагами квиза
    function syncQuizValues() {
        // При изменении полей обновляем значения и в других местах
        const allInputs = quizForm.querySelectorAll('input, textarea, select');
        
        allInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Удаляем класс ошибки при изменении значения
                this.classList.remove('is-invalid');
                
                // Синхронизация значений между мобильной и десктопной формами
                const desktopForm = document.getElementById('desktopCertificateForm');
                if (desktopForm) {
                    const desktopInput = desktopForm.querySelector(`[name="${this.name}"]`);
                    if (desktopInput && desktopInput !== this) {
                        if (this.type === 'checkbox' || this.type === 'radio') {
                            desktopInput.checked = this.checked;
                        } else {
                            desktopInput.value = this.value;
                        }
                    }
                }
                
                // Обновляем предпросмотр с небольшой задержкой
                if (window.updatePreview && typeof window.updatePreview === 'function') {
                    clearTimeout(window.updatePreviewTimeout);
                    window.updatePreviewTimeout = setTimeout(window.updatePreview, 300);
                }
            });
        });
    }
    
    // Обработка отправки формы
    quizForm.addEventListener('submit', function(e) {
        // Проверка последнего шага перед отправкой
        const lastStep = quizSteps[totalSteps - 1];
        let isValid = true;
        
        // Проверяем все шаги формы на наличие обязательных полей
        quizSteps.forEach(step => {
            const requiredFields = step.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                // Пропускаем скрытые поля (d-none)
                if (field.offsetParent === null && !field.classList.contains('position-absolute')) {
                    return;
                }
                
                // Универсальная обработка для полей типа file
                if (field.type === 'file') {
                    // Проверяем, есть ли уже загруженный файл
                    const fileUploaded = field.files && field.files.length > 0;
                    // Проверяем, был ли файл ранее загружен с помощью data-атрибута
                    const wasUploaded = field.getAttribute('data-file-uploaded') === 'true';
                    
                    console.log('Итоговая проверка файла ' + field.id + ':', {
                        fileUploaded,
                        wasUploaded,
                        isValid: fileUploaded || wasUploaded,
                        fieldValue: field.value,
                        dataAttr: field.getAttribute('data-file-uploaded')
                    });
                    
                    if (!fileUploaded && !wasUploaded) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        
                        // Если поле находится в неактивном шаге, активируем этот шаг
                        if (!field.closest('.quiz-step').classList.contains('active')) {
                            const stepIndex = Array.from(quizSteps).indexOf(field.closest('.quiz-step'));
                            if (stepIndex !== -1) {
                                currentStep = stepIndex;
                                showStep(currentStep);
                            }
                        }
                    } else {
                        field.classList.remove('is-invalid');
                    }
                } 
                // Обычная проверка для других типов полей
                else if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Если поле находится в неактивном шаге, активируем этот шаг
                    if (!field.closest('.quiz-step').classList.contains('active')) {
                        const stepIndex = Array.from(quizSteps).indexOf(field.closest('.quiz-step'));
                        if (stepIndex !== -1) {
                            currentStep = stepIndex;
                            showStep(currentStep);
                        }
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            });
        });
        
        if (!isValid) {
            e.preventDefault();
            
            // Добавляем сообщение об ошибке к текущему шагу
            const errorMsg = document.createElement('div');
            errorMsg.classList.add('alert', 'alert-danger', 'mt-3', 'mb-0', 'py-2', 'quiz-error');
            errorMsg.innerHTML = '<i class="fa-solid fa-exclamation-circle me-2"></i>Пожалуйста, заполните все обязательные поля';
            
            // Добавляем сообщение только если его еще нет
            if (!quizSteps[currentStep].querySelector('.quiz-error')) {
                quizSteps[currentStep].appendChild(errorMsg);
            }
            
            // Вибрация для обратной связи об ошибке
            if (window.safeVibrate) {
                window.safeVibrate([30, 50, 30]);
            }
            
            return false;
        }
        
        // Перед отправкой формы проверяем все поля загрузки файлов
        const fileInputs = quizForm.querySelectorAll('input[type="file"][required]');
        fileInputs.forEach(input => {
            // Если файл уже загружен (по атрибуту data-file-uploaded), убираем required
            if (input.getAttribute('data-file-uploaded') === 'true') {
                input.removeAttribute('required');
            }
        });
        
        return true;
    });
    
    // Инициализируем синхронизацию значений
    syncQuizValues();
});
</script>
