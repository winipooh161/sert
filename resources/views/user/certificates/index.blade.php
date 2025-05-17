@extends('layouts.lk')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <!-- Система папок для сертификатов -->
    <div class="folder-system mb-4">
        <div class="d-flex align-items-center mb-3">
            <button type="button" class="btn btn-sm btn-outline-primary me-2 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                <i class="fa-solid fa-folder-plus me-1"></i>
            </button>
            
            <div class="folder-navigation overflow-auto">
                <div class="btn-group d-flex flex-nowrap" style="min-width: max-content;">
                    <a href="{{ route('user.certificates.index') }}" class="btn btn-sm {{ !request('folder') ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fa-solid fa-certificate me-1"></i>Все
                    </a>
                    
                    @foreach($folders ?? [] as $folder)
                    <a href="{{ route('user.certificates.index', ['folder' => $folder->id]) }}" 
                        class="btn btn-sm {{ request('folder') == $folder->id ? 'btn-primary' : 'btn-outline-secondary' }} folder-btn"
                        data-folder-id="{{ $folder->id }}" data-folder-name="{{ $folder->name }}"
                        data-folder-color="{{ $folder->color }}">
                        <i class="fa-solid fa-folder me-1"></i>{{ $folder->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mb-md-4 gap-2">
        <h1 class="fw-bold fs-4 fs-md-3 mb-0">
            @if(request('folder') && isset($currentFolder))
                {{ $currentFolder->name }}
            @else
                Мои сертификаты
            @endif
        </h1>
        
        <button type="button" class="btn btn-sm btn-outline-info" onclick="startUserCertificatesTour()">
            <i class="fa-solid fa-question-circle me-1"></i>Обучение
        </button>
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

   
    <div class="row row-cols-2 row-cols-sm-2 g-3">
        @forelse ($certificates as $certificate)
            <div class="col">
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
                                <i class="fa-solid fa-external-link-alt me-1"></i>
                            </a>
                         
                            <button type="button" class="btn btn-outline-primary btn-sm" 
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

<!-- Модальное окно создания новой папки -->
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFolderModalLabel">Создать новую папку</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.folders.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="folder-name" class="form-label">Название папки</label>
                        <input type="text" class="form-control" id="folder-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="folder-color" class="form-label">Цвет папки</label>
                        <select class="form-select" id="folder-color" name="color">
                            <option value="primary">Синий</option>
                            <option value="success">Зеленый</option>
                            <option value="danger">Красный</option>
                            <option value="warning">Желтый</option>
                            <option value="info">Голубой</option>
                            <option value="dark">Черный</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Создать</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Добавляем модальное окно для подтверждения удаления папки -->
<div class="modal fade" id="deleteFolderModal" tabindex="-1" aria-labelledby="deleteFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFolderModalLabel">Удаление папки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Вы действительно хотите удалить папку <strong id="folderNameToDelete"></strong>?</p>
                <p class="text-danger">Внимание: Сертификаты будут удалены из этой папки, но останутся в системе.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form id="deleteFolderForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для управления папками при длительном нажатии -->
<div class="modal fade" id="folderManageModal" tabindex="-1" aria-labelledby="folderManageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="folderManageModalLabel">Управление папками</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Выберите папку для сертификата №<span id="certificateNumberInModal"></span>:</p>
                
                <!-- Доступные папки для добавления сертификата -->
                <div class="list-group mb-3">
                    @foreach($folders ?? [] as $folder)
                    <button type="button" class="list-group-item list-group-item-action folder-select-item d-flex justify-content-between align-items-center" 
                            data-folder-id="{{ $folder->id }}">
                        <span>
                            <i class="fa-solid fa-folder me-2 text-{{ $folder->color }}"></i>{{ $folder->name }}
                        </span>
                        <span class="folder-action-icon">
                            <i class="fa-solid fa-plus text-success"></i>
                        </span>
                    </button>
                    @endforeach
                </div>
                
                <!-- Папки, в которых уже находится сертификат (заполняется динамически) -->
                <div class="d-none" id="certificateCurrentFolders">
                    <hr>
                    <p class="mb-2">Сертификат находится в папках:</p>
                    <div class="list-group" id="currentFoldersList">
                        <!-- Заполняется динамически -->
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    @if(($folders ?? collect())->count() < 5)
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                        <i class="fa-solid fa-folder-plus me-2"></i>Создать новую папку
                    </button>
                    @else
                    <button type="button" class="btn btn-outline-secondary" disabled>
                        <i class="fa-solid fa-folder-plus me-2"></i>Лимит папок (5) достигнут
                    </button>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
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

<script>
// Временное добавление функции fetchWithErrorHandling, если она недоступна через app.js
if (!window.fetchWithErrorHandling) {
    window.fetchWithErrorHandling = async function(url, options = {}) {
        try {
            // Добавляем CSRF-токен к запросам
            if (!options.headers) {
                options.headers = {};
            }
            
            if (!options.headers['Content-Type'] && !options.headers['content-type']) {
                options.headers['Content-Type'] = 'application/json';
            }
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                options.headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const response = await fetch(url, options);
            
            // Проверяем статус ответа
            if (!response.ok) {
                const contentType = response.headers.get('content-type');
                let errorData = {};
                
                if (contentType && contentType.includes('application/json')) {
                    try {
                        errorData = await response.json();
                    } catch (e) {
                        console.error('Не удалось распарсить JSON ответ', e);
                    }
                } else {
                    errorData.message = `Ошибка HTTP: ${response.status} ${response.statusText}`;
                }
                
                throw new Error(errorData.message || `Ошибка HTTP: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                return response.text();
            }
        } catch (error) {
            console.error('Ошибка при выполнении запроса:', error);
            throw error;
        }
    };
}

// Временное добавление функции safeVibrate, если она недоступна через app.js
if (!window.safeVibrate) {
    window.safeVibrate = function(pattern) {
        try {
            if (navigator.vibrate && typeof navigator.vibrate === 'function' && document.hasFocus()) {
                navigator.vibrate(pattern);
            }
        } catch (e) {
            console.warn('Vibration API не поддерживается или недоступна', e);
        }
    };
}

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

// Функция для добавления сертификата в папку с улучшенной обработкой ошибок
function addToFolder(certificateId, folderId) {
    // Показываем индикатор загрузки
    const toastEl = document.getElementById('copyToast');
    document.getElementById('toastMessage').textContent = 'Добавление в папку...';
    const toast = new bootstrap.Toast(toastEl, { delay: 1000 });
    toast.show();
    
    // Используем улучшенную функцию для запроса
    window.fetchWithErrorHandling(`/user/certificates/${certificateId}/add-to-folder/${folderId}`, {
        method: 'POST',
    })
    .then(data => {
        if (data.success) {
            document.getElementById('toastMessage').textContent = data.message || 'Сертификат добавлен в папку';
            const newToast = new bootstrap.Toast(toastEl, { delay: 3000 });
            newToast.show();
            
            // Обновляем список текущих папок сертификата, если модальное окно открыто
            if (document.getElementById('folderManageModal').classList.contains('show')) {
                getCertificateFolders(certificateId);
            }
        } else {
            document.getElementById('toastMessage').textContent = data.message || 'Произошла ошибка';
            const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
            errorToast.show();
        }
    })
    .catch(error => {
        document.getElementById('toastMessage').textContent = 'Ошибка при добавлении в папку';
        const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
        errorToast.show();
        console.error('Ошибка:', error);
    });
}

// Функция для удаления сертификата из папки
function removeFromFolder(certificateId, folderId) {
    // Показываем индикатор загрузки
    const toastEl = document.getElementById('copyToast');
    document.getElementById('toastMessage').textContent = 'Удаление из папки...';
    const toast = new bootstrap.Toast(toastEl, { delay: 1000 });
    toast.show();
    
    window.fetchWithErrorHandling(`/user/certificates/${certificateId}/remove-from-folder/${folderId}`, {
        method: 'DELETE',
    })
    .then(data => {
        if (data.success) {
            document.getElementById('toastMessage').textContent = data.message || 'Сертификат удален из папки';
            const newToast = new bootstrap.Toast(toastEl, { delay: 3000 });
            newToast.show();
            
            // Обновляем список текущих папок сертификата
            getCertificateFolders(certificateId);
            
            // Если мы находимся в конкретной папке и удалили сертификат из неё, обновляем страницу
            const urlParams = new URLSearchParams(window.location.search);
            const currentFolder = urlParams.get('folder');
            if (currentFolder && currentFolder == folderId) {
                window.location.reload();
            }
        } else {
            document.getElementById('toastMessage').textContent = data.message || 'Произошла ошибка';
            const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
            errorToast.show();
        }
    })
    .catch(error => {
        document.getElementById('toastMessage').textContent = 'Ошибка при удалении из папки';
        const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
        errorToast.show();
        console.error('Ошибка:', error);
    });
}

// Функция для получения папок, в которых находится сертификат с улучшенной обработкой ошибок
function getCertificateFolders(certificateId) {
    // Показываем состояние загрузки
    const foldersList = document.getElementById('currentFoldersList');
    if (foldersList) {
        foldersList.innerHTML = '<div class="text-center py-2"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Загрузка папок...</div>';
    }
    
    const certificateCurrentFolders = document.getElementById('certificateCurrentFolders');
    
    window.fetchWithErrorHandling(`/user/certificates/${certificateId}/folders`, {
        method: 'GET',
    })
    .then(data => {
        if (data.success) {
            if (foldersList) {
                foldersList.innerHTML = ''; // Очищаем список
            }
            
            if (data.folders && data.folders.length > 0) {
                if (certificateCurrentFolders) {
                    certificateCurrentFolders.classList.remove('d-none');
                }
                
                // Заполняем список текущими папками
                data.folders.forEach(folder => {
                    if (foldersList) {
                        const folderItem = document.createElement('div');
                        folderItem.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                        folderItem.innerHTML = `
                            <span>
                                <i class="fa-solid fa-folder me-2 text-${folder.color}"></i>${folder.name}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFromFolder(${certificateId}, ${folder.id})">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        `;
                        foldersList.appendChild(folderItem);
                    }
                    
                    // Находим соответствующую кнопку в списке доступных папок и меняем иконку
                    const availableFolderBtn = document.querySelector(`.folder-select-item[data-folder-id="${folder.id}"]`);
                    if (availableFolderBtn) {
                        const iconSpan = availableFolderBtn.querySelector('.folder-action-icon');
                        if (iconSpan) {
                            iconSpan.innerHTML = '<i class="fa-solid fa-minus text-danger"></i>';
                        }
                        
                        // Меняем обработчик клика для удаления из папки вместо добавления
                        availableFolderBtn.onclick = function() {
                            removeFromFolder(certificateId, folder.id);
                        };
                    }
                });
            } else {
                if (certificateCurrentFolders) {
                    certificateCurrentFolders.classList.add('d-none');
                }
                if (foldersList) {
                    foldersList.innerHTML = '<div class="text-center py-2 text-muted">Сертификат не добавлен в папки</div>';
                }
            }
            
            // Сбрасываем иконки для папок, в которых нет сертификата
            document.querySelectorAll('.folder-select-item').forEach(item => {
                const folderId = item.dataset.folderId;
                const folderExists = data.folders && data.folders.some(folder => folder.id == folderId);
                
                if (!folderExists) {
                    const iconSpan = item.querySelector('.folder-action-icon');
                    if (iconSpan) {
                        iconSpan.innerHTML = '<i class="fa-solid fa-plus text-success"></i>';
                    }
                    
                    // Восстанавливаем обработчик клика для добавления в папку
                    item.onclick = function() {
                        addToFolder(certificateId, folderId);
                    };
                }
            });
        } else {
            if (certificateCurrentFolders) {
                certificateCurrentFolders.classList.add('d-none');
            }
            if (foldersList) {
                foldersList.innerHTML = `<div class="alert alert-danger py-2">Ошибка при загрузке папок: ${data.message || 'Неизвестная ошибка'}</div>`;
            }
            console.error('Ошибка при загрузке папок:', data.error || data.message);
        }
    })
    .catch(error => {
        console.error('Ошибка при получении папок сертификата:', error);
        
        if (certificateCurrentFolders) {
            certificateCurrentFolders.classList.add('d-none');
        }
        if (foldersList) {
            foldersList.innerHTML = '<div class="alert alert-danger py-2">Ошибка при загрузке папок. Пожалуйста, попробуйте позже.</div>';
        }
    });
}

