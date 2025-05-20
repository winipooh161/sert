

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3 py-md-4">
    <!-- Система папок для сертификатов -->
    <div class="folder-system mb-4">
        <div class="d-flex align-items-center mb-3">
            <button type="button" class="btn btn-sm btn-outline-primary me-2 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                <i class="fa-solid fa-folder-plus me-1" style="margin:0 !important;"></i> 
            </button>
            
            <div class="folder-navigation overflow-hidden">
                <div class="btn-group d-flex flex-nowrap" style="min-width: max-content;">
                    <a href="<?php echo e(route('user.certificates.index')); ?>" class="btn btn-sm <?php echo e(!request('folder') ? 'btn-primary' : 'btn-outline-secondary'); ?>">
                        <i class="fa-solid fa-certificate me-1"></i>Все
                    </a>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $folders ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('user.certificates.index', ['folder' => $folder->id])); ?>" 
                        class="btn btn-sm <?php echo e(request('folder') == $folder->id ? 'btn-primary' : 'btn-outline-secondary'); ?> folder-btn"
                        data-folder-id="<?php echo e($folder->id); ?>" data-folder-name="<?php echo e($folder->name); ?>"
                        data-folder-color="<?php echo e($folder->color); ?>">
                        <i class="fa-solid fa-folder me-1 text-<?php echo e($folder->color); ?>"></i><?php echo e($folder->name); ?>

                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <span class="btn btn-sm btn-outline-secondary disabled">
                        <i class="fa-solid fa-folder-open me-1"></i>Нет папок
                    </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
       
    </div>
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mb-md-4 gap-2">
        <h1 class="fw-bold fs-4 fs-md-3 mb-0">
            <?php if(request('folder') && isset($currentFolder)): ?>
                <?php echo e($currentFolder->name); ?>

            <?php else: ?>
                Мои сертификаты
            <?php endif; ?>
        </h1>
        
        <button type="button" class="btn btn-sm btn-outline-info" onclick="startUserCertificatesTour()">
            <i class="fa-solid fa-question-circle me-1"></i>Обучение
        </button>
    </div>

    <!-- Сообщения об ошибках/успешных операциях -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

   
    <div class="row row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4  g-3">
        <?php $__empty_1 = true; $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col">
                <div class="card border-0 rounded-4 shadow-sm h-100 certificate-card"
                    data-certificate-id="<?php echo e($certificate->id); ?>"
                    data-public-url="<?php echo e(route('certificates.public', $certificate->uuid)); ?>"
                    data-certificate-number="<?php echo e($certificate->certificate_number); ?>">
                    <!-- Используем загруженную обложку в качестве главного изображения карточки -->
                    <div class="certificate-cover-wrapper">
                        <img src="<?php echo e($certificate->cover_image_url); ?>" class="certificate-cover-image" alt="Обложка сертификата">
                        <div class="certificate-status-badge">
                            <?php if($certificate->status == 'active'): ?>
                                <span class="badge bg-success">Активен</span>
                            <?php elseif($certificate->status == 'used'): ?>
                                <span class="badge bg-secondary">Использован</span>
                            <?php elseif($certificate->status == 'expired'): ?>
                                <span class="badge bg-warning">Истек</span>
                            <?php elseif($certificate->status == 'canceled'): ?>
                                <span class="badge bg-danger">Отменен</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Кнопка добавления в папку -->
                        <div class="folder-action dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-folder-open"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <?php $__currentLoopData = $folders ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="addToFolder(<?php echo e($certificate->id); ?>, <?php echo e($folder->id); ?>)">
                                        <i class="fa-solid fa-folder me-1"></i> <?php echo e($folder->name); ?>

                                    </a>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <a href="<?php echo e(route('certificates.public', $certificate->uuid)); ?>" class="btn btn-primary btn-sm" target="_blank">
                                <i class="fa-solid fa-external-link-alt me-1" style="margin:0 !important"></i>
                            </a>
                         
                            <button type="button" class="btn btn-outline-primary btn-sm" style="color:#fff; !important" 
                                data-bs-toggle="modal" data-bs-target="#qrModal<?php echo e($certificate->id); ?>">
                                <i class="fa-solid fa-qrcode me-1"></i>QR
                            </button>
                        </div>
                   
                </div>
                
                <!-- Модальное окно с QR-кодом -->
                <div class="modal fade" id="qrModal<?php echo e($certificate->id); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">QR-код сертификата</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo e(urlencode(route('certificates.public', $certificate->uuid))); ?>" 
                                    class="img-fluid mb-2" alt="QR Code">
                                <p class="mb-0 small">Сертификат № <?php echo e($certificate->certificate_number); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Закрыть</button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="downloadQRCode('<?php echo e($certificate->certificate_number); ?>', this)">
                                    <i class="fa-solid fa-download me-1"></i>Скачать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex flex-column align-items-center py-4">
                            <i class="fa-solid fa-certificate text-muted fa-3x mb-3"></i>
                            <h5 class="fs-5 mb-2">У вас нет сертификатов</h5>
                            <p class="text-muted mb-0">Здесь будут отображаться полученные вами подарочные сертификаты</p>
                            
                            <?php if(!Auth::user()->hasRole('predprinimatel')): ?>
                            <div class="alert alert-info mt-3 w-75">
                                <i class="fa-solid fa-lightbulb me-2"></i>
                                <strong>Совет:</strong> Вы также можете переключиться на режим предпринимателя для создания собственных сертификатов
                                <form action="<?php echo e(route('role.switch')); ?>" method="POST" class="mt-2">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="role" value="predprinimatel">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-briefcase me-2"></i>Стать предпринимателем
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Пагинация -->
    <div class="mt-4 d-flex justify-content-center">
        <?php echo e($certificates->withQueryString()->links()); ?>

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
            <form id="create-folder-form" action="<?php echo e(route('user.folders.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
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

