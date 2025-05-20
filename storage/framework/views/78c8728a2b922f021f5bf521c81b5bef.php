

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
                <!-- Форма редактирования сертификата - колонка будет полной шириной на мобильных -->
                <div class="col-lg-3 order-2 order-lg-1 mt-3 mt-lg-0">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-0 fs-6">Параметры сертификата</h5>
                                <span class="badge bg-primary-subtle text-primary"><?php echo e($template->name); ?></span>
                            </div>
                            
                            <!-- Добавляем систему вкладок -->
                            <ul class="nav nav-tabs card-header-tabs" id="certificateTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main-tab-content" 
                                            type="button" role="tab" aria-controls="main-tab-content" aria-selected="true">
                                        <i class="fa-solid fa-info-circle me-1"></i><span class="d-none d-md-inline">Основное</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="visual-tab" data-bs-toggle="tab" data-bs-target="#visual-tab-content" 
                                            type="button" role="tab" aria-controls="visual-tab-content" aria-selected="false">
                                        <i class="fa-solid fa-image me-1"></i><span class="d-none d-md-inline">Визуал</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced-tab-content" 
                                            type="button" role="tab" aria-controls="advanced-tab-content" aria-selected="false">
                                        <i class="fa-solid fa-sliders me-1"></i><span class="d-none d-md-inline">Ещё</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('entrepreneur.certificates.store', $template)); ?>" id="certificateForm" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                
                                <!-- Контент вкладок -->
                                <div class="tab-content" id="certificateTabsContent">
                                    <!-- Вкладка с основной информацией -->
                                    <div class="tab-pane fade show active" id="main-tab-content" role="tabpanel" aria-labelledby="main-tab">
                                        <!-- Основные параметры сертификата -->
                                        <div class="mb-2 mb-sm-3">
                                            <label for="amount" class="form-label small fw-bold">Номинал сертификата *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-sm <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                    id="amount" name="amount" value="<?php echo e(old('amount', 3000)); ?>" min="100" step="100" required>
                                                <span class="input-group-text small">₽</span>
                                            </div>
                                            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="form-text small">Введите сумму номинала сертификата</div>
                                        </div>
                                        
                                        <div class="mb-2 mb-sm-3">
                                            <label for="valid_until" class="form-label small fw-bold">Срок действия *</label>
                                            <input type="date" class="form-control form-control-sm <?php $__errorArgs = ['valid_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="valid_until" name="valid_until" 
                                                value="<?php echo e(old('valid_until', now()->addMonths(3)->format('Y-m-d'))); ?>" 
                                                min="<?php echo e(now()->format('Y-m-d')); ?>" required>
                                            <?php $__errorArgs = ['valid_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="form-text small">Сертификат будет действителен до указанной даты</div>
                                        </div>
                                        
                                        <input type="hidden" name="valid_from" id="valid_from" value="<?php echo e(now()->format('Y-m-d')); ?>">
                                        
                                        <!-- Информация о получателе -->
                                        <div class="mb-2 mb-sm-3">
                                            <label for="recipient_name" class="form-label small fw-bold">Имя получателя *</label>
                                            <input type="text" class="form-control form-control-sm <?php $__errorArgs = ['recipient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="recipient_name" name="recipient_name" value="<?php echo e(old('recipient_name')); ?>" required>
                                            <?php $__errorArgs = ['recipient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="form-text small">Укажите имя человека, который получит сертификат</div>
                                        </div>
                                        
                                        <div class="mb-2 mb-sm-3">
                                            <label for="recipient_phone" class="form-label small fw-bold">Телефон получателя *</label>
                                            <input type="tel" class="form-control maskphone form-control-sm <?php $__errorArgs = ['recipient_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="recipient_phone" name="recipient_phone" value="<?php echo e(old('recipient_phone')); ?>" required>
                                            <?php $__errorArgs = ['recipient_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="form-text small">Номер телефона для идентификации получателя</div>
                                        </div>
                                        
                                        <div class="mb-2 mb-sm-3">
                                            <label for="recipient_email" class="form-label small fw-bold">Email получателя</label>
                                            <input type="email" class="form-control form-control-sm <?php $__errorArgs = ['recipient_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="recipient_email" name="recipient_email" value="<?php echo e(old('recipient_email')); ?>">
                                            <?php $__errorArgs = ['recipient_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="form-text small">Необязательно. Для отправки сертификата по email</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Вкладка с визуальными настройками -->
                                    <div class="tab-pane fade" id="visual-tab-content" role="tabpanel" aria-labelledby="visual-tab">
                                        <!-- Обложка сертификата -->
                                        <div class="mb-2 mb-sm-3">
                                            <label for="cover_image" class="form-label small fw-bold">Обложка сертификата *</label>
                                            <input type="file" class="form-control form-control-sm <?php $__errorArgs = ['cover_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="cover_image" name="cover_image" accept="image/*" required>
                                            <?php $__errorArgs = ['cover_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="form-text small">Загрузите изображение для карточки сертификата. Рекомендуемый размер: 500x300px</div>
                                            
                                            <div id="cover_image_preview" class="mt-2 text-center"></div>
                                        </div>
                                        
                                        <!-- Логотип компании -->
                                        <div class="mb-2 mb-sm-3">
                                            <label for="logo" class="form-label small fw-bold">Логотип компании</label>
                                            <div class="mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="logo_type" id="logo_default" value="default" checked>
                                                    <label class="form-check-label small" for="logo_default">
                                                        Использовать из профиля
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="logo_type" id="logo_custom" value="custom">
                                                    <label class="form-check-label small" for="logo_custom">
                                                        Загрузить новый
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="logo_type" id="logo_none" value="none">
                                                    <label class="form-check-label small" for="logo_none">
                                                        Не использовать логотип
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div id="default_logo_preview" class="mb-2 text-center p-2 border rounded">
                                                <img src="<?php echo e(Auth::user()->company_logo ? asset('storage/' . Auth::user()->company_logo) : asset('images/default-logo.png')); ?>" 
                                                    class="img-thumbnail" style="max-height: 60px;" alt="Текущий логотип">
                                                <div class="small text-muted mt-1 fs-7">Текущий логотип</div>
                                            </div>
                                            
                                            <div id="custom_logo_container" class="d-none">
                                                <input type="file" class="form-control form-control-sm <?php $__errorArgs = ['custom_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                    id="custom_logo" name="custom_logo" accept="image/*">
                                                <div class="form-text small">Рекомендуемый размер: 300x100px, PNG или JPG</div>
                                                
                                                <div id="custom_logo_preview" class="mt-2 text-center"></div>
                                            </div>
                                            
                                            <?php $__errorArgs = ['custom_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Вкладка с дополнительными настройками -->
                                    <div class="tab-pane fade" id="advanced-tab-content" role="tabpanel" aria-labelledby="advanced-tab">
                                        <!-- Сообщение -->
                                        <div class="mb-2 mb-sm-3">
                                            <label for="message" class="form-label small fw-bold">Сообщение или пожелание</label>
                                            <textarea class="form-control form-control-sm <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="message" name="message" rows="3"><?php echo e(old('message')); ?></textarea>
                                            <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="form-text small">Добавьте персональное сообщение или пожелание для получателя</div>
                                        </div>
                                        
                                        <!-- Добавляем выбор анимационного эффекта -->
                                        <div class="mb-2 mb-sm-3">
                                            <label for="animation_effect_id" class="form-label small fw-bold">Анимационный эффект</label>
                                            <div class="input-group">
                                                <input type="hidden" name="animation_effect_id" id="animation_effect_id" value="<?php echo e(old('animation_effect_id')); ?>">
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
                                    </div>
                                </div>

                                <!-- Кнопки управления формой -->
                                <div class="d-grid gap-1 gap-sm-2 mt-3 pt-2 border-top">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-plus me-1 me-sm-2"></i>Создать сертификат
                                    </button>
                                    <a href="<?php echo e(route('entrepreneur.certificates.select-template')); ?>" class="btn btn-outline-secondary btn-sm">
                                        Отмена
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Визуальный предпросмотр сертификата - колонка первая на мобильных -->
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-transparent border-0 pt-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                            <div class="d-flex align-items-center mb-2 mb-sm-0">
                                <h5 class="fw-bold mb-0 me-2 fs-6">Предпросмотр</h5>
                                <span class="badge bg-primary-subtle text-primary small"><?php echo e($template->name); ?></span>
                            </div>
                            <div class="device-toggle btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary active" data-device="desktop">
                                    <i class="fa-solid fa-desktop"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-device="tablet">
                                    <i class="fa-solid fa-tablet-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-device="mobile">
                                    <i class="fa-solid fa-mobile-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-2 p-sm-3">
                            <div class="alert alert-info mb-2 mb-sm-3 py-2 small">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Заполните форму слева, чтобы увидеть изменения в сертификате
                            </div>
                            <div class="certificate-preview-container" data-current-device="desktop">
                                <div class="certificate-preview-wrapper device-frame">
                                    <iframe id="certificatePreview" src="<?php echo e(route('template.preview', $template)); ?>" class="certificate-preview" frameborder="0" loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3 text-center">
                            <div class="btn-toolbar justify-content-center">
                                <div class="btn-group me-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="zoomInButton">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary" id="zoomOutButton">
                                        <i class="fa-solid fa-magnifying-glass-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="resetZoomButton">
                                        <i class="fa-solid fa-arrows-to-circle"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="rotateViewButton">
                                        <i class="fa-solid fa-rotate"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Модальное окно выбора анимационных эффектов -->
<div class="modal fade" id="animationEffectsModal" tabindex="-1" aria-labelledby="animationEffectsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="animationEffectsModalLabel">Выбор анимационного эффекта</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3" id="effectsList">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Загрузка...</span>
                        </div>
                        <p class="mt-2">Загрузка доступных эффектов...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-sm btn-primary" id="selectEffectButton" disabled data-bs-dismiss="modal">Выбрать эффект</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Маска для телефона (существующий код)
    var inputs = document.querySelectorAll("input.maskphone");
    for (var i = 0; i < inputs.length; i++) {
        var input = inputs[i];
        input.addEventListener("input", mask);
        input.addEventListener("focus", mask);
        input.addEventListener("blur", mask);
    }
    function mask(event) {
        var blank = "+_ (___) ___-__-__";
        var i = 0;
        var val = this.value.replace(/\D/g, "").replace(/^8/, "7").replace(/^9/, "79");
        this.value = blank.replace(/./g, function (char) {
            if (/[_\d]/.test(char) && i < val.length) return val.charAt(i++);
            return i >= val.length ? "" : char;
        });
        if (event.type == "blur") {
            if (this.value.length == 2) this.value = "";
        } else {
            setCursorPosition(this, this.value.length);
        }
    }
    function setCursorPosition(elem, pos) {
        elem.focus();
        if (elem.setSelectionRange) {
            elem.setSelectionRange(pos, pos);
            return;
        }
        if (elem.createTextRange) {
            var range = elem.createTextRange();
            range.collapse(true);
            range.moveEnd("character", pos);
            range.moveStart("character", pos);
            range.select();
            return;
        }
    }
    
    // Инициализируем вкладки
    const tabButtons = document.querySelectorAll('#certificateTabs .nav-link');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // При переключении вкладок обновляем предпросмотр для отображения актуальных данных
            setTimeout(updatePreview, 50);
        });
    });
    
    // Элементы DOM для предпросмотра
    const previewFrame = document.getElementById('certificatePreview');
    const formInputs = document.querySelectorAll('#certificateForm input, #certificateForm textarea');
    const previewContainer = document.querySelector('.certificate-preview-container');
    let scale = 1;
    let logoUrl = '<?php echo e(Auth::user()->company_logo ? asset('storage/' . Auth::user()->company_logo) : asset('images/default-logo.png')); ?>';
    
    // Переключение типа логотипа
    const logoDefault = document.getElementById('logo_default');
    const logoCustom = document.getElementById('logo_custom');
    const logoNone = document.getElementById('logo_none');
    const defaultLogoPreview = document.getElementById('default_logo_preview');
    const customLogoContainer = document.getElementById('custom_logo_container');
    const customLogoInput = document.getElementById('custom_logo');
    const customLogoPreview = document.getElementById('custom_logo_preview');
    
    logoDefault.addEventListener('change', function() {
        if (this.checked) {
            defaultLogoPreview.classList.remove('d-none');
            customLogoContainer.classList.add('d-none');
            logoUrl = '<?php echo e(Auth::user()->company_logo ? asset('storage/' . Auth::user()->company_logo) : asset('images/default-logo.png')); ?>';
            console.log("Установлен логотип по умолчанию:", logoUrl);
            updatePreview();
        }
    });
    
    logoCustom.addEventListener('change', function() {
        if (this.checked) {
            defaultLogoPreview.classList.add('d-none');
            customLogoContainer.classList.remove('d-none');
            // Если уже есть загруженный пользовательский логотип
            if (customLogoPreview.querySelector('img')) {
                logoUrl = customLogoPreview.querySelector('img').src;
                console.log("Установлен пользовательский логотип:", logoUrl);
                updatePreview();
            }
        }
    });
    
    // Предпросмотр загруженного логотипа
    customLogoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const tempLogoUrl = e.target.result;
                
                customLogoPreview.innerHTML = `
                    <img src="${tempLogoUrl}" class="img-thumbnail" style="max-height: 60px;" alt="Загруженный логотип">
                    <div class="small text-muted mt-1">Новый логотип</div>
                `;
                
                // Сразу обновляем логотип в предпросмотре с временным локальным URL
                logoUrl = tempLogoUrl;
                updatePreview();
                
                // Отправляем файл на сервер для временного хранения
                const formData = new FormData();
                formData.append('logo', customLogoInput.files[0]);
                formData.append('_token', '<?php echo e(csrf_token()); ?>');
                
                console.log("Отправка логотипа на сервер...");
                
                fetch('<?php echo e(route('entrepreneur.certificates.temp-logo')); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Сохраняем URL логотипа с сервера
                        logoUrl = data.logo_url;
                        console.log("Логотип успешно загружен на сервер:", logoUrl);
                        // Обновляем превью с серверным URL логотипа
                        updatePreview();
                    } else {
                        console.error('Ошибка загрузки логотипа:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Произошла ошибка:', error);
                });
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Улучшенная функция обновления предпросмотра
    const updatePreview = () => {
        // Получаем значения из полей формы
        const recipientName = document.getElementById('recipient_name').value || 'Имя получателя';
        const amount = document.getElementById('amount').value || '3000';
        const message = document.getElementById('message').value || '';
        
        // Устанавливаем текущую дату и срок действия
        const validFrom = document.getElementById('valid_from').value 
            ? new Date(document.getElementById('valid_from').value).toLocaleDateString('ru-RU')
            : new Date().toLocaleDateString('ru-RU');
            
        const validUntil = document.getElementById('valid_until').value 
            ? new Date(document.getElementById('valid_until').value).toLocaleDateString('ru-RU')
            : new Date(Date.now() + 90*24*60*60*1000).toLocaleDateString('ru-RU');
        
        // Компания
        const companyName = '<?php echo e(Auth::user()->company ?? config('app.name')); ?>';
        
        // Создаем параметры для запроса - БЕЗ логотипа
        const params = new URLSearchParams({
            recipient_name: recipientName,
            amount: `${Number(amount).toLocaleString('ru-RU')} ₽`,
            valid_from: validFrom,
            valid_until: validUntil,
            message: message,
            certificate_number: 'CERT-PREVIEW',
            company_name: companyName
        });
        
        // Обновляем iframe с новыми параметрами
        const iframeSrc = `<?php echo e(route('template.preview', $template)); ?>?${params.toString()}`;
        
        // Проверяем, нужно ли обновлять iframe
        if (previewFrame.src.split('?')[0] === iframeSrc.split('?')[0]) {
            // Только обновляем параметры для существующего iframe
            previewFrame.src = iframeSrc;
        } else {
            // Полностью меняем src, если изменился базовый URL
            previewFrame.src = iframeSrc;
        }
        
        // После загрузки iframe отправляем логотип через postMessage
        previewFrame.onload = function() {
            // Оптимизированная отправка логотипа
            setTimeout(() => {
                try {
                    previewFrame.contentWindow.postMessage({
                        type: 'update_logo',
                        logo_url: logoUrl
                    }, '*');
                } catch (error) {
                    console.error("Ошибка при отправке сообщения в iframe:", error);
                }
            }, 300);
        };
    };
    
    // Устанавливаем обработчики событий для полей ввода с троттлингом
    let updateTimeout;
    formInputs.forEach(input => {
        ['input', 'change', 'keyup', 'paste'].forEach(eventType => {
            input.addEventListener(eventType, function() {
                if (input.id !== 'custom_logo') {
                    clearTimeout(updateTimeout);
                    updateTimeout = setTimeout(updatePreview, 300); // Задержка для улучшения производительности
                }
            });
        });
    });
    
    // Управление масштабом предпросмотра с адаптивным шагом
    document.getElementById('zoomInButton').addEventListener('click', function() {
        const zoomStep = window.innerWidth < 768 ? 1.05 : 1.1;
        scale *= zoomStep;
        previewFrame.style.transform = `scale(${scale})`;
    });
    
    document.getElementById('zoomOutButton').addEventListener('click', function() {
        const zoomStep = window.innerWidth < 768 ? 0.95 : 0.9;
        scale *= zoomStep;
        previewFrame.style.transform = `scale(${scale})`;
    });
    
    document.getElementById('resetZoomButton').addEventListener('click', function() {
        scale = 1;
        previewFrame.style.transform = 'scale(1)';
    });
    
    // Переключение между устройствами с учетом размера экрана
    const deviceButtons = document.querySelectorAll('.device-toggle button');
    deviceButtons.forEach(button => {
        button.addEventListener('click', function() {
            deviceButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const device = this.getAttribute('data-device');
            previewContainer.setAttribute('data-current-device', device);
            
            // Автоматически сбрасываем масштаб при переключении устройства
            scale = 1;
            previewFrame.style.transform = 'scale(1)';
            
            // Для мобильных устройств, если выбран desktop, переключаем на tablet
            if (window.innerWidth < 576 && device === 'desktop') {
                setTimeout(() => {
                    const tabletButton = document.querySelector('[data-device="tablet"]');
                    if (tabletButton) tabletButton.click();
                }, 100);
            }
        });
    });
    
    // Поворот устройства с улучшенной адаптивностью
    document.getElementById('rotateViewButton').addEventListener('click', function() {
        const currentDevice = previewContainer.getAttribute('data-current-device');
        if (currentDevice !== 'desktop') {
            previewContainer.classList.toggle('landscape');
            // Сбрасываем масштаб при повороте
            scale = 1;
            previewFrame.style.transform = 'scale(1)';
        }
    });
    
    // Добавляем обработчик для опции "Не использовать логотип"
    logoNone.addEventListener('change', function() {
        if (this.checked) {
            defaultLogoPreview.classList.add('d-none');
            customLogoContainer.classList.add('d-none');
            logoUrl = 'none';
            updatePreview();
        }
    });
    
    // Адаптивные настройки при изменении размера окна
    window.addEventListener('resize', function() {
        // Для мобильных устройств принудительно выбираем tablet или mobile
        if (window.innerWidth < 576) {
            const currentDevice = previewContainer.getAttribute('data-current-device');
            if (currentDevice === 'desktop') {
                const tabletButton = document.querySelector('[data-device="tablet"]');
                if (tabletButton) tabletButton.click();
            }
        }
    });
    
    // Предпросмотр изображения обложки сертификата
    const coverImageInput = document.getElementById('cover_image');
    const coverImagePreview = document.getElementById('cover_image_preview');
    
    coverImageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                coverImagePreview.innerHTML = `
                    <div class="card shadow-sm">
                        <div class="card-body p-2">
                            <h6 class="card-title small mb-2">Предпросмотр обложки</h6>
                            <img src="${e.target.result}" class="img-fluid rounded mb-2" style="max-height: 200px;" alt="Предпросмотр обложки">
                            <p class="text-muted mb-0 small">Так обложка будет выглядеть в карточке сертификата</p>
                        </div>
                    </div>
                `;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Инициализация предпросмотра
    updatePreview();
    
    // Загрузка и обработка анимационных эффектов
    const effectsList = document.getElementById('effectsList');
    const selectEffectButton = document.getElementById('selectEffectButton');
    const animationEffectIdInput = document.getElementById('animation_effect_id');
    const selectedEffectNameInput = document.getElementById('selected_effect_name');
    let selectedEffectId = null;
    let effects = [];
    
    // Функция для загрузки списка эффектов
    function loadAnimationEffects() {
        fetch('<?php echo e(route("animation-effects.get")); ?>')
            .then(response => response.json())
            .then(data => {
                effects = data;
                renderEffectsList(data);
            })
            .catch(error => {
                console.error('Ошибка при загрузке эффектов:', error);
                effectsList.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <i class="fa-solid fa-exclamation-triangle text-warning fs-1 mb-3"></i>
                        <p>Не удалось загрузить анимационные эффекты</p>
                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="loadAnimationEffects()">
                            <i class="fa-solid fa-refresh me-1"></i>Попробовать снова
                        </button>
                    </div>
                `;
            });
    }
    
    // Функция для отображения списка эффектов
    function renderEffectsList(effects) {
        if (!effects || effects.length === 0) {
            effectsList.innerHTML = `
                <div class="col-12 text-center py-4">
                    <i class="fa-solid fa-ghost text-muted fs-1 mb-3"></i>
                    <p>Анимационные эффекты не найдены</p>
                </div>
            `;
            return;
        }
        
        // Создаем карточку для отсутствия эффекта
        let effectsHtml = `
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 effect-card ${!selectedEffectId ? 'selected' : ''}" data-effect-id="">
                    <div class="card-body text-center">
                        <h6 class="card-title">Без эффекта</h6>
                        <p class="card-text text-muted small">Сертификат без анимации</p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <button type="button" class="btn btn-sm ${!selectedEffectId ? 'btn-primary' : 'btn-outline-primary'}" onclick="previewEffect(null)">
                            Выбрать
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Добавляем карточки для каждого эффекта
        effects.forEach(effect => {
            const isSelected = selectedEffectId === effect.id;
            const particlesPreview = Array.isArray(effect.particles) && effect.particles.length > 0
                ? effect.particles.slice(0, 5).join(' ')
                : '✨';
            
            effectsHtml += `
                <div class="col-sm-6 col-lg-4">
                    <div class="card h-100 effect-card ${isSelected ? 'selected' : ''}" data-effect-id="${effect.id}">
                        <div class="card-body text-center">
                            <h6 class="card-title">${effect.name}</h6>
                            <p class="particles-preview">${particlesPreview}</p>
                            <p class="card-text text-muted small">${effect.description || 'Анимационный эффект'}</p>
                            <div class="badge bg-secondary-subtle text-secondary small mb-1">${getEffectTypeName(effect.type)}</div>
                        </div>
                        <div class="card-footer bg-transparent text-center">
                            <button type="button" class="btn btn-sm ${isSelected ? 'btn-primary' : 'btn-outline-primary'}" onclick="previewEffect(${effect.id})">
                                ${isSelected ? 'Выбрано' : 'Выбрать'}
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        effectsList.innerHTML = effectsHtml;
        
        // Глобальная функция для предпросмотра эффекта
        window.previewEffect = function(effectId) {
            // Снимаем выделение со всех карточек
            document.querySelectorAll('.effect-card').forEach(card => {
                card.classList.remove('selected');
                const button = card.querySelector('.btn');
                button.classList.replace('btn-primary', 'btn-outline-primary');
                button.textContent = 'Выбрать';
            });
            
            // Выделяем выбранную карточку
            if (effectId !== null) {
                const selectedCard = document.querySelector(`.effect-card[data-effect-id="${effectId}"]`);
                if (selectedCard) {
                    selectedCard.classList.add('selected');
                    const button = selectedCard.querySelector('.btn');
                    button.classList.replace('btn-outline-primary', 'btn-primary');
                    button.textContent = 'Выбрано';
                }
            } else {
                // Если выбрано "Без эффекта"
                const noEffectCard = document.querySelector('.effect-card[data-effect-id=""]');
                if (noEffectCard) {
                    noEffectCard.classList.add('selected');
                    const button = noEffectCard.querySelector('.btn');
                    button.classList.replace('btn-outline-primary', 'btn-primary');
                }
            }
            
            // Сохраняем выбранный эффект
            selectedEffectId = effectId;
            selectEffectButton.disabled = false;
            
            // Активируем временный предпросмотр эффекта, если он выбран
            if (effectId !== null) {
                const effect = effects.find(e => e.id === effectId);
                if (effect) {
                    showEffectPreview(effect);
                }
            }
        };
    }
    
    // Получение названия типа эффекта
    function getEffectTypeName(type) {
        const types = {
            'emoji': 'Эмодзи',
            'confetti': 'Конфетти',
            'snow': 'Снег',
            'fireworks': 'Фейерверк',
            'bubbles': 'Пузыри',
            'leaves': 'Листья',
            'stars': 'Звёзды'
        };
        return types[type] || type;
    }
    
    // Предпросмотр эффекта в модальном окне
    function showEffectPreview(effect) {
        // Создаем временный контейнер для предпросмотра эффекта
        const previewContainer = document.createElement('div');
        previewContainer.className = 'effect-preview-container';
        previewContainer.style.position = 'absolute';
        previewContainer.style.top = '0';
        previewContainer.style.left = '0';
        previewContainer.style.width = '100%';
        previewContainer.style.height = '100%';
        previewContainer.style.pointerEvents = 'none';
        previewContainer.style.zIndex = '1050';
        document.body.appendChild(previewContainer);
        
        // Создаем частицы для эффекта
        const particleCount = Math.min(effect.quantity || 30, 30);
        const particles = Array.isArray(effect.particles) ? effect.particles : ['✨'];
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            
            // Случайное расположение
            particle.style.position = 'absolute';
            particle.style.left = `${Math.random() * 90 + 5}%`;
            particle.style.top = `${Math.random() * 50 + 25}%`;
            
            // Случайный размер
            const size = Math.floor(Math.random() * 16) + 16;
            particle.style.fontSize = `${size}px`;
            
            // Случайная задержка анимации
            const delay = Math.random() * 2;
            particle.style.animationDelay = `${delay}s`;
            
            // Анимация
            particle.style.animation = `float-${effect.type} 3s ease-in-out infinite`;
            
            // Содержимое частицы
            const particleText = particles[Math.floor(Math.random() * particles.length)];
            particle.textContent = particleText;
            
            // Добавляем частицу в контейнер
            previewContainer.appendChild(particle);
        }
        
        // Удаляем предпросмотр через несколько секунд
        setTimeout(() => {
            if (previewContainer.parentNode) {
                previewContainer.parentNode.removeChild(previewContainer);
            }
        }, 2000);
    }
    
    // Применение выбранного эффекта
    selectEffectButton.addEventListener('click', function() {
        animationEffectIdInput.value = selectedEffectId || '';
        
        if (selectedEffectId) {
            const selectedEffect = effects.find(effect => effect.id === selectedEffectId);
            selectedEffectNameInput.value = selectedEffect ? selectedEffect.name : 'Выбранный эффект';
        } else {
            selectedEffectNameInput.value = 'Без эффекта';
        }
    });
    
    // Инициализация при открытии модального окна
    document.getElementById('animationEffectsModal').addEventListener('show.bs.modal', function () {
        // Если список эффектов еще не загружен
        if (effects.length === 0) {
            loadAnimationEffects();
        }
    });
});
</script>
<style>
/* Стили для скрытия бокового меню на этой странице */
aside, .sidebar-nav, .navbar-toggler {
    display: none !important;
}

/* Стили для карточек эффектов */
.effect-card {
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #dee2e6;
}

.effect-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.effect-card.selected {
    border-color: #0d6efd;
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
}

.particles-preview {
    font-size: 1.5em;
    line-height: 1;
    margin: 10px 0;
    letter-spacing: 0.2em;
}

/* Анимации для предпросмотра эффектов */
@keyframes float-emoji {
    0% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(180deg); }
    100% { transform: translateY(0) rotate(360deg); }
}

@keyframes float-confetti {
    0% { transform: translateY(0) rotate(0deg); opacity: 1; }
    50% { transform: translateY(-20px) rotate(180deg); opacity: 0.8; }
    100% { transform: translateY(0) rotate(360deg); opacity: 1; }
}

@keyframes float-snow {
    0% { transform: translateY(0) rotate(0deg); opacity: 0.8; }
    50% { transform: translateY(15px) rotate(5deg); opacity: 1; }
    100% { transform: translateY(0) rotate(0deg); opacity: 0.8; }
}

@keyframes float-fireworks {
    0% { transform: scale(0.3) translateY(0); opacity: 1; }
    50% { transform: scale(1) translateY(-30px); opacity: 0.8; }
    100% { transform: scale(1.2) translateY(-50px); opacity: 0; }
}

@keyframes float-bubbles {
    0% { transform: translateY(0) scale(1) rotate(0deg); opacity: 0.6; }
    50% { transform: translateY(-20px) scale(1.1) rotate(10deg); opacity: 0.9; }
    100% { transform: translateY(0) scale(1) rotate(0deg); opacity: 0.6; }
}

@keyframes float-leaves {
    0% { transform: translateY(0) rotate(0deg); opacity: 1; }
    50% { transform: translateY(10px) rotate(10deg); opacity: 0.8; }
    100% { transform: translateY(0) rotate(0deg); opacity: 1; }
}

@keyframes float-stars {
    0% { transform: scale(1) rotate(0deg); opacity: 0.8; }
    50% { transform: scale(1.2) rotate(45deg); opacity: 1; }
    100% { transform: scale(1) rotate(90deg); opacity: 0.8; }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views/entrepreneur/certificates/create.blade.php ENDPATH**/ ?>