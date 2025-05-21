

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <h1 class="h3 mb-4">Управление Telegram ботом</h1>

        <?php echo $__env->make('partials.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Информация о боте -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Информация о боте</h5>
            </div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo e($error); ?></div>
                <?php elseif(isset($botInfo) && $botInfo['ok']): ?>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">ID бота:</th>
                                    <td><?php echo e($botInfo['result']['id'] ?? 'Нет данных'); ?></td>
                                </tr>
                                <tr>
                                    <th>Имя бота:</th>
                                    <td><?php echo e($botInfo['result']['first_name'] ?? 'Нет данных'); ?></td>
                                </tr>
                                <tr>
                                    <th>Username:</th>
                                    <td><?php if(isset($botInfo['result']['username'])): ?> {{ $botInfo['result']['username'] }} <?php else: ?> Нет данных <?php endif; ?></td>
                                </tr>
                                <tr>
                                    <th>Может присоединяться к группам:</th>
                                    <td><?php echo e(isset($botInfo['result']['can_join_groups']) ? ($botInfo['result']['can_join_groups'] ? 'Да' : 'Нет') : 'Нет данных'); ?></td>
                                </tr>
                                <tr>
                                    <th>Может читать все сообщения:</th>
                                    <td><?php echo e(isset($botInfo['result']['can_read_all_group_messages']) ? ($botInfo['result']['can_read_all_group_messages'] ? 'Да' : 'Нет') : 'Нет данных'); ?></td>
                                </tr>
                                <tr>
                                    <th>Поддерживает inline запросы:</th>
                                    <td><?php echo e(isset($botInfo['result']['supports_inline_queries']) ? ($botInfo['result']['supports_inline_queries'] ? 'Да' : 'Нет') : 'Нет данных'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Не удалось получить информацию о боте.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Статус и настройка Webhook -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Webhook</h5>
            </div>
            <div class="card-body">
                <?php if(isset($webhookInfo) && $webhookInfo['ok']): ?>
                    <div class="mb-4">
                        <h6>Текущий статус webhook:</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">URL:</th>
                                        <td><?php echo e($webhookInfo['result']['url'] ?? 'Не установлен'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Последняя ошибка:</th>
                                        <td><?php echo e($webhookInfo['result']['last_error_message'] ?? 'Нет ошибок'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Последняя ошибка дата:</th>
                                        <td>
                                            <?php if(isset($webhookInfo['result']['last_error_date'])): ?>
                                                <?php echo e(date('Y-m-d H:i:s', $webhookInfo['result']['last_error_date'])); ?>

                                            <?php else: ?>
                                                Нет данных
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Максимальное кол-во соединений:</th>
                                        <td><?php echo e($webhookInfo['result']['max_connections'] ?? 'Нет данных'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Ожидающие обновления:</th>
                                        <td><?php echo e($webhookInfo['result']['pending_update_count'] ?? '0'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-4">Не удалось получить информацию о webhook.</div>
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Установить webhook</h6>
                                <p class="small text-muted">URL вебхука: <?php echo e(route('telegram.webhook')); ?></p>
                                <form action="<?php echo e(route('admin.telegram.setWebhook')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-primary">Установить webhook</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Удалить webhook</h6>
                                <p class="small text-muted">Отключить получение обновлений через webhook</p>
                                <form action="<?php echo e(route('admin.telegram.deleteWebhook')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger">Удалить webhook</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Отправка тестового сообщения -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Отправка тестового сообщения</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.telegram.sendTestMessage')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="chat_id" class="form-label">ID чата получателя</label>
                            <input type="text" class="form-control" id="chat_id" name="chat_id" required
                                placeholder="Например: 123456789">
                            <div class="form-text">ID чата можно узнать, отправив сообщение боту и посмотрев логи</div>
                        </div>
                        <div class="col-md-6">
                            <label for="message" class="form-label">Текст сообщения</label>
                            <input type="text" class="form-control" id="message" name="message" 
                                value="Это тестовое сообщение от бота" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Отправить сообщение</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Последние логи бота -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Последние логи бота</h5>
                <a href="#" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('logs-container').scrollIntoView()">
                    Прокрутить вниз
                </a>
            </div>
            <div class="card-body p-0">
                <div class="p-3 bg-light small" style="max-height: 400px; overflow-y: auto;">
                    <?php if(isset($logs) && count($logs) > 0): ?>
                        <pre id="logs-container" class="mb-0"><?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo htmlspecialchars($log); ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></pre>
                    <?php else: ?>
                        <div class="p-3 text-center text-muted">Нет доступных логов</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Прокрутка логов вниз при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        const logsContainer = document.getElementById('logs-container');
        if (logsContainer) {
            logsContainer.parentElement.scrollTop = logsContainer.parentElement.scrollHeight;
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\sert\resources\views/admin/telegram/index.blade.php ENDPATH**/ ?>