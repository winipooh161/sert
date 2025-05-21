<div class="card border-0 shadow-sm rounded-4 h-100 preview-card">
    <div class="card-header bg-transparent border-0 pt-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
        <div class="d-flex align-items-center mb-2 mb-sm-0">
            <h5 class="fw-bold mb-0 me-2 fs-6">Предпросмотр</h5>
        </div>
        <div class="device-toggle btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary active" data-device="desktop">
                <i class="fa-solid fa-desktop"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-device="tablet">
                <i class="fa-solid fa-tablet-alt"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-device="mobile">
                <i class="fa-solid fa-mobile-alt"></i>
            </button>
        </div>
    </div>

    <!-- Мобильная шторка предпросмотра (видима только на xs и sm устройствах) -->
    <div class="mobile-preview-drawer d-block d-lg-none">
        <div class="drawer-handle">
            <div class="drawer-indicator">
                <i class="fa-solid fa-chevron-down drawer-icon-down"></i>
                <i class="fa-solid fa-chevron-up drawer-icon-up d-none"></i>
            </div>
            <span class="drawer-title">Предпросмотр сертификата</span>
        </div>
        <div class="drawer-content">
            <div class="certificate-preview-container" data-current-device="mobile">
                <div class="certificate-preview-wrapper device-frame">
                    <iframe id="certificatePreview" src="{{ route('template.preview', $template) }}" class="certificate-preview" frameborder="0" loading="lazy"></iframe>
                </div>
            </div>
            <div class="preview-controls">
                <div class="btn-toolbar justify-content-center py-2">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-primary" id="zoomInButton">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" id="zoomOutButton">
                            <i class="fa-solid fa-magnifying-glass-minus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="resetZoomButton">
                            <i class="fa-solid fa-arrows-to-circle"></i>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="rotateViewButton">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Стандартная версия предпросмотра для больших экранов -->
    <div class="card-body p-2 p-sm-3 d-none d-lg-block">
        <div class="alert alert-info mb-2 mb-sm-3 py-2 small">
            <i class="fa-solid fa-info-circle me-1"></i>
            Заполните форму слева, чтобы увидеть изменения в сертификате
        </div>
        <div class="certificate-preview-container" data-current-device="desktop">
            <div class="certificate-preview-wrapper device-frame">
                <iframe id="desktopCertificatePreview" src="{{ route('template.preview', $template) }}" class="certificate-preview" frameborder="0" loading="lazy"></iframe>
            </div>
        </div>
    </div>
    
    <div class="card-footer bg-transparent border-0 pb-3 text-center d-none d-lg-block">
        <div class="btn-toolbar justify-content-center">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-primary" id="zoomInButton">
                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                </button>
                <button type="button" class="btn btn-sm btn-primary" id="zoomOutButton">
                    <i class="fa-solid fa-magnifying-glass-minus"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="resetZoomButton">
                    <i class="fa-solid fa-arrows-to-circle"></i>
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="rotateViewButton">
                    <i class="fa-solid fa-rotate"></i>
                </button>
            </div>
        </div>
    </div>
</div>
