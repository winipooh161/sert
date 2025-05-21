<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Dark Certificate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #a5b4fc;
            --secondary: #10b981;
            --dark: #111827;
            --light: #f8fafc;
            --accent: #f43f5e;
            --gray: #9ca3af;
            --surface: #1e293b;
            
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.1);
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.12);
            --shadow-md: 0 6px 12px rgba(0, 0, 0, 0.15), 0 0 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 15px 25px rgba(0, 0, 0, 0.18), 0 0 8px rgba(0, 0, 0, 0.1);
            
            --border-radius-sm: 0.5rem;
            --border-radius: 1rem;
            --border-radius-lg: 1.5rem;
            --border-radius-xl: 2rem;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--light);
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
            min-height: auto;
            height: auto;
            overflow: visible;
        }
        
        @media (min-width: 768px) {
            .certificate-container {
                grid-template-columns: 3fr 2fr;
                min-height: 100vh;
                align-items: center;
            }
        }

        /* Мобильная версия */
        @media (max-width: 767px) {
            body {
                min-height: auto;
                height: auto;
                overflow-y: auto;
                padding-bottom: 2rem;
            }
            
            .certificate-container {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
                padding: 1.5rem;
                height: auto;
            }
        }
        
        /* Первый блок - основная карточка */
        .certificate-main {
            position: relative;
            transform-style: preserve-3d;
            animation: fadeInSlide 1.2s ease-out forwards;
        }
        
        .certificate-card {
            background: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(15px);
            border-radius: var(--border-radius-lg);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(99, 102, 241, 0.2);
            transform-style: preserve-3d;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }
        
        .certificate-card:hover {
            transform: translateY(-15px);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        /* Световые эффекты */
        .light-effect {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, var(--primary-light) 0%, transparent 70%);
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
        }
        
        .light-top-right {
            top: -150px;
            right: -150px;
            width: 400px;
            height: 400px;
            animation: pulseLight 15s infinite alternate;
        }
        
        .light-bottom-left {
            bottom: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--secondary) 0%, transparent 70%);
            animation: pulseLight 12s infinite alternate-reverse;
        }
        
        @keyframes pulseLight {
            0% { transform: scale(1); opacity: 0.15; }
            50% { transform: scale(1.2); opacity: 0.2; }
            100% { transform: scale(1); opacity: 0.15; }
        }
        
        /* Декоративные линии */
        .decoration-line {
            position: absolute;
            background: linear-gradient(90deg, transparent, var(--primary-light), transparent);
            height: 1px;
            width: 50%;
            opacity: 0.3;
            z-index: 1;
        }
        
        .line-top {
            top: 60px;
            left: 25%;
            width: 50%;
            animation: lineMove 8s infinite alternate;
        }
        
        .line-bottom {
            bottom: 60px;
            right: 25%;
            width: 50%;
            animation: lineMove 8s infinite alternate-reverse;
        }
        
        @keyframes lineMove {
            0% { transform: translateX(-30px); width: 50%; opacity: 0.2; }
            100% { transform: translateX(30px); width: 60%; opacity: 0.4; }
        }
        
        /* Заголовок и лого */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
            margin-bottom: 2rem;
        }
        
        .company-branding {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .certificate-label {
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 2px;
            color: var(--gray);
            margin-bottom: 0.25rem;
        }
        
        .company-name {
            font-family: 'Raleway', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            background: linear-gradient(90deg, var(--primary-light), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
        }
        
        .company-logo {
            max-width: 110px;
            max-height: 80px;
            object-fit: contain;
            filter: drop-shadow(0 2px 8px rgba(99, 102, 241, 0.3));
            transition: all 0.4s ease;
        }
        
        .company-logo:hover {
            transform: scale(1.1);
            filter: drop-shadow(0 4px 12px rgba(99, 102, 241, 0.5));
        }
        
        /* Информация о получателе */
        .recipient-section {
            text-align: center;
            margin: 2.5rem 0;
            position: relative;
            z-index: 2;
        }
        
        .pre-recipient-text {
            color: var(--gray);
            font-size: 0.9rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
        }
        
        .recipient-name {
            font-family: 'Raleway', sans-serif;
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--light);
            margin-bottom: 1.5rem;
            position: relative;
            text-shadow: 0 2px 10px rgba(99, 102, 241, 0.3);
        }
        
        .recipient-name::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            border-radius: 2px;
        }
        
        /* Сумма сертификата */
        .amount-section {
            position: relative;
            z-index: 2;
            text-align: center;
            margin: 2rem 0;
        }
        
        .certificate-amount {
            font-family: 'Raleway', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #e0e7ff 0%, #6366f1 50%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
            position: relative;
            padding: 0.5rem 0;
        }
        
        .amount-backdrop {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            animation: pulse 3s infinite alternate;
        }
        
        /* Сообщение */
        .message-container {
            position: relative;
            z-index: 2;
            margin: 2rem 0;
            padding: 1.5rem;
            background: rgba(15, 23, 42, 0.6);
            border-radius: var(--border-radius);
            border: 1px solid rgba(99, 102, 241, 0.15);
            box-shadow: var(--shadow-md);
        }
        
        .message-text {
            font-style: italic;
            color: #e2e8f0;
            font-weight: 300;
            font-size: 1rem;
            text-align: center;
        }
        
        /* Срок действия */
        .validity-section {
            display: flex;
            justify-content: space-around;
            gap: 1rem;
            margin: 2.5rem 0;
            position: relative;
            z-index: 2;
        }
        
        .validity-item {
            flex: 1;
            padding: 1.2rem;
            background: rgba(15, 23, 42, 0.7);
            border-radius: var(--border-radius);
            border: 1px solid rgba(99, 102, 241, 0.15);
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }
        
        .validity-item:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: var(--shadow-md);
        }
        
        .validity-label {
            font-size: 0.8rem;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }
        
        .validity-date {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--light);
        }
        
        /* Второй блок - детали */
        .certificate-details {
            position: relative;
            animation: fadeInScale 1.2s ease-out forwards 0.3s;
            opacity: 0;
            transform: scale(0.95);
        }
        
        .details-panel {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(99, 102, 241, 0.15);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        /* Номер сертификата и QR */
        .cert-info-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }
        
        .cert-number-container h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--light);
        }
        
        .certificate-number {
            display: inline-block;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
            padding: 0.5rem 0.8rem;
            border-radius: var(--border-radius-sm);
            border: 1px solid rgba(99, 102, 241, 0.2);
        }
        
        .certificate-number::before {
            content: "№";
            margin-right: 0.25rem;
            opacity: 0.7;
        }
        
        .qr-code {
            width: 110px;
            height: 110px;
            background: white;
            border-radius: var(--border-radius-sm);
            padding: 8px;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }
        
        .qr-code::after {
            content: '';
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: linear-gradient(
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transform: rotate(45deg);
            animation: qrShinEffect 4s infinite;
        }
        
        @keyframes qrShinEffect {
            0% { left: -100%; top: -100%; }
            100% { left: 100%; top: 100%; }
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        /* Счетчик */
        .countdown-container {
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }
        
        .countdown-title {
            font-size: 0.9rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.7rem;
        }
        
        .countdown-display {
            background: linear-gradient(135deg, var(--dark) 0%, var(--surface) 100%);
            color: var(--light);
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            padding: 1rem;
            border-radius: var(--border-radius-sm);
            text-align: center;
            border: 1px solid rgba(99, 102, 241, 0.2);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        /* Эффект свечения для счетчика */
        .countdown-display::before {
            content: '';
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: linear-gradient(
                transparent,
                rgba(99, 102, 241, 0.1),
                transparent
            );
            transform: rotate(45deg);
            animation: shine 4s infinite;
        }
        
        @keyframes shine {
            0% { top: -100%; left: -100%; }
            100% { top: 100%; left: 100%; }
        }
        
        .progress-container {
            margin-top: 0.8rem;
            height: 8px;
            background: rgba(15, 23, 42, 0.5);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            width: 75%;
            border-radius: 4px;
            position: relative;
            transition: width 1s ease;
        }
        
        /* Преимущества */
        .benefits-section {
            flex: 1;
            margin-top: auto;
            position: relative;
            z-index: 2;
        }
        
        .benefits-title {
            font-size: 1.1rem;
            color: var(--light);
            margin-bottom: 1.2rem;
            position: relative;
            padding-bottom: 0.8rem;
        }
        
        .benefits-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 2px;
            background: var(--primary);
        }
        
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .benefit-item {
            background: rgba(15, 23, 42, 0.7);
            border-radius: var(--border-radius-sm);
            padding: 1rem;
            border: 1px solid rgba(99, 102, 241, 0.15);
            transition: all 0.3s ease;
        }
        
        .benefit-item:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.3);
        }
        
        .benefit-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 50%;
            margin-bottom: 0.8rem;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }
        
        .benefit-icon span {
            color: white;
            font-size: 1.2rem;
        }
        
        .benefit-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
            color: var(--light);
        }
        
        .benefit-desc {
            font-size: 0.8rem;
            color: var(--gray);
        }
        
        /* Анимации */
        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes fadeInSlide {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(0.95);
                opacity: 0.2;
            }
            100% {
                transform: scale(1.05);
                opacity: 0.3;
            }
        }
        
        /* Адаптивность */
        @media (max-width: 1024px) {
            .certificate-container {
                padding: 1.5rem;
            }
            
            .certificate-card {
                padding: 2rem;
            }
            
            .recipient-name {
                font-size: 2rem;
            }
            
            .certificate-amount {
                font-size: 3rem;
            }
        }
        
        @media (max-width: 767px) {
            .certificate-card, 
            .details-panel {
                padding: 1.5rem;
            }
            
            .header-section {
                flex-direction: column-reverse;
                gap: 1rem;
                text-align: center;
                margin-bottom: 1.5rem;
            }
            
            .cert-info-section {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .qr-code {
                margin: 0 auto;
            }
            
            .company-name {
                font-size: 1.6rem;
            }
            
            .company-logo {
                margin: 0 auto;
            }
            
            .recipient-name {
                font-size: 1.8rem;
            }
            
            .certificate-amount {
                font-size: 2.5rem;
            }
            
            .message-container {
                padding: 1rem;
            }
            
            .validity-section {
                flex-direction: column;
                gap: 1rem;
            }
            
            /* Исправление анимации для мобильных */
            .certificate-main,
            .certificate-details,
            .certificate-card,
            .details-panel {
                animation: none !important;
                transform: none !important;
                opacity: 1 !important;
            }
        }
        
        @media (max-width: 480px) {
            .certificate-container {
                padding: 1rem;
            }
            
            .certificate-card, 
            .details-panel {
                padding: 1.2rem;
            }
            
            .recipient-name {
                font-size: 1.6rem;
            }
            
            .certificate-amount {
                font-size: 2rem;
            }
            
            .benefits-grid {
                grid-template-columns: 1fr;
            }
            
            .countdown-display {
                font-size: 1.3rem;
                padding: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <!-- Первый блок - основной сертификат -->
        <div class="certificate-main">
            <div class="certificate-card">
                <!-- Фоновые элементы -->
                <div class="light-effect light-top-right"></div>
                <div class="light-effect light-bottom-left"></div>
                <div class="decoration-line line-top"></div>
                <div class="decoration-line line-bottom"></div>
                
                <!-- Заголовок и лого -->
                <div class="header-section">
                    <div class="company-branding">
                        <div class="certificate-label">Подарочный сертификат</div>
                        <h1 class="company-name">{company_name}</h1>
                    </div>
                    <img src="{company_logo}" alt="Логотип компании" class="company-logo">
                </div>
                
                <!-- Получатель -->
                <div class="recipient-section">
                    <div class="pre-recipient-text">Эксклюзивно для</div>
                    <h2 class="recipient-name">{recipient_name}</h2>
                </div>
                
                <!-- Сумма -->
                <div class="amount-section">
                    <div class="amount-backdrop"></div>
                    <div class="certificate-amount">{amount}</div>
                </div>
                
                <!-- Сообщение -->
                <div class="message-container">
                    <p class="message-text">{message}</p>
                </div>
                
                <!-- Срок действия -->
                <div class="validity-section">
                    <div class="validity-item">
                        <div class="validity-label">Дата активации</div>
                        <div class="validity-date">{valid_from}</div>
                    </div>
                    <div class="validity-item">
                        <div class="validity-label">Действителен до</div>
                        <div class="validity-date">{valid_until}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Второй блок - детали и преимущества -->
        <div class="certificate-details">
            <div class="details-panel">
                <!-- Номер сертификата и QR-код -->
                <div class="cert-info-section">
                    <div class="cert-number-container">
                        <h3>Информация о сертификате</h3>
                        <div class="certificate-number">{certificate_number}</div>
                    </div>
                    <div class="qr-code" id="qr-code">
                        <!-- QR-код будет добавлен с помощью JS -->
                    </div>
                </div>
                
                <!-- Счетчик времени -->
                <div class="countdown-container">
                    <div class="countdown-title">Оставшийся срок действия</div>
                    <div class="countdown-display" id="countdown-timer">
                        Загрузка...
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar" id="countdown-progress"></div>
                    </div>
                </div>
                
                <!-- Преимущества -->
                <div class="benefits-section">
                    <h3 class="benefits-title">Преимущества</h3>
                    <div class="benefits-grid">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <span>✓</span>
                            </div>
                            <h4 class="benefit-title">Гарантия</h4>
                            <p class="benefit-desc">100% подлинность и защита</p>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <span>⏱</span>
                            </div>
                            <h4 class="benefit-title">Удобство</h4>
                            <p class="benefit-desc">Используйте в любой момент</p>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <span>🎁</span>
                            </div>
                            <h4 class="benefit-title">Персонализация</h4>
                            <p class="benefit-desc">Индивидуальный подход</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Генерация QR-кода
            generateQR('{certificate_number}');
            
            // Запуск таймера обратного отсчета
            startCountdown();
            
            // Добавление 3D эффекта
            add3DEffect();
        });
        
        // Функция для генерации QR-кода
        function generateQR(data) {
            const qrElement = document.getElementById('qr-code');
            
            // В реальном проекте используется библиотека для генерации QR
            // Для демонстрации делаем просто имитацию
            qrElement.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 90 90">
                    <rect x="10" y="10" width="70" height="70" fill="#ffffff" />
                    <rect x="20" y="20" width="50" height="50" fill="#6366f1" />
                    <rect x="30" y="30" width="30" height="30" fill="#ffffff" />
                    <rect x="40" y="40" width="10" height="10" fill="#6366f1" />
                    <text x="45" y="85" text-anchor="middle" font-size="8" fill="#333">${data}</text>
                </svg>
            `;
        }
        
        // Функция для запуска таймера обратного отсчета
        function startCountdown() {
            // Получаем даты из сертификата
            const validFromElement = document.querySelector('.validity-item:first-child .validity-date');
            const validUntilElement = document.querySelector('.validity-item:last-child .validity-date');
            
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
                
                // Оставшийся процент времени
                const progressPercent = Math.max(0, Math.min(100, (timeDiff / totalDuration) * 100));
                document.getElementById('countdown-progress').style.width = `${progressPercent}%`;
                
                // Если время истекло
                if (timeDiff <= 0) {
                    document.getElementById('countdown-timer').innerText = "Срок действия истёк";
                    document.getElementById('countdown-timer').style.background = 
                        "linear-gradient(135deg, #ef4444 0%, #b91c1c 100%)";
                    return;
                }
                
                // Расчет оставшегося времени
                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
                
                // Правильное склонение слова "день"
                let daysLabel = "дней";
                if (days % 10 === 1 && days % 100 !== 11) {
                    daysLabel = "день";
                } else if ([2, 3, 4].includes(days % 10) && ![12, 13, 14].includes(days % 100)) {
                    daysLabel = "дня";
                }
                
                // Форматирование вывода
                let countdownText = "";
                
                if (days > 0) {
                    countdownText = `${days} ${daysLabel} ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                } else {
                    countdownText = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
                
                document.getElementById('countdown-timer').innerText = countdownText;
                
                // Изменение цвета при малом оставшемся сроке
                if (days < 3) {
                    document.getElementById('countdown-timer').style.background = 
                        "linear-gradient(135deg, #f97316 0%, #ea580c 100%)";
                    document.getElementById('countdown-timer').style.animation = 
                        "pulse 1.5s infinite alternate";
                }
            }
            
            // Запускаем обновление таймера
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
        
        // Добавление 3D-эффекта движения
        function add3DEffect() {
            if (window.innerWidth <= 768) return; // Отключаем на мобильных
            
            const card = document.querySelector('.certificate-card');
            const details = document.querySelector('.details-panel');
            const maxRotation = 8; // Максимальный угол поворота
            
            // Эффект для основной карточки
            document.addEventListener('mousemove', function(e) {
                const cardRect = card.getBoundingClientRect();
                const detailsRect = details.getBoundingClientRect();
                
                // Для основной карточки
                const cardCenterX = cardRect.left + cardRect.width / 2;
                const cardCenterY = cardRect.top + cardRect.height / 2;
                
                const cardPercentX = (e.clientX - cardCenterX) / (cardRect.width / 2);
                const cardPercentY = (e.clientY - cardCenterY) / (cardRect.height / 2);
                
                // Ограничиваем поворот
                if (Math.abs(cardPercentX) < 2 && Math.abs(cardPercentY) < 2) {
                    card.style.transform = `perspective(1000px) rotateY(${cardPercentX * maxRotation}deg) rotateX(${-cardPercentY * maxRotation}deg) scale3d(1, 1, 1)`;
                }
                
                // Для панели деталей
                const detailsCenterX = detailsRect.left + detailsRect.width / 2;
                const detailsCenterY = detailsRect.top + detailsRect.height / 2;
                
                const detailsPercentX = (e.clientX - detailsCenterX) / (detailsRect.width / 2);
                const detailsPercentY = (e.clientY - detailsCenterY) / (detailsRect.height / 2);
                
                // Ограничиваем поворот
                if (Math.abs(detailsPercentX) < 2 && Math.abs(detailsPercentY) < 2) {
                    details.style.transform = `perspective(1000px) rotateY(${detailsPercentX * maxRotation / 2}deg) rotateX(${-detailsPercentY * maxRotation / 2}deg) scale3d(1, 1, 1)`;
                }
            });
            
            // Возвращаем к исходному положению при уходе мыши
            document.addEventListener('mouseleave', function() {
                card.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg) scale3d(1, 1, 1)';
                details.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg) scale3d(1, 1, 1)';
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
        
        // Исправления для мобильных устройств
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.certificate-card, .details-panel').forEach(panel => {
                panel.style.transform = 'none';
                panel.style.animation = 'none';
                panel.style.transition = 'all 0.3s ease';
                panel.style.opacity = '1';
            });
            
            document.querySelector('.certificate-details').style.opacity = '1';
        }
    </script>
</body>
</html>
