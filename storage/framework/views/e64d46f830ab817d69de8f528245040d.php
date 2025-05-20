

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3 py-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mb-md-4 gap-2">
        <h1 class="fw-bold fs-4 fs-md-3 mb-0">Мои сертификаты</h1>
        <a href="<?php echo e(route('entrepreneur.certificates.select-template')); ?>" class="btn btn-primary btn-sm btn-md-lg">
            <i class="fa-solid fa-plus me-1 me-md-2"></i>Создать сертификат
        </a>
    </div>

    <!-- Сообщения об успешных операциях -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- Фильтры -->
    <div class="card border-0 rounded-4 shadow-sm mb-3 mb-md-4">
        <div class="card-body p-2 p-sm-3">
            <form action="<?php echo e(route('entrepreneur.certificates.index')); ?>" method="GET" class="row g-2">
                <div class="col-12 col-md-4">
                    <label for="filter_status" class="form-label small mb-1">Статус</label>
                    <select id="filter_status" name="status" class="form-select form-select-sm">
                        <option value="">Все статусы</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Активные</option>
                        <option value="used" <?php echo e(request('status') == 'used' ? 'selected' : ''); ?>>Использованные</option>
                        <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Истекшие</option>
                        <option value="canceled" <?php echo e(request('status') == 'canceled' ? 'selected' : ''); ?>>Отмененные</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label for="filter_search" class="form-label small mb-1">Поиск</label>
                    <input type="text" id="filter_search" name="search" class="form-control form-control-sm" placeholder="Поиск по номеру или получателю" value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-12 col-md-4 d-flex align-items-end">
                    <div class="btn-group w-100 mt-1 mt-md-0">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-filter me-1 d-none d-sm-inline-block"></i>Применить
                        </button>
                        <a href="<?php echo e(route('entrepreneur.certificates.index')); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-xmark me-1 d-none d-sm-inline-block"></i>Сбросить
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Таблица сертификатов -->
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-2">Номер</th>
                            <th class="py-2">Получатель</th>
                            <th class="py-2 d-none d-md-table-cell">Шаблон</th>
                            <th class="py-2">Сумма</th>
                            <th class="py-2 d-none d-lg-table-cell">Срок действия</th>
                            <th class="py-2">Статус</th>
                            <th class="py-2 text-end">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="align-middle"><?php echo e($certificate->certificate_number); ?></td>
                                <td class="align-middle"><?php echo e($certificate->recipient_name); ?></td>
                                <td class="align-middle d-none d-md-table-cell"><?php echo e($certificate->template->name); ?></td>
                                <td class="align-middle"><?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?> ₽</td>
                                <td class="align-middle d-none d-lg-table-cell">
                                    <span class="small"><?php echo e($certificate->valid_from->format('d.m.Y')); ?> - <?php echo e($certificate->valid_until->format('d.m.Y')); ?></span>
                                </td>
                                <td class="align-middle">
                                    <?php if($certificate->status == 'active'): ?>
                                        <span class="badge bg-success">Активен</span>
                                    <?php elseif($certificate->status == 'used'): ?>
                                        <span class="badge bg-secondary">Использован</span>
                                    <?php elseif($certificate->status == 'expired'): ?>
                                        <span class="badge bg-warning">Истек</span>
                                    <?php elseif($certificate->status == 'canceled'): ?>
                                        <span class="badge bg-danger">Отменен</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('entrepreneur.certificates.show', $certificate)); ?>" class="btn btn-sm btn-outline-primary" title="Просмотр">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if($certificate->status == 'active'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-info d-none d-sm-inline-block" title="Копировать ссылку" 
                                                onclick="copyPublicUrl('<?php echo e(route('certificates.public', $certificate->uuid)); ?>', '<?php echo e($certificate->certificate_number); ?>')">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                        <a href="<?php echo e(route('entrepreneur.certificates.edit', $certificate)); ?>" class="btn btn-sm btn-outline-secondary d-none d-sm-inline-block" title="Редактировать">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger d-none d-sm-inline-block" title="Отменить" 
                                                onclick="if(confirm('Вы действительно хотите отменить этот сертификат?')) { document.getElementById('delete-certificate-<?php echo e($certificate->id); ?>').submit(); }">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                        <div class="dropdown d-inline-block d-sm-none">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <?php if($certificate->status == 'active'): ?>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="copyPublicUrl('<?php echo e(route('certificates.public', $certificate->uuid)); ?>', '<?php echo e($certificate->certificate_number); ?>'); return false;">
                                                        <i class="fa-solid fa-copy me-2"></i>Копировать ссылку
                                                    </a>
                                                </li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('entrepreneur.certificates.edit', $certificate)); ?>">Редактировать</a></li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)" 
                                                    onclick="if(confirm('Вы действительно хотите отменить этот сертификат?')) { document.getElementById('delete-certificate-<?php echo e($certificate->id); ?>').submit(); }">
                                                    Отменить</a>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        <form id="delete-certificate-<?php echo e($certificate->id); ?>" action="<?php echo e(route('entrepreneur.certificates.destroy', $certificate)); ?>" method="POST" class="d-none">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fa-solid fa-certificate text-muted fa-2x mb-3"></i>
                                        <h5 class="fs-6 fs-md-5 mb-2">У вас еще нет сертификатов</h5>
                                        <p class="text-muted small mb-3 mb-md-4">Создайте свой первый подарочный сертификат прямо сейчас!</p>
                                        <a href="<?php echo e(route('entrepreneur.certificates.select-template')); ?>" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-plus me-1"></i>Создать первый сертификат
                                        </a>
                                        
                                        <?php if(!Auth::user()->hasRole('user')): ?>
                                        <div class="alert alert-info mt-3">
                                            <i class="fa-solid fa-lightbulb me-2"></i>
                                            <strong>Совет:</strong> Вы также можете переключиться на режим клиента для просмотра полученных сертификатов
                                            <form action="<?php echo e(route('role.switch')); ?>" method="POST" class="mt-2 text-center">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="role" value="user">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-user me-2"></i>Переключиться на режим клиента
                                                </button>
                                            </form>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Пагинация -->
    <div class="mt-3 d-flex justify-content-center justify-content-md-start">
        <?php echo e($certificates->withQueryString()->links()); ?>

    </div>

    <?php if(count($certificates) > 0): ?>
    <!-- Краткая статистика -->
    <div class="row g-2 g-md-4 mt-3 mt-md-4">
        <div class="col-6 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm text-center h-100">
                <div class="card-body p-2 p-md-3 d-flex flex-column justify-content-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-2 p-md-3 mb-2 mb-md-3 mx-auto">
                        <i class="fa-solid fa-certificate text-primary"></i>
                    </div>
                    <h3 class="fs-5 fs-md-3"><?php echo e($certificates->total()); ?></h3>
                    <p class="text-muted mb-0 small">Всего сертификатов</p>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm text-center h-100">
                <div class="card-body p-2 p-md-3 d-flex flex-column justify-content-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 p-2 p-md-3 mb-2 mb-md-3 mx-auto">
                        <i class="fa-solid fa-check-circle text-success"></i>
                    </div>
                    <h3 class="fs-5 fs-md-3"><?php echo e($activeCount ?? 0); ?></h3>
                    <p class="text-muted mb-0 small">Активных сертификатов</p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4 mt-2 mt-md-0">
            <div class="card border-0 rounded-4 shadow-sm text-center h-100">
                <div class="card-body p-2 p-md-3 d-flex flex-column justify-content-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 p-2 p-md-3 mb-2 mb-md-3 mx-auto">
                        <i class="fa-solid fa-coins text-warning"></i>
                    </div>
                    <h3 class="fs-5 fs-md-3"><?php echo e(number_format($totalAmount ?? 0, 0, '.', ' ')); ?> ₽</h3>
                    <p class="text-muted mb-0 small">Общая сумма сертификатов</p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
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
// Функция для копирования публичной ссылки сертификата
function copyPublicUrl(url, certNumber) {
    // Проверяем доступность Clipboard API перед его использованием
    if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
        // Копируем текст в буфер обмена с помощью современного API
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
</script>

<style>
/* Адаптивные стили для таблицы */
@media (max-width: 575.98px) {
    .table th, .table td {
        padding: 0.5rem 0.25rem !important;
        font-size: 0.85rem;
    }
    
    .badge {
        padding: 0.25em 0.5em;
        font-size: 0.7em;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .pagination {
        font-size: 0.85rem;
    }
    
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
    }
}

/* Стиль для мобильных кнопок действий */
.dropdown-toggle::after {
    display: none;
}

/* Улучшенный стиль для пагинации */
.pagination {
    justify-content: center;
}

/* Стиль для маленьких круглых иконок в карточках */
@media (max-width: 767.98px) {
    .card .fa-certificate, 
    .card .fa-check-circle,
    .card .fa-coins {
        font-size: 0.875rem;
    }
    
    .card .rounded-circle {
        width: 2.25rem;
        height: 2.25rem;
    }
}

@media (min-width: 768px) {
    .card .fa-certificate, 
    .card .fa-check-circle,
    .card .fa-coins {
        font-size: 1.25rem;
    }
    
    .card .rounded-circle {
        width: 3rem;
        height: 3rem;
    }
}

/* Улучшение для карточек на мобильных */
@media (max-width: 767.98px) {
    .row.g-2 .card {
        margin-bottom: 0 !important;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views/entrepreneur/certificates/index.blade.php ENDPATH**/ ?>