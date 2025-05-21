

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="fw-bold">Подарочный сертификат</h1>
                <div>
                    <button type="button" class="btn btn-outline-info me-2" onclick="startEntrepreneurCertificateTour()">
                        <i class="fa-solid fa-question-circle me-2"></i>Обучение
                    </button>
                    <a href="<?php echo e(route('entrepreneur.certificates.index')); ?>" class="btn btn-outline-secondary me-2">
                        <i class="fa-solid fa-arrow-left me-2"></i>К списку
                    </a>
                    <?php if($certificate->status == 'active'): ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-share-alt me-2"></i>Поделиться
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('certificates.public', $certificate->uuid)); ?>" target="_blank">
                                    <i class="fa-solid fa-external-link me-2"></i>Открыть публичную страницу
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="copyPublicUrl()">
                                    <i class="fa-solid fa-copy me-2"></i>Копировать ссылку
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#emailModal">
                                    <i class="fa-solid fa-envelope me-2"></i>Отправить по email
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Карточка сертификата -->
            <div class="card border-0 rounded-4 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Данные сертификата -->
                        <div class="col-md-2 p-4 border-end">
                            <h5 class="fw-bold mb-4">Информация о сертификате</h5>
                            
                            <div class="mb-4">
                                <span class="d-block text-muted mb-1">Статус</span>
                                <?php if($certificate->status == 'active'): ?>
                                    <span class="badge bg-success">Активен</span>
                                <?php elseif($certificate->status == 'used'): ?>
                                    <span class="badge bg-secondary">Использован</span>
                                <?php elseif($certificate->status == 'expired'): ?>
                                    <span class="badge bg-warning">Истек</span>
                                <?php elseif($certificate->status == 'canceled'): ?>
                                    <span class="badge bg-danger">Отменен</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Номер сертификата</span>
                                <span class="fw-bold"><?php echo e($certificate->certificate_number); ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Номинал</span>
                                <span class="fw-bold"><?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?> ₽</span>
                            </div>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Получатель</span>
                                <span class="fw-bold"><?php echo e($certificate->recipient_name); ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Срок действия</span>
                                <span class="fw-bold"><?php echo e($certificate->valid_from->format('d.m.Y')); ?> - <?php echo e($certificate->valid_until->format('d.m.Y')); ?></span>
                            </div>
                            
                            <?php if($certificate->recipient_email): ?>
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Email получателя</span>
                                <span class="fw-bold"><?php echo e($certificate->recipient_email); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Дата создания</span>
                                <span class="fw-bold"><?php echo e($certificate->created_at->format('d.m.Y H:i')); ?></span>
                            </div>
                            
                            <?php if($certificate->message): ?>
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Сообщение</span>
                                <div class="mt-2 p-3 bg-light rounded"><?php echo e($certificate->message); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Кнопки управления -->
                            <?php if($certificate->status == 'active'): ?>
                            <div class="mt-4 d-grid gap-2">
                                <a href="<?php echo e(route('entrepreneur.certificates.edit', $certificate)); ?>" class="btn btn-outline-primary">
                                    <i class="fa-solid fa-pen-to-square me-2"></i>Редактировать
                                </a>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    <i class="fa-solid fa-ban me-2"></i>Отменить сертификат
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Предпросмотр сертификата -->
                        <div class="col-md-10 p-4">
                            <h5 class="fw-bold mb-4">Дизайн сертификата</h5>
                            
                            <div class="certificate-preview-wrapper border rounded p-0 mb-4">
                                <iframe src="<?php echo e(route('template.preview', ['template' => $certificate->template, 
                                              'recipient_name' => $certificate->recipient_name,
                                              'amount' => number_format($certificate->amount, 0, '.', ' ') . ' ₽',
                                              'valid_from' => $certificate->valid_from->format('d.m.Y'),
                                              'valid_until' => $certificate->valid_until->format('d.m.Y'),
                                              'message' => $certificate->message ?? '',
                                              'certificate_number' => $certificate->certificate_number,
                                              'company_name' => Auth::user()->company ?? config('app.name')
                                              // Не передаем логотип в URL
                                              ])); ?>" 
                id="certificate-preview" class="certificate-preview" width="100%" height="400" frameborder="0"></iframe>
                            </div>
                            
                            <!-- QR-код сертификата -->
                            <h5 class="fw-bold mb-3">QR-код сертификата</h5>
                            <div class="d-flex align-items-center">
                                <div class="certificate-qr me-4">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo e(urlencode(route('certificates.public', $certificate->uuid))); ?>" 
                                         class="img-fluid" alt="QR Code">
                                </div>
                                <div>
                                    <p class="text-muted mb-3">Отсканируйте QR-код или поделитесь ссылкой для просмотра публичной страницы сертификата.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-primary" onclick="printCertificate()">
                                            <i class="fa-solid fa-print me-2"></i>Печать
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" onclick="downloadQR()">
                                            <i class="fa-solid fa-download me-2"></i>Скачать QR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно отправки по email -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Отправить сертификат</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('entrepreneur.certificates.send-email', $certificate)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email получателя</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo e($certificate->recipient_email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailMessage" class="form-label">Сопроводительное сообщение</label>
                        <textarea class="form-control" id="emailMessage" name="emailMessage" rows="3">Здравствуйте! 

Рад(а) отправить Вам подарочный сертификат на <?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?> рублей.

С уважением,
<?php echo e(Auth::user()->name); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно отмены сертификата -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Отменить сертификат</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Внимание! Отмена сертификата означает, что он больше не будет действителен. 
                    Это действие необратимо.
                </div>
                <p>Вы уверены, что хотите отменить сертификат <strong><?php echo e($certificate->certificate_number); ?></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отменить</button>
                <form action="<?php echo e(route('entrepreneur.certificates.destroy', $certificate)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Да, отменить сертификат</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Стили для скрытия бокового меню на этой странице */
aside, .sidebar-nav, .navbar-toggler {
    display: none !important;
}

/* Расширяем main-контент на всю ширину */
.main-content {
    margin-left: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* Дополнительные стили для лучшего вида на полном экране */
.container-fluid {
    max-width: 1800px;
    margin: 0 auto;
}

/* Улучшенные стили для навигационной панели без бокового меню */
.navbar {
    width: 100% !important;
}
</style>

<script>
function printCertificate() {
    // Улучшенная функция печати
    const printContent = document.querySelector('.certificate-preview').innerHTML;
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <html>
        <head>
            <title>Печать сертификата</title>
            <style>
                body {
                    font-family: 'Nunito', sans-serif;
                    padding: 20px;
                    max-width: 800px;
                    margin: 0 auto;
                }
                @media print {
                    body {
                        padding: 0;
                    }
                }
            </style>
        </head>
        <body>
            ${printContent}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    // Задержка для загрузки шрифтов перед печатью
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 300);
}

function downloadQR() {
    // Улучшенная функция загрузки QR-кода
    const qrImg = document.querySelector('.certificate-qr img');
    if (!qrImg) return alert('QR-код не найден');
    
    // Создаем временный Canvas для преобразования изображения
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = qrImg.naturalWidth || 300;
    canvas.height = qrImg.naturalHeight || 300;
    
    // Создаем улучшенное изображение
    const image = new Image();
    image.crossOrigin = 'Anonymous';
    image.onload = function() {
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(image, 0, 0);
        
        // Преобразуем Canvas в ссылку для скачивания
        try {
            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = 'certificate-qr-<?php echo e($certificate->certificate_number); ?>.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch(e) {
            // Запасной вариант если canvas недоступен из-за CORS
            alert('Не удалось скачать QR-код из-за ограничений безопасности. Попробуйте использовать контекстное меню на изображении.');
        }
    };
    image.onerror = function() {
        alert('Не удалось загрузить QR-код');
    };
    image.src = qrImg.src;
}

function copyPublicUrl() {
    const url = '<?php echo e(route('certificates.public', $certificate->uuid)); ?>';
    navigator.clipboard.writeText(url).then(() => {
        alert('Ссылка скопирована в буфер обмена!');
    });
}

// Дополним существующий скрипт вызовом тура при необходимости
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, нужно ли запустить тур автоматически при первом посещении
    const hasSeenTour = localStorage.getItem('entrepreneur_certificate_tour_seen');
    if (!hasSeenTour) {
        // Даем небольшую задержку для полной загрузки страницы
        setTimeout(() => {
            startEntrepreneurCertificateTour();
            // Отмечаем, что тур был показан
            localStorage.setItem('entrepreneur_certificate_tour_seen', 'true');
        }, 1000);
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views/entrepreneur/certificates/show.blade.php ENDPATH**/ ?>