// Функция для обработки длительного нажатия на папку
document.addEventListener('DOMContentLoaded', function() {
    let folderLongPressTimer;
    let preventFolderClick = false;
    const folderButtons = document.querySelectorAll('.folder-btn');
    
    // Длительность долгого нажатия (в миллисекундах)
    const folderLongPressDuration = 800;

    folderButtons.forEach(button => {
        // Сохраняем данные кнопки папки
        const folderId = button.dataset.folderId;
        const folderName = button.dataset.folderName;
        const folderColor = button.dataset.folderColor;
        
        // Обработчик для клика на кнопку папки
        button.addEventListener('click', function(e) {
            // Если это долгое нажатие, не выполняем переход по ссылке
            if (preventFolderClick) {
                e.preventDefault();
                return;
            }
        });
        
        // Обработчики начала нажатия для папок
        button.addEventListener('mousedown', function(e) {
            preventFolderClick = false;
            button.classList.add('btn-pressing'); // Добавляем класс анимации
            
            folderLongPressTimer = setTimeout(() => {
                preventFolderClick = true;
                handleFolderLongPress(folderId, folderName);
                button.classList.remove('btn-pressing'); // Удаляем класс анимации
                
                // Используем безопасную функцию вибрации
                window.safeVibrate && window.safeVibrate(100);
            }, folderLongPressDuration);
        });
        
        button.addEventListener('touchstart', function(e) {
            preventFolderClick = false;
            button.classList.add('btn-pressing'); // Добавляем класс анимации
            
            folderLongPressTimer = setTimeout(() => {
                preventFolderClick = true;
                handleFolderLongPress(folderId, folderName);
                button.classList.remove('btn-pressing'); // Удаляем класс анимации
                
                // Используем безопасную функцию вибрации
                window.safeVibrate && window.safeVibrate(100);
            }, folderLongPressDuration);
        }, { passive: true });
        
        // Обработчики прерывания нажатия для папок
        button.addEventListener('mouseup', function() {
            clearFolderLongPress(button);
        });
        button.addEventListener('mouseleave', function() {
            clearFolderLongPress(button);
        });
        button.addEventListener('touchend', function() {
            clearFolderLongPress(button);
        });
        button.addEventListener('touchcancel', function() {
            clearFolderLongPress(button);
        });
        button.addEventListener('touchmove', function() {
            clearFolderLongPress(button);
        });
    });
    
    // Очистка таймера долгого нажатия для папок
    function clearFolderLongPress(button) {
        clearTimeout(folderLongPressTimer);
        button.classList.remove('btn-pressing'); // Удаляем класс анимации
    }
    
    // Обработка долгого нажатия на папку - показ модального окна удаления папки
    function handleFolderLongPress(folderId, folderName) {
        // Устанавливаем данные в модальном окне
        document.getElementById('folderNameToDelete').textContent = folderName;
        
        // Устанавливаем URL для формы удаления
        const deleteForm = document.getElementById('deleteFolderForm');
        deleteForm.action = `/user/folders/${folderId}`;
        
        // Показываем модальное окно
        const deleteFolderModal = new bootstrap.Modal(document.getElementById('deleteFolderModal'));
        deleteFolderModal.show();
        
        // Попытка вибрации только если пользователь взаимодействовал с документом
        if (window.safeVibrate && document.wasInteractedWith) {
            window.safeVibrate(100);
        }
    }
});

