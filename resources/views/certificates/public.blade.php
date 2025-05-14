<!DOCTYPE html>
<html lang="ru">
<head>
        <!-- Иконки -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Подарочный сертификат</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }
        
        .certificate-container {
            height: 100vh;
            position: relative;
        }
        
        iframe#certificate-frame {
            height: 100vh;
            width: 100%;
            border: none;
            display: block;
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
        }
    </style>
</head>
<body>
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

        <!-- Кнопка для печати (добавить рядом с QR-кодом) -->
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const iframe = document.getElementById('certificate-frame');
        const logoUrl = '{{ $certificate->company_logo === null ? "none" : ($certificate->company_logo ? asset("storage/" . $certificate->company_logo) : ($certificate->user->company_logo ? asset("storage/" . $certificate->user->company_logo) : asset("images/default-logo.png"))) }}';
        console.log("Логотип для публичного сертификата:", logoUrl);
        
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


