<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainContainer = document.getElementById('mainContainer');
    const coverSection = document.getElementById('coverSection');
    const certificateSection = document.getElementById('certificateSection');
    const swipeIndicator = document.getElementById('swipeIndicator');
    const iframe = document.getElementById('certificate-frame');
    const animationEffectContainer = document.getElementById('animationEffectContainer');
    let animationTriggered = false; // Флаг для отслеживания запуска анимации
    
    // Получаем URL логотипа
    const logoUrl = '<?php echo e($certificate->company_logo === null ? "none" : ($certificate->company_logo ? asset("storage/" . $certificate->company_logo) : ($certificate->user->company_logo ? asset("storage/" . $certificate->user->company_logo) : asset("images/default-logo.png")))); ?>';
    console.log("Логотип для публичного сертификата:", logoUrl);
    
    // Функция для перехода к сертификату
    function showCertificate() {
        mainContainer.classList.add('scrolled');
        
        // Запускаем анимационный эффект автоматически при скролле к сертификату
        if (!animationTriggered) {
            animationTriggered = true;
            
            // Гарантируем, что effectData будет определена перед использованием
            if (typeof effectData === 'undefined') {
                // Если effectData не определена, инициализируем загрузку эффекта
                loadAnimationEffect().then(() => {
                    if (effectData) {
                        setTimeout(() => {
                            launchAnimationEffect();
                        }, 500); // Небольшая задержка для плавности
                    }
                });
            } else {
                // Если effectData уже загружена, сразу запускаем эффект
                setTimeout(() => {
                    launchAnimationEffect();
                }, 500);
            }
        }
    }
    
    // Функция для возврата к обложке
    function showCover() {
        mainContainer.classList.remove('scrolled');
    }
    
    // Обработчики для тач-скрина (свайп)
    let touchStartY = 0;
    let touchEndY = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartY = e.changedTouches[0].screenY;
    }, false);
    
    document.addEventListener('touchend', function(e) {
        touchEndY = e.changedTouches[0].screenY;
        handleSwipe();
    }, false);
    
    function handleSwipe() {
        const swipeDistance = touchStartY - touchEndY;
        
        // Определяем направление свайпа (вверх или вниз)
        if (swipeDistance > 50) { // Свайп вверх
            if (!mainContainer.classList.contains('scrolled')) {
                showCertificate();
            }
        } else if (swipeDistance < -50) { // Свайп вниз
            if (mainContainer.classList.contains('scrolled') && window.scrollY === 0) {
                showCover();
            }
        }
    }
    
    // Обработка колеса мыши
    let isScrolling = false;
    window.addEventListener('wheel', function(e) {
        if (isScrolling) return;
        isScrolling = true;
        
        setTimeout(() => {
            isScrolling = false;
        }, 1000); // Предотвращаем множественные события прокрутки
        
        if (e.deltaY > 0) { // Прокрутка вниз
            if (!mainContainer.classList.contains('scrolled')) {
                showCertificate();
            }
        } else if (e.deltaY < 0) { // Прокрутка вверх
            if (mainContainer.classList.contains('scrolled') && window.scrollY === 0) {
                showCover();
            }
        }
    });
    
    // Клик по индикатору свайпа также переключает вид
    swipeIndicator.addEventListener('click', function() {
        showCertificate();
    });
    
    // Функция обновления логотипа в iframe
    function updateLogoInIframe() {
        try {
            // Отправляем сообщение с URL логотипа в iframe
            iframe.contentWindow.postMessage({
                type: 'update_logo',
                logo_url: logoUrl
            }, '*');
            console.log("Логотип отправлен в iframe");
        } catch (error) {
            console.error("Ошибка отправки логотипа:", error);
        }
    }
    
    // Дождемся загрузки iframe
    iframe.addEventListener('load', function() {
        console.log("Iframe загружен, отправляем логотип...");
        
        // Первая попытка после небольшой задержки
        setTimeout(updateLogoInIframe, 500);
        
        // Дополнительная попытка через более длительное время для надежности
        setTimeout(updateLogoInIframe, 1500);
    });
    
    // Обработчик для получения ответа от iframe
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'logo_updated') {
            if (event.data.success) {
                console.log("Логотип успешно обновлен в iframe, обновлено элементов:", event.data.count);
            } else {
                console.warn("Не удалось обновить логотип:", event.data.error);
            }
        }
        
        // Реагируем на сообщение о загрузке сертификата
        if (event.data && event.data.type === 'certificate_loaded') {
            console.log("Получено уведомление о загрузке сертификата:", event.data.template);
        }
    });
    
    // Для iframe, которые могли быть загружены до установки обработчиков
    if (iframe.complete) {
        console.log("Iframe уже загружен, отправляем логотип немедленно...");
        updateLogoInIframe();
    }
    
    // Обработка нажатия на кнопку QR-кода для мобильных устройств
    const qrToggle = document.getElementById('adminQrToggle');
    const qrCode = document.getElementById('adminQrCode');
    const qrFullscreen = document.getElementById('qrFullscreenOverlay');
    const qrCloseBtn = document.getElementById('qrCloseButton');
    
    // Функция для открытия QR-кода с анимацией
    function openQRModal() {
        qrFullscreen.classList.add('active');
        // Предотвращаем скролл под модальным окном
        document.body.style.overflow = 'hidden';
    }
    
    // Функция для закрытия QR-кода с анимацией
    function closeQRModal() {
        qrFullscreen.classList.remove('active');
        // Возвращаем скролл после закрытия
        document.body.style.overflow = '';
    }
    
    // Обработчик для открытия QR-кода на весь экран при нажатии на toggle
    if (qrToggle) {
        qrToggle.addEventListener('click', openQRModal);
    }
    
    // Обработчик для открытия QR-кода на весь экран при нажатии на обычный QR
    if (qrCode) {
        qrCode.addEventListener('click', openQRModal);
    }
    
    // Обработчик для закрытия QR-кода на весь экран
    if (qrCloseBtn) {
        qrCloseBtn.addEventListener('click', closeQRModal);
    }
    
    // Закрытие по клику на overlay
    qrFullscreen.addEventListener('click', function(e) {
        if (e.target === qrFullscreen) {
            closeQRModal();
        }
    });
    
    // Закрытие по нажатии клавиши ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && qrFullscreen.classList.contains('active')) {
            closeQRModal();
        }
    });
});

