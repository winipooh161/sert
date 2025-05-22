<!-- Навигационная панель Bootstrap -->
<nav class="navbar navbar-expand-md navbar-light bg-transparent navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center hover-lift" href="<?php echo e(url('/')); ?>">
            <div class="brand-icon-wrapper me-2 rounded-circle bg-primary bg-opacity-10 p-2 d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-certificate text-primary"></i>
            </div>
            <span class="fw-bold"><?php echo e(config('app.name', 'Laravel')); ?></span>
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link nav-hover px-3" href="#benefits">
                        <i class="ri-award-line me-1"></i> Преимущества
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover px-3" href="#how-it-works">
                        <i class="ri-flow-chart me-1"></i> Как это работает
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover px-3" href="#pricing">
                        <i class="ri-money-dollar-circle-line me-1"></i> Тарифы
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover px-3" href="#faq">
                        <i class="ri-question-answer-line me-1"></i> FAQ
                    </a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                <?php if(auth()->guard()->guest()): ?>
                    <?php if(Route::has('login')): ?>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="<?php echo e(route('login')); ?>">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> <?php echo e(__('Login')); ?>

                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if(Route::has('register')): ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary rounded-pill px-4 ms-2 hover-lift" href="<?php echo e(route('register')); ?>">
                                <i class="fa-solid fa-user-plus me-1"></i> <?php echo e(__('Register')); ?>

                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center nav-hover" href="<?php echo e(url('/home')); ?>">
                            <i class="fa-solid fa-gauge-high me-1"></i> Панель
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <div class="avatar-circle me-2 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-user text-primary"></i>
                            </div>
                            <span><?php echo e(Auth::user()->name); ?></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end rounded-4 border-0 shadow-sm" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fa-regular fa-user me-2 text-primary"></i> Мой профиль
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket me-2 text-danger"></i> <?php echo e(__('Logout')); ?>

                            </a>

                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                <?php echo csrf_field(); ?>
                            </form>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\OSPanel\domains\sert\resources\views\layouts\header.blade.php ENDPATH**/ ?>