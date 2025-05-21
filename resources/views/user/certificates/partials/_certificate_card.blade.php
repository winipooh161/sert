<div class="card border-0 rounded-4 shadow-sm h-100 certificate-card"
    data-certificate-id="{{ $certificate->id }}"
    data-public-url="{{ route('certificates.public', $certificate->uuid) }}"
    data-certificate-number="{{ $certificate->certificate_number }}">
    <!-- Используем загруженную обложку в качестве главного изображения карточки -->
    <div class="certificate-cover-wrapper">
        <img src="{{ $certificate->cover_image_url }}" class="certificate-cover-image" alt="Обложка сертификата">
        <div class="certificate-status-badge">
            @if ($certificate->status == 'active')
                <span class="badge bg-success">Активен</span>
            @elseif ($certificate->status == 'used')
                <span class="badge bg-secondary">Использован</span>
            @elseif ($certificate->status == 'expired')
                <span class="badge bg-warning">Истек</span>
            @elseif ($certificate->status == 'canceled')
                <span class="badge bg-danger">Отменен</span>
            @endif
        </div>
        
        <!-- Добавляем отметку времени -->
       
            <small class="text-white certificate-time-badge">
                <i class="fa-regular fa-clock me-1"></i>
                {{ $certificate->created_at->format('H:i') }}
            </small>
       
        
        <!-- Кнопка добавления в папку -->
        <div class="folder-action dropdown">
            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-folder-open"></i>
            </button>
            <ul class="dropdown-menu">
                @foreach($folders ?? [] as $folder)
                <li>
                    <a class="dropdown-item" href="#" onclick="addToFolder({{ $certificate->id }}, {{ $folder->id }})">
                        <i class="fa-solid fa-folder me-1"></i> {{ $folder->name }}
                    </a>
                </li>
                @endforeach
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                        <i class="fa-solid fa-folder-plus me-1"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
 
    <!-- Действия с сертификатом -->
    <div class="certificate-actions">
        <a href="{{ route('certificates.public', $certificate->uuid) }}" class="btn btn-primary btn-sm" target="_blank">
            <i class="fa-solid fa-external-link-alt me-1" style="margin:0 !important"></i>
        </a>
     
        <button type="button" class="btn btn-outline-primary btn-sm" style="color:#fff; !important" 
            data-bs-toggle="modal" data-bs-target="#qrModal{{ $certificate->id }}">
            <i class="fa-solid fa-qrcode me-1"></i>QR
        </button>
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