// Инициализация обработки клика и долгого нажатия на карточки сертификатов
document.addEventListener('DOMContentLoaded', function() {
    let longPressTimer;
    let preventClick = false;
    const certificateCards = document.querySelectorAll('.certificate-card');
    
    // Длительность долгого нажатия (в миллисекундах)
    const longPressDuration = 800;

    certificateCards.forEach(card => {
        // Сохраняем данные карточки
        const certificateId = card.dataset.certificateId;
        const publicUrl = card.dataset.publicUrl;
        const certificateNumber = card.dataset.certificateNumber;
        
        // Обработчик для клика на карточку
        card.addEventListener('click', function(e) {
            // Если это долгое нажатие или клик был на кнопке, не переходим по ссылке
            if (preventClick || e.target.closest('.btn') || e.target.closest('.dropdown-menu')) {
                return;
            }
            
            // Открываем публичную ссылку сертификата в новой вкладке
            window.open(publicUrl, '_blank');
        });
        
        // Обработчики начала нажатия
        card.addEventListener('mousedown', function(e) {
            // Если клик был на кнопке или в выпадающем меню, не обрабатываем
            if (e.target.closest('.btn') || e.target.closest('.dropdown-menu')) {
                return;
            }
            
            preventClick = false;
            longPressTimer = setTimeout(() => {
                preventClick = true;
                handleLongPress(certificateId, certificateNumber);
                
                // Используем безопасную функцию вибрации
                window.safeVibrate && window.safeVibrate(100);
            }, longPressDuration);
        });
        
        card.addEventListener('touchstart', function(e) {
            // Если касание было на кнопке или в выпадающем меню, не обрабатываем
            if (e.target.closest('.btn') || e.target.closest('.dropdown-menu')) {
                return;
            }
            
            preventClick = false;
            longPressTimer = setTimeout(() => {
                preventClick = true;
                handleLongPress(certificateId, certificateNumber);
                
                // Используем безопасную функцию вибрации
                window.safeVibrate && window.safeVibrate(100);
            }, longPressDuration);
        }, { passive: true });
        
        // Обработчики прерывания нажатия
        card.addEventListener('mouseup', clearLongPress);
        card.addEventListener('mouseleave', clearLongPress);
        card.addEventListener('touchend', clearLongPress);
        card.addEventListener('touchcancel', clearLongPress);
        card.addEventListener('touchmove', clearLongPress);
    });
    
    // Очистка таймера долгого нажатия
    function clearLongPress() {
        clearTimeout(longPressTimer);
    }
    
    // Обработка долгого нажатия - показ модального окна управления папками
    function handleLongPress(certificateId, certificateNumber) {
        // Устанавливаем номер сертификата в модальном окне
        document.getElementById('certificateNumberInModal').textContent = certificateNumber;
        
        // Обрабатываем клики на элементы списка папок
        const folderItems = document.querySelectorAll('.folder-select-item');
        folderItems.forEach(item => {
            item.onclick = function() {
                const folderId = this.dataset.folderId;
                addToFolder(certificateId, folderId);
            };
        });
        
        // Показываем модальное окно сразу
        const folderManageModal = new bootstrap.Modal(document.getElementById('folderManageModal'));
        folderManageModal.show();
        
        // Затем загружаем папки (чтобы не блокировать UI)
        setTimeout(() => {
            getCertificateFolders(certificateId);
        }, 100);
        
        // Попытка вибрации только если пользователь взаимодействовал с документом
        if (window.safeVibrate && document.wasInteractedWith) {
            window.safeVibrate(100);
        }
    }
});

