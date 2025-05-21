

<?php $__env->startSection('content'); ?>
<div class="container-fluid ">
  

    <?php $__env->startComponent('components.page-header'); ?>
        <?php $__env->slot('title', 'Мой профиль'); ?>
        <?php $__env->slot('subtitle', 'Управление персональными данными и настройками'); ?>
    <?php echo $__env->renderComponent(); ?>
    
    <!-- Сообщения об успешных операциях -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row g-4 page-section">
        <!-- Основная информация -->
        <div class="col-lg-8">
            <?php $__env->startComponent('components.card', ['title' => 'Основная информация']); ?>
                <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row g-4">
                        <!-- Аватар -->
                        <div class="col-md-3">
                            <div class="text-center mb-3">
                                <div class="profile-avatar-wrapper position-relative d-inline-block">
                                    <div class="rounded-circle bg-light overflow-hidden position-relative" style="width: 120px; height: 120px;">
                                        <?php if(Auth::user()->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . Auth::user()->avatar)); ?>" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="<?php echo e(Auth::user()->name); ?>">
                                        <?php else: ?>
                                            <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-primary bg-opacity-10 text-primary fs-1">
                                                <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <label for="avatar_upload" class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm p-2 cursor-pointer avatar-edit-button">
                                        <i class="fa-solid fa-camera text-primary"></i>
                                        <input type="file" id="avatar_upload" name="avatar" class="d-none" accept="image/*">
                                    </label>
                                </div>
                                <p class="small text-muted mt-2">Нажмите на иконку камеры, чтобы загрузить фото</p>
                            </div>
                        </div>
                        
                        <!-- Личные данные -->
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Имя</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(Auth::user()->name); ?>" required>
                                    <?php $__errorArgs = ['name'];
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
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(Auth::user()->email); ?>" required>
                                    <?php $__errorArgs = ['email'];
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
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Телефон</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> maskphone" id="phone" name="phone" value="<?php echo e(Auth::user()->phone ?? ''); ?>">
                                        <button type="button" class="btn btn-outline-primary" id="changePhoneBtn">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <?php $__errorArgs = ['phone'];
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
                                
                                <div class="col-md-6">
                                    <label for="company" class="form-label">Компания</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['company'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="company" name="company" value="<?php echo e(Auth::user()->company ?? ''); ?>">
                                    <?php $__errorArgs = ['company'];
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
                                
                                <div class="col-12">
                                    <label for="bio" class="form-label">О себе</label>
                                    <textarea class="form-control <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="bio" name="bio" rows="3"><?php echo e(Auth::user()->bio ?? ''); ?></textarea>
                                    <?php $__errorArgs = ['bio'];
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
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-2"></i>Сохранить изменения
                        </button>
                    </div>
                </form>
            <?php echo $__env->renderComponent(); ?>
            
            <?php $__env->startComponent('components.card', ['title' => 'Безопасность', 'class' => 'mt-4']); ?>
                <form method="POST" action="<?php echo e(route('profile.password')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label">Текущий пароль</label>
                            <div class="input-group">
                                <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <?php $__errorArgs = ['current_password'];
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
                        
                        <div class="col-md-4">
                            <label for="password" class="form-label">Новый пароль</label>
                            <div class="input-group">
                                <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password" name="password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <?php $__errorArgs = ['password'];
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
                        
                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                            <div class="input-group">
                                <input type="password" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password_confirmation" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <?php $__errorArgs = ['password_confirmation'];
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
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-lock me-2"></i>Обновить пароль
                        </button>
                    </div>
                </form>
            <?php echo $__env->renderComponent(); ?>
        </div>
        
        <!-- Боковая информация -->
        <div class="col-lg-4">
            <?php $__env->startComponent('components.card', ['title' => 'Информация об аккаунте']); ?>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Статус</span>
                        <span class="badge bg-success">Активен</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Роль</span>
                        <span class="badge bg-primary"><?php echo e(Auth::user()->hasRole('admin') ? 'Администратор' : 'Предприниматель'); ?></span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Дата регистрации</span>
                        <span><?php echo e(Auth::user()->created_at->format('d.m.Y')); ?></span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Последнее обновление</span>
                        <span><?php echo e(Auth::user()->updated_at->format('d.m.Y')); ?></span>
                    </li>
                </ul>
            <?php echo $__env->renderComponent(); ?>
            
            <?php $__env->startComponent('components.card', ['title' => 'Уведомления', 'class' => 'mt-4']); ?>
                <form method="POST" action="<?php echo e(route('profile.notifications')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="email_notifications" 
                                   name="notification_preferences[email]" value="1" 
                                   <?php echo e(isset(json_decode(Auth::user()->notification_preferences, true)['email']) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="email_notifications">Email уведомления</label>
                        </div>
                        <div class="form-text">Получать уведомления на email</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="browser_notifications" 
                                   name="notification_preferences[browser]" value="1"
                                   <?php echo e(isset(json_decode(Auth::user()->notification_preferences, true)['browser']) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="browser_notifications">Push-уведомления</label>
                        </div>
                        <div class="form-text">Получать уведомления в браузере</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="sms_notifications" 
                                   name="notification_preferences[sms]" value="1"
                                   <?php echo e(isset(json_decode(Auth::user()->notification_preferences, true)['sms']) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="sms_notifications">SMS уведомления</label>
                        </div>
                        <div class="form-text">Получать уведомления по SMS</div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-2"></i>Сохранить настройки
                        </button>
                    </div>
                </form>
            <?php echo $__env->renderComponent(); ?>
        </div>
    </div>
</div>


<!-- Модальное окно подтверждения номера телефона -->
<div class="modal fade" id="phoneVerificationModal" tabindex="-1" aria-labelledby="phoneVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phoneVerificationModalLabel">Подтверждение номера телефона</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-mobile-screen fa-3x text-primary mb-3"></i>
                    <p>На указанный номер телефона <strong id="verificationPhone"></strong> отправлен код подтверждения.</p>
                    <p>Введите полученный 4-значный код:</p>
                </div>
                
                <div class="verification-code-inputs d-flex justify-content-center gap-2 mb-4">
                    <input type="text" class="form-control text-center verification-code-input" maxlength="1" id="code1" pattern="[0-9]">
                    <input type="text" class="form-control text-center verification-code-input" maxlength="1" id="code2" pattern="[0-9]">
                    <input type="text" class="form-control text-center verification-code-input" maxlength="1" id="code3" pattern="[0-9]">
                    <input type="text" class="form-control text-center verification-code-input" maxlength="1" id="code4" pattern="[0-9]">
                </div>
                
                <div class="alert alert-danger d-none" id="verificationError"></div>
                <div class="alert alert-success d-none" id="verificationSuccess"></div>
                
                <div class="text-center">
                    <p class="text-muted">Не получили код? <a href="#" id="resendCode">Отправить повторно</a></p>
                    <div id="countdown" class="text-muted small"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="verifyCodeBtn" disabled>Подтвердить</button>
            </div>
        </div>
    </div>
</div>

<script>
    
document.addEventListener("DOMContentLoaded", function () {
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
});
document.addEventListener('DOMContentLoaded', function() {
    // Предпросмотр аватара перед загрузкой
    document.getElementById('avatar_upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarContainer = document.querySelector('.rounded-circle.bg-light');
                
                // Проверяем, есть ли уже изображение в контейнере
                const existingImg = avatarContainer.querySelector('img');
                
                if (existingImg) {
                    // Если есть, просто меняем источник
                    existingImg.src = e.target.result;
                } else {
                    // Если нет, создаем новый элемент img и добавляем его
                    avatarContainer.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid w-100 h-100';
                    img.style.objectFit = 'cover';
                    avatarContainer.appendChild(img);
                }
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Переключение видимости пароля
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Изменение иконки
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
    
    // ИСПРАВЛЕНИЕ: убираем вложенный обработчик DOMContentLoaded и исправляем функцию инициации
    const phoneInput = document.getElementById('phone');
    const changePhoneBtn = document.getElementById('changePhoneBtn');
    
    if (changePhoneBtn && phoneInput) {
        console.log("Кнопка смены телефона найдена");
        changePhoneBtn.addEventListener('click', function() {
            console.log("Кнопка смены телефона нажата");
            let newPhone = phoneInput.value.trim();
            if (newPhone) {
                console.log("Начинаем верификацию для номера: " + newPhone);
                initiatePhoneVerification(newPhone);
            } else {
                alert('Пожалуйста, введите номер телефона');
                phoneInput.focus();
            }
        });
    } else {
        console.error("Не найдена кнопка смены телефона или поле ввода телефона");
    }
    
    // Обработчик ошибок для fetch запросов
    function handleFetchError(response) {
        if (!response.ok) {
            if (response.status === 422) {
                // Ошибки валидации
                return response.json().then(data => {
                    throw new Error(Object.values(data.errors).flat().join('\n'));
                });
            } else if (response.status === 400) {
                // Бизнес-ошибки с сообщением
                return response.json().then(data => {
                    throw new Error(data.message || 'Ошибка в запросе');
                });
            } else {
                throw new Error('Ошибка сервера: ' + response.status);
            }
        }
        return response.json();
    }
    
    // Функция инициирования верификации телефона
    function initiatePhoneVerification(phone) {
        console.log("Инициируем верификацию для номера: " + phone);
        
        // Очищаем номер телефона от всех нецифровых символов
        const cleanPhone = phone.replace(/\D/g, '');
        
        // Показываем индикатор загрузки
        const changePhoneBtn = document.getElementById('changePhoneBtn');
        const originalContent = changePhoneBtn.innerHTML;
        changePhoneBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        changePhoneBtn.disabled = true;
        
        fetch('<?php echo e(route("profile.phone.initiate")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ phone: cleanPhone })
        })
        .then(response => {
            console.log("Получен статус ответа:", response.status);
            return handleFetchError(response);
        })
        .then(data => {
            console.log("Данные от сервера:", data);
            
            // Восстанавливаем состояние кнопки
            changePhoneBtn.innerHTML = originalContent;
            changePhoneBtn.disabled = false;
            
            if (data.success) {
                document.getElementById('verificationPhone').textContent = phone;
                
                // Для режима отладки можно показать код в консоли
                if (data.debug_code) {
                    console.log("Отладочный код: " + data.debug_code);
                }
                
                // Сбрасываем состояние модального окна
                document.getElementById('verificationError').classList.add('d-none');
                document.getElementById('verificationSuccess').classList.add('d-none');
                
                // Очищаем поля ввода
                document.querySelectorAll('.verification-code-input').forEach(input => {
                    input.value = '';
                });
                
                // Показываем модальное окно
                const modal = new bootstrap.Modal(document.getElementById('phoneVerificationModal'));
                modal.show();
                
                // Устанавливаем фокус на первое поле ввода
                setTimeout(() => {
                    document.getElementById('code1').focus();
                }, 500);
                
                // Запускаем обратный отсчет
                startCountdown(60);
            } else {
                alert(data.message || 'Произошла ошибка при отправке кода.');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            
            // Восстанавливаем состояние кнопки
            changePhoneBtn.innerHTML = originalContent;
            changePhoneBtn.disabled = false;
            
            alert('Ошибка: ' + error.message);
        });
    }
    
    // Обработка ввода кода
    document.querySelectorAll('.verification-code-input').forEach(input => {
        input.addEventListener('input', function(e) {
            // Проверяем, что вводятся только цифры
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Переходим к следующему полю автоматически
            if (this.value.length === 1) {
                const nextInput = this.nextElementSibling;
                if (nextInput && nextInput.classList.contains('verification-code-input')) {
                    nextInput.focus();
                }
            }
            
            // Активируем кнопку, если все поля заполнены
            checkVerificationInputs();
        });
        
        input.addEventListener('keydown', function(e) {
            // При нажатии Backspace возвращаемся к предыдущему полю
            if (e.key === 'Backspace' && this.value.length === 0) {
                const prevInput = this.previousElementSibling;
                if (prevInput && prevInput.classList.contains('verification-code-input')) {
                    prevInput.focus();
                }
            }
        });
    });
    
    // Проверка заполнения всех полей кода
    function checkVerificationInputs() {
        const allFilled = Array.from(document.querySelectorAll('.verification-code-input'))
            .every(input => input.value.length === 1);
        
        document.getElementById('verifyCodeBtn').disabled = !allFilled;
    }
    
    // Обработчик нажатия кнопки подтверждения кода
    document.getElementById('verifyCodeBtn').addEventListener('click', function() {
        const code1 = document.getElementById('code1').value;
        const code2 = document.getElementById('code2').value;
        const code3 = document.getElementById('code3').value;
        const code4 = document.getElementById('code4').value;
        
        // Проверка заполненности всех полей
        if (!code1 || !code2 || !code3 || !code4) {
            document.getElementById('verificationError').textContent = 'Введите все цифры кода.';
            document.getElementById('verificationError').classList.remove('d-none');
            return;
        }
        
        // Отправка запроса на подтверждение кода
        fetch('<?php echo e(route("profile.phone.verify")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                code1: code1,
                code2: code2,
                code3: code3,
                code4: code4
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('verificationSuccess').textContent = data.message;
                document.getElementById('verificationSuccess').classList.remove('d-none');
                document.getElementById('verificationError').classList.add('d-none');
                
                // Обновляем значение поля телефона
                if (phoneInput) {
                    phoneInput.value = data.phone;
                }
                
                // Деактивируем кнопку подтверждения
                document.getElementById('verifyCodeBtn').disabled = true;
                
                // Закрываем модальное окно через 2 секунды
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('phoneVerificationModal')).hide();
                }, 2000);
            } else {
                document.getElementById('verificationError').textContent = data.message;
                document.getElementById('verificationError').classList.remove('d-none');
                document.getElementById('verificationSuccess').classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            document.getElementById('verificationError').textContent = 'Произошла ошибка при отправке запроса.';
            document.getElementById('verificationError').classList.remove('d-none');
        });
    });
    
    // Обработчик повторной отправки кода
    document.getElementById('resendCode').addEventListener('click', function(e) {
        e.preventDefault();
        
        if (this.classList.contains('disabled')) {
            return;
        }
        
        // Повторно отправляем запрос на инициацию верификации
        const phone = document.getElementById('verificationPhone').textContent;
        initiatePhoneVerification(phone);
    });
    
    // Функция обратного отсчета для повторной отправки кода
    function startCountdown(seconds) {
        const resendLink = document.getElementById('resendCode');
        const countdownEl = document.getElementById('countdown');
        
        resendLink.classList.add('disabled');
        countdownEl.textContent = `Повторная отправка возможна через ${seconds} секунд`;
        
        let remainingSeconds = seconds;
        const countdownInterval = setInterval(() => {
            remainingSeconds--;
            
            if (remainingSeconds <= 0) {
                clearInterval(countdownInterval);
                resendLink.classList.remove('disabled');
                countdownEl.textContent = '';
                return;
            }
            
            countdownEl.textContent = `Повторная отправка возможна через ${remainingSeconds} секунд`;
        }, 1000);
    }
});  
</script>

<style>
.profile-avatar-wrapper {
    margin: 0 auto;
}

.avatar-edit-button {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.avatar-edit-button:hover {
    background-color: #f8f9fa;
    transform: scale(1.1);
}

.toggle-password {
    cursor: pointer;
}

.verification-code-inputs .verification-code-input {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
    font-weight: bold;
}

.disabled {
    pointer-events: none;
    opacity: 0.6;
}

@media (max-width: 767.98px) {
    .profile-avatar-wrapper {
        margin-bottom: 2rem;
    }
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views/profile/index.blade.php ENDPATH**/ ?>