<!-- Модальное окно для подтверждения удаления папки -->
<div class="modal fade" id="deleteFolderModal" tabindex="-1" aria-labelledby="deleteFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteFolderModalLabel">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>Удаление папки
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fa-solid fa-folder-open text-danger fa-3x"></i>
                </div>
                <p>Вы действительно хотите удалить папку <strong id="folderNameToDelete" class="text-danger">-</strong>?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <form id="delete-folder-form" action="" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash me-2"></i>Удалить папку
                    </button>
                </form>
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
    
    // Формируем URL для запроса
    const url = `/user/certificates/${certificateId}/add-to-folder/${folderId}`;
    console.log('Отправляем запрос на:', url);
    
    // Получаем CSRF токен
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Проверяем наличие CSRF-токена
    if (!csrfToken) {
        console.error('CSRF токен отсутствует. Проверьте наличие meta тега с name="csrf-token"');
        document.getElementById('toastMessage').textContent = 'Ошибка безопасности: CSRF токен отсутствует';
        document.getElementById('copyToast').classList.add('bg-danger');
        document.getElementById('copyToast').classList.remove('bg-success');
        const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
        errorToast.show();
        return;
    }
    
    // Используем fetch API напрямую для большего контроля
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify({_token: csrfToken}) // Явно добавляем токен в тело запроса
    })
    .then(response => {
        console.log('Получен ответ, статус:', response.status);
        if (!response.ok) {
            if (response.status === 419) {
                throw new Error('Ошибка CSRF токена. Попробуйте обновить страницу.');
            }
            return response.json().then(err => {
                throw new Error(err.message || `Ошибка HTTP: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Данные ответа:', data);
        
        if (data.success) {
            document.getElementById('toastMessage').textContent = data.message || 'Сертификат добавлен в папку';
            document.getElementById('copyToast').classList.add('bg-success');
            document.getElementById('copyToast').classList.remove('bg-danger');
            const newToast = new bootstrap.Toast(toastEl, { delay: 3000 });
            newToast.show();
            
            // Обновляем список текущих папок сертификата, если модальное окно открыто
            if (document.getElementById('folderManageModal').classList.contains('show')) {
                getCertificateFolders(certificateId);
            } else {
                // Если мы находимся в режиме просмотра папки, обновим страницу для отображения изменений
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        } else {
            document.getElementById('toastMessage').textContent = data.message || 'Произошла ошибка';
            document.getElementById('copyToast').classList.add('bg-danger');
            document.getElementById('copyToast').classList.remove('bg-success');
            const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
            errorToast.show();
        }
    })
    .catch(error => {
        console.error('Ошибка при добавлении в папку:', error);
        
        document.getElementById('toastMessage').textContent = 'Ошибка при добавлении в папку: ' + error.message;
        document.getElementById('copyToast').classList.add('bg-danger');
        document.getElementById('copyToast').classList.remove('bg-success');
        const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
        errorToast.show();
    });
}

// Функция для удаления сертификата из папки
function removeFromFolder(certificateId, folderId) {
    // Показываем индикатор загрузки
    const toastEl = document.getElementById('copyToast');
    document.getElementById('toastMessage').textContent = 'Удаление из папки...';
    const toast = new bootstrap.Toast(toastEl, { delay: 1000 });
    toast.show();
    
    // Формируем URL для запроса
    const url = `/user/certificates/${certificateId}/remove-from-folder/${folderId}`;
    console.log('Отправляем запрос на:', url);
    
    // Получаем CSRF токен
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Проверяем наличие CSRF-токена
    if (!csrfToken) {
        console.error('CSRF токен отсутствует. Проверьте наличие meta тега с name="csrf-token"');
        document.getElementById('toastMessage').textContent = 'Ошибка безопасности: CSRF токен отсутствует';
        document.getElementById('copyToast').classList.add('bg-danger');
        document.getElementById('copyToast').classList.remove('bg-success');
        const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
        errorToast.show();
        return;
    }
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify({_token: csrfToken}) // Явно добавляем токен в тело запроса
    })
    .then(response => {
        console.log('Получен ответ, статус:', response.status);
        if (!response.ok) {
            if (response.status === 419) {
                throw new Error('Ошибка CSRF токена. Попробуйте обновить страницу.');
            }
            return response.json().then(err => {
                throw new Error(err.message || `Ошибка HTTP: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Данные ответа:', data);
        
        if (data.success) {
            document.getElementById('toastMessage').textContent = data.message || 'Сертификат удален из папки';
            document.getElementById('copyToast').classList.add('bg-success');
            document.getElementById('copyToast').classList.remove('bg-danger');
            const newToast = new bootstrap.Toast(toastEl, { delay: 3000 });
            newToast.show();
            
            // Обновляем список текущих папок сертификата
            if (document.getElementById('folderManageModal').classList.contains('show')) {
                getCertificateFolders(certificateId);
            }
            
            // Если мы находимся в конкретной папке и удалили сертификат из неё, обновляем страницу
            const urlParams = new URLSearchParams(window.location.search);
            const currentFolder = urlParams.get('folder');
            if (currentFolder && currentFolder == folderId) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        } else {
            document.getElementById('toastMessage').textContent = data.message || 'Произошла ошибка';
            document.getElementById('copyToast').classList.add('bg-danger');
            document.getElementById('copyToast').classList.remove('bg-success');
            const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
            errorToast.show();
        }
    })
    .catch(error => {
        console.error('Ошибка при удалении из папки:', error);
        
        document.getElementById('toastMessage').textContent = 'Ошибка при удалении из папки: ' + error.message;
        document.getElementById('copyToast').classList.add('bg-danger');
        document.getElementById('copyToast').classList.remove('bg-success');
        const errorToast = new bootstrap.Toast(toastEl, { delay: 3000 });
        errorToast.show();
    });
}

// Функция для получения папок, в которых находится сертификат
function getCertificateFolders(certificateId) {
    // Показываем состояние загрузки
    const foldersList = document.getElementById('foldersList');
    if (foldersList) {
        foldersList.innerHTML = `
            <div class="text-center p-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
                <p class="mb-0 small">Загрузка списка папок...</p>
            </div>
        `;
    }
    
    // Формируем URL для запроса
    const url = `/user/certificates/${certificateId}/folders`;
    console.log('Запрашиваем папки по URL:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Получен ответ, статус:', response.status);
        if (!response.ok) {
            throw new Error(`Ошибка HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Данные папок:', data);
        
        if (data.success) {
            if (foldersList) {
                renderFoldersList(foldersList, data.folders, certificateId);
            }
        } else {
            if (foldersList) {
                foldersList.innerHTML = `
                    <div class="alert alert-danger py-2">
                        Ошибка: ${data.message || 'Не удалось загрузить папки'}
                    </div>
                `;
            }
            console.error('Ошибка при загрузке папок:', data.message);
        }
    })
    .catch(error => {
        console.error('Ошибка при получении папок сертификата:', error);
        
        if (foldersList) {
            foldersList.innerHTML = `
                <div class="alert alert-danger py-2">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Ошибка загрузки: ${error.message}
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-sm btn-outline-secondary" onclick="getCertificateFolders(${certificateId})">
                        <i class="fas fa-sync me-1"></i>Повторить попытку
                    </button>
                </div>
            `;
        }
    });
}

// Функция для отрисовки списка папок
function renderFoldersList(foldersList, folders, certificateId) {
    foldersList.innerHTML = ''; // Очищаем список
    
    if (!folders || folders.length === 0) {
        foldersList.innerHTML = `
            <div class="text-center p-3">
                <i class="fas fa-folder-open text-muted fa-2x mb-2"></i>
                <p class="mb-0 text-muted">У вас пока нет папок</p>
                <p class="small text-muted mb-0">Создайте новую папку ниже</p>
            </div>
        `;
        return;
    }
    
    folders.forEach(folder => {
        const isInFolder = folder.has_certificate;
        const folderColor = folder.color || 'primary';
        
        const folderItem = document.createElement('div');
        folderItem.className = 'list-group-item d-flex justify-content-between align-items-center';
        folderItem.innerHTML = `
            <div>
                <i class="fas fa-folder me-2" style="color: var(--bs-${folderColor})"></i>
                ${folder.name}
            </div>
            <div class="btn-group btn-group-sm" role="group">
                ${isInFolder ? 
                    `<button type="button" 
                      class="btn btn-outline-danger remove-from-folder-btn" 
                      data-folder-id="${folder.id}" 
                      data-certificate-id="${certificateId}">
                        <i class="fas fa-minus"></i>
                    </button>` : 
                    `<button type="button" 
                      class="btn btn-outline-success add-to-folder-btn" 
                      data-folder-id="${folder.id}" 
                      data-certificate-id="${certificateId}">
                        <i class="fas fa-plus"></i>
                    </button>`
                }
            </div>
        `;
        
        foldersList.appendChild(folderItem);
    });
    
    // Добавляем обработчики событий на кнопки
    document.querySelectorAll('.add-to-folder-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const folderId = this.getAttribute('data-folder-id');
            const certId = this.getAttribute('data-certificate-id');
            addToFolder(certId, folderId);
        });
    });
    
    document.querySelectorAll('.remove-from-folder-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const folderId = this.getAttribute('data-folder-id');
            const certId = this.getAttribute('data-certificate-id');
            removeFromFolder(certId, folderId);
        });
    });
}

// Обработчик создания новой папки в модальном окне
document.addEventListener('DOMContentLoaded', function() {
    const createFolderBtn = document.getElementById('createFolderBtn');
    const newFolderName = document.getElementById('newFolderName');
    const folderActionStatus = document.getElementById('folderActionStatus');
    
    if (createFolderBtn && newFolderName) {
        createFolderBtn.addEventListener('click', function() {
            const folderName = newFolderName.value.trim();
            if (!folderName) {
                alert('Введите название папки');
                return;
            }
            
            createFolderBtn.disabled = true;
            createFolderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Создание...';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch('/user/folders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify({
                    name: folderName
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Ошибка создания папки');
                return response.json();
            })
            .then(data => {
                newFolderName.value = '';
                createFolderBtn.disabled = false;
                createFolderBtn.innerHTML = 'Создать';
                
                // Показываем сообщение об успехе
                if (folderActionStatus) {
                    folderActionStatus.querySelector('span').textContent = 'Папка успешно создана';
                    folderActionStatus.style.display = 'block';
                    folderActionStatus.classList.remove('text-danger');
                    folderActionStatus.classList.add('text-success');
                }
                
                // Обновляем список папок, если открыто модальное окно
                const currentCertificateId = document.getElementById('currentCertificateId');
                if (currentCertificateId && currentCertificateId.value) {
                    getCertificateFolders(currentCertificateId.value);
                } else {
                    // Обновляем страницу, если модальное окно не открыто
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            })
            .catch(error => {
                createFolderBtn.disabled = false;
                createFolderBtn.innerHTML = 'Создать';
                console.error('Ошибка при создании папки:', error);
                
                // Показываем сообщение об ошибке
                if (folderActionStatus) {
                    folderActionStatus.querySelector('span').textContent = 'Ошибка: не удалось создать папку';
                    folderActionStatus.style.display = 'block';
                    folderActionStatus.classList.remove('text-success');
                    folderActionStatus.classList.add('text-danger');
                }
            });
        });
        
        // Добавляем обработчик Enter для поля ввода
        newFolderName.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                createFolderBtn.click();
            }
        });
    }
});

// Инициализация обработки двойного нажатия на карточки сертификатов
document.addEventListener('DOMContentLoaded', function() {
    const certificateCards = document.querySelectorAll('.certificate-card');
    
    certificateCards.forEach(card => {
        // Сохраняем данные карточки
        const certificateId = card.dataset.certificateId;
        const publicUrl = card.dataset.publicUrl;
        const certificateNumber = card.dataset.certificateNumber;
        let clickTimer = null;
        let clickCount = 0;
        
        // Обработчик клика с задержкой для распознавания двойного клика
        card.addEventListener('click', function(e) {
            // Если клик был на кнопке или в выпадающем меню, не обрабатываем
            if (e.target.closest('.btn') || e.target.closest('.dropdown-menu')) {
                return;
            }
            
            e.preventDefault(); // Предотвращаем любые стандартные действия
            
            clickCount++;
            
            if (clickCount === 1) {
                clickTimer = setTimeout(function() {
                    clickCount = 0;
                    // Если это одиночный клик - открываем публичную страницу сертификата
                    window.open(publicUrl, '_blank');
                }, 300);
            } else if (clickCount === 2) {
                clearTimeout(clickTimer);
                clickCount = 0;
                // Если это двойной клик - показываем модальное окно управления папками
                handleCertificateAction(certificateId, certificateNumber);
                
                // Используем безопасную функцию вибрации
                window.safeVibrate && window.safeVibrate(100);
            }
        });
        
        // Сбрасываем счетчик при уходе мыши с элемента
        card.addEventListener('mouseout', function() {
            // Очищаем таймер при уходе с элемента, но не сбрасываем счетчик
            // чтобы не прерывать возможный двойной клик
            if (clickCount === 1) {
                clearTimeout(clickTimer);
                
                // Даем небольшую задержку перед сбросом счетчика
                setTimeout(() => {
                    clickCount = 0;
                }, 500);
            }
        });
    });
    
    // Обработка действия для сертификата - показ модального окна управления папками
    function handleCertificateAction(certificateId, certificateNumber) {
        // Устанавливаем номер сертификата в модальном окне
        document.getElementById('certificateNumberInModal').textContent = certificateNumber;
        
        // Сохраняем ID сертификата в скрытом поле модального окна
        document.getElementById('currentCertificateId').value = certificateId;
        
        // Показываем модальное окно сразу
        const folderManageModal = new bootstrap.Modal(document.getElementById('folderManageModal'));
        folderManageModal.show();
        
        // Затем загружаем папки (чтобы не блокировать UI)
        setTimeout(() => {
            getCertificateFolders(certificateId);
        }, 100);
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

// Обновляем текст в подсказке обучения 
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
                    intro: 'Двойной клик на карточке сертификата или папке откроет дополнительное меню управления.'
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

<script>
// Настраиваем модальное окно для удаления папки
document.addEventListener('DOMContentLoaded', function() {
    // Настраиваем модальное окно для удаления папки
    const deleteFolderModal = document.getElementById('deleteFolderModal');
    if (deleteFolderModal) {
        deleteFolderModal.addEventListener('show.bs.modal', function (event) {
            // Кнопка, которая вызвала модальное окно
            const button = event.relatedTarget;
            
            // Получаем данные папки
            let folderId = button.getAttribute('data-folder-id');
            let folderName = button.getAttribute('data-folder-name');
            
            // Обновляем имя папки в модальном окне
            const folderNameElement = document.getElementById('folderNameToDelete');
            if (folderNameElement) {
                folderNameElement.textContent = folderName;
            }
            
            // Обновляем форму удаления
            const deleteForm = document.getElementById('delete-folder-form');
            if (deleteForm) {
                deleteForm.action = `/user/folders/${folderId}`;
            }
        });
    }
    
    // Добавляем обработчик двойного клика для папок
    const folderButtons = document.querySelectorAll('.folder-btn');
    folderButtons.forEach(button => {
        let clickCount = 0;
        let clickTimer = null;
        
        // Обработчик клика
        button.addEventListener('click', function(e) {
            // Если это первый клик, позволяем обычную навигацию
            if (clickCount === 0) {
                clickCount++;
                
                // Сбрасываем счетчик кликов через некоторое время
                clickTimer = setTimeout(() => {
                    clickCount = 0;
                }, 300); // 300мс для регистрации двойного клика
            } 
            // Если это второй клик (двойной клик)
            else if (clickCount === 1) {
                // Предотвращаем стандартное действие (переход по ссылке)
                e.preventDefault();
                
                // Очищаем таймер и сбрасываем счетчик
                clearTimeout(clickTimer);
                clickCount = 0;
                
                // Получаем данные папки
                const folderId = this.getAttribute('data-folder-id');
                const folderName = this.getAttribute('data-folder-name');
                const folderColor = this.getAttribute('data-folder-color');
                
                // Показываем тактильную обратную связь на мобильных устройствах
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }
                
                // Открываем модальное окно удаления папки
                const bsModal = new bootstrap.Modal(document.getElementById('deleteFolderModal'));
                
                // Устанавливаем данные папки для модального окна
                const folderNameElement = document.getElementById('folderNameToDelete');
                if (folderNameElement) {
                    folderNameElement.textContent = folderName;
                    folderNameElement.className = `fw-bold text-${folderColor || 'primary'}`;
                }
                
                // Обновляем форму удаления
                const deleteForm = document.getElementById('delete-folder-form');
                if (deleteForm) {
                    deleteForm.action = `/user/folders/${folderId}`;
                }
                
                // Показываем модальное окно
                bsModal.show();
            }
        });
    });
});
</script>

<script>
// Настраиваем обработчик для папок - полностью предотвращаем стандартное поведение для всех кликов
document.addEventListener('DOMContentLoaded', function() {
    // Находим все кнопки папок
    const folderButtons = document.querySelectorAll('.folder-btn');
    
    folderButtons.forEach(button => {
        // Создаем переменные для отслеживания кликов
        let clickCount = 0;
        let clickTimer = null;
        
        // Добавляем обработчик события клика для каждой кнопки папки
        button.addEventListener('click', function(e) {
            // Предотвращаем стандартное поведение (переход по ссылке) для ЛЮБОГО клика
            e.preventDefault();
            e.stopPropagation();
            
            // Увеличиваем счетчик кликов
            clickCount++;
            
            // Если это первый клик
            if (clickCount === 1) {
                // Запоминаем URL для возможного перехода позже
                const href = this.getAttribute('href');
                
                // Добавляем небольшую визуальную обратную связь
                this.classList.add('btn-pressing');
                setTimeout(() => {
                    this.classList.remove('btn-pressing');
                }, 200);
                
                // Устанавливаем таймер ожидания второго клика
                clickTimer = setTimeout(() => {
                    // Если второй клик не произошел в течение таймаута (это одиночный клик)
                    clickCount = 0;
                    
                    // Выполняем загрузку через AJAX вместо перехода по ссылке
                    loadPageContent(href);
                }, 300); // 300мс для определения двойного клика
            } 
            // Если это второй клик (двойной клик)
            else if (clickCount === 2) {
                // Очищаем таймер ожидания перехода по ссылке
                clearTimeout(clickTimer);
                
                // Сбрасываем счетчик кликов
                clickCount = 0;
                
                // Получаем данные папки из атрибутов
                const folderId = this.getAttribute('data-folder-id');
                const folderName = this.getAttribute('data-folder-name');
                const folderColor = this.getAttribute('data-folder-color');
                
                // Вибрация для тактильной обратной связи
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }
                
                // Заполняем данные в модальном окне
                const folderNameElement = document.getElementById('folderNameToDelete');
                if (folderNameElement) {
                    folderNameElement.textContent = folderName;
                    folderNameElement.className = `text-${folderColor || 'danger'}`;
                }
                
                // Устанавливаем действие формы удаления
                const deleteForm = document.getElementById('delete-folder-form');
                if (deleteForm) {
                    deleteForm.action = `/user/folders/${folderId}`;
                }
                
                // Открываем модальное окно удаления папки
                const bsModal = new bootstrap.Modal(document.getElementById('deleteFolderModal'));
                bsModal.show();
            }
        });
        
        // Если пользователь перемещает мышь с папки после первого клика, отменяем таймер
        button.addEventListener('mouseleave', function() {
            if (clickCount === 1) {
                clearTimeout(clickTimer);
                clickCount = 0;
            }
        });
    });
});
</script>

<script>
// Глобальная функция для загрузки содержимого через AJAX
function loadPageContent(url, pushState = true) {
    // Показываем индикатор загрузки
    const loadingIndicator = document.createElement('div');
    loadingIndicator.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75';
    loadingIndicator.style.zIndex = '9999';
    loadingIndicator.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Загрузка...</span>
        </div>
    `;
    document.body.appendChild(loadingIndicator);
    
    // Выполняем AJAX запрос
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Ошибка сети');
        }
        return response.text();
    })
    .then(html => {
        // Создаем DOM из полученного HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Извлекаем основной контент
        const newContent = doc.querySelector('.container-fluid.py-3.py-md-4');
        
        if (newContent) {
            // Обновляем содержимое страницы
            const currentContent = document.querySelector('.container-fluid.py-3.py-md-4');
            currentContent.innerHTML = newContent.innerHTML;
            
            // Обновляем заголовок страницы, если он есть
            const pageTitle = doc.querySelector('title');
            if (pageTitle) {
                document.title = pageTitle.textContent;
            }
            
            // Обновляем URL в истории браузера, если требуется
            if (pushState) {
                window.history.pushState({path: url}, '', url);
                currentPage = url;
            }
            
            // Обновляем обработчики событий для нового содержимого
            setupEventHandlers();
            
            // Инициализируем ленивую подгрузку карточек
            initLazyLoading();
        }
    })
    .catch(error => {
        console.error('Ошибка при загрузке страницы:', error);
        // При ошибке просто перенаправляем пользователя
        window.location.href = url;
    })
    .finally(() => {
        // Удаляем индикатор загрузки
        document.body.removeChild(loadingIndicator);
    });
}

// Переменные для управления ленивой загрузкой
let currentPage = window.location.href;
let isLoading = false;
let currentOffset = 6; // Начнем со второй партии (первые 6 уже загружены)
let hasMoreCards = true; // Флаг наличия дополнительных карточек
let allCards = []; // Массив всех карточек

// Функция для инициализации ленивой загрузки карточек
function initLazyLoading() {
    // Получаем контейнер карточек
    const cardContainer = document.querySelector('.row.row-cols-sm-2.row-cols-md-2');
    if (!cardContainer) return;
    
    // Сохраняем все карточки и скрываем те, которые после 6-й
    allCards = Array.from(cardContainer.querySelectorAll('.col'));
    
    // Если карточек меньше или равно 6, нет необходимости в ленивой загрузке
    if (allCards.length <= 6) return;
    
    // Скрываем все карточки после 6-й
    for (let i = 6; i < allCards.length; i++) {
        allCards[i].style.display = 'none';
    }
    
    // Создаем и добавляем элемент-наблюдатель для бесконечной прокрутки
    const loaderElement = document.createElement('div');
    loaderElement.id = 'loadMoreTrigger';
    loaderElement.className = 'col-12 text-center p-4';
    loaderElement.innerHTML = `
        <button id="loadMoreBtn" class="btn btn-outline-primary">
            <i class="fa-solid fa-spinner me-2"></i>Загрузить ещё
        </button>
    `;
    cardContainer.appendChild(loaderElement);
    
    // Добавляем обработчик для кнопки "Загрузить ещё"
    document.getElementById('loadMoreBtn').addEventListener('click', loadMoreCards);
    
    // Создаем Intersection Observer для автоматической загрузки при прокрутке
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !isLoading && hasMoreCards) {
                loadMoreCards();
            }
        });
    }, { threshold: 0.5 });
    
    // Начинаем наблюдение за элементом-триггером
    observer.observe(loaderElement);
}

