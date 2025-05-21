<div class="quiz-step" id="quizStep3">
    <div class="text-center mb-4">
        <div class="quiz-step-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-3">
            <i class="fa-solid fa-calendar-alt text-primary fs-3"></i>
        </div>
        <h3 class="quiz-step-title">Срок действия</h3>
        <p class="text-muted">Выберите, до какой даты будет действителен сертификат</p>
    </div>
    
    <input type="hidden" name="valid_from" id="valid_from" value="<?php echo e(now()->format('Y-m-d')); ?>">
    
    <!-- Выбор длительности через предустановленные варианты -->
    <div class="mb-4">
        <label class="form-label fw-medium mb-2">Выберите длительность:</label>
        <div class="row gap-2 mx-0">
            <button type="button" class="col btn btn-outline-secondary duration-btn" data-duration="30">1 месяц</button>
            <button type="button" class="col btn btn-outline-secondary duration-btn" data-duration="90">3 месяца</button>
            <button type="button" class="col btn btn-outline-secondary duration-btn" data-duration="180">6 месяцев</button>
        </div>
        <div class="row gap-2 mx-0 mt-2">
            <button type="button" class="col btn btn-outline-secondary duration-btn" data-duration="365">1 год</button>
            <button type="button" class="col btn btn-outline-secondary duration-btn" data-duration="custom">Другой срок</button>
        </div>
    </div>
    
    <!-- Ручной выбор даты (скрыт по умолчанию) -->
    <div id="custom_date_selector" class="mb-3 mt-3 d-none">
        <label for="valid_until" class="form-label fw-medium mb-2">Действует до: *</label>
        <input type="date" class="form-control form-control-lg <?php $__errorArgs = ['valid_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            id="valid_until" name="valid_until" 
            value="<?php echo e(old('valid_until', now()->addMonths(3)->format('Y-m-d'))); ?>" 
            min="<?php echo e(now()->format('Y-m-d')); ?>" required>
        <?php $__errorArgs = ['valid_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    
    <!-- Показ выбранной даты в красивом формате -->
    <div class="card bg-light mt-3">
        <div class="card-body text-center">
            <h6 class="card-subtitle mb-2 text-muted">Сертификат будет действовать до:</h6>
            <h5 class="card-title" id="displayValidUntil"><?php echo e(now()->addMonths(3)->format('d.m.Y')); ?></h5>
            <p class="card-text small" id="daysRemaining">Осталось <?php echo e(now()->addMonths(3)->diffInDays(now())); ?> дней</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработчики для кнопок длительности
    const durationButtons = document.querySelectorAll('.duration-btn');
    const validUntilInput = document.getElementById('valid_until');
    const customDateSelector = document.getElementById('custom_date_selector');
    const displayValidUntil = document.getElementById('displayValidUntil');
    const daysRemaining = document.getElementById('daysRemaining');
    
    // Функция для обновления отображения даты
    function updateDisplayDate(date) {
        // Форматируем дату для отображения
        const displayDate = new Date(date);
        const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
        displayValidUntil.textContent = displayDate.toLocaleDateString('ru-RU', options);
        
        // Вычисляем разницу в днях
        const today = new Date();
        const diffTime = Math.abs(displayDate - today);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        daysRemaining.textContent = `Осталось ${diffDays} дней`;
    }
    
    // Инициализация с текущим значением
    updateDisplayDate(validUntilInput.value);
    
    durationButtons.forEach(button => {
        button.addEventListener('click', function() {
            const duration = this.getAttribute('data-duration');
            
            // Сбрасываем активное состояние у всех кнопок
            durationButtons.forEach(btn => btn.classList.remove('active', 'btn-primary', 'text-white'));
            
            // Активируем текущую кнопку
            this.classList.add('active', 'btn-primary', 'text-white');
            
            if (duration === 'custom') {
                // Показываем селектор даты для ручного выбора
                customDateSelector.classList.remove('d-none');
                
                // Плавная прокрутка к селектору даты
                setTimeout(() => {
                    customDateSelector.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
                
                return;
            } else {
                // Скрываем селектор даты
                customDateSelector.classList.add('d-none');
                
                // Устанавливаем срок действия сертификата
                const days = parseInt(duration);
                const today = new Date();
                const validUntil = new Date(today);
                validUntil.setDate(today.getDate() + days);
                
                // Форматируем дату для input
                const year = validUntil.getFullYear();
                const month = String(validUntil.getMonth() + 1).padStart(2, '0');
                const day = String(validUntil.getDate()).padStart(2, '0');
                
                validUntilInput.value = `${year}-${month}-${day}`;
                
                // Обновляем отображаемую дату
                updateDisplayDate(validUntil);
            }
            
            // Эффект вибрации для обратной связи
            if (window.safeVibrate) {
                window.safeVibrate(30);
            }
        });
    });
    
    // При ручном изменении даты обновляем отображение
    validUntilInput.addEventListener('change', function() {
        updateDisplayDate(this.value);
    });
    
    // Выбираем 3 месяца по умолчанию
    setTimeout(() => {
        const defaultButton = document.querySelector('.duration-btn[data-duration="90"]');
        if (defaultButton) defaultButton.click();
    }, 100);
});
</script>
<?php /**PATH C:\OSPanel\domains\sert\resources\views/entrepreneur/certificates/partials/quiz_steps/step3_validity.blade.php ENDPATH**/ ?>