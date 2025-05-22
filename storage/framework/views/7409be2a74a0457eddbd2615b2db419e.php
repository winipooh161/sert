

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Редактирование анимационного эффекта</h1>
        <a href="<?php echo e(route('admin.animation-effects.index')); ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Назад к списку
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Параметры эффекта "<?php echo e($animationEffect->name); ?>"</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.animation-effects.update', $animationEffect)); ?>" method="POST" id="effectForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Название эффекта *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="name" name="name" value="<?php echo e(old('name', $animationEffect->name)); ?>" required>
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
                                <label for="type" class="form-label">Тип эффекта *</label>
                                <select class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="type" name="type" required>
                                    <option value="emoji" <?php echo e(old('type', $animationEffect->type) == 'emoji' ? 'selected' : ''); ?>>Эмодзи (смайлики)</option>
                                    <option value="confetti" <?php echo e(old('type', $animationEffect->type) == 'confetti' ? 'selected' : ''); ?>>Конфетти</option>
                                    <option value="snow" <?php echo e(old('type', $animationEffect->type) == 'snow' ? 'selected' : ''); ?>>Снежинки</option>
                                    <option value="fireworks" <?php echo e(old('type', $animationEffect->type) == 'fireworks' ? 'selected' : ''); ?>>Фейерверк</option>
                                    <option value="bubbles" <?php echo e(old('type', $animationEffect->type) == 'bubbles' ? 'selected' : ''); ?>>Пузыри</option>
                                    <option value="leaves" <?php echo e(old('type', $animationEffect->type) == 'leaves' ? 'selected' : ''); ?>>Листья</option>
                                    <option value="stars" <?php echo e(old('type', $animationEffect->type) == 'stars' ? 'selected' : ''); ?>>Звезды</option>
                                </select>
                                <?php $__errorArgs = ['type'];
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
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="description" name="description" rows="2"><?php echo e(old('description', $animationEffect->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text small">Краткое описание эффекта (отображается в модальном окне выбора)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="particles" class="form-label">Частицы анимации *</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['particles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="particles" name="particles" value="<?php echo e(old('particles', implode(',', $animationEffect->particles))); ?>" required>
                            <?php $__errorArgs = ['particles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text small">Укажите символы или эмодзи через запятую (например: 🎉,🎊,✨,🎁,💫)</div>
                            
                            <div class="mt-2" id="particles-preview"></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="direction" class="form-label">Направление движения *</label>
                                <select class="form-select <?php $__errorArgs = ['direction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="direction" name="direction" required>
                                    <option value="center" <?php echo e(old('direction', $animationEffect->direction) == 'center' ? 'selected' : ''); ?>>К центру</option>
                                    <option value="top" <?php echo e(old('direction', $animationEffect->direction) == 'top' ? 'selected' : ''); ?>>Вверх</option>
                                    <option value="bottom" <?php echo e(old('direction', $animationEffect->direction) == 'bottom' ? 'selected' : ''); ?>>Вниз</option>
                                    <option value="random" <?php echo e(old('direction', $animationEffect->direction) == 'random' ? 'selected' : ''); ?>>Случайно</option>
                                </select>
                                <?php $__errorArgs = ['direction'];
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
                            <div class="col-md-4">
                                <label for="speed" class="form-label">Скорость анимации *</label>
                                <select class="form-select <?php $__errorArgs = ['speed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="speed" name="speed" required>
                                    <option value="slow" <?php echo e(old('speed', $animationEffect->speed) == 'slow' ? 'selected' : ''); ?>>Медленно</option>
                                    <option value="normal" <?php echo e(old('speed', $animationEffect->speed) == 'normal' ? 'selected' : ''); ?>>Стандартно</option>
                                    <option value="fast" <?php echo e(old('speed', $animationEffect->speed) == 'fast' ? 'selected' : ''); ?>>Быстро</option>
                                </select>
                                <?php $__errorArgs = ['speed'];
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
                            <div class="col-md-4">
                                <label for="color" class="form-label">Цвет (для не-эмодзи)</label>
                                <input type="color" class="form-control form-control-color w-100 <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="color" name="color" value="<?php echo e(old('color', $animationEffect->color)); ?>">
                                <?php $__errorArgs = ['color'];
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
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="size_min" class="form-label">Минимальный размер (px) *</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['size_min'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="size_min" name="size_min" value="<?php echo e(old('size_min', $animationEffect->size_min)); ?>" min="8" max="64" required>
                                <?php $__errorArgs = ['size_min'];
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
                            <div class="col-md-4">
                                <label for="size_max" class="form-label">Максимальный размер (px) *</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['size_max'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="size_max" name="size_max" value="<?php echo e(old('size_max', $animationEffect->size_max)); ?>" min="8" max="100" required>
                                <?php $__errorArgs = ['size_max'];
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
                            <div class="col-md-4">
                                <label for="quantity" class="form-label">Количество частиц *</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="quantity" name="quantity" value="<?php echo e(old('quantity', $animationEffect->quantity)); ?>" min="10" max="200" required>
                                <?php $__errorArgs = ['quantity'];
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
                        
                        <hr class="my-4">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Порядок сортировки</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="sort_order" name="sort_order" value="<?php echo e(old('sort_order', $animationEffect->sort_order)); ?>" min="0">
                                <?php $__errorArgs = ['sort_order'];
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
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" value="1" 
                                        id="is_active" name="is_active" <?php echo e(old('is_active', $animationEffect->is_active) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_active">
                                        Активен
                                    </label>
                                    <div class="form-text small">Если отмечено, эффект будет доступен для выбора</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fa-solid fa-trash me-1"></i>Удалить эффект
                            </button>
                            <div>
                                <a href="<?php echo e(route('admin.animation-effects.index')); ?>" class="btn btn-outline-secondary me-2">Отмена</a>
                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Предпросмотр эффекта</h5>
                        <a href="<?php echo e(route('admin.animation-effects.preview', $animationEffect)); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fa-solid fa-external-link-alt me-1"></i>В новом окне
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="animated-effect-preview">
                        <div class="preview-container" style="height: 300px; background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d); position: relative; overflow: hidden; border-radius: 8px;">
                            <!-- Здесь будет предпросмотр эффекта -->
                        </div>
                        
                        <div class="mt-3 text-center">
                            <button type="button" class="btn btn-sm btn-primary" id="previewButton">
                                <i class="fa-solid fa-play me-1"></i>Запустить анимацию
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Информация</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">ID эффекта:</dt>
                        <dd class="col-sm-7"><?php echo e($animationEffect->id); ?></dd>
                        
                        <dt class="col-sm-5">Слаг эффекта:</dt>
                        <dd class="col-sm-7"><?php echo e($animationEffect->slug); ?></dd>
                        
                        <dt class="col-sm-5">Дата создания:</dt>
                        <dd class="col-sm-7"><?php echo e($animationEffect->created_at->format('d.m.Y H:i')); ?></dd>
                        
                        <dt class="col-sm-5">Последнее обновление:</dt>
                        <dd class="col-sm-7"><?php echo e($animationEffect->updated_at->format('d.m.Y H:i')); ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Вы уверены, что хотите удалить анимационный эффект "<?php echo e($animationEffect->name); ?>"?</p>
                <p class="text-danger">Это действие нельзя отменить!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                <form action="<?php echo e(route('admin.animation-effects.destroy', $animationEffect)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .preview-particle {
        position: absolute;
        font-size: 24px;
        animation: preview-float 2s ease-in-out infinite;
    }
    
    @keyframes preview-float {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(180deg); }
        100% { transform: translateY(0) rotate(360deg); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const particlesInput = document.getElementById('particles');
    const previewContainer = document.getElementById('particles-preview');
    const previewButton = document.getElementById('previewButton');
    
    // Функция обновления предпросмотра частиц
    function updateParticlesPreview() {
        const particles = particlesInput.value.split(',').map(p => p.trim()).filter(p => p);
        
        let previewHTML = '<div class="d-flex flex-wrap gap-2">';
        particles.forEach(particle => {
            previewHTML += `<span class="badge bg-light text-dark p-2">${particle}</span>`;
        });
        previewHTML += '</div>';
        
        previewContainer.innerHTML = previewHTML;
        
        // Активируем кнопку предпросмотра, если есть частицы
        previewButton.disabled = particles.length === 0;
    }
    
    // Обработчик изменения поля частиц
    particlesInput.addEventListener('input', updateParticlesPreview);
    
    // Функция для визуального предпросмотра анимации
    previewButton.addEventListener('click', function() {
        const previewArea = document.querySelector('.preview-container');
        const particles = particlesInput.value.split(',').map(p => p.trim()).filter(p => p);
        
        // Очищаем предыдущий предпросмотр
        document.querySelectorAll('.preview-particle').forEach(el => el.remove());
        
        // Создаем случайные частицы для предпросмотра
        const particleCount = Math.min(document.getElementById('quantity').value, 30); // Ограничиваем для предпросмотра
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('span');
            particle.className = 'preview-particle';
            
            // Выбираем случайную частицу
            const randomParticle = particles[Math.floor(Math.random() * particles.length)];
            particle.textContent = randomParticle;
            
            // Случайное позиционирование
            const left = Math.random() * 90 + 5; // 5-95%
            const top = Math.random() * 80 + 10; // 10-90%
            
            // Случайный размер
            const minSize = parseInt(document.getElementById('size_min').value);
            const maxSize = parseInt(document.getElementById('size_max').value);
            const size = Math.floor(Math.random() * (maxSize - minSize + 1)) + minSize;
            
            // Случайная задержка анимации
            const delay = Math.random() * 2;
            
            // Применяем стили
            particle.style.left = `${left}%`;
            particle.style.top = `${top}%`;
            particle.style.fontSize = `${size}px`;
            particle.style.animationDelay = `${delay}s`;
            
            // Добавляем в контейнер
            previewArea.appendChild(particle);
        }
    });
    
    // Инициализация предпросмотра при загрузке
    updateParticlesPreview();
    
    // Запуск первого предпросмотра
    setTimeout(function() {
        previewButton.click();
    }, 500);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views\admin\animation-effects\edit.blade.php ENDPATH**/ ?>