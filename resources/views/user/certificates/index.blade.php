@extends('layouts.lk')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mb-md-4 gap-2">
        <h1 class="fw-bold fs-4 fs-md-3 mb-0">Мои сертификаты</h1>
    </div>

    <!-- Сообщения об ошибках/успешных операциях -->
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

 
    
    <!-- Фильтры и поиск -->
    <div class="card border-0 rounded-4 shadow-sm mb-3 mb-md-4">
        <div class="card-body p-2 p-sm-3">
            <form action="{{ route('user.certificates.index') }}" method="GET" class="row g-2">
                <div class="col-12 col-md-4">
                    <label for="filter_status" class="form-label small mb-1">Статус</label>
                    <select id="filter_status" name="status" class="form-select form-select-sm">
                        <option value="">Все статусы</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                        <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Использованные</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Истекшие</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Отмененные</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label for="filter_search" class="form-label small mb-1">Поиск</label>
                    <input type="text" id="filter_search" name="search" class="form-control form-control-sm" placeholder="Поиск по номеру" value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-4 d-flex align-items-end">
                    <div class="btn-group w-100 mt-1 mt-md-0">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-filter me-1 d-none d-sm-inline-block"></i>Применить
                        </button>
                        <a href="{{ route('user.certificates.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-xmark me-1 d-none d-sm-inline-block"></i>Сбросить
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Карточки сертификатов -->
    <div class="row g-3">
        @forelse ($certificates as $certificate)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 certificate-card">
                    <div class="card-body p-3">
                        <!-- Статус сертификата и иконка -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if ($certificate->status == 'active')
                                <span class="badge bg-success">Активен</span>
                            @elseif ($certificate->status == 'used')
                                <span class="badge bg-secondary">Использован</span>
                            @elseif ($certificate->status == 'expired')
                                <span class="badge bg-warning">Истек</span>
                            @elseif ($certificate->status == 'canceled')
                                <span class="badge bg-danger">Отменен</span>
                            @endif
                            <div class="bg-light rounded-circle certificate-icon p-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-certificate"></i>
                            </div>
                        </div>
                        
                        <!-- Номер сертификата -->
                        <h5 class="card-title fs-6">
                            Сертификат #{{ $certificate->certificate_number }}
                        </h5>
                        
                        <!-- Основная информация -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Номинал:</span>
                                <span class="fw-bold">{{ number_format($certificate->amount, 0, '.', ' ') }} ₽</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Действует до:</span>
                                <span>{{ $certificate->valid_until->format('d.m.Y') }}</span>
                            </div>
                        </div>
                        
                        <!-- Информация о шаблоне -->
                        <div class="mb-3 p-2 bg-light rounded small">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    @if($certificate->template && $certificate->template->image)
                                        <img src="{{ asset('storage/' . $certificate->template->image) }}" class="certificate-thumbnail" alt="Шаблон">
                                    @else
                                        <div class="certificate-thumbnail-placeholder d-flex align-items-center justify-content-center bg-secondary bg-opacity-10">
                                            <i class="fa-solid fa-image text-secondary"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $certificate->template ? $certificate->template->name : 'Стандартный' }}</div>
                                    <div class="text-muted">{{ $certificate->created_at->format('d.m.Y') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Действия с сертификатом -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('certificates.public', $certificate->uuid) }}" class="btn btn-primary btn-sm" target="_blank">
                                <i class="fa-solid fa-external-link-alt me-1"></i>Открыть публичную страницу
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm flex-grow-1" 
                                    onclick="copyPublicUrl('{{ route('certificates.public', $certificate->uuid) }}', '{{ $certificate->certificate_number }}')">
                                    <i class="fa-solid fa-copy me-1"></i>Копировать ссылку
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm flex-grow-1" 
                                    data-bs-toggle="modal" data-bs-target="#qrModal{{ $certificate->id }}">
                                    <i class="fa-solid fa-qrcode me-1"></i>QR-код
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Модальное окно с QR-кодом -->
                <div class="modal fade" id="qrModal{{ $certificate->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">QR-код сертификата</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('certificates.public', $certificate->uuid)) }}" 
                                    class="img-fluid mb-2" alt="QR Code">
                                <p class="mb-0 small">Сертификат № {{ $certificate->certificate_number }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Закрыть</button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="downloadQRCode('{{ $certificate->certificate_number }}', this)">
                                    <i class="fa-solid fa-download me-1"></i>Скачать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex flex-column align-items-center py-4">
                            <i class="fa-solid fa-certificate text-muted fa-3x mb-3"></i>
                            <h5 class="fs-5 mb-2">У вас нет сертификатов</h5>
                            <p class="text-muted mb-0">Здесь будут отображаться полученные вами подарочные сертификаты</p>
                            
                            @if(!Auth::user()->hasRole('predprinimatel'))
                            <div class="alert alert-info mt-3 w-75">
                                <i class="fa-solid fa-lightbulb me-2"></i>
                                <strong>Совет:</strong> Вы также можете переключиться на режим предпринимателя для создания собственных сертификатов
                                <form action="{{ route('role.switch') }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="role" value="predprinimatel">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-briefcase me-2"></i>Стать предпринимателем
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Пагинация -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $certificates->withQueryString()->links() }}
    </div>
