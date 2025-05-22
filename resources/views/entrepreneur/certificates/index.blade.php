@extends('layouts.lk')

@section('content')
    <div class="container-fluid py-3 py-md-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mb-md-4 gap-2">
            <h1 class="fw-bold fs-4 fs-md-3 mb-0">Выданные сертификаты</h1>
        </div>

        <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
                <i class="fa-solid fa-search text-muted"></i>
            </span>
            <input type="text" id="search-certificate" class="form-control border-start-0"
                placeholder="Поиск по имени или телефону" autocomplete="off">
            <button type="button" id="clear-search" class="btn btn-outline-secondary d-none">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div id="search-type-indicator" class="small text-muted mt-1"></div>

        <!-- Контейнер для результатов поиска -->
        <div id="search-results" class="mb-4 d-none">
            <h3 class="fs-5 fw-bold mb-3">Результаты поиска</h3>
            <div id="search-results-container"
                class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
                <!-- Сюда будут добавляться результаты поиска -->
            </div>
            <div id="no-results-message" class="text-center py-4 d-none">
                <i class="fa-solid fa-search text-muted fa-2x mb-2"></i>
                <p class="text-muted">Сертификаты не найдены</p>
            </div>
        </div>

        <!-- Индикатор загрузки -->
        <div id="loading-indicator" class="text-center py-3 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
        </div>

        <!-- Сообщения об успешных операциях -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Основной контейнер для сертификатов -->
        <div id="certificates-container">
            <!-- Группируем сертификаты по дате создания и сортируем от новых к старым -->
            @php
                $certificatesByDate = $certificates
                    ->groupBy(function ($certificate) {
                        return $certificate->created_at->format('Y-m-d');
                    })
                    ->sortKeysDesc(); // Сортировка дат по убыванию, чтобы свежие были сверху
                $isFirstGroup = true; // Флаг для первой группы
            @endphp

            @forelse ($certificatesByDate as $date => $dateGroupCertificates)
                <!-- Заголовок для группы сертификатов по дате -->
                <div class="date-group-heading mb-2 mt-4">
                    <h2 class="fs-6 fw-bold text-muted">
                        @php
                            $carbonDate = \Carbon\Carbon::parse($date);
                            $today = \Carbon\Carbon::today();
                            $yesterday = \Carbon\Carbon::yesterday();
                        @endphp

                        @if ($carbonDate->isSameDay($today))
                            Сегодня
                        @elseif ($carbonDate->isSameDay($yesterday))
                            Вчера
                        @else
                            {{ $carbonDate->format('d.m.Y') }}
                        @endif
                    </h2>
                </div>

                <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
                    @if ($isFirstGroup)
                        <!-- Карточка-кнопка создания нового сертификата (только в первой группе) -->
                        <div class="col">
                            <a href="{{ route('entrepreneur.certificates.select-template') }}" class="card-link">
                                <div
                                    class="card border-0 rounded-4 shadow-sm h-100 certificate-card create-certificate-card">
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4">
                                        <div class="create-icon-wrapper mb-3">
                                            <i class="fa-solid fa-plus fa-3x"></i>
                                        </div>
                                        <h5 class="mb-0 text-center">Создать сертификат</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @php $isFirstGroup = false; @endphp
                    @endif

                    @foreach ($dateGroupCertificates as $certificate)
                        <div class="col certificate-item" data-recipient-name="{{ $certificate->recipient_name }}"
                            data-recipient-phone="{{ $certificate->recipient_phone }}"
                            data-certificate-number="{{ $certificate->certificate_number }}">
                            <div class="card border-0 rounded-4 shadow-sm h-100 certificate-card"
                                data-certificate-id="{{ $certificate->id }}"
                                data-public-url="{{ route('certificates.public', $certificate->uuid) }}"
                                data-certificate-number="{{ $certificate->certificate_number }}">
                                <a href="{{ route('certificates.public', $certificate->uuid) }}" class="card-link"
                                    target="_blank">
                                    <div class="certificate-cover-wrapper">
                                        <img src="{{ $certificate->cover_image_url }}" class="certificate-cover-image"
                                            alt="Обложка сертификата">
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
                                    </div>

                                    <!-- Действия с сертификатом -->
                                    <div class="certificate-actions" onclick="event.stopPropagation();">
                                        <button type="button" class="btn btn-outline-primary btn-sm copy-link-btn"
                                            onclick="copyPublicUrl('{{ route('certificates.public', $certificate->uuid) }}', '{{ $certificate->certificate_number }}')">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                        <a href="{{ route('entrepreneur.certificates.edit', $certificate) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                
            @endforelse
        </div> <!-- Конец основного контейнера для сертификатов -->

        <!-- Индикатор загрузки при скролле -->
        <div id="scroll-loading" class="text-center py-4 d-none">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
            <span class="ms-2">Загрузка сертификатов...</span>
        </div>

        <!-- Пагинация -->
        @if (isset($certificates) && $certificates->hasPages())
            <div class="mt-4 d-flex justify-content-center pagination" id="pagination-container">
                {{ $certificates->withQueryString()->links() }}
            </div>
        @endif

        <!-- Если сертификатов нет, но нам нужно все равно отобразить кнопку создания -->
        @if ($certificatesByDate->isEmpty())
            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
                <div class="col">
                    <a href="{{ route('entrepreneur.certificates.select-template') }}" class="card-link">
                        <div class="card border-0 rounded-4 shadow-sm h-100 certificate-card create-certificate-card">
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4">
                                <div class="create-icon-wrapper mb-3">
                                    <i class="fa-solid fa-plus fa-3x"></i>
                                </div>
                                <h5 class="mb-0 text-center">Создать сертификат</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Toast-уведомление для подтверждения копирования -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div id="copyToast" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fa-solid fa-check-circle me-2"></i>
                    <span id="toastMessage">Ссылка скопирована в буфер обмена</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Закрыть"></button>
            </div>
        </div>
    </div>

    <style>
        /* Стили для карточек сертификатов */
        .card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }

        .certificate-card {
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-link:hover .certificate-card {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .certificate-cover-wrapper {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .certificate-cover-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card-link:hover .certificate-cover-image {
            transform: scale(1.05);
        }

        .certificate-status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .certificate-time-badge {
            position: absolute;
            bottom: 10px;
            left: 10px;
            max-width: 77px;
            background: rgba(0, 0, 0, 0.5);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
        }

        .certificate-actions {
            display: flex;
            gap: 5px;
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        .date-group-heading {
            padding-left: 0.5rem;
            border-left: 3px solid var(--bs-primary);
        }

        /* Стили для адаптивной сетки */
        @media (max-width: 575.98px) {
            .row-cols-1>.col {
                flex: 0 0 auto;
                width: 100%;
            }
        }

        .pagination svg {
            width: 35px;
            height: 35px;
        }

        /* Стили для карточки создания сертификата */
        .create-certificate-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 200px;
        }

        .create-icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bs-primary);
            transition: all 0.3s ease;
        }

        .card-link:hover .create-icon-wrapper {
            background-color: var(--bs-primary);
            color: white;
            transform: scale(1.1);
        }

        /* Стили для поисковой строки */
        #search-certificate.phone-input {
            letter-spacing: 0.5px;
            font-family: monospace;
        }

        /* Анимация подгрузки элементов */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .certificate-item {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Стили для индикатора загрузки */
        #loading-indicator,
        #scroll-loading {
            transition: opacity 0.3s;
        }
    </style>

    <script>
        // Функция для копирования публичной ссылки сертификата
        function copyPublicUrl(url, certNumber) {
            // Останавливаем всплытие события, чтобы не срабатывала ссылка-родитель
            event.preventDefault();
            event.stopPropagation();

            // Проверяем доступность Clipboard API перед его использованием
            if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
                // Копируем текст в буфер обмена с помощью современного API
                navigator.clipboard.writeText(url).then(() => {
                    // Показываем toast-уведомление с успешным копированием
                    const toastEl = document.getElementById('copyToast');
                    document.getElementById('toastMessage').textContent =
                        `Ссылка на сертификат ${certNumber} скопирована`;
                    const toast = new bootstrap.Toast(toastEl, {
                        delay: 3000
                    });
                    toast.show();
                }).catch(err => {
                    console.error('Ошибка при копировании: ', err);
                    // Запасной вариант при возникновении ошибок доступа
                    fallbackCopyTextToClipboard(url, certNumber);
                });
            } else {
                // Если Clipboard API недоступен, используем запасной метод
                fallbackCopyTextToClipboard(url, certNumber);
            }
        }

        // Запасной метод копирования для браузеров без поддержки Clipboard API
        function fallbackCopyTextToClipboard(text, certNumber) {
            const textArea = document.createElement("textarea");
            textArea.value = text;

            // Делаем элемент невидимым
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

        // Добавляем код для умной поисковой строки и ленивой загрузки
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-certificate');
            const clearButton = document.getElementById('clear-search');
            const searchTypeIndicator = document.getElementById('search-type-indicator');
            const searchResults = document.getElementById('search-results');
            const searchResultsContainer = document.getElementById('search-results-container');
            const noResultsMessage = document.getElementById('no-results-message');
            const mainContainer = document.getElementById('certificates-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            const paginationContainer = document.getElementById('pagination-container');
            const scrollLoading = document.getElementById('scroll-loading');

            let currentPage = 1;
            let isLoading = false;
            let hasMorePages = {{ $certificates->hasMorePages() ? 'true' : 'false' }};
            let searchMode = false;
            let searchTimer = null;
            let lastSearchQuery = '';
            let isPhoneMode = false;
            let phoneRawValue = '';

            // Функция для форматирования телефонного номера в формат +7 (XXX) XXX-XX-XX
            function formatPhoneNumber(value) {
                // Очищаем от всех нецифровых символов
                const digits = value.replace(/\D/g, '');

                // Проверяем, если пустая строка, возвращаем пустую строку
                if (!digits.length) {
                    return '';
                }

                // Определяем, нужно ли вставлять код страны +7
                let formattedPhone = '';
                let remainingDigits = digits;

                // Если первая цифра 8 или 7, заменяем на +7, иначе просто добавляем +7
                if (digits.length > 0) {
                    if (digits[0] === '8' || digits[0] === '7') {
                        formattedPhone = '+7';
                        remainingDigits = digits.substring(1);
                    } else {
                        formattedPhone = '+7';
                        // Оставляем все цифры
                    }
                }

                // Форматируем оставшиеся цифры
                if (remainingDigits.length > 0) {
                    formattedPhone += ' (';
                    formattedPhone += remainingDigits.substring(0, Math.min(3, remainingDigits.length));
                }

                if (remainingDigits.length > 3) {
                    formattedPhone += ') ';
                    formattedPhone += remainingDigits.substring(3, Math.min(6, remainingDigits.length));
                }

                if (remainingDigits.length > 6) {
                    formattedPhone += '-';
                    formattedPhone += remainingDigits.substring(6, Math.min(8, remainingDigits.length));
                }

                if (remainingDigits.length > 8) {
                    formattedPhone += '-';
                    formattedPhone += remainingDigits.substring(8, Math.min(10, remainingDigits.length));
                }

                return formattedPhone;
            }

            // Функция для получения чистого номера без форматирования
            function getDigitsOnly(value) {
                return value.replace(/\D/g, '');
            }

            // Определение типа ввода (телефон или имя) и применение маски
            searchInput.addEventListener('input', function(e) {
                let value = e.target.value.trim();

                // Очищаем предыдущий таймер
                if (searchTimer) clearTimeout(searchTimer);

                // Показываем или скрыиваем кнопку очистки
                if (value.length > 0) {
                    clearButton.classList.remove('d-none');
                } else {
                    clearButton.classList.add('d-none');
                    isPhoneMode = false;
                    resetSearch();
                    return;
                }

                // Определяем, является ли ввод телефонным номером
                // Если в начале строки уже стоит +7, или первые символы - цифры
                const digitOnlyValue = getDigitsOnly(value);
                const isPhoneInput = /^[0-9+\-\s()]*$/.test(value);

                // Если режим телефона еще не активирован - проверяем по типу ввода
                if (!isPhoneMode && isPhoneInput) {
                    isPhoneMode = true;
                    searchInput.classList.add('phone-input');
                    searchTypeIndicator.textContent = 'Поиск по номеру телефона';
                    // Сохраняем позицию курсора до изменения значения поля
                    const cursorPos = searchInput.selectionStart;

                    // Сохраняем только цифры для последующего форматирования
                    phoneRawValue = digitOnlyValue;

                    // Форматируем телефон и устанавливаем новое значение
                    const formattedPhone = formatPhoneNumber(digitOnlyValue);
                    searchInput.value = formattedPhone;

                    // Восстанавливаем позицию курсора с учетом добавленных символов форматирования
                    const newCursorPos = cursorPos + (formattedPhone.length - value.length);
                    searchInput.setSelectionRange(newCursorPos, newCursorPos);
                }
                // Если режим телефона уже активирован
                else if (isPhoneMode) {
                    // Сохраняем позицию курсора
                    const cursorPos = searchInput.selectionStart;

                    // Получаем только цифры из текущего значения
                    const currentDigits = getDigitsOnly(value);

                    // Если пользователь удаляет символы - обрабатываем это
                    if (currentDigits.length < phoneRawValue.length) {
                        phoneRawValue = currentDigits;
                    }
                    // Если добавляет - проверяем, что добавляются только цифры
                    else if (currentDigits !== phoneRawValue) {
                        phoneRawValue = currentDigits;
                    }

                    // Форматируем телефон и устанавливаем новое значение
                    const formattedPhone = formatPhoneNumber(phoneRawValue);
                    searchInput.value = formattedPhone;

                    // Устанавливаем корректную позицию курсора
                    // Это сложное вычисление, т.к. нужно учесть добавленные символы форматирования
                    let newCursorPos = cursorPos;
                    const addedFormatChars = formattedPhone.length - value.length;

                    if (addedFormatChars > 0) {
                        newCursorPos += addedFormatChars;
                    }

                    // Если курсор оказался на символе форматирования, сдвигаем его вперед
                    const formatChars = [' ', '(', ')', '-'];
                    while (newCursorPos < formattedPhone.length && formatChars.includes(formattedPhone[
                            newCursorPos])) {
                        newCursorPos++;
                    }

                    // Устанавливаем позицию курсора
                    setTimeout(() => {
                        searchInput.setSelectionRange(newCursorPos, newCursorPos);
                    }, 0);
                } else {
                    // Это похоже на имя
                    isPhoneMode = false;
                    searchInput.classList.remove('phone-input');
                    searchTypeIndicator.textContent = 'Поиск по имени';
                }

                // Устанавливаем таймер для начала поиска после паузы во вводе
                searchTimer = setTimeout(() => {
                    if ((isPhoneMode && phoneRawValue.length >= 3) || (!isPhoneMode && value
                            .length >= 3)) {
                        performSearch(isPhoneMode ? searchInput.value : value);
                    } else if (value.length === 0) {
                        resetSearch();
                    }
                }, 500);
            });

            // Специальная обработка при нажатии клавиш в поле телефона
            searchInput.addEventListener('keydown', function(e) {
                if (!isPhoneMode) return;

                // Запрещаем ввод букв в режиме телефона
                if (e.key.length === 1 && isNaN(parseInt(e.key)) && !['(', ')', '+', '-', ' '].includes(e
                        .key)) {
                    e.preventDefault();
                }
            });

            // Очистка поля поиска
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                clearButton.classList.add('d-none');
                searchInput.classList.remove('phone-input');
                searchTypeIndicator.textContent = '';
                isPhoneMode = false;
                phoneRawValue = '';
                resetSearch();
            });

            // Функция сброса поиска
            function resetSearch() {
                searchMode = false;
                searchResults.classList.add('d-none');
                mainContainer.classList.remove('d-none');
                if (paginationContainer) paginationContainer.classList.remove('d-none');
            }

            // Функция для выполнения поиска
            function performSearch(query) {
                if (query === lastSearchQuery) return;
                lastSearchQuery = query;

                searchMode = true;
                loadingIndicator.classList.remove('d-none');
                searchResults.classList.remove('d-none');
                mainContainer.classList.add('d-none');
                if (paginationContainer) paginationContainer.classList.add('d-none');

                // Очищаем предыдущие результаты
                searchResultsContainer.innerHTML = '';

                // AJAX запрос для поиска
                fetch(`/entrepreneur/certificates/search?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.classList.add('d-none');

                        if (data.certificates.length === 0) {
                            noResultsMessage.classList.remove('d-none');
                        } else {
                            noResultsMessage.classList.add('d-none');

                            // Отображаем результаты
                            data.certificates.forEach(cert => {
                                const certElement = createCertificateElement(cert);
                                searchResultsContainer.appendChild(certElement);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка поиска:', error);
                        loadingIndicator.classList.add('d-none');
                        noResultsMessage.classList.remove('d-none');
                        noResultsMessage.querySelector('p').textContent = 'Ошибка при выполнении поиска';
                    });
            }

            // Функция для создания элемента сертификата
            function createCertificateElement(cert) {
                const col = document.createElement('div');
                col.className = 'col certificate-item';
                col.dataset.recipientName = cert.recipient_name;
                col.dataset.recipientPhone = cert.recipient_phone;
                col.dataset.certificateNumber = cert.certificate_number;

                // Создаем HTML структуру карточки сертификата
                col.innerHTML = `
            <div class="card border-0 rounded-4 shadow-sm h-100 certificate-card"
                 data-certificate-id="${cert.id}"
                 data-public-url="${cert.public_url}"
                 data-certificate-number="${cert.certificate_number}">
                <a href="${cert.public_url}" class="card-link" target="_blank">
                <div class="certificate-cover-wrapper">
                    <img src="${cert.cover_image_url}" class="certificate-cover-image" alt="Обложка сертификата">
                    <div class="certificate-status-badge">
                        ${getStatusBadge(cert.status)}
                    </div>
                    <small class="text-white certificate-time-badge">
                        <i class="fa-regular fa-clock me-1"></i>
                        ${formatTime(cert.created_at)}
                    </small>
                </div>
                <div class="certificate-actions" onclick="event.stopPropagation();">
                    <button type="button" class="btn btn-outline-primary btn-sm copy-link-btn"
                        onclick="copyPublicUrl('${cert.public_url}', '${cert.certificate_number}')">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                    <a href="/entrepreneur/certificates/${cert.id}/edit" class="btn btn-outline-primary btn-sm">
                        <i class="fa-solid fa-edit"></i>
                    </a>
                </div>
                </a>
            </div>
        `;

                return col;
            }

            // Вспомогательная функция для форматирования статуса
            function getStatusBadge(status) {
                switch (status) {
                    case 'active':
                        return '<span class="badge bg-success">Активен</span>';
                    case 'used':
                        return '<span class="badge bg-secondary">Использован</span>';
                    case 'expired':
                        return '<span class="badge bg-warning">Истек</span>';
                    case 'canceled':
                        return '<span class="badge bg-danger">Отменен</span>';
                    default:
                        return '';
                }
            }

            // Вспомогательная функция для форматирования времени
            function formatTime(dateTimeString) {
                const date = new Date(dateTimeString);
                return date.toLocaleTimeString('ru-RU', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // Ленивая загрузка сертификатов при прокрутке
            function loadMoreCertificates() {
                if (isLoading || !hasMorePages || searchMode) return;

                isLoading = true;
                currentPage++;
                scrollLoading.classList.remove('d-none');

                // AJAX запрос для загрузки следующей страницы
                fetch(`/entrepreneur/certificates?page=${currentPage}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        isLoading = false;
                        scrollLoading.classList.add('d-none');

                        if (data.certificates.length > 0) {
                            // Группируем сертификаты по датам
                            const groupedCertificates = groupCertificatesByDate(data.certificates);

                            // Добавляем группы сертификатов на страницу
                            for (const [date, certificates] of Object.entries(groupedCertificates)) {
                                // Проверяем, существует ли уже группа с этой датой
                                let dateGroup = document.querySelector(`[data-date="${date}"]`);

                                if (!dateGroup) {
                                    // Создаем новую группу даты
                                    const dateHeadingText = formatDateHeading(date);
                                    const dateHeading = document.createElement('div');
                                    dateHeading.className = 'date-group-heading mb-2 mt-4';
                                    dateHeading.dataset.date = date;
                                    dateHeading.innerHTML =
                                        `<h2 class="fs-6 fw-bold text-muted">${dateHeadingText}</h2>`;

                                    mainContainer.appendChild(dateHeading);

                                    // Создаем контейнер для карточек
                                    const rowContainer = document.createElement('div');
                                    rowContainer.className =
                                        'row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3';
                                    rowContainer.dataset.dateContainer = date;

                                    mainContainer.appendChild(rowContainer);
                                    dateGroup = rowContainer;
                                } else {
                                    // Используем существующий контейнер
                                    dateGroup = document.querySelector(`[data-date-container="${date}"]`);
                                }

                                // Добавляем сертификаты в группу
                                certificates.forEach(cert => {
                                    const certElement = createCertificateElement(cert);
                                    dateGroup.appendChild(certElement);
                                });
                            }

                            // Обновляем состояние пагинации
                            hasMorePages = data.has_more_pages;
                        } else {
                            hasMorePages = false;
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при загрузке сертификатов:', error);
                        isLoading = false;
                        scrollLoading.classList.add('d-none');
                    });
            }

            // Группировка сертификатов по дате
            function groupCertificatesByDate(certificates) {
                const groups = {};
                certificates.forEach(cert => {
                    const date = cert.created_at.split('T')[0]; // Получаем YYYY-MM-DD
                    if (!groups[date]) {
                        groups[date] = [];
                    }
                    groups[date].push(cert);
                });
                return groups;
            }

            // Форматирование заголовка даты
            function formatDateHeading(dateString) {
                const date = new Date(dateString);
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);

                if (date.toDateString() === today.toDateString()) {
                    return 'Сегодня';
                } else if (date.toDateString() === yesterday.toDateString()) {
                    return 'Вчера';
                } else {
                    return date.toLocaleDateString('ru-RU');
                }
            }

            // Обработка события прокрутки для ленивой загрузки
            window.addEventListener('scroll', function() {
                const {
                    scrollTop,
                    scrollHeight,
                    clientHeight
                } = document.documentElement;

                // Если пользователь достиг 80% высоты страницы, загружаем следующую порцию данных
                if (scrollTop + clientHeight >= scrollHeight * 0.8 && !isLoading && hasMorePages && !
                    searchMode) {
                    loadMoreCertificates();
                }
            });

            // Инициализируем IntersectionObserver для ленивой загрузки изображений
            if ('IntersectionObserver' in window) {
                const imgObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.dataset.src;
                            if (src) {
                                img.src = src;
                                img.removeAttribute('data-src');
                            }
                            observer.unobserve(img);
                        }
                    });
                });

                // Применяем наблюдатель к изображениям сертификатов
                document.querySelectorAll('.certificate-cover-image[data-src]').forEach(img => {
                    imgObserver.observe(img);
                });
            } else {
                // Запасной вариант для браузеров без поддержки IntersectionObserver
                document.querySelectorAll('.certificate-cover-image[data-src]').forEach(img => {
                    img.src = img.dataset.src;
                });
            }
        });
    </script>
@endsection
