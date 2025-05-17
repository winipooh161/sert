<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Базовые мета-теги -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Подарочный сертификат для {{ $certificate->recipient_name }} на сумму {{ number_format($certificate->amount, 0, '.', ' ') }}₽</title>
    <meta name="description" content="Подарочный сертификат на сумму {{ number_format($certificate->amount, 0, '.', ' ') }}₽. Действителен до {{ $certificate->valid_until->format('d.m.Y') }}.">
    <meta name="keywords" content="подарочный сертификат, подарок, сертификат, {{ $certificate->user->company ?? config('app.name') }}">
    
    <!-- Канонический URL -->
    <link rel="canonical" href="{{ route('certificates.public', $certificate->uuid) }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('certificates.public', $certificate->uuid) }}">
    <meta property="og:title" content="Подарочный сертификат для {{ $certificate->recipient_name }}">
    <meta property="og:description" content="Подарочный сертификат на сумму {{ number_format($certificate->amount, 0, '.', ' ') }}₽. Действителен до {{ $certificate->valid_until->format('d.m.Y') }}.">
    <meta property="og:image" content="{{ asset('storage/' . $certificate->cover_image) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ $certificate->user->company ?? config('app.name') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ route('certificates.public', $certificate->uuid) }}">
    <meta name="twitter:title" content="Подарочный сертификат для {{ $certificate->recipient_name }}">
    <meta name="twitter:description" content="Подарочный сертификат на сумму {{ number_format($certificate->amount, 0, '.', ' ') }}₽. Действителен до {{ $certificate->valid_until->format('d.m.Y') }}.">
    <meta name="twitter:image" content="{{ asset('storage/' . $certificate->cover_image) }}">
    
    <!-- Дополнительные мета-теги -->
    <meta name="author" content="{{ $certificate->user->company ?? config('app.name') }}">
    <meta name="robots" content="index, follow">
    
    <!-- Структурированные данные JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "Подарочный сертификат",
        "description": "Подарочный сертификат на сумму {{ number_format($certificate->amount, 0, '.', ' ') }}₽",
        "offers": {
            "@type": "Offer",
            "price": "{{ $certificate->amount }}",
            "priceCurrency": "RUB",
            "validFrom": "{{ $certificate->valid_from->toIso8601String() }}",
            "validThrough": "{{ $certificate->valid_until->toIso8601String() }}",
            "availability": "https://schema.org/InStock"
        },
        "provider": {
            "@type": "Organization",
            "name": "{{ $certificate->user->company ?? config('app.name') }}"
        }
    }
    </script>

    <!-- Иконки -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        
        /* Основной контейнер */
        .main-container {
            width: 100%;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }
        
        /* Секция с обложкой */
        .cover-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            background-color: #000;
        }
        
        /* Контейнер для обложки */
        .cover-container {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Обложка сертификата */
        .cover-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
            transform: scale(1);
        }
        
        /* Анимация появления обложки */
        @keyframes fadeInCover {
            from { opacity: 0; transform: scale(1.1); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .cover-image {
            animation: fadeInCover 1.5s ease forwards;
        }
        
        /* Затемнение поверх обложки */
        .cover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, 
                rgba(0,0,0,0.2) 0%, 
                rgba(0,0,0,0.3) 70%, 
                rgba(0,0,0,0.7) 100%);
            z-index: 2;
        }
        
        /* Название и информация о сертификате */
      .cover-info {
    position: absolute;
    bottom: 180px;
    left: 0;
    width: 100%;
     padding: 0 0px;
    color: white;
    z-index: 3;
    text-align: center;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}
        
        .cover-info h1 {
            font-size: 32px;
            margin: 0 0 10px;
            font-weight: 700;
        }
        
        .cover-info p {
            font-size: 18px;
            margin: 5px 0;
            opacity: 0.9;
        }
        
        /* Индикатор свайпа/скролла */
        .swipe-indicator {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: white;
            z-index: 3;
            animation: bounce 2s infinite;
            width: 100px;
        }
        
        .swipe-indicator i {
            font-size: 24px;
            margin-bottom: 5px;
            display: block;
        }
        
        .swipe-indicator span {
            font-size: 14px;
            opacity: 0.8;
        }
        
        /* Анимация индикатора */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0) translateX(-50%); }
            40% { transform: translateY(-15px) translateX(-50%); }
            60% { transform: translateY(-7px) translateX(-50%); }
        }
        
        /* Секция с сертификатом */
        .certificate-section {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 5;
            transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            background-color: #fff;
        }
        
        .certificate-container {
            height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        iframe#certificate-frame {
            height: 100vh;
            width: 100%;
            border: none;
            display: block;
        }
        
        /* Для состояния после скролла/свайпа */
        .scrolled .cover-section {
            transform: translateY(-100%);
        }
        
        .scrolled .certificate-section {
            transform: translateY(-100%);
        }
        
        /* Стили для QR-кода администратора */
        .admin-qr-code {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .admin-qr-code img {
            max-width: 150px;
            height: auto;
        }
        
        .admin-qr-code p {
            margin: 5px 0;
            font-size: 12px;
            color: #555;
        }
        
        /* Адаптивные стили */
        @media (max-width: 768px) {
            .admin-qr-code {
                bottom: 10px;
                right: 10px;
                padding: 8px;
            }
            
            .admin-qr-code img {
                max-width: 100px;
            }
            
            .cover-info h1 {
                font-size: 24px;
            }
            
            .cover-info p {
                font-size: 14px;
            }
            
            .swipe-indicator i {
                font-size: 20px;
            }
            
            .swipe-indicator span {
                font-size: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .admin-qr-code {
                bottom: 5px;
                right: 5px;
                padding: 5px;
            }
            
            .admin-qr-code img {
                max-width: 80px;
            }
            
            .admin-qr-code p {
                font-size: 10px;
            }
            
            .cover-info {
                bottom: 60px;
                padding: 0 0px;
            }
            
            .cover-info h1 {
                font-size: 20px;
            }
            
            .cover-info p {
                font-size: 13px;
            }
        }
        
        /* Добавляем кнопку для скрытия/показа QR кода на мобильных устройствах */
        .admin-qr-toggle {
            display: none;
            position: fixed;
            bottom: 10px;
            right: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            z-index: 1001;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            cursor: pointer;
        }
        
        @media (max-width: 480px) {
            .admin-qr-toggle {
                display: block;
            }
            
            .admin-qr-code {
                transform: translateY(200%);
                opacity: 0;
                visibility: hidden;
            }
            
            .admin-qr-code.visible {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }
        }
        
        /* Стили для модального окна QR-кода на весь экран */
        .qr-fullscreen-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .qr-fullscreen-overlay.active {
            display: flex;
            opacity: 1;
        }
        
        .qr-fullscreen-content {
            text-align: center;
            max-width: 90%;
        }
        
        .qr-fullscreen-content img {
            max-width: 80%;
            max-height: 70vh;
            margin-bottom: 20px;
        }
        
        .qr-fullscreen-content p {
            color: white;
            font-size: 16px;
            margin: 15px 0;
        }
        
        .qr-close-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: transparent;
            border: 2px solid white;
            color: white;
            font-size: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .qr-close-button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Стили для кнопки печати */
        .print-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
        }

        .print-button .btn {
            color: #000;
            background: #fff;
            border-radius: 20px;
            border: none;
            width: 50px;
            height: 50px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .print-button .btn:hover {
            transform: scale(1.1);
        }

        /* Стили для модального окна выбора формата печати */
        .print-options-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .print-options-overlay.active {
            display: flex;
            opacity: 1;
        }

        .print-options-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 90%;
            width: 400px;
            text-align: center;
            position: relative;
        }

        .print-close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: transparent;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }

        .print-format-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        /* Адаптивность для мобильных устройств */
        @media (max-width: 768px) {
            .print-button {
                bottom: 20px;
                left: 10px;
            }
            
            .print-button .btn {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
            
            /* На мобильных показываем текст о свайпе */
            .swipe-indicator span.desktop-text {
                display: none;
            }
        }
        
        /* Для десктопов показываем текст о скролле */
        @media (min-width: 769px) {
            .swipe-indicator span.mobile-text {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="main-container" id="mainContainer">
        <!-- Секция с обложкой -->
        <div class="cover-section" id="coverSection">
            <div class="cover-container">
                <img class="cover-image" src="{{ asset('storage/' . $certificate->cover_image) }}" alt="Обложка сертификата">
                <div class="cover-overlay"></div>
                
                <div class="cover-info">
                    <h1>Подарочный сертификат</h1>
                    <p>{{ $certificate->recipient_name }}</p>
                    <p>на сумму {{ number_format($certificate->amount, 0, '.', ' ') }} ₽</p>
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
                <iframe id="certificate-frame" src="{{ route('template.preview', [
                    'template' => $certificate->template,
                    'recipient_name' => $certificate->recipient_name,
                    'amount' => number_format($certificate->amount, 0, '.', ' ') . ' ₽',
                    'valid_from' => $certificate->valid_from->format('d.m.Y'),
                    'valid_until' => $certificate->valid_until->format('d.m.Y'),
                    'message' => $certificate->message ?? '',
                    'certificate_number' => $certificate->certificate_number,
                    'company_name' => $certificate->user->company ?? config('app.name')
                    // Логотип передается через postMessage для избежания ошибки URI Too Large
                ]) }}" frameborder="0"></iframe>
                
                <!-- QR-код для администратора (предпринимателя) -->
                <div class="admin-qr-code" id="adminQrCode">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('entrepreneur.certificates.admin-verify', $certificate)) }}" alt="Admin QR Code" id="adminQrImage">
                    <p>QR-код для проверки</p>
                </div>
                
                <!-- Кнопка для показа/скрытия QR кода на мобильных устройствах -->
                <div class="admin-qr-toggle" id="adminQrToggle">QR</div>
                
                <!-- Модальное окно для QR-кода на весь экран -->
                <div class="qr-fullscreen-overlay" id="qrFullscreenOverlay">
                    <button class="qr-close-button" id="qrCloseButton">&times;</button>
                    <div class="qr-fullscreen-content">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode(route('entrepreneur.certificates.admin-verify', $certificate)) }}" alt="QR Code Fullscreen" id="qrFullscreenImage">
                        <p>Сертификат №{{ $certificate->certificate_number }}</p>
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
                            <a href="{{ route('certificates.print', [$certificate, 'format' => 'a4', 'orientation' => 'landscape']) }}" class="btn btn-primary" target="_blank">
                                <i class="fa-solid fa-file-pdf me-2"></i>A4 (Альбомная)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainContainer = document.getElementById('mainContainer');
        const coverSection = document.getElementById('coverSection');
        const certificateSection = document.getElementById('certificateSection');
        const swipeIndicator = document.getElementById('swipeIndicator');
        const iframe = document.getElementById('certificate-frame');
        
        // Получаем URL логотипа
        const logoUrl = '{{ $certificate->company_logo === null ? "none" : ($certificate->company_logo ? asset("storage/" . $certificate->company_logo) : ($certificate->user->company_logo ? asset("storage/" . $certificate->user->company_logo) : asset("images/default-logo.png"))) }}';
        console.log("Логотип для публичного сертификата:", logoUrl);
        
        // Функция для перехода к сертификату
        function showCertificate() {
            mainContainer.classList.add('scrolled');
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
        
        // Закрытие по нажатию клавиши ESC
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

    // Закрытие по нажатию Esc
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('printOptionsOverlay').classList.contains('active')) {
            hidePrintOptions();
        }
    });
    </script>
</body>
</html>


