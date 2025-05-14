<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TemplatePreviewController extends Controller
{
    /**
     * Отображает шаблон сертификата для предпросмотра в iframe.
     *
     * @param  CertificateTemplate  $template
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(CertificateTemplate $template, Request $request)
    {
        // Получаем данные из запроса или задаем значения по умолчанию
        $previewData = [
            'recipient_name' => $request->input('recipient_name', 'Иванов Иван'),
            'amount' => $request->input('amount', '3 000'),
            'valid_from' => $request->input('valid_from', date('d.m.Y')),
            'valid_until' => $request->input('valid_until', date('d.m.Y', strtotime('+3 month'))),
            'message' => $request->input('message', 'Ваше сообщение или пожелание'),
            'certificate_number' => $request->input('certificate_number', 'CERT-DEMO'),
            'company_name' => $request->input('company_name', config('app.name')),
            // Используем стандартный логотип по умолчанию
            'company_logo' => $request->input('company_logo', asset('images/default-logo.png'))
        ];

        // Проверяем наличие файла шаблона
        $templatePath = public_path($template->template_path);
        if (!file_exists($templatePath)) {
            Log::error("Template file not found: {$templatePath}");
            return response('<div class="alert alert-danger">Файл шаблона не найден</div>')->header('Content-Type', 'text/html');
        }

        // Читаем содержимое файла шаблона
        $html = file_get_contents($templatePath);

        // Заменяем плейсхолдеры на значения
        foreach ($previewData as $key => $value) {
            if (strpos($html, '{'.$key.'}') !== false) {
                $html = str_replace('{'.$key.'}', $value, $html);
            }
        }

        // Добавляем улучшенный скрипт для обработки postMessage с детальным логированием
        $html .= <<<HTML
        <script>
        // Функция для обновления всех возможных логотипов на странице
        function updateLogos(logoUrl) {
            console.log('Обновление логотипов на:', logoUrl);
            
            try {
                // Проверяем, если logoUrl равен 'none', скрываем все элементы логотипа
                if (logoUrl === 'none' || logoUrl === '') {
                    // Находим все возможные элементы, которые могут быть логотипом
                    const companyLogoElements = document.querySelectorAll('.company-logo');
                    const logoImages = document.querySelectorAll('img[src*="logo"], img[alt*="logo"], img[alt*="Логотип"]');
                    
                    let hiddenCount = 0;
                    
                    // Скрываем все найденные элементы с классом company-logo
                    if (companyLogoElements.length > 0) {
                        console.log('Найдено элементов с классом company-logo:', companyLogoElements.length);
                        companyLogoElements.forEach(element => {
                            if (element.tagName === 'IMG') {
                                element.style.display = 'none';
                                hiddenCount++;
                                console.log('Скрыт элемент с классом company-logo');
                            }
                        });
                    }
                    
                    // Скрываем все изображения с "logo" в src или alt, которые не были скрыты ранее
                    if (logoImages.length > 0) {
                        console.log('Найдено изображений с logo в атрибутах:', logoImages.length);
                        logoImages.forEach(img => {
                            if (!companyLogoElements.includes(img)) {
                                img.style.display = 'none';
                                hiddenCount++;
                                console.log('Скрыто изображение логотипа');
                            }
                        });
                    }
                    
                    // Отправляем ответ родителю
                    if (window.parent && window.parent.postMessage) {
                        window.parent.postMessage({
                            type: 'logo_updated',
                            success: true,
                            count: hiddenCount,
                            mode: 'hidden'
                        }, '*');
                    }
                    
                    return hiddenCount;
                }
                
                // Для случая с обычным логотипом - оставляем существующий код
                // Предварительная загрузка изображения для проверки доступности
                const img = new Image();
                img.crossOrigin = "anonymous"; // Для избежания CORS-проблем
                
                img.onload = function() {
                    console.log('Изображение логотипа успешно загружено:', logoUrl);
                    
                    // Находим все возможные элементы, которые могут быть логотипом
                    const companyLogoElements = document.querySelectorAll('.company-logo');
                    const logoImages = document.querySelectorAll('img[src*="logo"], img[alt*="logo"], img[alt*="Логотип"]');
                    const allImages = document.querySelectorAll('img'); // Как запасной вариант
                    
                    let updatedCount = 0;
                    
                    // Обновляем все найденные элементы с классом company-logo
                    if (companyLogoElements.length > 0) {
                        console.log('Найдено элементов с классом company-logo:', companyLogoElements.length);
                        companyLogoElements.forEach(element => {
                            if (element.tagName === 'IMG') {
                                element.src = logoUrl;
                                updatedCount++;
                                console.log('Обновлен элемент с классом company-logo');
                            }
                        });
                    } else {
                        console.log('Элементы с классом company-logo не найдены');
                    }
                    
                    // Обновляем все изображения с "logo" в src или alt
                    if (logoImages.length > 0) {
                        console.log('Найдено изображений с logo в атрибутах:', logoImages.length);
                        logoImages.forEach(img => {
                            if (!companyLogoElements.includes(img)) {
                                img.src = logoUrl;
                                updatedCount++;
                                console.log('Обновлено изображение логотипа');
                            }
                        });
                    } else {
                        console.log('Изображения с logo в атрибутах не найдены, ищем любые изображения...');
                        
                        // Если не нашли элементов по стандартным критериям, обновляем первое найденное изображение
                        if (updatedCount === 0 && allImages.length > 0) {
                            allImages[0].src = logoUrl;
                            updatedCount++;
                            console.log('Обновлено первое найденное изображение как логотип');
                        }
                    }
                    
                    // Отправляем ответ родителю
                    if (window.parent && window.parent.postMessage) {
                        window.parent.postMessage({
                            type: 'logo_updated',
                            success: true,
                            count: updatedCount
                        }, '*');
                    }
                    
                    return updatedCount;
                };
                
                img.onerror = function() {
                    console.error('Не удалось загрузить изображение логотипа:', logoUrl);
                    
                    if (window.parent && window.parent.postMessage) {
                        window.parent.postMessage({
                            type: 'logo_updated',
                            success: false,
                            error: 'Не удалось загрузить изображение логотипа'
                        }, '*');
                    }
                };
                
                // Запускаем загрузку изображения
                img.src = logoUrl;
            } catch (error) {
                console.error('Ошибка при обработке логотипа:', error);
                return 0;
            }
        }
        
        // Обновляем логотип при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            const initialLogoUrl = '{$previewData['company_logo']}';
            console.log('Инициализация логотипа при загрузке страницы:', initialLogoUrl);
            updateLogos(initialLogoUrl);
        });
        
        // Слушатель для обработки сообщений от родительского окна
        window.addEventListener('message', function(event) {
            console.log('Получено сообщение от родительского окна:', event.data);
            
            if (event.data && event.data.type === 'update_logo') {
                try {
                    const logoUrl = event.data.logo_url;
                    console.log('Обновление логотипа через postMessage:', logoUrl);
                    updateLogos(logoUrl);
                } catch (error) {
                    console.error('Ошибка обновления логотипа:', error);
                }
            }
        });
        </script>
        HTML;

        return response($html)->header('Content-Type', 'text/html');
    }
}
