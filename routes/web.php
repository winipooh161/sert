<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Entrepreneur\CertificatesController;
use App\Http\Controllers\Entrepreneur\DashboardController as EntrepreneurDashboardController;
use App\Http\Controllers\Entrepreneur\AnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicCertificateController; // Добавляем новый контроллер
use App\Http\Controllers\User\CertificatesController as UserCertificatesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Подключение маршрутов администратора
require __DIR__.'/admin.php';

Route::get('/', function () {
    // Проверяем авторизацию пользователя
    if (Auth::check()) {
        // Если пользователь авторизован, перенаправляем согласно роли
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->hasRole('predprinimatel')) {
            return redirect()->route('entrepreneur.certificates.index');
        }
        if (auth()->user()->hasRole('user')) {
            return redirect()->route('user.certificates.index');
        }
    }
    
    // Если пользователь не авторизован или нет определенной роли,
    // показываем стандартную страницу приветствия
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
    if (auth()->user()->hasRole('user')) {
        return redirect()->route('user.certificates.index');
    }
    // Если нет специальных ролей, редирект на главную
    return redirect('/');
})->name('home');

// Публичный API для получения анимационных эффектов
Route::get('/api/animation-effects', [App\Http\Controllers\Admin\AnimationEffectsController::class, 'getEffects'])->name('animation-effects.get');

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
    
    // Добавляем маршрут для переключения между ролями
    Route::post('/role/switch', [App\Http\Controllers\RoleSwitcherController::class, 'switchRole'])->name('role.switch');
});

// Маршруты для верификации телефона
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/profile/phone/initiate', [App\Http\Controllers\ProfileController::class, 'initiatePhoneChange'])
        ->name('profile.phone.initiate');
    
    Route::post('/profile/phone/verify', [App\Http\Controllers\ProfileController::class, 'verifyPhoneChange'])
        ->name('profile.phone.verify');
});

// Маршруты для обычного пользователя
Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    // Редирект на страницу сертификатов по умолчанию
    Route::get('/', function () {
        return redirect()->route('user.certificates.index');
    });
    
    // Маршруты для просмотра сертификатов пользователя
    Route::get('/certificates', [UserCertificatesController::class, 'index'])->name('certificates.index');
});

// Маршрут для публичной печати сертификата
Route::get('certificates/{certificate}/print', 
    [App\Http\Controllers\CertificatePrintController::class, 'printPublic'])
    ->name('certificates.print');
// Маршруты для управления папками сертификатов
Route::prefix('user/folders')->name('user.folders.')->middleware(['auth', 'role:user'])->group(function () {
    Route::post('/', [App\Http\Controllers\User\CertificateFolderController::class, 'store'])->name('store');
    Route::put('/{folder}', [App\Http\Controllers\User\CertificateFolderController::class, 'update'])->name('update');
    Route::delete('/{folder}', [App\Http\Controllers\User\CertificateFolderController::class, 'destroy'])->name('destroy');
});

// Маршруты для управления папками пользователя
Route::post('/user/folders', [App\Http\Controllers\User\FoldersController::class, 'store'])
    ->name('user.folders.store')
    ->middleware(['auth', 'role:user']);
    
Route::delete('/user/folders/{folder}', [App\Http\Controllers\User\FoldersController::class, 'destroy'])
    ->name('user.folders.destroy')
    ->middleware(['auth', 'role:user']);

// Маршруты для получения папок сертификата и управления ими
Route::prefix('user/certificates')->name('user.certificates.')->middleware(['auth', 'role:user'])->group(function () {
    // Получение списка папок с информацией о принадлежности сертификата
    Route::get('/{certificate}/folders', [App\Http\Controllers\User\CertificateFolderController::class, 'getFolders'])
        ->name('folders');
    
    // Добавление сертификата в папку
    Route::post('/{certificate}/add-to-folder/{folder}', [App\Http\Controllers\User\CertificateFolderController::class, 'addToFolder'])
        ->name('add-to-folder');
    
    // Удаление сертификата из папки
    Route::delete('/{certificate}/remove-from-folder/{folder}', [App\Http\Controllers\User\CertificateFolderController::class, 'removeFromFolder'])
        ->name('remove-from-folder');
});

// Маршрут для ленивой загрузки сертификатов
Route::get('/user/certificates/load-more', [UserCertificatesController::class, 'loadMore'])
    ->name('user.certificates.load-more')
    ->middleware(['auth', 'role:user']);

// Маршруты для работы с папками сертификатов
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Создание папки
    Route::post('/folders', [App\Http\Controllers\FolderController::class, 'store'])->name('folders.store');
    
    // Удаление папки
    Route::delete('/folders/{folder}', [App\Http\Controllers\FolderController::class, 'destroy'])->name('folders.destroy');
    
    // Получение папок сертификата
    Route::get('/certificates/{certificate}/folders', [App\Http\Controllers\FolderController::class, 'getCertificateFolders'])->name('certificates.folders');
    
    // Добавление сертификата в папку
    Route::post('/certificates/{certificate}/add-to-folder/{folder}', [App\Http\Controllers\FolderController::class, 'addCertificateToFolder'])->name('certificates.add-to-folder');
    
    // Удаление сертификата из папки
    Route::delete('/certificates/{certificate}/remove-from-folder/{folder}', [App\Http\Controllers\FolderController::class, 'removeCertificateFromFolder'])->name('certificates.remove-from-folder');
});

// Маршруты для Telegram бота
Route::prefix('telegram')->name('telegram.')->group(function () {
    Route::post('/webhook', [App\Http\Controllers\TelegramBotController::class, 'webhook'])
        ->name('webhook');
    Route::get('/set-webhook', [App\Http\Controllers\TelegramBotController::class, 'setWebhook'])
        ->name('setWebhook');
    Route::get('/get-me', [App\Http\Controllers\TelegramBotController::class, 'getMe'])
        ->name('getMe');
    Route::get('/get-webhook-info', [App\Http\Controllers\TelegramBotController::class, 'getWebhookInfo'])
        ->name('getWebhookInfo');
    Route::get('/delete-webhook', [App\Http\Controllers\TelegramBotController::class, 'deleteWebhook'])
        ->name('deleteWebhook');
    Route::get('/send-keyboard/{chatId}', [App\Http\Controllers\TelegramBotController::class, 'sendKeyboard'])
        ->name('sendKeyboard');
    Route::get('/send-test/{chatId}', [App\Http\Controllers\TelegramBotController::class, 'sendTestMessage'])
        ->name('sendTestMessage');
});

if (app()->environment('production')) {
    URL::forceScheme('https');
}