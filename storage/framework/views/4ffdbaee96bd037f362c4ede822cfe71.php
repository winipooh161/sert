

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Хлебные крошки -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Панель администратора</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.users.index')); ?>">Пользователи</a></li>
            <li class="breadcrumb-item active" aria-current="page">Создание пользователя</li>
        </ol>
    </nav>
    
    <h1 class="mb-4">Создание нового пользователя</h1>
    
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
                <?php echo csrf_field(); ?>
                
                <div class="row g-4">
                    <!-- Основная информация -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Основная информация</h4>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Имя пользователя *</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email адрес *</label>
                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль *</label>
                            <div class="input-group">
                                <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="password" name="password" required autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Подтверждение пароля *</label>
                            <input type="password" class="form-control" 
                                id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                    
                    <!-- Роли и настройки -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Роли и настройки</h4>
                        
                        <div class="mb-3">
                            <label class="form-label">Назначьте роли *</label>
                            
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="roles[]" 
                                        id="role_<?php echo e($role->id); ?>" value="<?php echo e($role->id); ?>" 
                                        <?php echo e(in_array($role->id, old('roles', [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="role_<?php echo e($role->id); ?>">
                                        <?php echo e($role->name); ?>

                                        <small class="text-muted d-block"><?php echo e($role->description); ?></small>
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <?php $__errorArgs = ['roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fa-solid fa-circle-info me-2"></i>
                            Пользователь получит уведомление о создании учетной записи со всеми учетными данными.
                        </div>
                    </div>
                </div>
                
                <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-end">
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary me-2">Отмена</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus me-1"></i> Создать пользователя
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функционал показа/скрытия пароля
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Меняем иконку
        const icon = this.querySelector('i');
        if (type === 'text') {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views\admin\users\create.blade.php ENDPATH**/ ?>