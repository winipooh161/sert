@extends('layouts.lk')

@section('content')
<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('items', [
            ['title' => 'Настройки', 'url' => '#']
        ])
    @endcomponent

    @component('components.page-header')
        @slot('title', 'Настройки')
        @slot('subtitle', 'Управление настройками вашего аккаунта')
    @endcomponent
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="nav flex-column nav-pills sticky-top" id="settings-tab" role="tablist" aria-orientation="vertical" style="top: 100px;">
                <button class="nav-link active mb-2" id="account-tab" data-bs-toggle="pill" data-bs-target="#account" type="button" role="tab">
                    <i class="fa-solid fa-user me-2"></i>Аккаунт
                </button>
                <button class="nav-link mb-2" id="notifications-tab" data-bs-toggle="pill" data-bs-target="#notifications" type="button" role="tab">
                    <i class="fa-solid fa-bell me-2"></i>Уведомления
                </button>
                <button class="nav-link mb-2" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                    <i class="fa-solid fa-shield-halved me-2"></i>Безопасность
                </button>
                <button class="nav-link mb-2" id="payments-tab" data-bs-toggle="pill" data-bs-target="#payments" type="button" role="tab">
                    <i class="fa-solid fa-credit-card me-2"></i>Платежные данные
                </button>
                <button class="nav-link mb-2" id="appearance-tab" data-bs-toggle="pill" data-bs-target="#appearance" type="button" role="tab">
                    <i class="fa-solid fa-palette me-2"></i>Внешний вид
                </button>
                <button class="nav-link" id="data-tab" data-bs-toggle="pill" data-bs-target="#data" type="button" role="tab">
                    <i class="fa-solid fa-database me-2"></i>Данные
                </button>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="tab-content" id="settingsTabContent">
                <!-- Настройки аккаунта -->
                <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                    @component('components.card', ['title' => 'Основная информация'])
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="text-center position-relative">
                                        <div class="avatar-upload">
                                            <div class="avatar-preview rounded-circle mb-3 mx-auto">
                                                @if(Auth::user()->avatar)
                                                    <div id="imagePreview" style="background-image: url({{ asset('storage/' . Auth::user()->avatar) }});"></div>
                                                @else
                                                    <div id="imagePreview" class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary">
                                                        <span class="h1">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="avatar-edit position-absolute bottom-0 end-0">
                                                <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png" />
                                                <label for="avatar" class="btn btn-sm btn-primary rounded-circle">
                                                    <i class="fa-solid fa-camera"></i>
                                                </label>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-2">Нажмите на иконку для загрузки фото</small>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Имя</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Телефон</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="language" class="form-label">Язык интерфейса</label>
                                            <select class="form-select" id="language" name="language">
                                                <option value="ru" selected>Русский</option>
                                                <option value="en">English</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h6>Дополнительная информация</h6>
                            <hr>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="company" class="form-label">Название компании</label>
                                    <input type="text" class="form-control" id="company" name="company" value="{{ Auth::user()->company ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="position" class="form-label">Должность</label>
                                    <input type="text" class="form-control" id="position" name="position" value="{{ Auth::user()->position ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label for="bio" class="form-label">О себе</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="3">{{ Auth::user()->bio ?? '' }}</textarea>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save me-2"></i>Сохранить изменения
                                </button>
                            </div>
                        </form>
                    @endcomponent
                </div>
                
                <!-- Настройки уведомлений -->
                <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                    @component('components.card', ['title' => 'Настройки уведомлений'])
                        <form method="POST" action="{{ route('profile.notifications') }}">
                            @csrf
                            @method('PUT')
                            
                            <h6 class="mb-3">Email-уведомления</h6>
                            <div class="mb-4">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_new_certificate" name="notifications[email_new_certificate]" checked>
                                    <label class="form-check-label" for="email_new_certificate">Создание нового сертификата</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_certificate_used" name="notifications[email_certificate_used]" checked>
                                    <label class="form-check-label" for="email_certificate_used">Использование сертификата</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="email_certificate_expiring" name="notifications[email_certificate_expiring]" checked>
                                    <label class="form-check-label" for="email_certificate_expiring">Уведомление об истечении срока действия</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email_news" name="notifications[email_news]">
                                    <label class="form-check-label" for="email_news">Новости и обновления</label>
                                </div>
                            </div>
                            
                            <h6 class="mb-3">Push-уведомления</h6>
                            <div class="mb-4">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="push_new_certificate" name="notifications[push_new_certificate]" checked>
                                    <label class="form-check-label" for="push_new_certificate">Создание нового сертификата</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="push_certificate_used" name="notifications[push_certificate_used]" checked>
                                    <label class="form-check-label" for="push_certificate_used">Использование сертификата</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="push_certificate_expiring" name="notifications[push_certificate_expiring]" checked>
                                    <label class="form-check-label" for="push_certificate_expiring">Уведомление об истечении срока действия</label>
                                </div>
                            </div>
                            
                            <h6 class="mb-3">SMS-уведомления</h6>
                            <div class="mb-4">
                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle me-2"></i>
                                    SMS-уведомления доступны только для верифицированных номеров телефонов.
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="sms_certificate_used" name="notifications[sms_certificate_used]">
                                    <label class="form-check-label" for="sms_certificate_used">Использование сертификата</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sms_certificate_expiring" name="notifications[sms_certificate_expiring]">
                                    <label class="form-check-label" for="sms_certificate_expiring">Уведомление об истечении срока действия</label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save me-2"></i>Сохранить настройки
                                </button>
                            </div>
                        </form>
                    @endcomponent
                </div>
                
                <!-- Безопасность -->
                <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                    @component('components.card', ['title' => 'Смена пароля'])
                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="current_password" class="form-label">Текущий пароль</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="password" class="form-label">Новый пароль</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-lock me-2"></i>Изменить пароль
                                </button>
                            </div>
                        </form>
                    @endcomponent
                    
                    @component('components.card', ['title' => 'Двухфакторная аутентификация', 'class' => 'mt-4'])
                        <div class="alert alert-info">
                            <i class="fa-solid fa-shield-halved me-2"></i>
                            Двухфакторная аутентификация добавляет дополнительный уровень безопасности к вашей учетной записи.
                        </div>
                        
                        <p>Текущий статус: <span class="badge bg-danger">Отключена</span></p>
                        
                        <button type="button" class="btn btn-primary">
                            <i class="fa-solid fa-lock me-2"></i>Включить 2FA
                        </button>
                    @endcomponent
                    
                    @component('components.card', ['title' => 'Активные сеансы', 'class' => 'mt-4'])
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Устройство</th>
                                        <th>IP-адрес</th>
                                        <th>Местоположение</th>
                                        <th>Последняя активность</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fa-brands fa-chrome fa-lg me-2 text-primary"></i>
                                                <div>
                                                    <div>Chrome на Windows</div>
                                                    <small class="text-success">Текущая сессия</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>192.168.1.100</td>
                                        <td>Москва, Россия</td>
                                        <td>Сейчас</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fa-brands fa-safari fa-lg me-2 text-primary"></i>
                                                <div>Safari на iPhone</div>
                                            </div>
                                        </td>
                                        <td>172.16.254.1</td>
                                        <td>Санкт-Петербург, Россия</td>
                                        <td>22.05.2023, 14:30</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-xmark me-1"></i>Завершить
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-danger">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>Завершить все сеансы
                            </button>
                        </div>
                    @endcomponent
                </div>
                
                <!-- Платежные данные -->
                <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                    @component('components.card', ['title' => 'Платежные данные'])
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            Ваши платежные данные будут использоваться для автоматических платежей и выставления счетов.
                        </div>
                        
                        <h6 class="mb-3">Сохраненные карты</h6>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-brands fa-cc-visa fa-2x text-primary me-2"></i>
                                                <div class="fw-bold">•••• 4242</div>
                                            </div>
                                            <div class="badge bg-success">Основная</div>
                                        </div>
                                        <div class="small text-muted">Истекает 09/2025</div>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-danger">Удалить</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-brands fa-cc-mastercard fa-2x text-danger me-2"></i>
                                                <div class="fw-bold">•••• 8456</div>
                                            </div>
                                        </div>
                                        <div class="small text-muted">Истекает 12/2024</div>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-primary">Сделать основной</button>
                                            <button class="btn btn-sm btn-outline-danger ms-2">Удалить</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="mb-3">Добавить новую карту</h6>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="card_number" class="form-label">Номер карты</label>
                                    <input type="text" class="form-control" id="card_number" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="col-md-3">
                                    <label for="expiry" class="form-label">Срок действия</label>
                                    <input type="text" class="form-control" id="expiry" placeholder="ММ/ГГ">
                                </div>
                                <div class="col-md-3">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cvv" placeholder="123">
                                </div>
                                <div class="col-md-6">
                                    <label for="card_holder" class="form-label">Имя владельца</label>
                                    <input type="text" class="form-control" id="card_holder" placeholder="IVAN IVANOV">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-plus me-2"></i>Добавить карту
                                </button>
                            </div>
                        </form>
                    @endcomponent
                    
                    @component('components.card', ['title' => 'История платежей', 'class' => 'mt-4'])
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th>Описание</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#INV-001</td>
                                        <td>15.05.2023</td>
                                        <td>1 200 ₽</td>
                                        <td><span class="badge bg-success">Оплачен</span></td>
                                        <td>Подписка "Бизнес" - 1 месяц</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fa-solid fa-download me-1"></i>Чек
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#INV-002</td>
                                        <td>15.04.2023</td>
                                        <td>1 200 ₽</td>
                                        <td><span class="badge bg-success">Оплачен</span></td>
                                        <td>Подписка "Бизнес" - 1 месяц</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fa-solid fa-download me-1"></i>Чек
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endcomponent
                </div>
                
                <!-- Внешний вид -->
                <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                    @component('components.card', ['title' => 'Настройки интерфейса'])
                        <form>
                            <div class="mb-4">
                                <label class="form-label">Тема</label>
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="theme" id="theme-light" value="light" checked>
                                            <label class="form-check-label" for="theme-light">
                                                Светлая
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="theme" id="theme-dark" value="dark">
                                            <label class="form-check-label" for="theme-dark">
                                                Темная
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="theme" id="theme-system" value="system">
                                            <label class="form-check-label" for="theme-system">
                                                Системная
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Основной цвет</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="color" id="color-blue" value="blue" checked style="width: 24px; height: 24px; background-color: #0d6efd; border-color: #0d6efd;">
                                        <label class="form-check-label" for="color-blue">
                                            Синий
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="color" id="color-green" value="green" style="width: 24px; height: 24px; background-color: #198754; border-color: #198754;">
                                        <label class="form-check-label" for="color-green">
                                            Зеленый
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="color" id="color-purple" value="purple" style="width: 24px; height: 24px; background-color: #6f42c1; border-color: #6f42c1;">
                                        <label class="form-check-label" for="color-purple">
                                            Фиолетовый
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="color" id="color-orange" value="orange" style="width: 24px; height: 24px; background-color: #fd7e14; border-color: #fd7e14;">
                                        <label class="form-check-label" for="color-orange">
                                            Оранжевый
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Настройки интерфейса</label>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="show_animations" checked>
                                    <label class="form-check-label" for="show_animations">Включить анимации</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="compact_sidebar">
                                    <label class="form-check-label" for="compact_sidebar">Компактное боковое меню</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="sticky_header" checked>
                                    <label class="form-check-label" for="sticky_header">Закрепленная верхняя панель</label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save me-2"></i>Сохранить настройки
                                </button>
                            </div>
                        </form>
                    @endcomponent
                </div>
                
                <!-- Данные -->
                <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
                    @component('components.card', ['title' => 'Управление данными'])
                        <div class="mb-4">
                            <h6>Экспорт данных</h6>
                            <p class="text-muted">Вы можете экспортировать все свои данные в различных форматах:</p>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-outline-primary">
                                    <i class="fa-solid fa-file-excel me-2"></i>Экспорт в Excel
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="fa-solid fa-file-csv me-2"></i>Экспорт в CSV
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="fa-solid fa-file-pdf me-2"></i>Экспорт в PDF
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Импорт данных</h6>
                            <p class="text-muted">Загрузите данные из файла:</p>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="data_import">
                                <button class="btn btn-outline-primary" type="button">Импорт</button>
                            </div>
                            <div class="form-text">
                                Поддерживаемые форматы: Excel (.xlsx), CSV (.csv)
                            </div>
                        </div>
                        
                        <div class="alert alert-danger">
                            <h6 class="alert-heading mb-1">Удаление аккаунта</h6>
                            <p class="mb-2">При удалении аккаунта будут безвозвратно удалены все ваши данные, включая сертификаты и платежную информацию.</p>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fa-solid fa-trash me-2"></i>Удалить аккаунт
                            </button>
                        </div>
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно удаления аккаунта -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Это действие необратимо. Все ваши данные будут полностью удалены.
                </div>
                <p>Пожалуйста, введите <strong>{{ Auth::user()->email }}</strong> для подтверждения:</p>
                <div class="mb-3">
                    <input type="text" class="form-control" id="confirm_email">
                </div>
                <div class="mb-3">
                    <label for="delete_reason" class="form-label">Причина удаления (необязательно):</label>
                    <select class="form-select" id="delete_reason">
                        <option value="" selected>Выберите причину...</option>
                        <option value="no_need">Больше не нужен сервис</option>
                        <option value="another_service">Перешёл на другой сервис</option>
                        <option value="not_satisfied">Не устраивает функционал</option>
                        <option value="too_expensive">Слишком дорого</option>
                        <option value="other">Другое</option>
                    </select>
                </div>
                <div class="mb-3" id="other_reason_container" style="display: none;">
                    <label for="other_reason" class="form-label">Укажите свою причину:</label>
                    <textarea class="form-control" id="other_reason"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fa-solid fa-trash me-2"></i>Удалить аккаунт
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Переключение видимости пароля
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Изменение иконки
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
    
    // Предпросмотр аватара
    document.getElementById('avatar').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').style.backgroundImage = `url(${e.target.result})`;
                document.getElementById('imagePreview').innerHTML = '';
                document.getElementById('imagePreview').classList.remove('bg-primary', 'bg-opacity-10', 'd-flex', 'align-items-center', 'justify-content-center', 'text-primary');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Показать поле "Другая причина" при выборе соответствующей опции
    document.getElementById('delete_reason').addEventListener('change', function() {
        const otherReasonContainer = document.getElementById('other_reason_container');
        otherReasonContainer.style.display = this.value === 'other' ? 'block' : 'none';
    });
    
    // Проверка email для подтверждения удаления
    document.getElementById('confirm_email').addEventListener('input', function() {
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn.disabled = this.value !== "{{ Auth::user()->email }}";
    });
    
    // Обработка отправки формы удаления
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        alert('В реальном приложении здесь был бы запрос на удаление аккаунта');
        // Здесь должен быть код для отправки формы удаления
    });
});
</script>

<style>
.avatar-upload {
    position: relative;
    max-width: 150px;
    margin: 0 auto;
}

.avatar-preview {
    width: 120px;
    height: 120px;
    position: relative;
    overflow: hidden;
}

.avatar-preview > div {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}

.avatar-edit {
    position: absolute;
    right: 5px;
    bottom: 5px;
    z-index: 1;
}

.avatar-edit input {
    display: none;
}
</style>
@endpush
@endsection
