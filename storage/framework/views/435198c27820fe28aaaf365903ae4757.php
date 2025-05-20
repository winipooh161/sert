<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Базовые мета-теги -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Подарочный сертификат для <?php echo e($certificate->recipient_name); ?> на сумму <?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?>₽</title>
    <meta name="description" content="Подарочный сертификат на сумму <?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?>₽. Действителен до <?php echo e($certificate->valid_until->format('d.m.Y')); ?>.">
    <meta name="keywords" content="подарочный сертификат, подарок, сертификат, <?php echo e($certificate->user->company ?? config('app.name')); ?>">
    
    <!-- Канонический URL -->
    <link rel="canonical" href="<?php echo e(route('certificates.public', $certificate->uuid)); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(route('certificates.public', $certificate->uuid)); ?>">
    <meta property="og:title" content="Подарочный сертификат для <?php echo e($certificate->recipient_name); ?>">
    <meta property="og:description" content="Подарочный сертификат на сумму <?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?>₽. Действителен до <?php echo e($certificate->valid_until->format('d.m.Y')); ?>.">
    <meta property="og:image" content="<?php echo e(asset('storage/' . $certificate->cover_image)); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="<?php echo e($certificate->user->company ?? config('app.name')); ?>">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo e(route('certificates.public', $certificate->uuid)); ?>">
    <meta name="twitter:title" content="Подарочный сертификат для <?php echo e($certificate->recipient_name); ?>">
    <meta name="twitter:description" content="Подарочный сертификат на сумму <?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?>₽. Действителен до <?php echo e($certificate->valid_until->format('d.m.Y')); ?>.">
    <meta name="twitter:image" content="<?php echo e(asset('storage/' . $certificate->cover_image)); ?>">
    
    <!-- Дополнительные мета-теги -->
    <meta name="author" content="<?php echo e($certificate->user->company ?? config('app.name')); ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Стили -->
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">


    <!-- Структурированные данные JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "Подарочный сертификат",
        "description": "Подарочный сертификат на сумму <?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?>₽",
        "offers": {
            "@type": "Offer",
            "price": "<?php echo e($certificate->amount); ?>",
            "priceCurrency": "RUB",
            "validFrom": "<?php echo e($certificate->valid_from->toIso8601String()); ?>",
            "validThrough": "<?php echo e($certificate->valid_until->toIso8601String()); ?>",
            "availability": "https://schema.org/InStock"
        },
        "provider": {
            "@type": "Organization",
            "name": "<?php echo e($certificate->user->company ?? config('app.name')); ?>"
        }
    }
    </script>

    <!-- Иконки -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

    <!-- Стили для анимационных эффектов -->
    <style>
     
    </style>
