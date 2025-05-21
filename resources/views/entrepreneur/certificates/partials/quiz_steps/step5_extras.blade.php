<div class="quiz-step" id="quizStep5">
    <div class="text-center mb-4">
        <div class="quiz-step-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-3">
            <i class="fa-solid fa-wand-magic-sparkles text-primary fs-3"></i>
        </div>
        <h3 class="quiz-step-title">Дополнительные функции</h3>
        <p class="text-muted">Добавьте анимационные эффекты и проверьте данные</p>
    </div>
    
    <div class="mb-4">
        <h6 class="fw-medium mb-3">Анимационный эффект:</h6>
        
        <!-- Улучшенный интерфейс выбора эффекта -->
        <div class="card border p-2">
            <!-- Скрытые поля для эффекта -->
            <input type="hidden" name="animation_effect_id" id="animation_effect_id" value="{{ old('animation_effect_id') }}">
            <input type="hidden" id="selected_effect_name" value="Не выбран">
            
            <!-- Отображение текущего выбранного эффекта -->
            <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                <div class="flex-grow-1">
                    <span class="fw-medium">Текущий эффект:</span>
                    <span id="current_effect_display">Не выбран</span>
                </div>
                <div class="badge bg-light text-primary" id="effect_type_badge"></div>
            </div>
            
            <!-- Примеры эффектов для быстрого выбора -->
            <div class="quick-effects mb-2">
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary quick-effect-btn" data-effect-id="" data-effect-name="Без эффекта">
                        <i class="fa-solid fa-ban me-1"></i>Нет
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary quick-effect-btn" data-effect-id="1" data-effect-name="Конфетти">
                        <i class="fa-solid fa-certificate me-1"></i>Конфетти
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary quick-effect-btn" data-effect-id="2" data-effect-name="Снежинки">
                        <i class="fa-solid fa-snowflake me-1"></i>Снег
                    </button>
                </div>
            </div>
            
            <!-- Кнопка для открытия полного модального окна с эффектами -->
            <button type="button" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center w-100" data-bs-toggle="modal" data-bs-target="#animationEffectsModal">
                <i class="fa-solid fa-wand-sparkles me-2"></i>
                <span class="d-none d-sm-inline">Выбрать другой эффект</span>
                <span class="d-inline d-sm-none">Больше эффектов</span>
            </button>
        </div>
        
        <div class="form-text small mt-2">Анимация привлечет внимание получателя при просмотре сертификата</div>
    </div>
    
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body p-3">
            <h5 class="card-title fw-semibold fs-6 mb-3">Проверка заполненных данных</h5>
            
            <div class="mb-3 row align-items-center">
                <div class="col-5 text-muted">Номинал:</div>
                <div class="col-7 fw-medium" id="summary_amount">3000 ₽</div>
            </div>
            
            <div class="mb-3 row align-items-center">
                <div class="col-5 text-muted">Получатель:</div>
                <div class="col-7 fw-medium" id="summary_recipient">&mdash;</div>
            </div>
            
            <div class="mb-3 row align-items-center">
                <div class="col-5 text-muted">Срок действия:</div>
                <div class="col-7 fw-medium" id="summary_validity">{{ now()->addMonths(3)->format('d.m.Y') }}</div>
            </div>
            
            <div class="mb-3 row align-items-center">
                <div class="col-5 text-muted">Обложка:</div>
                <div class="col-7" id="summary_cover">
                    <span class="badge bg-secondary">Не выбрана</span>
                </div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-5 text-muted">Эффект:</div>
                <div class="col-7" id="summary_effect">
                    <span class="badge bg-secondary">Нет эффекта</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация быстрого выбора эффектов
    const quickEffectButtons = document.querySelectorAll('.quick-effect-btn');
    const animationEffectId = document.getElementById('animation_effect_id');
    const selectedEffectName = document.getElementById('selected_effect_name');
    const currentEffectDisplay = document.getElementById('current_effect_display');
    const effectTypeBadge = document.getElementById('effect_type_badge');
    const summaryEffect = document.getElementById('summary_effect');

    // Устанавливаем предварительно загруженное состояние, если оно есть
    if (animationEffectId && animationEffectId.value) {
        // Находим кнопку с соответствующим ID и активируем её
        const matchingButton = [...quickEffectButtons].find(btn => 
            btn.getAttribute('data-effect-id') === animationEffectId.value
        );
        
        if (matchingButton) {
            matchingButton.classList.add('active', 'btn-primary');
            matchingButton.classList.remove('btn-outline-primary');
            
            // Обновляем отображение
            const effectName = matchingButton.getAttribute('data-effect-name');
            if (selectedEffectName) selectedEffectName.value = effectName;
            if (currentEffectDisplay) currentEffectDisplay.textContent = effectName;
            if (effectTypeBadge) {
                effectTypeBadge.textContent = "Выбран";
                effectTypeBadge.classList.add('bg-success', 'text-white');
                effectTypeBadge.classList.remove('bg-light', 'text-primary');
            }
            if (summaryEffect) {
                summaryEffect.innerHTML = `<span class="badge bg-primary">${effectName}</span>`;
            }
        }
    }

    quickEffectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const effectId = this.getAttribute('data-effect-id');
            const effectName = this.getAttribute('data-effect-name');
            
            // Обновляем скрытые поля
            animationEffectId.value = effectId;
            selectedEffectName.value = effectName;
            
            // Обновляем отображение
            currentEffectDisplay.textContent = effectName;
            
            // Сбрасываем активное состояние всех кнопок
            quickEffectButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
            this.classList.add('active', 'btn-primary');
            
            // Обновляем информацию в итоге
            if (effectId) {
                summaryEffect.innerHTML = `<span class="badge bg-primary">${effectName}</span>`;
                effectTypeBadge.textContent = "Выбран";
                effectTypeBadge.classList.remove('bg-light', 'bg-danger');
                effectTypeBadge.classList.add('bg-success', 'text-white');
            } else {
                summaryEffect.innerHTML = `<span class="badge bg-secondary">Нет эффекта</span>`;
                effectTypeBadge.textContent = "Не выбран";
                effectTypeBadge.classList.remove('bg-success', 'text-white');
                effectTypeBadge.classList.add('bg-light', 'text-primary');
            }
            
            // Обратная связь при нажатии
            if (window.safeVibrate) {
                window.safeVibrate(30);
            }
        });
    });
    
    // Обновление итоговой информации
    function updateSummary() {
        // Номинал
        const amountTypeRadios = document.querySelectorAll('input[name="amount_type"]');
        let amountType = 'money';
        
        for (const radio of amountTypeRadios) {
            if (radio.checked) {
                amountType = radio.value;
                break;
            }
        }
        
        if (amountType === 'money') {
            const amount = document.getElementById('amount')?.value || '3000';
            document.getElementById('summary_amount').textContent = `${Number(amount).toLocaleString('ru-RU')} ₽`;
        } else {
            const percent = document.getElementById('percent_value')?.value || '10';
            document.getElementById('summary_amount').textContent = `${percent}%`;
        }
        
        // Получатель
        const recipientName = document.getElementById('recipient_name')?.value || '&mdash;';
        document.getElementById('summary_recipient').innerHTML = recipientName;
        
        // Срок действия
        const validUntil = document.getElementById('valid_until')?.value;
        if (validUntil) {
            const date = new Date(validUntil);
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            document.getElementById('summary_validity').textContent = date.toLocaleDateString('ru-RU', options);
        }
        
        // Проверяем обложку
        const coverInput = document.getElementById('cover_image');
        if (coverInput && (coverInput.files?.length > 0 || coverInput.getAttribute('data-file-uploaded') === 'true')) {
            document.getElementById('summary_cover').innerHTML = `<span class="badge bg-success">Выбрана</span>`;
        } else {
            document.getElementById('summary_cover').innerHTML = `<span class="badge bg-secondary">Не выбрана</span>`;
        }
    }
    
    // Обновляем при изменении любого поля
    document.querySelectorAll('input, textarea, select').forEach(input => {
        ['input', 'change'].forEach(eventType => {
            input.addEventListener(eventType, updateSummary);
        });
    });
    
    // Обновляем при открытии последнего шага
    document.getElementById('nextStepBtn')?.addEventListener('click', function() {
        setTimeout(updateSummary, 100);
    });
    
    // Инициализация при загрузке страницы
    setTimeout(updateSummary, 500);
    
    // Обновляем данные при выборе эффекта в модальном окне
    if (window.selectEffectButton) {
        const originalClick = window.selectEffectButton.onclick;
        window.selectEffectButton.onclick = function() {
            if (originalClick) originalClick.call(this);
            
            // Обновляем отображение после выбора эффекта
            setTimeout(() => {
                if (selectedEffectName && currentEffectDisplay) {
                    currentEffectDisplay.textContent = selectedEffectName.value;
                    
                    if (animationEffectId.value) {
                        effectTypeBadge.textContent = "Выбран";
                        effectTypeBadge.classList.remove('bg-light', 'bg-danger');
                        effectTypeBadge.classList.add('bg-success', 'text-white');
                    } else {
                        effectTypeBadge.textContent = "Не выбран";
                        effectTypeBadge.classList.remove('bg-success', 'text-white');
                        effectTypeBadge.classList.add('bg-light', 'text-primary');
                    }
                }
                
                updateSummary();
            }, 100);
        };
    }
});
</script>

<style>
/* Стили для оптимизации на мобильных устройствах */
@media (max-width: 767.98px) {
    /* Уменьшаем размер кнопок быстрого выбора эффекта */
    .quick-effect-btn {
        padding: 0.35rem 0.5rem;
        font-size: 0.8rem;
    }
    
    /* Улучшаем отображение на мобильных экранах */
    .card.border.p-2 {
        padding: 0.75rem !important;
    }
    
    /* Более четкое отображение текущего эффекта */
    #current_effect_display {
        font-weight: 500;
    }
}
</style>
