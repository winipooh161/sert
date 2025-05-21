<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Gift Certificate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6d28d9;
            --primary-dark: #5b21b6;
            --primary-light: #ddd6fe;
            --secondary: #10b981;
            --dark: #1e1b4b;
            --light: #f5f3ff;
            --accent: #f97316;
            
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 6px 10px rgba(0, 0, 0, 0.08), 0 0 6px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 15px 25px rgba(0, 0, 0, 0.12), 0 0 8px rgba(0, 0, 0, 0.06);
            
            --border-radius-sm: 0.5rem;
            --border-radius: 1rem;
            --border-radius-lg: 2rem;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
            color: #333;
            min-height: 100vh;
            line-height: 1.5;
            overflow-x: hidden;
            padding: 0;
            margin: 0;
        }
        
        .certificate-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            perspective: 1000px;
            /* Изменяем для обеспечения прокрутки на мобильных устройствах */
            min-height: auto;
            height: auto;
            overflow: visible;
        }
        
        @media (min-width: 768px) {
            .certificate-container {
                grid-template-columns: 1fr 1fr;
                min-height: 100vh;
                align-items: center;
            }
        }

        /* Исправления для мобильной версии */
        @media (max-width: 767px) {
            body {
                min-height: auto;
                height: auto;
                overflow-y: auto;
                padding-bottom: 3rem;
            }
            
            .certificate-container {
                display: flex;
                flex-direction: column;
                gap: 2rem;
                height: auto;
            }
            
            .certificate-card, 
            .details-panel {
                height: auto;
                min-height: auto;
            }
            
            .certificate-main, 
            .certificate-details {
                width: 100%;
                height: auto;
                min-height: auto;
            }
        }
        
        /* Первый блок - главная информация сертификата */
        .certificate-main {
            position: relative;
            transform-style: preserve-3d;
            transform: translateZ(0);
            animation: fadeInUp 1s ease-out forwards;
        }
        
        .certificate-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transform-style: preserve-3d;
            transition: transform 0.5s ease;
            z-index: 1;
        }
        
        .certificate-card:hover {
            transform: translateY(-10px);
        }
        
        .pulse-circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            opacity: 0.1;
            filter: blur(60px);
            z-index: -1;
            animation: pulse 15s infinite alternate;
        }
        
        .pulse-circle-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -100px;
        }
        
        .pulse-circle-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -50px;
            animation-delay: 2s;
        }
        
        .header-ribbon {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        }
        
        .logo-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .company-name {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: -0.5px;
            position: relative;
            transform: translateZ(10px);
        }
        
        .company-logo {
            max-width: 100px;
            max-height: 70px;
            object-fit: contain;
            transform: translateZ(20px);
        }
        
        .recipient-info {
            margin: 1.5rem 0;
            text-align: center;
        }
        
        .pre-recipient-text {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .recipient-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
            position: relative;
            transform: translateZ(30px);
        }
        
        .certificate-value {
            position: relative;
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin: 1rem 0;
            transform: translateZ(40px);
        }
        
        .message-box {
            background: rgba(245, 243, 255, 0.7);
            padding: 1rem;
            border-radius: var(--border-radius-sm);
            margin: 1.5rem 0;
            font-style: italic;
            position: relative;
            transform: translateZ(20px);
            box-shadow: var(--shadow-sm);
        }
        
        .validity-dates {
            display: flex;
            justify-content: space-between;
            margin: 1.5rem 0;
            position: relative;
            transform: translateZ(15px);
        }
        
        .validity-item {
            text-align: center;
            flex: 1;
            padding: 1rem;
            background: rgba(245, 243, 255, 0.5);
            border-radius: var(--border-radius-sm);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .validity-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow);
        }
        
        .validity-item:first-child {
            margin-right: 1rem;
        }
        
        .validity-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .validity-date {
            font-weight: 600;
            color: var(--dark);
        }
        
        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            transform-style: preserve-3d;
            pointer-events: none;
        }
        
        .floating-icon {
            position: absolute;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
        }
        
        .icon-1 {
            top: 15%;
            left: 10%;
            background: var(--primary);
            animation: floatAnimation 10s ease-in-out infinite;
        }
        
        .icon-2 {
            top: 60%;
            right: 15%;
            background: var(--secondary);
            animation: floatAnimation 13s ease-in-out infinite 1s;
        }
        
        .icon-3 {
            bottom: 15%;
            left: 20%;
            background: var(--accent);
            animation: floatAnimation 8s ease-in-out infinite 0.5s;
        }
        
        /* Второй блок - детали сертификата */
        .certificate-details {
            position: relative;
            animation: fadeInRight 1s ease-out forwards 0.3s;
            opacity: 0;
        }
        
        .details-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .qr-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .certificate-number {
            background: rgba(109, 40, 217, 0.1);
            font-family: 'Space Grotesk', sans-serif;
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
        }
        
        .certificate-number::before {
            content: "№";
            margin-right: 0.25rem;
            opacity: 0.7;
        }
        
        .qr-code {
            width: 100px;
            height: 100px;
            background-color: #fff;
            border-radius: 8px;
            padding: 5px;
            box-shadow: var(--shadow);
            transform-style: preserve-3d;
            animation: rotateY 20s linear infinite;
            position: relative;
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .countdown-section {
            margin-bottom: 2rem;
        }
        
        .countdown-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .countdown-display {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: var(--border-radius-sm);
            padding: 1rem;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            justify-content: center;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        
        .countdown-display::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transform: rotate(45deg);
            animation: shine 4s infinite;
        }
        
        .progress-bar {
            margin-top: 0.5rem;
            background: rgba(0, 0, 0, 0.1);
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary) 0%, var(--primary) 100%);
            width: 75%;
            transition: width 1s ease;
        }
        
        .company-info {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .company-info-title {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .contact-data {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 1.5rem;
        }
        
        .security-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #666;
            margin-top: 1rem;
        }
        
        .security-icon {
            width: 16px;
            height: 16px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.6rem;
            font-weight: bold;
        }
        
        /* Анимации */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInRight {
            0% {
                opacity: 0;
                transform: translateX(40px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.2;
            }
        }
        
        @keyframes floatAnimation {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(10px, -10px) rotate(5deg);
            }
            50% {
                transform: translate(0, 5px) rotate(0deg);
            }
            75% {
                transform: translate(-10px, -5px) rotate(-5deg);
            }
        }
        
        @keyframes rotateY {
            0% {
                transform: rotateY(0deg);
            }
            100% {
                transform: rotateY(360deg);
            }
        }
        
        @keyframes shine {
            0% {
                left: -100%;
                opacity: 0;
            }
            50% {
                left: 100%;
                opacity: 0.3;
            }
            100% {
                left: 100%;
                opacity: 0;
            }
        }
        
        /* 3D эффекты и анимации для элементов */
        .certificate-3d-element {
            transform-style: preserve-3d;
            transition: transform 0.3s ease;
        }
        
        .certificate-container:hover .recipient-name {
            animation: pulseText 2s infinite alternate;
        }
        
        @keyframes pulseText {
            0% {
                text-shadow: 0 0 0 rgba(0, 0, 0, 0);
            }
            100% {
                text-shadow: 0 0 10px rgba(109, 40, 217, 0.3);
            }
        }
        
        /* Responsive */
        @media (max-width: 767px) {
            .certificate-container {
                padding: 1rem;
                gap: 1rem;
                height: auto;
                overflow-y: visible;
            }
            
            .certificate-card,
            .details-panel {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }
            
            .recipient-name {
                font-size: 1.5rem;
            }
            
            .certificate-value {
                font-size: 2rem;
            }
            
            .validity-dates {
                flex-direction: column;
            }
            
            .validity-item:first-child {
                margin-right: 0;
                margin-bottom: 1rem;
            }
            
            .qr-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .qr-code {
                margin-top: 1rem;
            }
            
            /* Исправляем проблему с анимацией, чтобы не блокировала доступ к содержимому */
            .certificate-details {
                opacity: 1;
                animation: none;
            }
            
            /* Убедимся, что анимации не мешают прокрутке */
            .certificate-main,
            .certificate-details,
            .certificate-card,
            .details-panel {
                transform: none !important;
                animation: none !important;
            }
            
            /* Исправляем высоту и прокрутку */
            html, body {
                height: auto;
                overflow-y: auto !important;
            }
        }
        
        /* Дополнительные исправления для очень маленьких экранов */
        @media (max-width: 480px) {
            .certificate-container {
                padding: 0.75rem;
                gap: 1rem;
            }
            
            .certificate-card,
            .details-panel {
                padding: 1rem;
                border-radius: 10px;
            }
            
            .company-logo {
                max-width: 80px;
                max-height: 50px;
            }
            
            .certificate-value {
                font-size: 1.75rem;
                margin: 0.5rem 0;
            }
            
            .message-box {
                padding: 0.75rem;
                margin: 1rem 0;
            }
            
            .countdown-display {
                font-size: 1.2rem;
                padding: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <!-- Первый блок - основная информация о сертификате -->
        <div class="certificate-main">
            <div class="certificate-card">
                <div class="header-ribbon"></div>
                <div class="pulse-circle pulse-circle-1"></div>
                <div class="pulse-circle pulse-circle-2"></div>
                
                <div class="logo-area">
                    <h1 class="company-name">{company_name}</h1>
                    <img src="{company_logo}" alt="Логотип компании" class="company-logo">
                </div>
                
                <div class="recipient-info">
                    <p class="pre-recipient-text">Подарочный сертификат для</p>
                    <h2 class="recipient-name certificate-3d-element">{recipient_name}</h2>
                </div>
                
                <div class="certificate-value certificate-3d-element">{amount}</div>
                
                <div class="message-box">
                    {message}
                </div>
                
                <div class="validity-dates">
                    <div class="validity-item">
                        <div class="validity-label">Начало действия</div>
                        <div class="validity-date">{valid_from}</div>
                    </div>
                    <div class="validity-item">
                        <div class="validity-label">Действителен до</div>
                        <div class="validity-date">{valid_until}</div>
                    </div>
                </div>
                
                <div class="floating-icons">
                    <div class="floating-icon icon-1"></div>
                    <div class="floating-icon icon-2"></div>
                    <div class="floating-icon icon-3"></div>
                </div>
            </div>
        </div>
        
        <!-- Второй блок - детали сертификата -->
        <div class="certificate-details">
            <div class="details-panel">
                <div class="qr-section">
                    <div>
                        <h3>Ваш цифровой сертификат</h3>
                        <div class="certificate-number">{certificate_number}</div>
                    </div>
                    
                    <div class="qr-code" id="qr-code">
                        <!-- QR-код будет сгенерирован JavaScript -->
                    </div>
                </div>
                
                <div class="countdown-section">
                    <h4 class="countdown-title">Оставшееся время действия</h4>
                    <div class="countdown-display" id="countdown-timer">
                        Загрузка...
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="countdown-progress"></div>
                    </div>
                </div>
                
                <div class="company-info">
                    <h4 class="company-info-title">Информация о компании</h4>
                    <div class="contact-data">
                        <p><strong>{company_name}</strong> с удовольствием предоставляет вам этот сертификат.</p>
                        <p>Для использования предъявите этот сертификат или его QR-код.</p>
                    </div>
                    <div class="security-badge">
                        <div class="security-icon">✓</div>
                        <span>Сертификат защищен цифровой подписью</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Создаем простой QR-код (на реальном сервере можно заменить на настоящую генерацию)
            generateQR('{certificate_number}');
            
            // Запускаем обратный отсчет
            startCountdown();
            
            // Добавляем 3D-эффект при движении мыши
            addTiltEffect();
        });
        
        // Функция генерации QR-кода (простая визуализация)
        function generateQR(data) {
            const qrElement = document.getElementById('qr-code');
            
            // В реальном проекте здесь можно использовать библиотеку для генерации QR кода,
            // а пока просто создаем фейковое изображение
            qrElement.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" viewBox="0 0 90 90">
                    <rect x="10" y="10" width="70" height="70" fill="#ffffff" />
                    <rect x="20" y="20" width="50" height="50" fill="#6d28d9" />
                    <rect x="30" y="30" width="30" height="30" fill="#ffffff" />
                    <rect x="40" y="40" width="10" height="10" fill="#6d28d9" />
                    <text x="45" y="85" text-anchor="middle" font-size="8" fill="#333">${data}</text>
                </svg>
            `;
        }
        
        // Функция запуска обратного отсчета
        function startCountdown() {
            // Получаем даты из сертификата
            const validFromElement = document.querySelector('.validity-date:first-child');
            const validUntilElement = document.querySelector('.validity-date:last-child');
            
            if (!validFromElement || !validUntilElement) return;
            
            const validFromText = validFromElement.innerText;
            const validUntilText = validUntilElement.innerText;
            
            // Парсим даты (формат дд.мм.гггг)
            const parseDate = function(dateText) {
                const parts = dateText.split('.');
                if (parts.length !== 3) return new Date();
                
                const day = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10) - 1; // Месяцы в JS начинаются с 0
                const year = parseInt(parts[2], 10);
                
                return new Date(year, month, day, 23, 59, 59);
            };
            
            const validFromDate = parseDate(validFromText);
            const validUntilDate = parseDate(validUntilText);
            
            // Функция обновления таймера
            function updateCountdown() {
                const currentDate = new Date();
                const timeDiff = validUntilDate - currentDate;
                
                // Общая длительность сертификата
                const totalDuration = validUntilDate - validFromDate;
                
                // Прогресс (оставшееся время в процентах)
                const progressPercent = Math.max(0, Math.min(100, (timeDiff / totalDuration) * 100));
                document.getElementById('countdown-progress').style.width = `${progressPercent}%`;
                
                // Если время истекло
                if (timeDiff <= 0) {
                    document.getElementById('countdown-timer').innerText = "Срок истек";
                    document.getElementById('countdown-timer').style.background = "#dc2626";
                    return;
                }
                
                // Расчет оставшихся дней, часов, минут и секунд
                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
                
                // Корректное склонение для русского языка
                let daysLabel = "дней";
                if (days % 10 === 1 && days % 100 !== 11) {
                    daysLabel = "день";
                } else if ([2, 3, 4].includes(days % 10) && ![12, 13, 14].includes(days % 100)) {
                    daysLabel = "дня";
                }
                
                // Вывод результата
                let countdownText = "";
                
                if (days > 0) {
                    countdownText = `${days} ${daysLabel} ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                } else {
                    countdownText = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
                
                document.getElementById('countdown-timer').innerText = countdownText;
                
                // Изменение цвета для предупреждения
                if (days < 3) {
                    document.getElementById('countdown-timer').style.background = 
                        `linear-gradient(135deg, #f97316 0%, #dc2626 100%)`;
                }
            }
            
            // Первый запуск
            updateCountdown();
            
            // Обновление каждую секунду
            setInterval(updateCountdown, 1000);
        }
        
        // Функция добавления 3D-эффекта при движении мыши
        function addTiltEffect() {
            const cards = document.querySelectorAll('.certificate-card, .details-panel');
            const maxTilt = 5; // Максимальный угол наклона в градусах
            
            document.addEventListener('mousemove', function(e) {
                if (window.innerWidth <= 768) return; // Отключаем на мобильных
                
                const mouseX = e.clientX;
                const mouseY = e.clientY;
                
                cards.forEach(card => {
                    const rect = card.getBoundingClientRect();
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;
                    
                    const percentX = (mouseX - centerX) / (rect.width / 2);
                    const percentY = (mouseY - centerY) / (rect.height / 2);
                    
                    // Ограничиваем эффект только когда мышь находится относительно близко к карточке
                    const distance = Math.sqrt(percentX * percentX + percentY * percentY);
                    
                    if (distance < 2) {
                        const tiltX = -percentY * maxTilt;
                        const tiltY = percentX * maxTilt;
                        
                        card.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
                    } else {
                        card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
                    }
                });
            });
            
            // Возвращаем в начальное положение при выходе мыши
            document.addEventListener('mouseleave', function() {
                cards.forEach(card => {
                    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
                });
            });
        }
        
        // Обработчик для динамической загрузки логотипа через postMessage
        window.addEventListener('message', function(event) {
            if (event.data && event.data.type === 'update_logo') {
                const logoUrl = event.data.logo_url;
                const logoImages = document.querySelectorAll('.company-logo');
                
                logoImages.forEach(img => {
                    if (logoUrl === 'none') {
                        img.style.display = 'none';
                    } else {
                        img.src = logoUrl;
                        img.style.display = 'block';
                    }
                });
                
                // Отправляем подтверждение обратно
                if (event.source) {
                    event.source.postMessage({
                        type: 'logo_updated',
                        success: true
                    }, '*');
                }
            }
        });
        
        // Исправление для мобильных устройств - отключаем 3D-эффекты, которые могут мешать прокрутке
        if (window.innerWidth <= 767) {
            const cards = document.querySelectorAll('.certificate-card, .details-panel');
            cards.forEach(card => {
                card.style.transform = 'none';
                card.style.animation = 'none';
                
                // Убираем listener события мыши для мобильных устройств
                card.style.transition = 'none';
            });
            
            // Отключаем 3D эффект для мобильных
            document.removeEventListener('mousemove', addTiltEffect);
        }
        
        // Проверяем высоту контента и задаем минимальную высоту body, если нужно
        function adjustBodyHeight() {
            const container = document.querySelector('.certificate-container');
            if (window.innerWidth <= 767 && container) {
                const containerHeight = container.offsetHeight;
                document.body.style.minHeight = (containerHeight + 50) + 'px';
            } else {
                document.body.style.minHeight = '100vh';
            }
        }
        
        // Вызываем функцию при загрузке и изменении размера окна
        adjustBodyHeight();
        window.addEventListener('resize', adjustBodyHeight);
    </script>
</body>
</html>