</head>
<body>
    <div class="main-container" id="mainContainer">
        <!-- Секция с обложкой -->
        <div class="cover-section" id="coverSection">
            <div class="cover-container">
                <img class="cover-image" src="<?php echo e(asset('storage/' . $certificate->cover_image)); ?>" alt="Обложка сертификата">
                <div class="cover-overlay"></div>
                
                <div class="cover-info">
                    <h1>Подарочный сертификат</h1>
                    <p><?php echo e($certificate->recipient_name); ?></p>
                    <p>на сумму <?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?> ₽</p><br>
                    <p class="certificate-timer">Дней до окончания сертификата: <br>
                        <span id="daysRemaining" class="days-remaining"><?php echo e($certificate->valid_until->diffInDays(now())); ?></span>
                        <span id="daysText"><?php echo e($certificate->valid_until->diffInDays(now()) == 1 ? 'день' : ($certificate->valid_until->diffInDays(now()) >= 2 && $certificate->valid_until->diffInDays(now()) <= 4 ? 'дня' : 'дней')); ?></span>
                    </p>
                </div>
                
                <div class="swipe-indicator" id="swipeIndicator">
                    <i class="fa-solid fa-chevron-up"></i>
                    <span class="mobile-text">Свайпните вверх</span>
                    <span class="desktop-text">Прокрутите вниз</span>
                </div>
            </div>
        </div>
        
        <!-- Секция с сертификатом -->
        <div class="certificate-section" id="certificateSection">
            <div class="certificate-container">
                <iframe id="certificate-frame" src="<?php echo e(route('template.preview', [
                    'template' => $certificate->template,
                    'recipient_name' => $certificate->recipient_name,
                    'amount' => number_format($certificate->amount, 0, '.', ' ') . ' ₽',
                    'valid_from' => $certificate->valid_from->format('d.m.Y'),
                    'valid_until' => $certificate->valid_until->format('d.m.Y'),
                    'message' => $certificate->message ?? '',
                    'certificate_number' => $certificate->certificate_number,
                    'company_name' => $certificate->user->company ?? config('app.name')
                    // Логотип передается через postMessage для избежания ошибки URI Too Large
                ])); ?>" frameborder="0"></iframe>
                
                <!-- QR-код для администратора (предпринимателя) -->
                <div class="admin-qr-code" id="adminQrCode">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo e(urlencode(route('entrepreneur.certificates.admin-verify', $certificate))); ?>" alt="Admin QR Code" id="adminQrImage">
                    <p>QR-код для проверки</p>
                </div>
                
                <!-- Кнопка для показа/скрытия QR кода на мобильных устройствах -->
                <div class="admin-qr-toggle" id="adminQrToggle">QR</div>
                
                <!-- Модальное окно для QR-кода на весь экран -->
                <div class="qr-fullscreen-overlay" id="qrFullscreenOverlay">
                    <button class="qr-close-button" id="qrCloseButton">&times;</button>
                    <div class="qr-fullscreen-content">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=<?php echo e(urlencode(route('entrepreneur.certificates.admin-verify', $certificate))); ?>" alt="QR Code Fullscreen" id="qrFullscreenImage">
                        <p>Сертификат №<?php echo e($certificate->certificate_number); ?></p>
                        <p>Отсканируйте этот QR-код для проверки сертификата</p>
                    </div>
                </div>

                <!-- Кнопка для печати -->
                <div class="print-button" id="printButton">
                    <button class="btn btn-light rounded-circle shadow" onclick="showPrintOptions()">
                        <i class="fa-solid fa-print"></i>
                    </button>
                </div>

                <!-- Модальное окно выбора опций печати -->
                <div class="print-options-overlay" id="printOptionsOverlay">
                    <div class="print-options-content">
                        <button class="print-close-button" onclick="hidePrintOptions()">&times;</button>
                        <h3>Печать сертификата</h3>
                        <p>Выберите формат для печати:</p>
                        <div class="print-format-buttons">
                            <a href="<?php echo e(route('certificates.print', [$certificate, 'format' => 'a4', 'orientation' => 'landscape'])); ?>" class="btn btn-primary" target="_blank">
                                <i class="fa-solid fa-file-pdf me-2"></i>A4 (Альбомная)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Убираем кнопку для запуска анимационного эффекта -->
    
    <!-- Контейнер для анимационного эффекта -->
    <div class="animation-effect-container" id="animationEffectContainer"></div>

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
            // если он не был запущен ранее
            if (!animationTriggered) {
                animationTriggered = true;
                loadAnimationEffect().then(() => {
                    if (effectData) {
                        setTimeout(() => {
                            launchAnimationEffect();
                        }, 500); // Небольшая задержка для плавности
                    }
                });
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
        
        // Обработчик для открытия QR-кода на весь экран при нажатии на toggle
        if (qrToggle) {
            qrToggle.addEventListener('click', function() {
                qrFullscreen.classList.add('active');
            });
        }
        
        // Обработчик для открытия QR-кода на весь экран при нажатии на обычный QR
        if (qrCode) {
            qrCode.addEventListener('click', function() {
                qrFullscreen.classList.add('active');
            });
        }
        
        // Обработчик для закрытия QR-кода на весь экран
        if (qrCloseBtn) {
            qrCloseBtn.addEventListener('click', function() {
                qrFullscreen.classList.remove('active');
            });
        }
        
        // Закрытие по клику на overlay
        qrFullscreen.addEventListener('click', function(e) {
            if (e.target === qrFullscreen) {
                qrFullscreen.classList.remove('active');
            }
        });
        
        // Закрытие по нажатии клавиши ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && qrFullscreen.classList.contains('active')) {
                qrFullscreen.classList.remove('active');
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
    const launchEffectButton = document.getElementById('launchEffectButton');
    const effectContainer = document.getElementById('animationEffectContainer');
    let effectData = null;
    
    // Функция для загрузки эффекта
    async function loadAnimationEffect() {
        try {
            <?php if(isset($certificate->animation_effect_id) && $certificate->animation_effect_id): ?>
            const response = await fetch('<?php echo e(route("animation-effects.get")); ?>');
            const effects = await response.json();
            effectData = effects.find(effect => effect.id === <?php echo e($certificate->animation_effect_id); ?>);
            
            if (effectData) {
                console.log('Загружен анимационный эффект:', effectData.name);
            }
            <?php endif; ?>
        } catch (error) {
            console.error('Ошибка при загрузке анимационного эффекта:', error);
        }
    }
    
    // Функция для запуска анимации
    function launchAnimationEffect() {
        if (!effectData) return;
        
        // Очищаем контейнер
        effectContainer.innerHTML = '';
        
        // Получаем параметры эффекта
        const particles = Array.isArray(effectData.particles) ? effectData.particles : ['✨'];
        const type = effectData.type || 'emoji';
        const direction = effectData.direction || 'random';
        const speed = effectData.speed || 'normal';
        const quantity = Math.min(effectData.quantity || 50, 100);
        
        // Создаем частицы
        for (let i = 0; i < quantity; i++) {
            const particle = document.createElement('span');
            particle.className = `animation-particle animation-${type}`;
            
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
            effectContainer.appendChild(particle);
        }
        
        // Показываем анимацию
        effectContainer.style.display = 'block';
        
        // Очищаем контейнер через некоторое время
        setTimeout(() => {
            // Постепенное удаление частиц для более плавного завершения
            const particles = effectContainer.querySelectorAll('.animation-particle');
            particles.forEach((particle, index) => {
                setTimeout(() => {
                    particle.style.transition = 'opacity 0.5s ease';
                    particle.style.opacity = '0';
                    
                    setTimeout(() => {
                        if (particle.parentNode) {
                            particle.parentNode.removeChild(particle);
                        }
                    }, 500);
                }, index * 50);
            });
        }, 7000);
    }
    
    // Инициализация
    if (launchEffectButton) {
        loadAnimationEffect();
        
        launchEffectButton.addEventListener('click', function() {
            launchAnimationEffect();
            
            // Анимация кнопки при нажатии
            this.classList.add('animate__animated', 'animate__rubberBand');
            setTimeout(() => {
                this.classList.remove('animate__animated', 'animate__rubberBand');
            }, 1000);
        });
    }
    </script>
</body>
</html>


<?php /**PATH C:\OSPanel\domains\sert\resources\views/certificates/public.blade.php ENDPATH**/ ?>