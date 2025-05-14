<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Личный кабинет</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700" rel="stylesheet">
    
    <!-- Иконки -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 (добавляем глобально для всех страниц) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    @stack('styles')
    
    <!-- Scripts -->
    @vite(['resources/css/lk.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app" class="d-flex flex-column vh-100">
        <!-- ===================== МОБИЛЬНАЯ ШАПКА ===================== -->
        <header class="navbar navbar-expand-lg sticky-top bg-white border-bottom d-lg-none">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                
                <!-- Кнопка переключения бокового меню -->
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </header>

        <div class="container-fluid vh-100 d-flex flex-grow-1 p-0">
            <!-- ===================== БОКОВАЯ НАВИГАЦИЯ ===================== -->
            <aside id="sidebarMenu" class="offcanvas-lg offcanvas-start bg-white border-end" tabindex="-1">
                <div class="offcanvas-header d-lg-none">
                    <h5 class="offcanvas-title">Меню</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <div class="sidebar-sticky d-flex flex-column h-100">
                        <!-- Лого и название проекта -->
                        <div class="sidebar-logo d-none d-lg-flex align-items-center ps-4 py-3">
                            <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center">
                                <span class="fs-5 fw-semibold text-dark sidebar-logo-text">{{ config('app.name', 'Laravel') }}</span>
                            </a>
                            <button id="sidebarToggleBtn" class="btn btn-sm btn-link ms-auto me-3 text-dark d-none d-lg-block">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                        </div>
                        
                        <!-- Профиль пользователя -->
                        <div class="sidebar-user d-flex align-items-center border-bottom p-3">
                            <div class="avatar-wrapper rounded-circle overflow-hidden flex-shrink-0" style="">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 bg-primary text-white d-flex align-items-center justify-content-center">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="user-info overflow-hidden">
                                <h6 class="mb-0 sidebar-user-name text-truncate">{{ Auth::user()->name }}</h6>
                                <span class="text-muted small sidebar-user-role d-block text-truncate">
                                    @if(Auth::user()->hasRole('admin'))
                                        Администратор
                                    @elseif(Auth::user()->hasRole('predprinimatel'))
                                        Предприниматель
                                    @else
                                        Пользователь
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <!-- Упрощенная навигация -->
                        <nav class="sidebar-nav flex-grow-1 py-3">
                            <ul class="nav flex-column">
                                @if(Auth::user()->hasRole('admin'))
                                    <!-- Навигация администратора -->
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                            <i class="fa-solid fa-gauge-high sidebar-icon"></i>
                                            <span class="sidebar-text">Панель управления</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                            <i class="fa-solid fa-users sidebar-icon"></i>
                                            <span class="sidebar-text">Пользователи</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('admin.templates.*') ? 'active' : '' }}" href="{{ route('admin.templates.index') }}">
                                            <i class="fa-solid fa-palette sidebar-icon"></i>
                                            <span class="sidebar-text">Шаблоны</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('admin.template-categories.*') ? 'active' : '' }}" href="{{ route('admin.template-categories.index') }}">
                                            <i class="fa-solid fa-folder sidebar-icon"></i>
                                            <span class="sidebar-text">Категории шаблонов</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('admin.certificates.*') ? 'active' : '' }}" href="{{ route('admin.certificates.index') }}">
                                            <i class="fa-solid fa-certificate sidebar-icon"></i>
                                            <span class="sidebar-text">Сертификаты</span>
                                        </a>
                                    </li>
                                @endif
                                
                                @if(Auth::user()->hasRole('predprinimatel'))
                                    <!-- Навигация предпринимателя - только самое важное -->
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('entrepreneur.certificates.index') ? 'active' : '' }}" href="{{ route('entrepreneur.certificates.index') }}">
                                            <i class="fa-solid fa-certificate sidebar-icon"></i>
                                            <span class="sidebar-text">Мои сертификаты</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('entrepreneur.certificates.select-template') ? 'active' : '' }}" href="{{ route('entrepreneur.certificates.select-template') }}">
                                            <i class="fa-solid fa-plus sidebar-icon"></i>
                                            <span class="sidebar-text">Создать сертификат</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('entrepreneur.analytics.*') ? 'active' : '' }}" href="{{ route('entrepreneur.analytics.statistics') }}">
                                            <i class="fa-solid fa-chart-simple sidebar-icon"></i>
                                            <span class="sidebar-text">Аналитика</span>
                                        </a>
                                    </li>
                                @endif
                                
                                <!-- Общие упрощенные пункты меню -->
                                <li class="nav-item sidebar-divider"></li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('profile.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                                        <i class="fa-solid fa-user sidebar-icon"></i>
                                        <span class="sidebar-text">Профиль</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracket sidebar-icon"></i>
                                        <span class="sidebar-text">Выход</span>
                                    </a>
                                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </nav>
                        
                        <!-- Подвал бокового меню - упрощен -->
                        <div class="sidebar-footer border-top py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="sidebar-version text-muted small">
                                    <span>Версия 1.0.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- ===================== ОСНОВНОЕ СОДЕРЖИМОЕ ===================== -->
            <main class="flex-grow-1 main-content overflow-auto">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Select2 (добавляем глобально) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ru.js"></script>
    
    <!-- Скрипт для управления боковым меню -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация всплывающих подсказок Bootstrap
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
        
        // Переключение бокового меню
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        if (sidebarToggleBtn) {
            // Определяем все элементы, которые нужно скрывать при сжатии меню
            const sidebarText = document.querySelectorAll('.sidebar-text');
            const sidebarUserName = document.querySelector('.sidebar-user-name');
            const sidebarUserRole = document.querySelector('.sidebar-user-role');
            const sidebarLogoText = document.querySelector('.sidebar-logo-text');
            const sidebarVersion = document.querySelector('.sidebar-version');
            
            // Функция для переключения состояния меню
            function toggleSidebar() {
                const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
                
                // Обновляем иконку кнопки переключения
                const icon = sidebarToggleBtn.querySelector('i');
                if (isCollapsed) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-bars-staggered');
                    
                    // Скрываем текстовые элементы
                    sidebarText.forEach(item => {
                        item.style.opacity = '0';
                        item.style.visibility = 'hidden';
                    });
                    
                    if (sidebarUserName) sidebarUserName.style.display = 'none';
                    if (sidebarUserRole) sidebarUserRole.style.display = 'none';
                    if (sidebarLogoText) sidebarLogoText.style.display = 'none';
                    if (sidebarVersion) sidebarVersion.style.display = 'none';
                } else {
                    icon.classList.remove('fa-bars-staggered');
                    icon.classList.add('fa-bars');
                    
                    // Показываем текстовые элементы после анимации
                    setTimeout(() => {
                        sidebarText.forEach(item => {
                            item.style.opacity = '1';
                            item.style.visibility = 'visible';
                        });
                        
                        if (sidebarUserName) sidebarUserName.style.display = 'block';
                        if (sidebarUserRole) sidebarUserRole.style.display = 'block';
                        if (sidebarLogoText) sidebarLogoText.style.display = 'block';
                        if (sidebarVersion) sidebarVersion.style.display = 'block';
                    }, 300); // Задержка соответствует длительности transition
                }
                
                // Сохраняем состояние в localStorage
                localStorage.setItem('sidebar-collapsed', isCollapsed);
            }
            
            // Обработка клика по кнопке переключения
            sidebarToggleBtn.addEventListener('click', toggleSidebar);
            
            // Восстановление состояния при загрузке
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                document.body.classList.add('sidebar-collapsed');
                
                // Скрываем текстовые элементы сразу
                sidebarText.forEach(item => {
                    item.style.opacity = '0';
                    item.style.visibility = 'hidden';
                });
                
                if (sidebarUserName) sidebarUserName.style.display = 'none';
                if (sidebarUserRole) sidebarUserRole.style.display = 'none';
                if (sidebarLogoText) sidebarLogoText.style.display = 'none';
                if (sidebarVersion) sidebarVersion.style.display = 'none';
                
                // Обновляем также иконку при загрузке
                const icon = sidebarToggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-bars-staggered');
                }
            }
        }
        
        // Обработка мобильного меню
        const mobileLinks = document.querySelectorAll('.offcanvas-body .nav-link');
        mobileLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                const sidebarMenu = document.getElementById('sidebarMenu');
                if (window.innerWidth < 992) {
                    const bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebarMenu);
                    if (bsOffcanvas) {
                        bsOffcanvas.hide();
                    }
                }
            });
        });
    });
    </script>
    
    @stack('scripts')
</body>
</html>
