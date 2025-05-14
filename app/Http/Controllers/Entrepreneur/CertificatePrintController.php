<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CertificatePrintController extends Controller
{
    /**
     * Создание нового экземпляра контроллера
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:predprinimatel|admin']);
    }

    /**
     * Отображает страницу с опциями печати сертификата
     */
    public function showOptions(Certificate $certificate)
    {
        // Проверка доступа: только владелец сертификата или админ может печатать
        $this->authorize('view', $certificate);
        
        return view('entrepreneur.certificates.print-options', compact('certificate'));
    }
    
    /**
     * Создает PDF файл для печати в указанном формате
     */
    public function generatePrintable(Request $request, Certificate $certificate)
    {
        // Проверка доступа: только владелец сертификата или админ может печатать
        $this->authorize('view', $certificate);
        
        $request->validate([
            'format' => ['required', 'string', 'in:a4,a5,a6,letter,legal,custom'],
            'orientation' => ['required', 'string', 'in:portrait,landscape'],
            'custom_width' => ['required_if:format,custom', 'numeric', 'min:50', 'max:1000'],
            'custom_height' => ['required_if:format,custom', 'numeric', 'min:50', 'max:1000'],
            'unit' => ['required_if:format,custom', 'string', 'in:mm,cm,inch'],
        ]);
        
        // Получение параметров печати
        $format = $request->input('format');
        $orientation = $request->input('orientation');
        
        // Настройки PDF
        $options = [
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
        ];
        
        // Настройка формата бумаги
        if ($format === 'custom') {
            $width = $request->input('custom_width');
            $height = $request->input('custom_height');
            $unit = $request->input('unit');
            
            // Преобразование единиц измерения в миллиметры для DomPDF
            if ($unit === 'cm') {
                $width *= 10;
                $height *= 10;
            } elseif ($unit === 'inch') {
                $width *= 25.4;
                $height *= 25.4;
            }
            
            $pageSize = [$width, $height];
        } else {
            $pageSize = $format;
        }
        
        try {
            // Создание HTML-содержимого для печатной версии
            $printHtml = $this->preparePrintContent($certificate, $format, $orientation);
            
            // Генерация PDF
            $pdf = PDF::loadHTML($printHtml)
                ->setPaper($pageSize, $orientation)
                ->setOptions($options);
            
            // Генерация имени файла
            $fileName = "certificate-{$certificate->certificate_number}-{$format}-{$orientation}.pdf";
            
            // Отправка файла на скачивание
            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('Ошибка при создании PDF: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при создании PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Подготовка HTML-содержимого для печати
     */
    private function preparePrintContent(Certificate $certificate, $format, $orientation)
    {
        // Получаем шаблон сертификата
        $template = $certificate->template;
        $templatePath = public_path($template->template_path);
        
        if (!file_exists($templatePath)) {
            abort(404, 'Файл шаблона не найден');
        }
        
        // Читаем содержимое HTML-файла
        $html = file_get_contents($templatePath);
        
        // Заменяем плейсхолдеры на реальные значения
        $html = str_replace('{recipient_name}', $certificate->recipient_name, $html);
        $html = str_replace('{amount}', number_format($certificate->amount, 0, '.', ' ') . ' ₽', $html);
        $html = str_replace('{valid_from}', $certificate->valid_from->format('d.m.Y'), $html);
        $html = str_replace('{valid_until}', $certificate->valid_until->format('d.m.Y'), $html);
        $html = str_replace('{message}', $certificate->message ?? '', $html);
        $html = str_replace('{certificate_number}', $certificate->certificate_number, $html);
        $html = str_replace('{company_name}', $certificate->user->company ?? config('app.name'), $html);
        
        // Добавляем стили для печати с адаптивным масштабированием в зависимости от формата бумаги
        $html .= $this->getPrintStyles($format, $orientation);
        
        // Обработка логотипа компании
        $logoUrl = $this->getLogoUrl($certificate);
        if ($logoUrl !== 'none') {
            $html = str_replace('{company_logo}', $logoUrl, $html);
            $html = preg_replace('/class="company-logo"([^>]*)src="[^"]*"/', 'class="company-logo"$1src="'.$logoUrl.'"', $html);
        } else {
            // Если логотип не нужен, скрываем его
            $html = str_replace('{company_logo}', '', $html);
            $html = preg_replace('/<img[^>]*class="company-logo"[^>]*>/i', '', $html);
        }

        // Оборачиваем содержимое в контейнер для масштабирования
        $html = $this->wrapContentInScalingContainer($html);
        
        return $html;
    }
    
    /**
     * Оборачивает содержимое в масштабируемый контейнер
     */
    private function wrapContentInScalingContainer($html)
    {
        // Ищем тег body и его содержимое
        if (preg_match('/<body[^>]*>(.*)<\/body>/si', $html, $matches)) {
            $bodyContent = $matches[1];
            
            // Оборачиваем содержимое в масштабируемый контейнер
            $wrappedContent = '<div class="certificate-scaling-container">' . $bodyContent . '</div>';
            
            // Заменяем оригинальное содержимое body
            $html = str_replace($bodyContent, $wrappedContent, $html);
        }
        
        return $html;
    }
    
    /**
     * Получение URL логотипа
     */
    private function getLogoUrl(Certificate $certificate)
    {
        if ($certificate->company_logo === 'none') {
            return 'none';
        } elseif ($certificate->company_logo) {
            return asset('storage/' . $certificate->company_logo);
        } elseif ($certificate->user && $certificate->user->company_logo) {
            return asset('storage/' . $certificate->user->company_logo);
        } else {
            return asset('images/default-logo.png');
        }
    }
    
    /**
     * Получение CSS стилей для печати с адаптацией под разные форматы
     */
    private function getPrintStyles($format, $orientation)
    {
        // Основные стили для всех форматов
        $commonStyles = <<<HTML
        <style>
            @page {
                size: {$format} {$orientation};
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                width: 100%;
                height: 100%;
                position: relative;
                overflow: hidden;
            }
            .no-print, .print-button, .admin-qr-code, .admin-qr-toggle {
                display: none !important;
            }
            .certificate-scaling-container {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                height: 100%;
                transform-origin: center center;
                display: flex;
                justify-content: center;
                align-items: center;
                overflow: hidden;
            }
            /* Для всех внутренних элементов, чтобы избежать обрезки */
            .certificate-scaling-container * {
                max-width: 100%;
                box-sizing: border-box;
            }
        </style>
        HTML;
        
        // Добавляем специфические стили в зависимости от формата и ориентации
        $formatSpecificStyles = '';
        
        // Для формата A4
        if ($format === 'a4') {
            if ($orientation === 'landscape') {
                $formatSpecificStyles = <<<HTML
                <style>
                    /* A4 Landscape: 297mm × 210mm */
                    .certificate-scaling-container {
                        transform: scale(0.98);
                    }
                </style>
                HTML;
            } else { // portrait
                $formatSpecificStyles = <<<HTML
                <style>
                    /* A4 Portrait: 210mm × 297mm */
                    .certificate-scaling-container {
                        transform: scale(0.85);
                    }
                </style>
                HTML;
            }
        }
        
        // Для формата A5 - УЛУЧШЕННЫЕ СТИЛИ
        else if ($format === 'a5') {
            if ($orientation === 'landscape') {
                $formatSpecificStyles = <<<HTML
                <style>
                    /* A5 Landscape: 210mm × 148mm */
                    html, body {
                        width: 100%;
                        height: 100%;
                        overflow: hidden;
                    }
                    .certificate-scaling-container {
                        transform: scale(0.65);
                        transform-origin: center center;
                    }
                    /* Гарантируем, что контент сертификата помещается целиком */
                    .certificate-content, .certificate-inner, .certificate-body {
                        width: 100%;
                        max-width: 100%;
                        max-height: 100%;
                    }
                </style>
                HTML;
            } else { // portrait
                $formatSpecificStyles = <<<HTML
                <style>
                    /* A5 Portrait: 148mm × 210mm */
                    html, body {
                        width: 100%;
                        height: 100%;
                        overflow: hidden;
                    }
                    .certificate-scaling-container {
                        transform: scale(0.55);
                        transform-origin: center center;
                    }
                    /* Гарантируем, что контент сертификата помещается целиком */
                    .certificate-content, .certificate-inner, .certificate-body {
                        width: 100%;
                        max-width: 100%;
                        max-height: 100%;
                    }
                </style>
                HTML;
            }
        }
        
        // Для формата A6 - УЛУЧШЕННЫЕ СТИЛИ
        else if ($format === 'a6') {
            if ($orientation === 'landscape') {
                $formatSpecificStyles = <<<HTML
                <style>
                    /* A6 Landscape: 148mm × 105mm */
                    html, body {
                        width: 100%;
                        height: 100%;
                        overflow: hidden;
                    }
                    .certificate-scaling-container {
                        transform: scale(0.45);
                        transform-origin: center center;
                    }
                    /* Гарантируем, что контент сертификата помещается целиком */
                    .certificate-content, .certificate-inner, .certificate-body {
                        width: 100%;
                        max-width: 100%;
                        max-height: 100%;
                    }
                </style>
                HTML;
            } else { // portrait
                $formatSpecificStyles = <<<HTML
                <style>
                    /* A6 Portrait: 105mm × 148mm */
                    html, body {
                        width: 100%;
                        height: 100%;
                        overflow: hidden;
                    }
                    .certificate-scaling-container {
                        transform: scale(0.38);
                        transform-origin: center center;
                    }
                    /* Гарантируем, что контент сертификата помещается целиком */
                    .certificate-content, .certificate-inner, .certificate-body {
                        width: 100%;
                        max-width: 100%;
                        max-height: 100%;
                    }
                </style>
                HTML;
            }
        }
        
        // Для других стандартных форматов
        else if ($format === 'letter') {
            if ($orientation === 'landscape') {
                $formatSpecificStyles = <<<HTML
                <style>
                    /* Letter Landscape: 279mm × 216mm */
                    .certificate-scaling-container {
                        transform: scale(0.95);
                    }
                </style>
                HTML;
            } else {
                $formatSpecificStyles = <<<HTML
                <style>
                    /* Letter Portrait: 216mm × 279mm */
                    .certificate-scaling-container {
                        transform: scale(0.80);
                        transform-origin: top center;
                    }
                </style>
                HTML;
            }
        }
        
        // Для произвольных форматов используем универсальный подход
        else if ($format === 'custom') {
            $formatSpecificStyles = <<<HTML
            <style>
                /* Custom size - универсальный подход */
                .certificate-scaling-container {
                    transform: scale(0.9);
                    transform-origin: top left;
                }
                @media print {
                    .certificate-scaling-container {
                        transform: scale(1);
                        max-width: 100%;
                        max-height: 100%;
                        page-break-inside: avoid;
                    }
                }
            </style>
            HTML;
        }
        
        // Добавляем настройки для печати
        $printMediaStyles = <<<HTML
        <style>
            @media print {
                html, body {
                    width: 100%;
                    height: 100%;
                    margin: 0;
                    padding: 0;
                    overflow: hidden;
                }
                
                /* Предотвращаем разрыв страницы внутри сертификата */
                .certificate-scaling-container {
                    page-break-inside: avoid;
                    break-inside: avoid;
                }
                
                /* Дополнительные стили для исправления масштабирования при печати */
                @page {
                    size: {$format} {$orientation};
                    margin: 0;
                }
            }
        </style>
        HTML;
        
        return $commonStyles . $formatSpecificStyles . $printMediaStyles;
    }
}
