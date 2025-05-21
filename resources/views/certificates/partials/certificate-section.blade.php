<!-- Секция с сертификатом -->
<div class="certificate-section" id="certificateSection">
    <div class="certificate-container">
        <iframe id="certificate-frame" src="{{ route('template.preview', [
            'template' => $certificate->template,
            'recipient_name' => $certificate->recipient_name,
            'amount' => number_format($certificate->amount, 0, '.', ' ') . ' ₽',
            'valid_from' => format_date($certificate->valid_from, 'd.m.Y'),
            'valid_until' => format_date($certificate->valid_until, 'd.m.Y'),
            'message' => $certificate->message ?? '',
            'certificate_number' => $certificate->certificate_number,
            'company_name' => $certificate->user->company ?? config('app.name')
        ]) }}" frameborder="0"></iframe>
        
        <!-- QR-код для администратора (предпринимателя) -->
        <div class="admin-qr-code" id="adminQrCode">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('entrepreneur.certificates.admin-verify', $certificate)) }}" alt="Admin QR Code" id="adminQrImage">
            <p>QR-код для проверки</p>
        </div>
        
        <!-- Кнопка для показа/скрытия QR кода на мобильных устройствах -->
        <div class="admin-qr-toggle" id="adminQrToggle">QR</div>
        
        <!-- Кнопка для печати -->
        <div class="print-button" id="printButton">
            <button class="btn btn-light rounded-circle shadow" onclick="showPrintOptions()">
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
    </div>
</div>
