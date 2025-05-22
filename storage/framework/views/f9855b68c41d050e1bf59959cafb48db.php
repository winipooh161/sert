<!-- Модальное окно для QR-кода на весь экран -->
<div class="qr-fullscreen-overlay" id="qrFullscreenOverlay">
    <button class="qr-close-button" id="qrCloseButton">&times;</button>
    <div class="qr-fullscreen-content">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=<?php echo e(urlencode(route('entrepreneur.certificates.admin-verify', $certificate))); ?>" alt="QR Code Fullscreen" id="qrFullscreenImage">
        <p>Сертификат №<?php echo e($certificate->certificate_number); ?></p>
        <p>Отсканируйте этот QR-код для проверки сертификата</p>
    </div>
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
<?php /**PATH C:\OSPanel\domains\sert\resources\views\certificates\partials\modals.blade.php ENDPATH**/ ?>