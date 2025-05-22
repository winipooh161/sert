<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ваш подарочный сертификат</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }
        .certificate-details {
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .certificate-preview {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
        }
        .button {
            display: inline-block;
            background-color: #4e73df;
            color: #fff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 50px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://placehold.co/150x50?text=Logo" alt="Logo" class="logo">
            <h1>Ваш подарочный сертификат</h1>
        </div>
        
        <div class="content">
            <p>Здравствуйте!</p>
            
            <?php if($message): ?>
                <p><?php echo e($message); ?></p>
            <?php else: ?>
                <p>Вам отправлен подарочный сертификат. Ниже вы найдете всю необходимую информацию.</p>
            <?php endif; ?>
            
            <div class="certificate-details">
                <h3>Информация о сертификате</h3>
                
                <div class="info-row">
                    <div class="info-label">Номер:</div>
                    <div class="info-value"><?php echo e($certificate->certificate_number); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Получатель:</div>
                    <div class="info-value"><?php echo e($certificate->recipient_name); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Сумма:</div>
                    <div class="info-value"><?php echo e(number_format($certificate->amount, 0, '.', ' ')); ?> ₽</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Действует до:</div>
                    <div class="info-value"><?php echo e($certificate->valid_until->format('d.m.Y')); ?></div>
                </div>
            </div>
            
            <div class="certificate-preview">
                <?php echo preg_replace('/\{(\w+)\}/', function($matches) use ($certificate) {
                    $key = $matches[1];
                    
                    if ($key == 'certificate_number') return $certificate->certificate_number;
                    if ($key == 'recipient_name') return $certificate->recipient_name;
                    if ($key == 'amount') return number_format($certificate->amount, 0, '.', ' ') . ' ₽';
                    if ($key == 'valid_from') return $certificate->valid_from->format('d.m.Y');
                    if ($key == 'valid_until') return $certificate->valid_until->format('d.m.Y');
                    if ($key == 'message') return $certificate->message ?? '';
                    
                    // Проверяем кастомные поля
                    if (isset($certificate->custom_fields[$key])) {
                        return $certificate->custom_fields[$key];
                    }
                    
                    return $matches[0]; // Если ничего не найдено, возвращаем как есть
                }, $certificate->template->html_template); ?>

            </div>
            
            <div style="text-align: center;">
                <a href="#" class="button">Просмотреть сертификат онлайн</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Это автоматическое сообщение, пожалуйста, не отвечайте на него.</p>
            <p>&copy; <?php echo e(date('Y')); ?> Ваша компания. Все права защищены.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\sert\resources\views\emails\certificate.blade.php ENDPATH**/ ?>