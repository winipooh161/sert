

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3 py-md-4">
    <div class="d-flex flex-column justify-content-between mb-3 mb-md-4">
        <h1 class="fw-bold fs-4 fs-md-3 mb-2">Выберите шаблон для сертификата</h1>
        <p class="lead fs-6 fs-md-5 mb-0">Выберите дизайн для вашего подарочного сертификата</p>
    </div>
    
    <!-- Добавляем табы для категорий - адаптивная версия -->
    <div class="card border-0 rounded-4 shadow-sm mb-3 mb-md-4">
        <div class="card-body p-2 p-sm-3">
            <div class="templates-tabs-wrapper">
                <ul class="nav nav-tabs template-tabs flex-nowrap overflow-x-auto" id="templateCategories" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" 
                                role="tab" aria-controls="all" aria-selected="true">Все шаблоны</button>
                    </li>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="category-<?php echo e($category->id); ?>-tab" data-bs-toggle="tab" 
                                data-bs-target="#category-<?php echo e($category->id); ?>" type="button" role="tab" 
                                aria-controls="category-<?php echo e($category->id); ?>" aria-selected="false"><?php echo e($category->name); ?></button>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="tab-content">
        <!-- Таб для всех шаблонов -->
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            <div class="row g-2 g-md-3 g-lg-4">
                <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card template-card shadow-sm h-100">
                            <div class="card-img-wrapper">
                                <?php if($template->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $template->image)); ?>" class="card-img-top" alt="<?php echo e($template->name); ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="fa-solid fa-image text-muted fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($template->is_premium): ?>
                                    <span class="badge badge-premium">Премиум</span>
                                <?php endif; ?>
                                
                                <div class="template-overlay d-flex align-items-center justify-content-center">
                                    <a href="#" class="btn btn-sm btn-light me-2" data-bs-toggle="modal" data-bs-target="#previewModal-<?php echo e($template->id); ?>">
                                        <i class="fa-solid fa-eye me-1"></i><span class="d-none d-sm-inline">Предпросмотр</span>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="card-body p-2 p-sm-3 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold mb-0 fs-6"><?php echo e($template->name); ?></h5>
                                    <span class="badge bg-info text-white small"><?php echo e($template->category->name); ?></span>
                                </div>
                                <p class="card-text text-muted flex-grow-1 small"><?php echo e(Str::limit($template->description, 80)); ?></p>
                                <a href="<?php echo e(route('entrepreneur.certificates.create', $template)); ?>" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus me-1 me-sm-2"></i>Выбрать этот шаблон
                                </a>
                            </div>
                        </div>
                        
                        <!-- Модальное окно с предпросмотром - улучшено для мобильных -->
                        <div class="modal fade" id="previewModal-<?php echo e($template->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-md-down">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fs-6">Предпросмотр "<?php echo e($template->name); ?>"</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0 p-sm-2">
                                        <div class="preview-container border rounded p-0">
                                            <iframe src="<?php echo e(route('template.preview', $template)); ?>" class="template-preview-iframe" frameborder="0" width="100%" loading="lazy"></iframe>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                        <a href="<?php echo e(route('entrepreneur.certificates.create', $template)); ?>" class="btn btn-sm btn-primary">
                                            Выбрать шаблон
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center p-4">
                            <i class="fa-solid fa-info-circle fa-2x mb-3"></i>
                            <h5 class="fs-6 fs-md-5">Шаблоны сертификатов отсутствуют</h5>
                            <p class="mb-0 small">Пожалуйста, свяжитесь с администратором для добавления шаблонов.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Табы для отдельных категорий -->
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="tab-pane fade" id="category-<?php echo e($category->id); ?>" role="tabpanel" aria-labelledby="category-<?php echo e($category->id); ?>-tab">
            <div class="row g-2 g-md-3 g-lg-4">
                <?php $templatesInCategory = $templates->where('category_id', $category->id); ?>
                <?php $__empty_1 = true; $__currentLoopData = $templatesInCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card template-card shadow-sm h-100">
                            <div class="card-img-wrapper">
                                <?php if($template->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $template->image)); ?>" class="card-img-top" alt="<?php echo e($template->name); ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="fa-solid fa-image text-muted fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($template->is_premium): ?>
                                    <span class="badge badge-premium">Премиум</span>
                                <?php endif; ?>
                                
                                <div class="template-overlay d-flex align-items-center justify-content-center">
                                    <a href="#" class="btn btn-sm btn-light me-2" data-bs-toggle="modal" data-bs-target="#previewModal-<?php echo e($template->id); ?>">
                                        <i class="fa-solid fa-eye me-1"></i><span class="d-none d-sm-inline">Предпросмотр</span>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="card-body p-2 p-sm-3 d-flex flex-column">
                                <h5 class="card-title fw-bold fs-6"><?php echo e($template->name); ?></h5>
                                <p class="card-text text-muted flex-grow-1 small"><?php echo e(Str::limit($template->description, 80)); ?></p>
                                <a href="<?php echo e(route('entrepreneur.certificates.create', $template)); ?>" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus me-1 me-sm-2"></i>Выбрать этот шаблон
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center p-3 p-md-4">
                            <i class="fa-solid fa-info-circle fa-2x mb-3"></i>
                            <h5 class="fs-6 fs-md-5">В этой категории нет шаблонов</h5>
                            <p class="mb-0 small">Выберите другую категорию или обратитесь к администратору.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<style>