// Добавляем функции для модального окна печати
function showPrintOptions() {
    document.getElementById('printOptionsOverlay').classList.add('active');
}

function hidePrintOptions() {
    document.getElementById('printOptionsOverlay').classList.remove('active');
}

// Закрытие по клику вне контента
document.getElementById('printOptionsOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        hidePrintOptions();
    }
});

// Закрытие по нажатия Esc
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('printOptionsOverlay').classList.contains('active')) {
        hidePrintOptions();
    }
});

// Добавляем функцию для обновления оставшихся дней и склонения слова
function updateDaysRemaining() {
    // Получаем даты из сертификата
    const validUntilDate = new Date('<?php echo e($certificate->valid_until); ?>');
    const currentDate = new Date();
    
    // Вычисляем разницу в днях
    const timeDiff = validUntilDate - currentDate;
    const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
    
    // Получаем элементы для обновления
    const daysRemainingElement = document.getElementById('daysRemaining');
    const daysTextElement = document.getElementById('daysText');
    
    // Обновляем число дней
    daysRemainingElement.textContent = daysDiff > 0 ? daysDiff : 0;
    
    // Функция для правильного склонения слова "день"
    function getDaysDeclension(days) {
        if (days % 10 === 1 && days % 100 !== 11) {
            return 'день';
        } else if ([2, 3, 4].includes(days % 10) && ![12, 13, 14].includes(days % 100)) {
            return 'дня';
        } else {
            return 'дней';
        }
    }
    
    // Обновляем текст с правильным склонением
    if (daysDiff <= 0) {
        daysRemainingElement.textContent = '0';
        daysTextElement.textContent = 'дней';
        daysRemainingElement.classList.add('expired');
    } else {
        daysTextElement.textContent = getDaysDeclension(daysDiff);
        
        // Добавляем соответствующие классы для стилизации
        if (daysDiff <= 3) {
            daysRemainingElement.classList.add('critical');
        } else if (daysDiff <= 7) {
            daysRemainingElement.classList.add('warning');
        } else {
            daysRemainingElement.classList.add('normal');
        }
    }
}

// Запускаем функцию при загрузке страницы
updateDaysRemaining();

// Обработчик для анимационного эффекта
const effectContainer = document.getElementById('animationEffectContainer');
let effectData = null;

// Функция для загрузки эффекта
async function loadAnimationEffect() {
    try {
        <?php if(isset($certificate->animation_effect_id) && $certificate->animation_effect_id): ?>
        console.log('Загружаем эффект ID: <?php echo e($certificate->animation_effect_id); ?>');
        const response = await fetch('<?php echo e(route("animation-effects.get")); ?>');
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const effects = await response.json();
        console.log('Загружены эффекты:', effects);
        effectData = effects.find(effect => effect.id === <?php echo e($certificate->animation_effect_id); ?>);
        
        if (effectData) {
            console.log('Загружен анимационный эффект:', effectData);
            return true;
        }
        <?php else: ?>
        console.log('Сертификат не имеет анимационного эффекта');
        <?php endif; ?>
    } catch (error) {
        console.error('Ошибка при загрузке анимационного эффекта:', error);
    }
    return false;
}

