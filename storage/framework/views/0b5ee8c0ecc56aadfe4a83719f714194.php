<!-- Основные параметры сертификата -->
<div class="mb-2 mb-sm-3">
    <label for="amount" class="form-label small fw-bold">Номинал сертификата *</label>
    <div class="mb-2 flex">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="amount_type" id="amount_type_money" value="money" checked>
            <label class="form-check-label small" for="amount_type_money">
               $
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="amount_type" id="amount_type_percent" value="percent">
            <label class="form-check-label small" for="amount_type_percent">
                %
            </label>
        </div>
    </div>
    
    <!-- Денежный номинал (показывается по умолчанию) -->
    <div id="money_amount_block">
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
    </div>
    
    <!-- Процентный номинал (скрыт по умолчанию) -->
    <div id="percent_amount_block" class="d-none">
        <div class="input-group">
            <input type="number" class="form-control form-control-sm" 
                id="percent_value" name="percent_value" value="<?php echo e(old('percent_value', 10)); ?>" min="1" max="100" step="1">
            <span class="input-group-text small">%</span>
        </div>
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
    <?php $__errorArgs = ['percent_value'];
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
</div>
<?php /**PATH C:\OSPanel\domains\sert\resources\views\entrepreneur\certificates\partials\form_tabs\main_tab.blade.php ENDPATH**/ ?>