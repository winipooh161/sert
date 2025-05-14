<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TemplatesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Entrepreneur\CertificatesController;
use App\Http\Controllers\Entrepreneur\DashboardController as EntrepreneurDashboardController;
use App\Http\Controllers\Entrepreneur\AnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicCertificateController; // Добавляем новый контроллер
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Публичные маршруты для просмотра сертификатов
Route::get('/certificate/{uuid}', [PublicCertificateController::class, 'show'])
    ->name('certificates.public');

// Новый маршрут для превью шаблона в iframe
Route::get('/template-preview/{template}', [App\Http\Controllers\TemplatePreviewController::class, 'show'])
    ->name('template.preview');

// Маршрут по умолчанию после входа - редирект в нужный раздел в зависимости от роли
Route::get('/home', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->user()->hasRole('predprinimatel')) {
        return redirect()->route('entrepreneur.certificates.index');
    }
    // Если нет специальных ролей, редирект на главную
    return redirect('/');
})->name('home');

// Маршруты для администратора
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UsersController::class);
    Route::resource('templates', TemplatesController::class);
    
    // Маршрут для переключения активности шаблона
    Route::post('/templates/{template}/toggle-active', [TemplatesController::class, 'toggleActive'])
        ->name('templates.toggle-active');
    
    // Для отладки: явно прописываем роут для создания шаблона
    Route::post('/templates', [TemplatesController::class, 'store'])->name('templates.store');
});

// Админ - категории шаблонов
Route::prefix('admin/template-categories')->name('admin.template-categories.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\TemplateCategoriesController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\TemplateCategoriesController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\TemplateCategoriesController::class, 'store'])->name('store');
    Route::get('/{templateCategory}/edit', [App\Http\Controllers\Admin\TemplateCategoriesController::class, 'edit'])->name('edit');
    Route::put('/{templateCategory}', [App\Http\Controllers\Admin\TemplateCategoriesController::class, 'update'])->name('update');
    Route::delete('/{templateCategory}', [App\Http\Controllers\Admin\TemplateCategoriesController::class, 'destroy'])->name('destroy');
    Route::post('/{templateCategory}/toggle-active', [App\Http\Controllers\Admin\TemplateCategoriesController::class, 'toggleActive'])->name('toggle-active');
});

// Маршруты для предпринимателя 
Route::prefix('entrepreneur')->name('entrepreneur.')->middleware(['auth', 'role:predprinimatel'])->group(function () {
    // Редирект на страницу сертификатов по умолчанию
    Route::get('/', function () {
        return redirect()->route('entrepreneur.certificates.index');
    });
    
    // Добавляем маршрут для dashboard
    Route::get('/dashboard', [EntrepreneurDashboardController::class, 'index'])->name('dashboard');
    
    // Маршруты для сертификатов
    Route::get('/certificates', [CertificatesController::class, 'index'])->name('certificates.index');
    
    // Маршрут для выбора шаблона перед созданием сертификата
    Route::get('/certificates/select-template', [CertificatesController::class, 'selectTemplate'])
        ->name('certificates.select-template');
    
    // Специальный маршрут для создания сертификата с выбранным шаблоном
    Route::get('/certificates/create/{template}', [CertificatesController::class, 'create'])
        ->name('certificates.create');
    
    Route::post('/certificates/{template}', [CertificatesController::class, 'store'])
        ->name('certificates.store');
    
    // Маршрут для временного сохранения логотипа
    Route::post('/certificates/temp-logo', [CertificatesController::class, 'tempLogo'])
        ->name('certificates.temp-logo');
    
    // Дополнительные маршруты для действий с сертификатами
    Route::post('/certificates/{certificate}/send-email', [CertificatesController::class, 'sendEmail'])
        ->name('certificates.send-email');
    
    // Стандартные маршруты ресурса за исключением create и store
    Route::resource('certificates', CertificatesController::class)
        ->except(['create', 'store']);
    
    // Маршрут для быстрой проверки сертификата по QR-коду
    Route::get('/certificates/admin-verify/{certificate}', [CertificatesController::class, 'adminVerify'])
        ->name('certificates.admin-verify');
    
    // Маршрут для отметки сертификата как использованного
    Route::post('/certificates/{certificate}/mark-as-used', [CertificatesController::class, 'markAsUsed'])
        ->name('certificates.mark-as-used');
    
    // Аналитика и отчеты
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/statistics', [AnalyticsController::class, 'statistics'])->name('statistics');
        Route::get('/reports', [AnalyticsController::class, 'reports'])->name('reports');
    });
    
    // Маршруты для печати сертификатов (предприниматель) - исправлено дублирование префикса
    Route::get('certificates/{certificate}/print', 
        [App\Http\Controllers\Entrepreneur\CertificatePrintController::class, 'showOptions'])
        ->name('certificates.print');
        
    Route::post('certificates/{certificate}/print', 
        [App\Http\Controllers\Entrepreneur\CertificatePrintController::class, 'generatePrintable'])
        ->name('certificates.print.generate');
        
    Route::get('certificates/{certificate}/quick-print', 
        [App\Http\Controllers\Entrepreneur\CertificatePrintController::class, 'generatePrintable'])
        ->name('certificates.quick-print');
});

// Маршруты для профиля (доступны всем авторизованным)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
});

// Дополнительно: создаем отдельную группу маршрутов для сертификатов админа
Route::prefix('admin/certificates')->name('admin.certificates.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [CertificatesController::class, 'index'])->name('index');
    Route::get('/select-template', [CertificatesController::class, 'selectTemplate'])
        ->name('select-template');
    Route::get('/create/{template}', [CertificatesController::class, 'create'])
        ->name('create');
    Route::post('/{template}', [CertificatesController::class, 'store'])
        ->name('store');
    Route::get('/{certificate}', [CertificatesController::class, 'show'])
        ->name('show');
    Route::get('/{certificate}/edit', [CertificatesController::class, 'edit'])
        ->name('edit');
    Route::put('/{certificate}', [CertificatesController::class, 'update'])
        ->name('update');
    Route::delete('/{certificate}', [CertificatesController::class, 'destroy'])
        ->name('destroy');
    Route::post('/{certificate}/send-email', [CertificatesController::class, 'sendEmail'])
        ->name('send-email');
});

// Маршрут для публичной печати сертификата
Route::get('certificates/{certificate}/print', 
    [App\Http\Controllers\CertificatePrintController::class, 'printPublic'])
    ->name('certificates.print');

