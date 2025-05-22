

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3 py-md-4">
    <!-- Система папок для сертификатов -->
    <?php echo $__env->make('user.certificates.partials._folder_system', ['folders' => $folders, 'currentFolder' => $currentFolder ?? null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
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
    <?php echo $__env->make('user.certificates.partials._alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Группируем сертификаты по дате создания и сортируем от новых к старым -->
    <?php
        $certificatesByDate = $certificates->groupBy(function($certificate) {
            return $certificate->created_at->format('Y-m-d');
        })->sortKeysDesc(); // Сортировка дат по убыванию, чтобы свежие были сверху
    ?>

    <?php $__empty_1 = true; $__currentLoopData = $certificatesByDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $dateGroupCertificates): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <!-- Заголовок для группы сертификатов по дате -->
        <?php echo $__env->make('user.certificates.partials._date_heading', ['date' => $date], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
            <?php $__currentLoopData = $dateGroupCertificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col">
                    <?php echo $__env->make('user.certificates.partials._certificate_card', ['certificate' => $certificate], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <?php echo $__env->make('user.certificates.partials._empty_state', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <!-- Итоговая карточка со статистикой -->
    <?php if(count($certificates) > 0): ?>
        <?php echo $__env->make('user.certificates.partials._statistics', ['certificates' => $certificates], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    
    <!-- Пагинация -->
    <div class="mt-4 d-flex justify-content-center pagination">
        <?php echo e($certificates->withQueryString()->links()); ?>

    </div>
</div>

<!-- Подключение модальных окон -->
<?php echo $__env->make('user.certificates.partials._modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Подключение скриптов -->
<?php echo $__env->make('user.certificates.partials._scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Подключение стилей -->
<?php echo $__env->make('user.certificates.partials._styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views\user\certificates\index.blade.php ENDPATH**/ ?>