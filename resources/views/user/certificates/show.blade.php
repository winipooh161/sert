@extends('layouts.lk')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="fw-bold">Подарочный сертификат</h1>
                <a href="{{ route('user.certificates.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>К списку
                </a>
            </div>
            
            <!-- Сообщения об успешных операциях -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Карточка сертификата -->
            <div class="card border-0 rounded-4 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Данные сертификата -->
                        <div class="col-md-4 col-lg-3 p-4 border-end">
                            <h5 class="fw-bold mb-4">Информация о сертификате</h5>
                            
                            <div class="mb-4">
                                <span class="d-block text-muted mb-1">Статус</span>
                                @if($certificate->status == 'active')
                                    <span class="badge bg-success">Активен</span>
                                @elseif($certificate->status == 'used')
                                    <span class="badge bg-secondary">Использован</span>
                                @elseif($certificate->status == 'expired')
                                    <span class="badge bg-warning">Истек</span>
                                @elseif($certificate->status == 'canceled')
                                    <span class="badge bg-danger">Отменен</span>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Номер сертификата</span>
                                <span class="fw-bold">{{ $certificate->certificate_number }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Номинал</span>
                                <span class="fw-bold">{{ number_format($certificate->amount, 0, '.', ' ') }} ₽</span>
                            </div>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Отправитель</span>
                                <span class="fw-bold">{{ $certificate->user->name }}</span>
                                <span class="d-block small text-muted">{{ $certificate->user->company }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Получатель</span>
                                <span class="fw-bold">{{ $certificate->recipient_name }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Срок действия</span>
                                <span class="fw-bold">{{ $certificate->valid_from->format('d.m.Y') }} - {{ $certificate->valid_until->format('d.m.Y') }}</span>
                            </div>
                            
                            @if($certificate->message)
                            <div class="mb-3">
                                <span class="d-block text-muted mb-1">Сообщение</span>
                                <div class="mt-2 p-3 bg-light rounded">{{ $certificate->message }}</div>
                            </div>
                            @endif
                            
                            <!-- Кнопки управления -->
                            @if($certificate->status == 'active')
                            <div class="mt-4 d-grid">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#useModal">
                                    <i class="fa-solid fa-check-circle me-2"></i>Использовать сертификат
                                </button>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Предпросмотр сертификата -->
                        <div class="col-md-8 col-lg-9 p-4">
                            <h5 class="fw-bold mb-4">Дизайн сертификата</h5>
                            
                            <div class="certificate-preview-wrapper border rounded p-0 mb-4">
                                <iframe src="{{ route('template.preview', ['template' => $certificate->template, 
                                              'recipient_name' => $certificate->recipient_name,
                                              'amount' => number_format($certificate->amount, 0, '.', ' ') . ' ₽',
                                              'valid_from' => $certificate->valid_from->format('d.m.Y'),
                                              'valid_until' => $certificate->valid_until->format('d.m.Y'),
                                              'message' => $certificate->message ?? '',
                                              'certificate_number' => $certificate->certificate_number,
                                              'company_name' => $certificate->user->company ?? config('app.name')
                                              ]) }}" 
                                id="certificate-preview" class="certificate-preview" width="100%" height="500" frameborder="0"></iframe>
                            </div>

                            <!-- Информация об использовании -->
                            @if($certificate->status == 'active')
                            <div class="alert alert-info">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                <span>Данный сертификат активен и готов к использованию. Для использования нажмите кнопку "Использовать сертификат" слева.</span>
                            </div>
                            @elseif($certificate->status == 'used')
                            <div class="alert alert-secondary">
                                <i class="fa-solid fa-check-circle me-2"></i>
                                <span>Этот сертификат был использован {{ $certificate->used_at ? $certificate->used_at->format('d.m.Y H:i') : 'ранее' }}.</span>
                            </div>
                            @elseif($certificate->status == 'expired')
                            <div class="alert alert-warning">
                                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                <span>Срок действия этого сертификата истек. Он больше не может быть использован.</span>
                            </div>
                            @elseif($certificate->status == 'canceled')
                            <div class="alert alert-danger">
                                <i class="fa-solid fa-ban me-2"></i>
                                <span>Этот сертификат был отменен отправителем.</span>
                            </div>
                            @endif

                            <!-- Кнопки печати -->
                            <div class="mt-4">
                                <button class="btn btn-outline-primary" onclick="printCertificate()">
                                    <i class="fa-solid fa-print me-2"></i>Распечатать сертификат
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно использования сертификата -->
@if($certificate->status == 'active')
<div class="modal fade" id="useModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Использовать сертификат</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Внимание! После подтверждения сертификат будет помечен как использованный. 
                    Это действие необратимо.
                </div>
                <p>Вы уверены, что хотите использовать сертификат <strong>{{ $certificate->certificate_number }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form action="{{ route('user.certificates.mark-as-used', $certificate) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Да, использовать сертификат</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

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

/* Адаптивная высота iframe */
@media (max-width: 768px) {
    .certificate-preview {
        height: 300px;
    }
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

// Добавляем скрипт для поддержки логотипа после загрузки окна
document.addEventListener('DOMContentLoaded', function() {
    const previewFrame = document.getElementById('certificate-preview');
    if(previewFrame) {
        // Используем логотип из объекта сертификата или 'none' если логотип не указан
        const logoUrl = '{{ $certificate->company_logo === null ? "none" : ($certificate->company_logo ? asset("storage/" . $certificate->company_logo) : ($certificate->user->company_logo ? asset("storage/" . $certificate->user->company_logo) : asset("images/default-logo.png"))) }}';
        
        // Функция для отправки логотипа в iframe
        function sendLogoToIframe() {
            try {
                previewFrame.contentWindow.postMessage({
                    type: 'update_logo',
                    logo_url: logoUrl
                }, '*');
            } catch (error) {
                console.error("Ошибка при отправке логотипа:", error);
            }
        }
        
        // Отправляем логотип после загрузки iframe
        previewFrame.addEventListener('load', function() {
            setTimeout(sendLogoToIframe, 500);
        });
        
        // Для случаев когда iframe уже загружен к моменту выполнения скрипта
        if (previewFrame.complete) {
            sendLogoToIframe();
        }
    }
});
</script>
@endsection
