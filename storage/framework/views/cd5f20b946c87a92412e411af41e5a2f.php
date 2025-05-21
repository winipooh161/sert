<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?> - Панель администратора</title>

       <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700" rel="stylesheet">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#000000">
    <meta name="description" content="Платформа для управления сертификатами">
    <link rel="manifest" href="<?php echo e(asset('/manifest.json')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('/icons/icon-192x192.png')); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="<?php echo e(config('app.name', 'Laravel')); ?>">
    
    <!-- Иконки -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 (добавляем глобально для всех страниц) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <?php echo $__env->yieldPushContent('styles'); ?>
      <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/lk.css', 'resources/js/app.js']); ?>
    <!-- Custom Admin Styles -->
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        
        .content-wrapper {
            display: flex;
            flex: 1;
        }
        
        .sidebar {
            width: 250px;
            min-height: 100vh;
            border-right: 1px solid #e3e3e3;
            background-color: #f8f9fa;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .nav-link.active {
            background-color: #e9ecef;
        }
        
        .admin-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e3e3;
            padding: 10px 20px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: fixed;
                z-index: 1;
                display: none;
            }
            
            .sidebar.show {
                display: block;
            }
        }
    </style>
    
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <div id="app">
        <!-- Верхняя навигационная панель -->
        <header class="admin-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn d-md-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="h5 mb-0">Панель администратора</h1>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo e(Auth::user()->name); ?>

                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>">Профиль</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Выход
                                </a>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            <!-- Боковое меню -->
            <aside class="sidebar">
                <?php if (isset($component)) { $__componentOriginal2880b66d47486b4bfeaf519598a469d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2880b66d47486b4bfeaf519598a469d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $attributes = $__attributesOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $component = $__componentOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__componentOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>
            </aside>

            <!-- Основное содержимое -->
            <main class="main-content">
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    <script>
        // Переключение бокового меню на мобильных устройствах
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\sert\resources\views/layouts/admin.blade.php ENDPATH**/ ?>