/* Адаптивные стили для страницы выбора шаблонов */

/* Улучшенная навигация по категориям */
.templates-tabs-wrapper {
    position: relative;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.template-tabs {
    flex-wrap: nowrap;
    width: max-content;
    min-width: 100%;
}

.template-tabs::-webkit-scrollbar {
    height: 3px;
}

.template-tabs::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.2);
    border-radius: 3px;
}

.template-tabs .nav-link {
    white-space: nowrap;
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
}

/* Адаптивные стили для карточек шаблонов */
.template-card {
    border-radius: 0.75rem;
}

.template-card .card-img-wrapper {
    padding-top: 60%; /* Более компактное соотношение сторон для мобильных */
}

.badge-premium {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
}

/* Модальное окно предпросмотра */
.template-preview-iframe {
    width: 100%;
    height: 50vh;
    min-height: 300px;
    border: none;
    overflow: auto;
}

/* Адаптивные стили для различных размеров экрана */
@media (max-width: 767.98px) {
    .template-card .card-img-wrapper {
        padding-top: 50%; /* Более широкое соотношение для лучшей видимости на мобильных */
    }
    
    .card-title {
        font-size: 1rem;
    }
    
    .template-card .card-body {
        padding: 0.75rem;
    }
    
    .template-overlay .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

@media (min-width: 768px) {
    .template-card .card-img-wrapper {
        padding-top: 66.66%; /* Возвращаем к соотношению 3:2 на более широких экранах */
    }
}

/* Улучшенный стиль для вкладок категорий */
.nav-tabs .nav-link {
    color: #6c757d;
    border-radius: 0;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
    font-weight: 600;
}

/* Фикс для мобильных модальных окон */
@media (max-width: 767.98px) {
    .modal-fullscreen-md-down {
        margin: 0;
    }
    
    .modal-fullscreen-md-down .modal-content {
        border: none;
        border-radius: 0;
        min-height: 100vh;
    }
    
    .modal-fullscreen-md-down .template-preview-iframe {
        height: calc(100vh - 120px);
    }
    
    /* Более компактные кнопки в футере модального окна */
    .modal-footer .btn {
        padding: 0.25rem 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация табов Bootstrap с прокруткой к активному
    var triggerTabList = [].slice.call(document.querySelectorAll('#templateCategories button'));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
            
            // Прокручиваем tab в видимую область при клике
            setTimeout(() => {
                triggerEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }, 150);
        });
    });
    
    // Настройка iframe для подходящей высоты
    function adjustIframeHeight() {
        const iframes = document.querySelectorAll('.template-preview-iframe');
        const isMobile = window.innerWidth < 768;
        
        iframes.forEach(iframe => {
            iframe.style.height = isMobile ? 'calc(100vh - 120px)' : '500px';
        });
    }
    
    // Вызываем при загрузке и изменении размера окна
    window.addEventListener('resize', adjustIframeHeight);
    adjustIframeHeight();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views\entrepreneur\certificates\select-template.blade.php ENDPATH**/ ?>