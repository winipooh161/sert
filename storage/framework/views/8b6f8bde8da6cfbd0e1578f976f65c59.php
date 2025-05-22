<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Новый заказ шаблона</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #0d6efd;
            margin-bottom: 20px;
        }
        .order-details {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .field-name {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Новый заказ шаблона сертификата</h1>
        
        <div class="order-details">
            <p><span class="field-name">Имя/Организация:</span> <?php echo e($orderData['name'] ?? 'Не указано'); ?></p>
            <p><span class="field-name">Email:</span> <?php echo e($orderData['email'] ?? 'Не указан'); ?></p>
            <p><span class="field-name">Телефон:</span> <?php echo e($orderData['phone'] ?? 'Не указан'); ?></p>
            <p><span class="field-name">Описание шаблона:</span> <?php echo e($orderData['description'] ?? 'Не указано'); ?></p>
            <p><span class="field-name">Цель:</span> <?php echo e($orderData['purpose'] ?? 'Не указана'); ?></p>
            <p><span class="field-name">Желаемые сроки:</span> <?php echo e($orderData['deadline'] ?? 'Не указаны'); ?></p>
        </div>
        
        <p>Заказ получен через Telegram бота.</p>
    </div>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\sert\resources\views\emails\template-order.blade.php ENDPATH**/ ?>