// Функция для загрузки дополнительных карточек
function loadMoreCards() {
    if (isLoading || !hasMoreCards) return;
    
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const loadMoreTrigger = document.getElementById('loadMoreTrigger');
    
    // Показываем индикатор загрузки
    loadMoreBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Загрузка...`;
    loadMoreBtn.disabled = true;
    isLoading = true;
    
    // Показываем следующие 6 карточек с анимацией
    setTimeout(() => {
        // Определяем количество карточек для показа (максимум 6)
        const endIndex = Math.min(currentOffset + 6, allCards.length);
        let shownCount = 0;
        
        // Показываем карточки по одной с задержкой
        const showNextCard = (index) => {
            if (index < endIndex) {
                allCards[index].style.display = '';
                // Анимация появления
                allCards[index].style.opacity = '0';
                allCards[index].style.transform = 'translateY(20px)';
                setTimeout(() => {
                    allCards[index].style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    allCards[index].style.opacity = '1';
                    allCards[index].style.transform = 'translateY(0)';
                }, 50);
                
                shownCount++;
                // Показываем следующую карточку через 100мс
                setTimeout(() => showNextCard(index + 1), 100);
            } else {
                // Обновляем состояние
                currentOffset = endIndex;
                isLoading = false;
                
                // Проверяем, остались ли еще карточки для загрузки
                if (currentOffset >= allCards.length) {
                    hasMoreCards = false;
                    loadMoreTrigger.remove(); // Удаляем триггер, когда все карточки загружены
                } else {
                    // Восстанавливаем кнопку
                    loadMoreBtn.innerHTML = `<i class="fa-solid fa-plus me-2"></i>Загрузить ещё`;
                    loadMoreBtn.disabled = false;
                    
                    // Показываем, сколько еще осталось
                    const remainingCards = allCards.length - currentOffset;
                    loadMoreBtn.innerHTML += ` <span class="badge bg-light text-dark">${remainingCards}</span>`;
                }
            }
        };
        
        // Начинаем показывать карточки
        showNextCard(currentOffset);
    }, 800); // Небольшая задержка для убедительного эффекта загрузки
}

// AJAX навигация без перезагрузки страницы
document.addEventListener('DOMContentLoaded', function() {    
    // Функция для настройки обработчиков событий
    window.setupEventHandlers = function() {
        // Добавляем обработчики для ссылок на папки
        document.querySelectorAll('.folder-navigation a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                loadPageContent(url);
            });
        });
        
        // Добавляем обработчики двойного клика для папок
        const folderButtons = document.querySelectorAll('.folder-btn');
        folderButtons.forEach(button => setupFolderClickHandler(button));
        
        // Обновляем обработчики для сертификатов
        setupCertificateHandlers();
        
        // Обновляем форму создания новой папки
        setupFolderFormHandlers();
    }
    
    // Функция настройки обработчика для клика на папку
    function setupFolderClickHandler(folder) {
        // Сохраняем данные папки
        const folderId = folder.dataset.folderId;
        const folderName = folder.dataset.folderName;
        const folderColor = folder.dataset.folderColor;
        
        let clickCount = 0;
        let clickTimer = null;
        
        // Добавляем обработчик клика
        folder.addEventListener('click', function(e) {
            // Если это первый клик, позволяем обычную навигацию
            if (clickCount === 0) {
                clickCount++;
                
                // Сбрасываем счетчик кликов через некоторое время
                clickTimer = setTimeout(() => {
                    clickCount = 0;
                }, 300); // 300мс для регистрации двойного клика
            } 
            // Если это второй клик (двойной клик)
            else if (clickCount === 1) {
                // Предотвращаем стандартное действие (переход по ссылке)
                e.preventDefault();
                
                // Очищаем таймер и сбрасываем счетчик
                clearTimeout(clickTimer);
                clickCount = 0;
                
                // Показываем тактильную обратную связь на мобильных устройствах
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }
                
                // Открываем модальное окно удаления папки
                const bsModal = new bootstrap.Modal(document.getElementById('deleteFolderModal'));
                
                // Устанавливаем данные папки для модального окна
                const folderNameElement = document.getElementById('folderNameToDelete');
                if (folderNameElement) {
                    folderNameElement.textContent = folderName;
                    folderNameElement.className = `fw-bold text-${folderColor || 'primary'}`;
                }
                
                // Обновляем форму удаления
                const deleteForm = document.getElementById('delete-folder-form');
                if (deleteForm) {
                    deleteForm.action = `/user/folders/${folderId}`;
                }
                
                // Показываем модальное окно
                bsModal.show();
            }
        });
        
        // Сбрасываем счетчик при уходе мыши с элемента
        folder.addEventListener('mouseout', function() {
            // Очищаем таймер при уходе с элемента, но не сбрасываем счетчик
            // чтобы не прерывать возможный двойной клик
            if (clickCount === 1) {
                clearTimeout(clickTimer);
                
                // Даем небольшую задержку перед сбросом счетчика
                setTimeout(() => {
                    clickCount = 0;
                }, 500);
            }
        });
    }
    
    // Настройка обработчиков событий для сертификатов
    function setupCertificateHandlers() {
        const certificateCards = document.querySelectorAll('.certificate-card');
        
        certificateCards.forEach(card => {
            // Сохраняем данные карточки
            const certificateId = card.dataset.certificateId;
            const publicUrl = card.dataset.publicUrl;
            const certificateNumber = card.dataset.certificateNumber;
            let clickTimer = null;
            let clickCount = 0;
            
            // Обработчик клика с задержкой для распознавания двойного клика
            card.addEventListener('click', function(e) {
                // Если клик был на кнопке или в выпадающем меню, не обрабатываем
                if (e.target.closest('.btn') || e.target.closest('.dropdown-menu')) {
                    return;
                }
                
                e.preventDefault(); // Предотвращаем любые стандартные действия
                
                clickCount++;
                
                if (clickCount === 1) {
                    clickTimer = setTimeout(function() {
                        clickCount = 0;
                        // Если это одиночный клик - открываем публичную страницу сертификата
                        window.open(publicUrl, '_blank');
                    }, 300);
                } else if (clickCount === 2) {
                    clearTimeout(clickTimer);
                    clickCount = 0;
                    // Если это двойной клик - показываем модальное окно управления папками
                    if (typeof handleCertificateAction === 'function') {
                        handleCertificateAction(certificateId, certificateNumber);
                    }
                    
                    // Используем безопасную функцию вибрации
                    window.safeVibrate && window.safeVibrate(100);
                }
            });
        });
    }
    
    // Настройка AJAX-отправки для формы создания папки
    function setupFolderFormHandlers() {
        const createFolderForm = document.getElementById('create-folder-form');
        
        if (createFolderForm) {
            createFolderForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Получаем данные формы
                const formData = new FormData(this);
                const folderName = formData.get('name');
                const folderColor = formData.get('color');
                
                // Получаем CSRF-токен и заголовки
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Создаем кнопку с индикатором загрузки
                const submitBtn = this.querySelector('[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Создание...';
                
                // Отправляем запрос
                fetch('/user/folders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: folderName,
                        color: folderColor,
                        _token: csrfToken
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Возвращаем кнопку в исходное состояние
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    if (data.success) {
                        // Закрываем модальное окно
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createFolderModal'));
                        modal.hide();
                        
                        // Добавляем новую папку в DOM
                        addFolderToDOM(data.folder);
                        
                        // Показываем уведомление
                        showToast(data.message, 'success');
                        
                        // Очищаем форму
                        createFolderForm.reset();
                    } else {
                        showToast(data.message || 'Ошибка при создании папки', 'danger');
                    }
                })
                .catch(error => {
                    // Возвращаем кнопку в исходное состояние
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    console.error('Ошибка при создании папки:', error);
                    showToast('Ошибка при создании папки', 'danger');
                });
            });
        }
    }
    
    // Функция для добавления новой папки в DOM
    function addFolderToDOM(folder) {
        const folderNavigation = document.querySelector('.folder-navigation .btn-group');
        
        if (folderNavigation) {
            // Проверяем, есть ли сообщение "Нет папок"
            const noFoldersMsg = folderNavigation.querySelector('.btn.disabled');
            if (noFoldersMsg) {
                noFoldersMsg.remove();
            }
            
            // Создаем новый элемент папки
            const folderBtn = document.createElement('a');
            folderBtn.href = `/user/certificates?folder=${folder.id}`;
            folderBtn.className = 'btn btn-sm btn-outline-secondary folder-btn';
            folderBtn.setAttribute('data-folder-id', folder.id);
            folderBtn.setAttribute('data-folder-name', folder.name);
            folderBtn.setAttribute('data-folder-color', folder.color);
            folderBtn.innerHTML = `<i class="fa-solid fa-folder me-1 text-${folder.color}"></i>${folder.name}`;
            
            // Добавляем папку в навигацию
            folderNavigation.appendChild(folderBtn);
            
            // Добавляем обработчик для новой папки
            setupFolderClickHandler(folderBtn);
        }
    }
    
    // AJAX-обработка формы удаления папки
    const deleteFolderForm = document.getElementById('delete-folder-form');
    if (deleteFolderForm) {
        deleteFolderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Получаем URL формы и ID папки
            const url = this.getAttribute('action');
            const folderId = url.split('/').pop();
            
            // Получаем CSRF-токен
            const csrfToken = document.querySelector('input[name="_token"]').value;
            
            // Создаем кнопку с индикатором загрузки
            const submitBtn = this.querySelector('[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Удаление...';
            
            // Отправляем запрос
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({_token: csrfToken})
            })
            .then(response => response.json())
            .then(data => {
                // Возвращаем кнопку в исходное состояние
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                if (data.success) {
                    // Закрываем модальное окно
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteFolderModal'));
                    modal.hide();
                    
                    // Показываем уведомление
                    showToast(data.message, 'success');
                    
                    // Удаляем папку из DOM
                    removeFolderFromDOM(folderId);
                    
                    // Если мы на странице этой папки, перенаправляем на главную
                    const urlParams = new URLSearchParams(window.location.search);
                    const currentFolder = urlParams.get('folder');
                    if (currentFolder === folderId) {
                        // Загружаем главную страницу через AJAX вместо перезагрузки
                        loadPageContent("<?php echo e(route('user.certificates.index')); ?>");
                    }
                } else {
                    showToast(data.message || 'Ошибка при удалении папки', 'danger');
                }
            })
            .catch(error => {
                // Возвращаем кнопку в исходное состояние
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                console.error('Ошибка при удалении папки:', error);
                showToast('Ошибка при удалении папки', 'danger');
            });
        });
    }
    
    // Функция для удаления папки из DOM
    function removeFolderFromDOM(folderId) {
        const folderBtn = document.querySelector(`.folder-btn[data-folder-id="${folderId}"]`);
        
        if (folderBtn) {
            folderBtn.remove();
            
            // Проверяем, остались ли папки
            const remainingFolders = document.querySelectorAll('.folder-btn');
            if (remainingFolders.length === 0) {
                // Если папок не осталось, добавляем сообщение "Нет папок"
                const folderNavigation = document.querySelector('.folder-navigation .btn-group');
                
                if (folderNavigation) {
                    const noFoldersSpan = document.createElement('span');
                    noFoldersSpan.className = 'btn btn-sm btn-outline-secondary disabled';
                    noFoldersSpan.innerHTML = '<i class="fa-solid fa-folder-open me-1"></i>Нет папок';
                    
                    folderNavigation.appendChild(noFoldersSpan);
                }
            }
        }
    }
    
    // Функция для отображения уведомлений
    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('copyToast');
        document.getElementById('toastMessage').textContent = message;
        
        // Устанавливаем цвет в зависимости от типа
        toastEl.className = 'toast align-items-center text-white border-0';
        toastEl.classList.add(`bg-${type}`);
        
        // Показываем уведомление
        const toast = new bootstrap.Toast(toastEl, {
            delay: 3000
        });
        toast.show();
    }
    
    // Инициализируем обработчики при загрузке страницы
    setupEventHandlers();
    
    // Инициализируем ленивую загрузку карточек
    initLazyLoading();
    
    // Обрабатываем навигацию по истории браузера (кнопки вперед/назад)
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.path) {
            loadPageContent(event.state.path, false);
        } else {
            loadPageContent(location.href, false);
        }
    });
    
    // Сохраняем начальное состояние в истории браузера
    window.history.replaceState({path: currentPage}, '', currentPage);
});
</script>

<style>
/* Стили для эффекта появления карточек */
.col {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Индикатор загрузки */
#loadMoreTrigger {
    margin: 20px 0;
    padding: 20px;
}

/* Анимация пульсации для кнопки загрузки */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

#loadMoreBtn:hover {
    animation: pulse 1.5s infinite;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views/user/certificates/index.blade.php ENDPATH**/ ?>