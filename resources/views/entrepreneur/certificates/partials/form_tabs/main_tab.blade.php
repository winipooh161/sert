<!-- Основные параметры сертификата -->
<div class="mb-2 mb-sm-3">
    <label for="amount" class="form-label small fw-bold">Номинал сертификата *</label>
    <div class="mb-2 flex">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="amount_type" id="amount_type_money" value="money" checked>
            <label class="form-check-label small" for="amount_type_money">
               $
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="amount_type" id="amount_type_percent" value="percent">
            <label class="form-check-label small" for="amount_type_percent">
                %
            </label>
        </div>
    </div>
    
    <!-- Денежный номинал (показывается по умолчанию) -->
    <div id="money_amount_block">
        <div class="input-group">
            <input type="number" class="form-control form-control-sm @error('amount') is-invalid @enderror" 
                id="amount" name="amount" value="{{ old('amount', 3000) }}" min="100" step="100" required>
            <span class="input-group-text small">₽</span>
        </div>
    </div>
    
    <!-- Процентный номинал (скрыт по умолчанию) -->
    <div id="percent_amount_block" class="d-none">
        <div class="input-group">
            <input type="number" class="form-control form-control-sm" 
                id="percent_value" name="percent_value" value="{{ old('percent_value', 10) }}" min="1" max="100" step="1">
            <span class="input-group-text small">%</span>
        </div>
    </div>
    
    @error('amount')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @error('percent_value')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-2 mb-sm-3">
    <label for="valid_until" class="form-label small fw-bold">Срок действия *</label>
    <input type="date" class="form-control form-control-sm @error('valid_until') is-invalid @enderror" 
        id="valid_until" name="valid_until" 
        value="{{ old('valid_until', now()->addMonths(3)->format('Y-m-d')) }}" 
        min="{{ now()->format('Y-m-d') }}" required>
    @error('valid_until')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text small">Сертификат будет действителен до указанной даты</div>
</div>

<input type="hidden" name="valid_from" id="valid_from" value="{{ now()->format('Y-m-d') }}">

<!-- Информация о получателе -->
<div class="mb-2 mb-sm-3">
    <label for="recipient_name" class="form-label small fw-bold">Имя получателя *</label>
    <input type="text" class="form-control form-control-sm @error('recipient_name') is-invalid @enderror" 
        id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" required>
    @error('recipient_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-2 mb-sm-3">
    <label for="recipient_phone" class="form-label small fw-bold">Телефон получателя *</label>
    <input type="tel" class="form-control maskphone form-control-sm @error('recipient_phone') is-invalid @enderror" 
        id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone') }}" required>
    @error('recipient_phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text small">Номер телефона для идентификации получателя</div>
</div>

<div class="mb-2 mb-sm-3">
    <label for="recipient_email" class="form-label small fw-bold">Email получателя</label>
    <input type="email" class="form-control form-control-sm @error('recipient_email') is-invalid @enderror" 
        id="recipient_email" name="recipient_email" value="{{ old('recipient_email') }}">
    @error('recipient_email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
