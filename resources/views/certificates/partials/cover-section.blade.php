<!-- Секция с обложкой -->
<div class="cover-section" id="coverSection">
    <div class="cover-container">
        <img class="cover-image" src="{{ asset('storage/' . $certificate->cover_image) }}" alt="Обложка сертификата">
        <div class="cover-overlay"></div>
        
        <div class="cover-info">
            <h1>Подарочный сертификат</h1>
            <p>{{ $certificate->recipient_name }}</p>
            <p>на сумму {{ number_format($certificate->amount, 0, '.', ' ') }} ₽</p><br>
            <p class="certificate-timer">Дней до окончания сертификата: <br>
                <span id="daysRemaining" class="days-remaining">
                    @php
                        // Безопасное получение количества дней с учетом возможности строкового представления даты
                        $validUntil = is_string($certificate->valid_until) ? \Carbon\Carbon::parse($certificate->valid_until) : $certificate->valid_until;
                        $daysRemaining = $validUntil->diffInDays(now());
                    @endphp
                    {{ $daysRemaining }}
                </span>
                <span id="daysText">
                    @if($daysRemaining == 1)
                        день
                    @elseif($daysRemaining >= 2 && $daysRemaining <= 4)
                        дня
                    @else
                        дней
                    @endif
                </span>
            </p>
        </div>
        
        <div class="swipe-indicator" id="swipeIndicator">
            <i class="fa-solid fa-chevron-up"></i>
            <span class="mobile-text">Свайпните вверх</span>
            <span class="desktop-text">Прокрутите вниз</span>
        </div>
    </div>
</div>
