<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use Illuminate\Http\Request;

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
        ];

        // Подготавливаем HTML шаблона для отображения
        $html = $template->html_template;

        // Заменяем плейсхолдеры на данные
        foreach ($previewData as $key => $value) {
            $html = str_replace('{'.$key.'}', $value, $html);
        }

        return response($html)->header('Content-Type', 'text/html');
    }
}