/**
 * Загружает библиотеку Intro.js если она не загружена
 * @returns {Promise} Promise, который разрешается после загрузки библиотеки
 */
function loadIntroJs() {
    return new Promise((resolve, reject) => {
        // Проверяем, загружена ли уже библиотека
        if (typeof introJs !== 'undefined') {
            resolve();
            return;
        }
        
        // Загружаем CSS для Intro.js
        if (!document.querySelector('link[href*="introjs.min.css"]')) {
            const linkCSS = document.createElement('link');
            linkCSS.rel = 'stylesheet';
            linkCSS.href = 'https://cdn.jsdelivr.net/npm/intro.js@7.0.1/minified/introjs.min.css';
            document.head.appendChild(linkCSS);
        }
        
        // Загружаем JavaScript библиотеку
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/intro.js@7.0.1/minified/intro.min.js';
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

/**
 * Запускает обучение для страницы сертификатов пользователя
 */
function startUserCertificatesTour() {
    loadIntroJs().then(() => {
        const tour = introJs();
        
        tour.setOptions({
            nextLabel: 'Далее',
            prevLabel: 'Назад',
      
            doneLabel: 'Готово',
            showBullets: true,
            showProgress: true,
            disableInteraction: false,
            scrollToElement: true,
            overlayOpacity: 0.7,
            steps: [
                {
                    title: 'Добро пожаловать!',
                    intro: 'Здесь отображаются ваши подарочные сертификаты. Давайте познакомимся с основными функциями.'
                },
                {
                    element: document.querySelector('.folder-system'),
                    title: 'Система папок',
                    intro: 'Здесь вы можете организовать ваши сертификаты по папкам для удобного доступа.'
                },
                {
                    element: document.querySelector('button[data-bs-target="#createFolderModal"]'),
                    title: 'Создание папки',
                    intro: 'Нажмите на эту кнопку, чтобы создать новую папку для организации ваших сертификатов.'
                },
                {
                    element: document.querySelector('.folder-navigation'),
                    title: 'Навигация по папкам',
                    intro: 'Используйте эти кнопки для переключения между папками с сертификатами.'
                }
            ]
        });
        
        // Добавляем шаги в зависимости от наличия сертификатов на странице
        if (document.querySelector('.certificate-card')) {
            tour.addSteps([
                {
                    element: document.querySelector('.certificate-card'),
                    title: 'Карточка сертификата',
                    intro: 'Это карточка вашего подарочного сертификата. Нажмите на нее, чтобы открыть детальную информацию.'
                },
                {
                    element: document.querySelector('.certificate-status-badge'),
                    title: 'Статус сертификата',
                    intro: 'Здесь показан текущий статус сертификата: активен, использован, истек или отменен.'
                },
                
                {
                    element: document.querySelector('.certificate-actions'),
                    title: 'Действия с сертификатом',
                    intro: 'Здесь находятся кнопки для просмотра и управления сертификатом.'
                },
                {
                    title: 'Дополнительные функции',
                    intro: 'Длительное нажатие на карточке сертификата или папке откроет дополнительное меню управления.'
                }
            ]);
        } else {
            tour.addSteps([
                {
                    element: document.querySelector('.col-12 .card'),
                    title: 'Пока нет сертификатов',
                    intro: 'Здесь будут отображаться ваши подарочные сертификаты, когда они появятся.'
                }
            ]);
        }
        
        tour.addStep({
            title: 'Готово!',
            intro: 'Теперь вы знаете, как управлять своими подарочными сертификатами. Если у вас возникнут вопросы, вы всегда можете повторить обучение, нажав кнопку "Обучение".'
        });
        
        tour.start();
    }).catch(error => {
        console.error('Не удалось загрузить Intro.js:', error);
        alert('Не удалось загрузить обучение. Пожалуйста, проверьте интернет-соединение и попробуйте снова.');
    });
}

// Автоматический запуск обучения при первом посещении страницы
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, было ли уже показано обучение
    const hasSeenUserTour = localStorage.getItem('user_certificates_tour_seen');
    
    // Если обучение еще не было показано
    if (!hasSeenUserTour) {
        // Даем небольшую задержку для полной загрузки страницы
        setTimeout(() => {
            startUserCertificatesTour();
            // Отмечаем, что обучение было показано
            localStorage.setItem('user_certificates_tour_seen', 'true');
        }, 1000);
    }
});
</script>

<style>
/* ...existing code... */

/* Указатель для карточек сертификатов, чтобы показать что они кликабельны */
.certificate-card {
    position: relative; /* Для правильного абсолютного позиционирования дочерних элементов */
    cursor: pointer;
}

/* Визуальный эффект при нажатии на карточку */
.certificate-card:active {
    transform: scale(0.98);
}

/* Длительное нажатие - индикатор */
.long-press-animation {
    animation: pulse-long-press 1s infinite;
}

@keyframes pulse-long-press {
    0% {
        box-shadow: 0 0 0 0 rgba(var(--bs-primary-rgb), 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(var(--bs-primary-rgb), 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(var(--bs-primary-rgb), 0);
    }
}

/* ...existing code... */
</style>
@endsection
