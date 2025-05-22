<div class="quiz-step" id="quizStep2">
    <div class="text-center mb-4">
        <div class="quiz-step-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-3">
            <i class="fa-solid fa-user text-primary fs-3"></i>
        </div>
        <h3 class="quiz-step-title">Информация о получателе</h3>
        <p class="text-muted">Введите данные человека, который получит сертификат</p>
    </div>
    
    <div class="mb-3">
        <label for="recipient_name" class="form-label fw-medium mb-2">Имя получателя *</label>
        <input type="text" class="form-control form-control-lg @error('recipient_name') is-invalid @enderror" 
            id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" 
            placeholder="Как зовут получателя" required>
        @error('recipient_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="recipient_phone" class="form-label fw-medium mb-2">Телефон получателя *</label>
        <input type="tel" class="form-control form-control-lg maskphone @error('recipient_phone') is-invalid @enderror" 
            id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone') }}" 
            placeholder="+7 (___) ___-__-__" required>
        @error('recipient_phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text small">Номер телефона для идентификации получателя</div>
    </div>
    
    {{-- <div class="mb-3">
        <label for="recipient_email" class="form-label fw-medium mb-2">Email получателя</label>
        <input type="email" class="form-control form-control-lg @error('recipient_email') is-invalid @enderror" 
            id="recipient_email" name="recipient_email" value="{{ old('recipient_email') }}"
            placeholder="email@example.com">
        @error('recipient_email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div> --}}
    
    <div class="mb-3">
        <label for="message" class="form-label fw-medium mb-2">Сообщение или пожелание</label>
        <textarea class="form-control form-control-lg @error('message') is-invalid @enderror" 
            id="message" name="message" rows="3" placeholder="Напишите пожелание или поздравление">{{ old('message') }}</textarea>
        @error('message')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Маска для телефона (если еще не инициализирована в основном скрипте)
    const phoneInput = document.getElementById('recipient_phone');
    
    if (phoneInput && !phoneInput.hasAttribute('data-mask-initialized')) {
        phoneInput.setAttribute('data-mask-initialized', 'true');
        
        const applyMask = function(event) {
            let blank = "+_ (___) ___-__-__";
            let i = 0;
            let val = this.value.replace(/\D/g, "").replace(/^8/, "7").replace(/^9/, "79");
            
            this.value = blank.replace(/./g, function (char) {
                if (/[_\d]/.test(char) && i < val.length) return val.charAt(i++);
                return i >= val.length ? "" : char;
            });
            
            if (event.type == "blur" && this.value.length <= 4) {
                this.value = "";
            }
        };
        
        phoneInput.addEventListener('input', applyMask);
        phoneInput.addEventListener('focus', applyMask);
        phoneInput.addEventListener('blur', applyMask);
    }
});
</script>
