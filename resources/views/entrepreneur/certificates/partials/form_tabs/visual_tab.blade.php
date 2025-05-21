<!-- Обложка сертификата -->
<div class="mb-2 mb-sm-3">
    <label for="cover_image" class="form-label small fw-bold">Обложка сертификата *</label>
    <input type="file" class="form-control form-control-sm @error('cover_image') is-invalid @enderror" 
        id="cover_image" name="cover_image" accept="image/*" required>
    @error('cover_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text small">Загрузите изображение для карточки сертификата. Рекомендуемый размер: 500x300px</div>
    
    <div id="cover_image_preview" class="mt-2 text-center"></div>
</div>

<!-- Логотип компании -->
<div class="mb-2 mb-sm-3">
    <label for="logo" class="form-label small fw-bold">Логотип компании</label>
    <div class="mb-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="logo_type" id="logo_default" value="default" checked>
            <label class="form-check-label small" for="logo_default">
                Использовать из профиля
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="logo_type" id="logo_custom" value="custom">
            <label class="form-check-label small" for="logo_custom">
                Загрузить новый
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="logo_type" id="logo_none" value="none">
            <label class="form-check-label small" for="logo_none">
                Не использовать логотип
            </label>
        </div>
    </div>
    
    <div id="default_logo_preview" class="mb-2 text-center p-2 border rounded">
        <img src="{{ Auth::user()->company_logo ? asset('storage/' . Auth::user()->company_logo) : asset('images/default-logo.png') }}" 
            class="img-thumbnail" style="max-height: 60px;" alt="Текущий логотип">
        <div class="small text-muted mt-1 fs-7">Текущий логотип</div>
    </div>
    
    <div id="custom_logo_container" class="d-none">
        <input type="file" class="form-control form-control-sm @error('custom_logo') is-invalid @enderror" 
            id="custom_logo" name="custom_logo" accept="image/*">
        <div class="form-text small">Рекомендуемый размер: 300x100px, PNG или JPG</div>
        
        <div id="custom_logo_preview" class="mt-2 text-center"></div>
    </div>
    
    @error('custom_logo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
