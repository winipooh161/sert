<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['type' => 'info', 'icon' => null, 'title' => null, 'time' => null, 'read' => false]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['type' => 'info', 'icon' => null, 'title' => null, 'time' => null, 'read' => false]); ?>
<?php foreach (array_filter((['type' => 'info', 'icon' => null, 'title' => null, 'time' => null, 'read' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $typeClasses = [
        'info' => 'bg-primary bg-opacity-10 text-primary',
        'success' => 'bg-success bg-opacity-10 text-success',
        'warning' => 'bg-warning bg-opacity-10 text-warning',
        'danger' => 'bg-danger bg-opacity-10 text-danger',
        'secondary' => 'bg-secondary bg-opacity-10 text-secondary'
    ];
    
    $typeIcons = [
        'info' => 'fa-solid fa-info-circle',
        'success' => 'fa-solid fa-check-circle',
        'warning' => 'fa-solid fa-exclamation-triangle',
        'danger' => 'fa-solid fa-exclamation-circle',
        'secondary' => 'fa-solid fa-bell'
    ];
    
    $bgClass = $typeClasses[$type] ?? $typeClasses['info'];
    $iconClass = $icon ?? $typeIcons[$type] ?? $typeIcons['info'];
?>

<a <?php echo e($attributes->merge(['class' => 'dropdown-item d-flex align-items-center py-2'])); ?> href="#">
    <div class="flex-shrink-0 me-3">
        <div class="avatar rounded-circle <?php echo e($bgClass); ?> p-2" style="width: 40px; height: 40px;">
            <i class="<?php echo e($iconClass); ?>"></i>
        </div>
    </div>
    <div class="flex-grow-1 <?php echo e($read ? 'text-muted' : ''); ?>">
        <div class="d-flex justify-content-between align-items-center">
            <div class="fw-semibold"><?php echo e($title); ?></div>
            <?php if(!$read): ?>
                <div class="ms-2">
                    <span class="badge rounded-pill bg-primary" style="width: 8px; height: 8px; padding: 0;"></span>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-truncate small"><?php echo e($slot); ?></div>
        <?php if($time): ?>
            <div class="text-muted small mt-1"><?php echo e($time); ?></div>
        <?php endif; ?>
    </div>
</a>
<?php /**PATH C:\OSPanel\domains\sert\resources\views\components\notification.blade.php ENDPATH**/ ?>