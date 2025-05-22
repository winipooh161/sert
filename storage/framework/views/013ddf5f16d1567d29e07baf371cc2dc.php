

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 rounded-4 shadow">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4">Проверка сертификата</h2>
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success mb-4">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger mb-4">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fa-solid fa-certificate fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">Сертификат подтвержден</h5>
                                <p class="mb-0">Сертификат #<?php echo e($certificate->certificate_number); ?> является действительным.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <dl>
                                <dt>Номер сертификата</dt>
                                <dd><?php echo e($certificate->certificate_number); ?></dd>
                                
                                <dt>Получатель</dt>
                                <dd><?php echo e($certificate->recipient_name); ?></dd>
                                
                                <dt>Сумма</dt>
                                <dd><?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?> ₽</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl>
                                <dt>Статус</dt>
                                <dd>
                                    <?php if($certificate->status == 'active'): ?>
                                        <span class="badge bg-success">Активен</span>
                                    <?php elseif($certificate->status == 'used'): ?>
                                        <span class="badge bg-secondary">Использован</span>
                                    <?php elseif($certificate->status == 'expired'): ?>
                                        <span class="badge bg-warning">Истек</span>
                                    <?php elseif($certificate->status == 'canceled'): ?>
                                        <span class="badge bg-danger">Отменен</span>
                                    <?php endif; ?>
                                </dd>
                                
                                <dt>Срок действия</dt>
                                <dd><?php echo e($certificate->valid_from->format('d.m.Y')); ?> - <?php echo e($certificate->valid_until->format('d.m.Y')); ?></dd>
                                
                                <dt>Шаблон</dt>
                                <dd><?php echo e($certificate->template->name); ?></dd>
                            </dl>
                        </div>
                    </div>
                    
                    <?php if($certificate->status == 'active'): ?>
                        <form action="<?php echo e(route('entrepreneur.certificates.mark-as-used', $certificate)); ?>" method="POST" class="text-center">
                            <?php echo csrf_field(); ?>
                            <p class="mb-4">Подтвердите использование сертификата. Это действие изменит статус сертификата на "Использован".</p>
                            <button type="submit" class="btn btn-lg btn-primary">
                                <i class="fa-solid fa-check-circle me-2"></i>Подтвердить использование
                            </button>
                        </form>
                    <?php elseif($certificate->status == 'used'): ?>
                        <div class="text-center">
                            <div class="alert alert-success">
                                <i class="fa-solid fa-check-circle me-2"></i>Сертификат уже был использован <?php echo e($certificate->used_at ? $certificate->used_at->format('d.m.Y H:i') : 'ранее'); ?>

                            </div>
                            <a href="<?php echo e(route('entrepreneur.certificates.show', $certificate)); ?>" class="btn btn-primary">
                                <i class="fa-solid fa-eye me-2"></i>Просмотр деталей
                            </a>
                        </div>
                    <?php elseif($certificate->status == 'expired'): ?>
                        <div class="text-center">
                            <div class="alert alert-warning">
                                <i class="fa-solid fa-exclamation-circle me-2"></i>Срок действия сертификата истек
                            </div>
                            <a href="<?php echo e(route('entrepreneur.certificates.show', $certificate)); ?>" class="btn btn-primary">
                                <i class="fa-solid fa-eye me-2"></i>Просмотр деталей
                            </a>
                        </div>
                    <?php elseif($certificate->status == 'canceled'): ?>
                        <div class="text-center">
                            <div class="alert alert-danger">
                                <i class="fa-solid fa-times-circle me-2"></i>Сертификат был отменен
                            </div>
                            <a href="<?php echo e(route('entrepreneur.certificates.show', $certificate)); ?>" class="btn btn-primary">
                                <i class="fa-solid fa-eye me-2"></i>Просмотр деталей
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent text-center p-3">
                    <div class="btn-group">
                        <a href="<?php echo e(route('entrepreneur.certificates.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-2"></i>К списку сертификатов
                        </a>
                        <a href="<?php echo e(route('entrepreneur.certificates.show', $certificate)); ?>" class="btn btn-outline-primary">
                            <i class="fa-solid fa-eye me-2"></i>Просмотр сертификата
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views\entrepreneur\certificates\admin-verify.blade.php ENDPATH**/ ?>