// Функция для запуска анимации
function launchAnimationEffect() {
    if (!effectData) {
        console.log('Нет данных для запуска эффекта');
        return;
    }
    
    console.log('Запуск анимационного эффекта:', effectData.name);
    
    // Очищаем контейнер
    if (effectContainer) {
        effectContainer.innerHTML = '';
    }
    
    // Создаем контейнер для анимации, если его нет
    let animContainer = document.querySelector('.animation-container');
    if (!animContainer) {
        animContainer = document.createElement('div');
        animContainer.className = 'animation-container';
        document.body.appendChild(animContainer);
    }
    
    // Очищаем предыдущую анимацию
    animContainer.innerHTML = '';
    
    // Получаем параметры эффекта
    const particles = Array.isArray(effectData.particles) ? effectData.particles : ['✨'];
    const type = effectData.type || 'emoji';
    const direction = effectData.direction || 'random';
    const speed = effectData.speed || 'normal';
    const quantity = Math.min(effectData.quantity || 50, 100);
    
    console.log(`Создаем ${quantity} частиц типа ${type}`);
    
    // Создаем частицы
    for (let i = 0; i < quantity; i++) {
        const particle = document.createElement('span');
        particle.className = `animation-particle particle-${type}`;
        
        // Выбираем случайную частицу
        const randomParticle = particles[Math.floor(Math.random() * particles.length)];
        particle.textContent = randomParticle;
        
        // Случайное позиционирование в зависимости от эффекта
        let left, top;
        
        if (type === 'snow' || type === 'leaves') {
            left = Math.random() * 100;
            top = -10 - Math.random() * 10; // Начинаем за пределами экрана сверху
        } else if (type === 'fireworks') {
            left = 40 + Math.random() * 20; // Примерно посередине
            top = 70 + Math.random() * 20; // Снизу экрана
        } else {
            left = Math.random() * 100;
            top = Math.random() * 100;
        }
        
        particle.style.left = `${left}%`;
        particle.style.top = `${top}%`;
        
        // Случайный размер
        const size = Math.floor(Math.random() * 24) + 16;
        particle.style.fontSize = `${size}px`;
        
        // Случайная задержка анимации
        const delay = Math.random() * 3;
        particle.style.animationDelay = `${delay}s`;
        
        // Скорость анимации
        let duration;
        switch (speed) {
            case 'slow': duration = 5 + Math.random() * 3; break;
            case 'fast': duration = 2 + Math.random() * 1; break;
            case 'normal':
            default: duration = 3 + Math.random() * 2; break;
        }
        particle.style.animationDuration = `${duration}s`;
        
        // Направление движения
        if (direction === 'center') {
            particle.classList.add('direction-center');
        } else if (direction === 'top') {
            particle.classList.add('direction-top');
        } else if (direction === 'bottom') {
            particle.classList.add('direction-bottom');
        } else if (direction === 'random') {
            const randomDirection = ['random-1', 'random-2', 'random-3'][Math.floor(Math.random() * 3)];
            particle.classList.add(`direction-${randomDirection}`);
        }
        
        // Добавляем частицу в контейнер
        animContainer.appendChild(particle);
    }
    
    // Плавно показываем анимацию
    setTimeout(() => {
        console.log('Показываем анимацию');
        animContainer.classList.add('visible');
    }, 50);
    
    // Очищаем контейнер через некоторое время
    setTimeout(() => {
        // Плавно скрываем контейнер
        animContainer.classList.remove('visible');
        
        // Удаляем частицы после завершения анимации
        setTimeout(() => {
            animContainer.innerHTML = '';
        }, 800); // Время соответствует длительности transition в CSS
    }, 7000);
}

// Загружаем данные эффекта при инициализации
loadAnimationEffect();

// Добавляем кнопку для ручного запуска эффекта (если нужно)
// Проверяем наличие кнопки запуска эффекта
const launchEffectButton = document.getElementById('launchEffectButton');

if (launchEffectButton) {
    launchEffectButton.addEventListener('click', function() {
        console.log('Кнопка запуска эффекта нажата');
        launchAnimationEffect();
        
        // Анимация кнопки при нажатии
        this.classList.add('animate__animated', 'animate__rubberBand');
        setTimeout(() => {
            this.classList.remove('animate__animated', 'animate__rubberBand');
        }, 1000);
    });
} else {
    // Если кнопки нет, создаем её
    const effectBtn = document.createElement('button');
    effectBtn.id = 'launchEffectButton';
    effectBtn.className = 'effect-button animate__animated';
    effectBtn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i>';
    effectBtn.title = 'Запустить эффект';
    
    effectBtn.addEventListener('click', function() {
        console.log('Кнопка запуска эффекта нажата');
        launchAnimationEffect();
        
        // Анимация кнопки при нажатии
        this.classList.add('animate__animated', 'animate__rubberBand');
        setTimeout(() => {
            this.classList.remove('animate__animated', 'animate__rubberBand');
        }, 1000);
    });
    
    // Добавляем кнопку на страницу
    document.body.appendChild(effectBtn);
}
</script>
<?php /**PATH C:\OSPanel\domains\sert\resources\views/certificates/partials/scripts.blade.php ENDPATH**/ ?>