</div>

<!-- Toast-уведомление для подтверждения копирования -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
    <div id="copyToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa-solid fa-check-circle me-2"></i>
                <span id="toastMessage">Ссылка скопирована в буфер обмена</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Закрыть"></button>
        </div>
    </div>
</div>

<style>
/* Стили для карточек сертификатов */
.certificate-card {
    transition: transform 0.2s;
    overflow: hidden;
}

.certificate-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}

.certificate-icon {
    width: 40px;
    height: 40px;
}

.certificate-thumbnail {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.certificate-thumbnail-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 4px;
}

/* Адаптивные стили */
@media (max-width: 767.98px) {
    .certificate-card {
        margin-bottom: 1rem;
    }
    
    .certificate-icon {
        width: 32px;
        height: 32px;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Стили для пагинации */
.pagination {
    justify-content: center;
}
</style>

<script>
// Функция для копирования публичной ссылки сертификата
function copyPublicUrl(url, certNumber) {
    if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
        navigator.clipboard.writeText(url).then(() => {
            // Показываем toast-уведомление с успешным копированием
            const toastEl = document.getElementById('copyToast');
            document.getElementById('toastMessage').textContent = `Ссылка на сертификат ${certNumber} скопирована`;
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();
        }).catch(err => {
            console.error('Ошибка при копировании: ', err);
            fallbackCopyTextToClipboard(url, certNumber);
        });
    } else {
        fallbackCopyTextToClipboard(url, certNumber);
    }
}

// Запасной метод копирования для браузеров без поддержки Clipboard API
function fallbackCopyTextToClipboard(text, certNumber) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
  
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            const toastEl = document.getElementById('copyToast');
            document.getElementById('toastMessage').textContent = `Ссылка на сертификат ${certNumber} скопирована`;
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();
        } else {
            alert('Не удалось скопировать ссылку. Пожалуйста, скопируйте её вручную: ' + text);
        }
    } catch (err) {
        console.error('Ошибка при копировании: ', err);
        alert('Не удалось скопировать ссылку. Пожалуйста, скопируйте её вручную: ' + text);
    }
  
    document.body.removeChild(textArea);
}

// Функция для скачивания QR-кода
function downloadQRCode(certNumber, buttonElement) {
    // Находим изображение QR-кода в модальном окне
    const modal = buttonElement.closest('.modal');
    const qrImage = modal.querySelector('img');
    
    if (!qrImage) return;
    
    // Создаем элемент canvas для преобразования изображения
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Загружаем изображение в canvas
    const image = new Image();
    image.crossOrigin = "Anonymous";
    image.onload = function() {
        canvas.width = image.width;
        canvas.height = image.height;
        
        // Рисуем белый фон и изображение
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(image, 0, 0);
        
        // Конвертируем canvas в URL данных
        const dataURL = canvas.toDataURL('image/png');
        
        // Создаем временную ссылку для скачивания
        const downloadLink = document.createElement('a');
        downloadLink.href = dataURL;
        downloadLink.download = `qr-code-certificate-${certNumber}.png`;
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    };
    
    image.src = qrImage.src;
}
</script>
@endsection
