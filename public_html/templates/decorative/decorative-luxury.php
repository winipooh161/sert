<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Gift Card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            perspective: 1000px;
            background: radial-gradient(ellipse at center, #e0e0e0 0%, #b0b0b0 100%);
            overflow-x: hidden;
        }
        
        .certificate {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transform-style: preserve-3d;
            transform: rotateX(5deg) rotateY(0deg);
            animation: float 6s ease-in-out infinite;
            transition: transform 0.5s ease;
            /* Добавляем анимацию появления */
            opacity: 0;
            transform: perspective(1000px) rotateX(90deg) scale(0.5);
            animation: certificateAppear 1.5s forwards 0.8s, float 6s ease-in-out infinite 2.3s;
        }
        
        /* Анимация появления сертификата */
        @keyframes certificateAppear {
            0% {
                opacity: 0;
                transform: perspective(1000px) rotateX(90deg) scale(0.5);
            }
            70% {
                opacity: 1;
                transform: perspective(1000px) rotateX(-10deg) scale(1.05);
            }
            85% {
                transform: perspective(1000px) rotateX(5deg) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: perspective(1000px) rotateX(5deg) rotateY(0deg) scale(1);
            }
        }
        
        /* Таймер обратного отсчета */
        .countdown-timer {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(212, 175, 55, 0.5);
            border-radius: 8px;
            padding: 8px 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            font-size: 0.85rem;
            font-weight: 600;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            transform: translateZ(30px);
            z-index: 100;
        }
        
        .countdown-timer-label {
            font-size: 0.7rem;
            font-weight: 400;
            color: #666;
            margin-bottom: 3px;
        }
        
        .countdown-timer-value {
            font-family: 'Montserrat', sans-serif;
            color: #d4af37;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            animation: pulse 2s infinite alternate;
        }
        
        .countdown-urgent {
            color: #e74c3c;
            animation: urgentPulse 1s infinite alternate;
        }
        
        @keyframes urgentPulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        
        /* Дополнительная анимация для таймера */
        .countdown-timer::before {
            content: '';
            position: absolute;
            top: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            background-color: #d4af37;
            border-radius: 50%;
            animation: blink 2s infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        
        .certificate-wrapper {
            position: relative;
            padding: 40px;
            z-index: 2;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            border-radius: 12px;
        }
        
        .certificate-border {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 2px solid rgba(212, 175, 55, 0.7);
            border-radius: 10px;
            z-index: 1;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
            animation: pulse 4s infinite alternate;
        }
        
        .luxury-pattern {
            position: absolute;
            z-index: 0;
            opacity: 0.7;
            pointer-events: none;
        }
        
        .pattern-top-right {
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle at center, rgba(212, 175, 55, 0.4) 0%, transparent 70%);
            transform: rotate(-15deg) translateZ(-10px);
            animation: rotatePattern 20s linear infinite;
        }
        
        .pattern-bottom-left {
            bottom: -50px;
            left: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle at center, rgba(212, 175, 55, 0.4) 0%, transparent 70%);
            transform: rotate(15deg) translateZ(-10px);
            animation: rotatePattern 25s linear infinite reverse;
        }
        
        /* Декоративные элементы */
        .decorative-corner {
            position: absolute;
            width: 100px;
            height: 100px;
            z-index: 2;
            opacity: 0.8;
        }
        
        .corner-top-left {
            top: 0;
            left: 0;
            border-top: 3px solid rgba(212, 175, 55, 0.7);
            border-left: 3px solid rgba(212, 175, 55, 0.7);
            border-top-left-radius: 15px;
            animation: shineCorner 3s infinite alternate;
        }
        
        .corner-top-right {
            top: 0;
            right: 0;
            border-top: 3px solid rgba(212, 175, 55, 0.7);
            border-right: 3px solid rgba(212, 175, 55, 0.7);
            border-top-right-radius: 15px;
            animation: shineCorner 3s infinite alternate 0.5s;
        }
        
        .corner-bottom-left {
            bottom: 0;
            left: 0;
            border-bottom: 3px solid rgba(212, 175, 55, 0.7);
            border-left: 3px solid rgba(212, 175, 55, 0.7);
            border-bottom-left-radius: 15px;
            animation: shineCorner 3s infinite alternate 1s;
        }
        
        .corner-bottom-right {
            bottom: 0;
            right: 0;
            border-bottom: 3px solid rgba(212, 175, 55, 0.7);
            border-right: 3px solid rgba(212, 175, 55, 0.7);
            border-bottom-right-radius: 15px;
            animation: shineCorner 3s infinite alternate 1.5s;
        }
        
        .certificate-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 3;
        }
        
        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
            transform: translateZ(10px);
        }
        
        .certificate-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 60%;
            height: 2px;
            background: linear-gradient(to right, rgba(212, 175, 55, 0.9), transparent);
            animation: expandWidth 4s infinite alternate;
        }
        
        .company-logo {
            max-width: 120px;
            max-height: 120px;
            object-fit: contain;
            transform: translateZ(5px);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            transition: all 0.3s ease;
        }
        
        .company-logo:hover {
            transform: translateZ(15px) scale(1.05);
        }
        
        .certificate-content {
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
            margin: 30px 0;
            z-index: 3;
        }
        
        .pre-recipient-text {
            font-weight: 300;
            font-size: 1rem;
            color: #666;
            text-align: center;
        }
        
        .recipient-name {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 600;
            text-align: center;
            margin: 10px 0;
            color: #1a1a1a;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
            transform: translateZ(20px);
            animation: pulseText 2s infinite alternate;
        }
        
        .certificate-amount {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            color: #d4af37;
            text-align: center;
            margin: 20px 0;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            transform: translateZ(30px);
            position: relative;
        }
        
        .certificate-amount::before,
        .certificate-amount::after {
            content: '';
            position: absolute;
            height: 2px;
            width: 30%;
            background: linear-gradient(to right, transparent, rgba(212, 175, 55, 0.7), transparent);
            animation: floatLine 3s infinite alternate;
        }
        
        .certificate-amount::before {
            top: -15px;
            left: 35%;
        }
        
        .certificate-amount::after {
            bottom: -15px;
            left: 35%;
            animation-delay: 1.5s;
        }
        
        .message-text {
            font-style: italic;
            text-align: center;
            color: #555;
            padding: 15px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.4);
            margin: 15px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            transform: translateZ(5px);
        }
        
        .certificate-validity {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 10px 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .validity-item {
            text-align: center;
        }
        
        .validity-label {
            font-size: 0.85rem;
            color: #777;
        }
        
        .date-text {
            font-weight: 600;
            color: #333;
            margin-top: 5px;
        }
        
        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            position: relative;
        }
        
        .certificate-company {
            text-align: left;
        }
        
        .company-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .signature-label {
            font-size: 0.85rem;
            color: #666;
            position: relative;
        }
        
        .signature-label::after {
            content: '';
            display: block;
            width: 120px;
            height: 1px;
            background-color: #666;
            margin-top: 5px;
        }
        
        .certificate-number {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            color: #555;
            background: rgba(212, 175, 55, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            transform: translateZ(5px);
        }
        
        /* 3D-анимации */
        @keyframes float {
            0% {
                transform: rotateX(5deg) rotateY(0deg) translateZ(0px);
                box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            }
            50% {
                transform: rotateX(3deg) rotateY(2deg) translateZ(10px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            }
            100% {
                transform: rotateX(5deg) rotateY(0deg) translateZ(0px);
                box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            }
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 15px rgba(212, 175, 55, 0.3); }
            50% { box-shadow: 0 0 25px rgba(212, 175, 55, 0.5); }
            100% { box-shadow: 0 0 15px rgba(212, 175, 55, 0.3); }
        }
        
        @keyframes rotatePattern {
            0% { transform: rotate(0deg) translateZ(-10px); }
            100% { transform: rotate(360deg) translateZ(-10px); }
        }
        
        @keyframes shineCorner {
            0% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        @keyframes floatLine {
            0% { width: 30%; left: 35%; }
            100% { width: 40%; left: 30%; }
        }
        
        @keyframes expandWidth {
            0% { width: 60%; }
            100% { width: 80%; }
        }
        
        @keyframes pulseText {
            0% { transform: translateZ(20px); }
            100% { transform: translateZ(25px); text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.15); }
        }
        
        /* Медиа-запросы для адаптивности */
        @media (max-width: 768px) {
            .certificate-wrapper {
                padding: 25px;
            }
            
            .certificate-title {
                font-size: 1.8rem;
            }
            
            .recipient-name {
                font-size: 1.6rem;
            }
            
            .certificate-amount {
                font-size: 2.2rem;
            }
            
            .company-logo {
                max-width: 80px;
                max-height: 80px;
            }
            
            .decorative-corner {
                width: 60px;
                height: 60px;
            }
        }
        
        @media (max-width: 480px) {
            .certificate-wrapper {
                padding: 20px;
            }
            
            .certificate-header {
                flex-direction: column;
                gap: 15px;
                align-items: center;
                text-align: center;
            }
            
            .certificate-title {
                font-size: 1.5rem;
            }
            
            .recipient-name {
                font-size: 1.4rem;
            }
            
            .certificate-amount {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="certificate">
        <!-- Декоративные элементы -->
        <div class="luxury-pattern pattern-top-right"></div>
        <div class="luxury-pattern pattern-bottom-left"></div>
        
        <div class="decorative-corner corner-top-left"></div>
        <div class="decorative-corner corner-top-right"></div>
        <div class="decorative-corner corner-bottom-left"></div>
        <div class="decorative-corner corner-bottom-right"></div>
        
        <div class="certificate-wrapper">
            <div class="certificate-border"></div>
            
            <div class="certificate-header">
                <h1 class="certificate-title">{company_name}</h1>
                <img src="{company_logo}" alt="Логотип компании" class="company-logo">
            </div>
            
            <div class="certificate-content">
                <div class="pre-recipient-text">Этот подарочный сертификат выдан для</div>
                <div class="recipient-name">{recipient_name}</div>
                
                <div class="certificate-amount">{amount}</div>
                
                <div class="message-text">{message}</div>
                
                <div class="certificate-validity">
                    <div class="validity-item">
                        <div class="validity-label">Действителен с</div>
                        <div class="date-text">{valid_from}</div>
                    </div>
                    <div class="validity-item">
                        <div class="validity-label">Действителен до</div>
                        <div class="date-text">{valid_until}</div>
                    </div>
                </div>
            </div>
            
            <div class="certificate-footer">
                <div class="certificate-company">
                    <div class="company-name">{company_name}</div>
                    <div class="signature-label">Подпись</div>
                </div>
                
                <div class="certificate-number">№ {certificate_number}</div>
            </div>
        </div>
        
        <div class="countdown-timer">
            <div class="countdown-timer-label">Срок действия сертификата</div>
            <div class="countdown-timer-value" id="countdownValue">Загрузка...</div>
        </div>
    </div>
    
    <script>
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
        
        // Добавление интерактивности 3D-эффектам
        document.addEventListener('DOMContentLoaded', function() {
            const certificate = document.querySelector('.certificate');
            
            // Эффект 3D-тилта при движении мыши
            document.addEventListener('mousemove', function(e) {
                if (window.innerWidth > 768) { // Только для десктопов
                    const x = e.clientX / window.innerWidth;
                    const y = e.clientY / window.innerHeight;
                    
                    const tiltX = (y - 0.5) * 10; // Наклон по оси X
                    const tiltY = (0.5 - x) * 10; // Наклон по оси Y
                    
                    certificate.style.transform = `rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
                }
            });
            
            // Возвращение в исходное положение при уходе мыши
            document.addEventListener('mouseleave', function() {
                certificate.style.transform = 'rotateX(5deg) rotateY(0deg)';
            });
            
            // Запускаем обновление таймера
            updateCountdown();
            
            // Обновляем таймер каждую секунду
            setInterval(updateCountdown, 1000);
        });
        
        // Функция для обновления значения таймера
        function updateCountdown() {
            const countdownElement = document.getElementById('countdownValue');
            if (!countdownElement) return;
            
            try {
                // Получаем даты начала и окончания из переменных сертификата
                const validFromElement = document.querySelector('.validity-item:nth-child(1) .date-text');
                const validUntilElement = document.querySelector('.validity-item:nth-child(2) .date-text');
                
                if (!validFromElement || !validUntilElement) {
                    countdownElement.innerText = 'Даты не найдены';
                    return;
                }
                
                const validFromText = validFromElement.innerText;
                const validUntilText = validUntilElement.innerText;
                
                // Парсим даты в формате дд.мм.гггг
                const parseDate = function(dateText) {
                    const parts = dateText.split('.');
                    if (parts.length !== 3) throw new Error('Неверный формат даты');
                    
                    const day = parseInt(parts[0], 10);
                    const month = parseInt(parts[1], 10) - 1; // Месяцы в JS начинаются с 0
                    const year = parseInt(parts[2], 10);
                    
                    return new Date(year, month, day, 23, 59, 59);
                };
                
                const validFromDate = parseDate(validFromText);
                const validUntilDate = parseDate(validUntilText);
                const currentDate = new Date();
                
                // Разница в миллисекундах до окончания действия
                const differenceMs = validUntilDate - currentDate;
                
                // Общая длительность сертификата
                const totalDuration = validUntilDate - validFromDate;
                
                // Процент оставшегося времени
                const percentageLeft = Math.max(0, Math.min(100, (differenceMs / totalDuration) * 100));
                
                if (differenceMs > 0) {
                    // Расчет оставшихся дней, часов, минут и секунд
                    const days = Math.floor(differenceMs / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((differenceMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((differenceMs % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((differenceMs % (1000 * 60)) / 1000);
                    
                    // Форматирование вывода
                    let timeLeft = '';
                    
                    if (days > 0) {
                        timeLeft += `${days} ${getDayWord(days)} `;
                    }
                    
                    timeLeft += `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                    countdownElement.innerText = timeLeft;
                    
                    // Визуальное оформление в зависимости от оставшегося времени
                    if (days < 3) {
                        countdownElement.classList.add('countdown-urgent');
                    } else {
                        countdownElement.classList.remove('countdown-urgent');
                    }
                    
                    // Добавляем подсказку с процентом оставшегося времени
                    if (document.querySelector('.countdown-timer-progress')) {
                        document.querySelector('.countdown-timer-progress').remove();
                    }
                    
                    const progressElement = document.createElement('div');
                    progressElement.className = 'countdown-timer-progress';
                    progressElement.style.width = '100%';
                    progressElement.style.height = '3px';
                    progressElement.style.marginTop = '5px';
                    progressElement.style.background = '#e0e0e0';
                    progressElement.style.borderRadius = '2px';
                    progressElement.style.position = 'relative';
                    
                    const progressBarElement = document.createElement('div');
                    progressBarElement.style.position = 'absolute';
                    progressBarElement.style.left = '0';
                    progressBarElement.style.top = '0';
                    progressBarElement.style.height = '100%';
                    progressBarElement.style.width = percentageLeft + '%';
                    progressBarElement.style.background = percentageLeft > 25 ? '#d4af37' : '#e74c3c';
                    progressBarElement.style.borderRadius = '2px';
                    progressBarElement.style.transition = 'width 1s';
                    
                    progressElement.appendChild(progressBarElement);
                    countdownElement.parentElement.appendChild(progressElement);
                    
                } else {
                    // Если срок действия истек
                    countdownElement.innerText = 'Срок истёк';
                    countdownElement.classList.add('countdown-urgent');
                }
            } catch (error) {
                console.error('Ошибка при расчете времени:', error);
                countdownElement.innerText = 'Ошибка расчета';
            }
        }
        
        // Функция для правильного склонения слова "день"
        function getDayWord(days) {
            if (days % 100 >= 11 && days % 100 <= 19) {
                return 'дней';
            } else if (days % 10 === 1) {
                return 'день';
            } else if (days % 10 >= 2 && days % 10 <= 4) {
                return 'дня';
            } else {
                return 'дней';
            }
        }
    </script>
</body>
</html>
