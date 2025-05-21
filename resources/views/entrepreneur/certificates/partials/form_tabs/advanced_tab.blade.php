<!-- Сообщение -->
<div class="mb-2 mb-sm-3">
    <label for="message" class="form-label small fw-bold">Сообщение или пожелание</label>
    <textarea class="form-control form-control-sm @error('message') is-invalid @enderror" 
        id="message" name="message" rows="3">{{ old('message') }}</textarea>
    @error('message')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text small">Добавьте персональное сообщение или пожелание для получателя</div>
</div>

<!-- Выбор анимационного эффекта -->
<div class="mb-2 mb-sm-3">
    <label for="animation_effect_id" class="form-label small fw-bold">Анимационный эффект</label>
    <div class="input-group">
        <input type="hidden" name="animation_effect_id" id="animation_effect_id" value="{{ old('animation_effect_id') }}">
        <input type="text" class="form-control form-control-sm" id="selected_effect_name" placeholder="Не выбран" readonly>
        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#animationEffectsModal">
            <i class="fa-solid fa-wand-sparkles me-1"></i>Выбрать
        </button>
    </div>
    <div class="form-text small">Выберите анимационный эффект, который будет отображаться при просмотре сертификата</div>
</div>

<!-- Место для дополнительных настроек -->
<div class="alert alert-info py-2 small">
    <i class="fa-solid fa-info-circle me-1"></i>
    Совет: вы сможете распечатать сертификат после его создания
</div>
