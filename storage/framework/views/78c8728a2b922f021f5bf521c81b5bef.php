

<?php $__env->startSection('content'); ?>
<div class="certificate-editor">
    <div class="editor-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between py-2 py-sm-3">
                <h1 class="h4 h5-sm fw-bold mb-0">Создание сертификата</h1>
                <a href="<?php echo e(route('entrepreneur.certificates.select-template')); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1 me-sm-2"></i><span class="d-none d-sm-inline">Вернуться к шаблонам</span>
                </a>
            </div>
        </div>
    </div>
    
    <div class="editor-body">
        <div class="container-fluid">
            <div class="row">
                <!-- Визуальный предпросмотр сертификата - на мобильных перемещаем в начало -->
                <div class="col-lg-9 order-lg-1 order-2">
                    <?php echo $__env->make('entrepreneur.certificates.partials.certificate_preview', ['template' => $template], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
              
                <!-- Форма редактирования сертификата - десктопная версия -->
                <div class="col-lg-3 order-1 order-lg-2 mt-3 mt-lg-0 d-none d-lg-block">
                    <?php echo $__env->make('entrepreneur.certificates.partials.certificate_form', ['template' => $template], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                
                <!-- Мобильная версия в формате квиза - показывается только на мобильных -->
                <div class="col-12 order-1 d-block d-lg-none mt-3">
                    <?php echo $__env->make('entrepreneur.certificates.partials.certificate_quiz', ['template' => $template], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно выбора анимационных эффектов -->
<?php echo $__env->make('entrepreneur.certificates.partials.animation_effects_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Стили для страницы -->
<?php $__env->startPush('styles'); ?>
    <?php echo $__env->make('entrepreneur.certificates.partials.certificate_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<!-- Скрипты для страницы -->
<?php $__env->startPush('scripts'); ?>
    <?php echo $__env->make('entrepreneur.certificates.partials.certificate_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<!-- Добавляем скрипт для подготовки форм перед отправкой -->
<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Определение мобильного устройства
    const isMobile = window.innerWidth < 992;

    // Безопасная функция для добавления обработчика событий
    function safeAddEventListener(elementId, eventType, handler) {
        const element = document.getElementById(elementId);
        if (element) {
            element.addEventListener(eventType, handler);
        }
    }

    // Добавляем обработчики на обе формы
    safeAddEventListener('desktopCertificateForm', 'submit', function(e) {
        // При отправке десктопной формы отключаем required у полей мобильной формы
        if (!isMobile) {
            const mobileForm = document.getElementById('mobileCertificateForm');
            if (mobileForm) {
                const mobileRequiredFields = mobileForm.querySelectorAll('[required]');
                mobileRequiredFields.forEach(field => {
                    field.removeAttribute('required');
                });
            }
        }
    });
    
    safeAddEventListener('mobileCertificateForm', 'submit', function(e) {
        // При отправке мобильной формы отключаем required у полей десктопной формы
        if (isMobile) {
            const desktopForm = document.getElementById('desktopCertificateForm');
            if (desktopForm) {
                const desktopRequiredFields = desktopForm.querySelectorAll('[required]');
                desktopRequiredFields.forEach(field => {
                    field.removeAttribute('required');
                });
            }
        }
    });
    
    // Исправление для iframe и ошибки "companyLogoElements.includes is not a function"
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'logo_elements_check') {
            // Отправляем безопасную версию обработчика обратно в iframe
            try {
                const iframe = document.getElementById('certificatePreview');
                if (iframe && iframe.contentWindow === event.source) {
                    iframe.contentWindow.postMessage({
                        type: 'logo_elements_fix',
                        message: 'Используйте Array.from для NodeList'
                    }, '*');
                }
            } catch (error) {
                console.error('Ошибка при обработке сообщения от iframe:', error);
            }
        } else if (event.data && event.data.type === 'iframe_ready') {
            // Iframe загружен и исправления применены
            console.log('Iframe ready:', event.data.message);
            
            // Обновляем предпросмотр, так как iframe полностью готов
            if (window.updatePreview && typeof window.updatePreview === 'function') {
                window.updatePreview();
            }
        }
    });
    
    // Инициализация форм при загрузке страницы
    window.addEventListener('load', function() {
        // Запускаем обновление предпросмотра после полной загрузки страницы
        setTimeout(() => {
            if (window.updatePreview && typeof window.updatePreview === 'function') {
                window.updatePreview();
            }
            
            // Обеспечиваем синхронизацию между предпросмотрами на разных устройствах
            const desktopPreview = document.getElementById('desktopCertificatePreview');
            const mobilePreview = document.getElementById('certificatePreview');
            
            if (desktopPreview && mobilePreview) {
                // Синхронизируем src между iframe предпросмотров
                mobilePreview.addEventListener('load', function() {
                    if (desktopPreview.src !== this.src) {
                        desktopPreview.src = this.src;
                    }
                });
                
                desktopPreview.addEventListener('load', function() {
                    if (mobilePreview.src !== this.src) {
                        mobilePreview.src = this.src;
                    }
                });
            }
        }, 500);
    });
    
    // Добавляем обработчики для всех полей формы, чтобы сразу обновлять предпросмотр
    const allFormInputs = document.querySelectorAll('#desktopCertificateForm input, #desktopCertificateForm select, #desktopCertificateForm textarea, #mobileCertificateForm input, #mobileCertificateForm select, #mobileCertificateForm textarea');
    
    allFormInputs.forEach(input => {
        input.addEventListener('change', function() {
            console.log(`Поле ${this.name} изменено: ${this.value}`);
            
            // Синхронизируем данные между формами
            const otherForm = this.closest('form').id === 'desktopCertificateForm' 
                ? document.getElementById('mobileCertificateForm')
                : document.getElementById('desktopCertificateForm');
            
            if (otherForm) {
                const correspondingInput = otherForm.querySelector(`[name="${this.name}"]`);
                if (correspondingInput && correspondingInput !== this) {
                    if (this.type === 'checkbox' || this.type === 'radio') {
                        correspondingInput.checked = this.checked;
                    } else if (this.type !== 'file') { // Не синхронизируем файлы
                        correspondingInput.value = this.value;
                    }
                }
            }
            
            // Принудительно вызываем обновление предпросмотра
            if (window.updatePreview && typeof window.updatePreview === 'function') {
                window.updatePreview();
            }
        });
    });
    
    // Запускаем обновление предпросмотра при загрузке страницы
    window.addEventListener('load', function() {
        setTimeout(() => {
            if (window.updatePreview && typeof window.updatePreview === 'function') {
                console.log('Обновление предпросмотра при загрузке страницы...');
                window.updatePreview();
            }
        }, 500);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views/entrepreneur/certificates/create.blade.php ENDPATH**/ ?>