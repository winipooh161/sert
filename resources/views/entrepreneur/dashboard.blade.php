@extends('layouts.lk')

@section('content')
<div class="container-fluid py-4">
    <!-- Хлебные крошки -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">Личный кабинет</li>
        </ol>
    </nav>
    
    <!-- Приветствие и быстрые действия -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h2 mb-1">Добро пожаловать, {{ Auth::user()->name }}!</h1>
            <p class="text-muted">Управление сертификатами и аналитика</p>
        </div>
        <div class="d-flex mt-3 mt-md-0">
            <a href="{{ route('entrepreneur.certificates.select-template') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-2"></i>Создать сертификат
            </a>
        </div>
    </div>
    
    <!-- Информационная панель -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm hover-lift h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 rounded-circle p-3" style="background: rgba(13,110,253,0.1);">
                            <i class="fa-solid fa-certificate text-primary fa-fw fa-lg"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0">Всего сертификатов</h6>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $totalCertificates }}</h2>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm hover-lift h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 rounded-circle p-3" style="background: rgba(25,135,84,0.1);">
                            <i class="fa-solid fa-check-circle text-success fa-fw fa-lg"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0">Активные</h6>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $activeCertificates }}</h2>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $totalCertificates > 0 ? ($activeCertificates / $totalCertificates * 100) : 0 }}%" 
                             aria-valuenow="{{ $totalCertificates > 0 ? ($activeCertificates / $totalCertificates * 100) : 0 }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        {{ $totalCertificates > 0 ? number_format($activeCertificates / $totalCertificates * 100, 0) : 0 }}% от общего числа
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm hover-lift h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 rounded-circle p-3" style="background: rgba(108,117,125,0.1);">
                            <i class="fa-solid fa-check-double text-secondary fa-fw fa-lg"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0">Использовано</h6>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $usedCertificates }}</h2>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-secondary" role="progressbar" 
                             style="width: {{ $totalCertificates > 0 ? ($usedCertificates / $totalCertificates * 100) : 0 }}%" 
                             aria-valuenow="{{ $totalCertificates > 0 ? ($usedCertificates / $totalCertificates * 100) : 0 }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        {{ $totalCertificates > 0 ? number_format($usedCertificates / $totalCertificates * 100, 0) : 0 }}% от общего числа
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm hover-lift h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 rounded-circle p-3" style="background: rgba(253,126,20,0.1);">
                            <i class="fa-solid fa-ruble-sign text-warning fa-fw fa-lg"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-0">Общая сумма</h6>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ number_format($totalAmount, 0, '.', ' ') }} ₽</h2>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-success d-flex align-items-center">
                            <i class="fa-solid fa-arrow-up me-1"></i> 12%
                        </span>
                        <span class="small text-muted">За последний месяц</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Графики и таблицы -->
    <div class="row g-4 mb-4">
        <!-- График активности -->
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-transparent pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Статистика сертификатов</h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary active">Неделя</button>
                            <button type="button" class="btn btn-outline-secondary">Месяц</button>
                            <button type="button" class="btn btn-outline-secondary">Год</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <!-- Здесь будет график -->
                        <canvas id="certificatesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Статистика по категориям -->
        <div class="col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-header bg-transparent pt-4">
                    <h5 class="mb-0">Распределение статусов</h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="chart-container mb-3" style="position: relative; height:200px; width:200px;">
                        <!-- Здесь будет круговая диаграмма -->
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator status-active me-2"></div>
                                <span>Активные</span>
                            </div>
                            <span class="fw-bold">{{ $activeCertificates }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator status-expired me-2"></div>
                                <span>Истекшие</span>
                            </div>
                            <span class="fw-bold">{{ $expiredCertificates }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator status-pending me-2"></div>
                                <span>Использованные</span>
                            </div>
                            <span class="fw-bold">{{ $usedCertificates }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Последние сертификаты -->
    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center pt-4">
            <h5 class="mb-0">Последние сертификаты</h5>
            <a href="{{ route('entrepreneur.certificates.index') }}" class="btn btn-sm btn-outline-primary">
                Все сертификаты <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">№ Сертификата</th>
                            <th>Получатель</th>
                            <th>Шаблон</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th class="text-end pe-4">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentCertificates as $certificate)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $certificate->certificate_number }}</td>
                                <td>{{ $certificate->recipient_name }}</td>
                                <td>{{ $certificate->template->name }}</td>
                                <td>{{ number_format($certificate->amount, 0, '.', ' ') }} ₽</td>
                                <td>
                                    @if ($certificate->status == 'active')
                                        <span class="badge bg-success">Активен</span>
                                    @elseif ($certificate->status == 'used')
                                        <span class="badge bg-secondary">Использован</span>
                                    @elseif ($certificate->status == 'expired')
                                        <span class="badge bg-warning">Истек</span>
                                    @elseif ($certificate->status == 'canceled')
                                        <span class="badge bg-danger">Отменен</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('entrepreneur.certificates.show', $certificate) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Просмотр">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('entrepreneur.certificates.edit', $certificate) }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Редактировать">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fa-regular fa-folder-open text-muted fa-3x mb-3"></i>
                                        <h6 class="mb-2">У вас еще нет созданных сертификатов</h6>
                                        <p class="text-muted mb-4">Создайте свой первый сертификат прямо сейчас!</p>
                                        <a href="{{ route('entrepreneur.certificates.select-template') }}" class="btn btn-primary">
                                            <i class="fa-solid fa-plus me-2"></i>Создать первый сертификат
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Рекомендации -->
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5>Узнайте больше о возможностях сертификатов</h5>
                    <p class="text-muted mb-0">Мы подготовили для вас подробное руководство по использованию сертификатов для вашего бизнеса.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="#" class="btn btn-outline-primary">Перейти к руководству</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Проверка наличия элемента, чтобы избежать ошибок на страницах, где графиков нет
    if (!document.getElementById('certificatesChart')) return;
    
    // Линейный график для сертификатов
    var ctx = document.getElementById('certificatesChart').getContext('2d');
    var certificatesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
            datasets: [{
                label: 'Создано',
                data: [3, 2, 5, 1, 4, 6, 2],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }, {
                label: 'Использовано',
                data: [1, 1, 2, 0, 3, 1, 1],
                borderColor: '#6c757d',
                backgroundColor: 'rgba(108, 117, 125, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    position: 'top',
                    align: 'end'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
    
    // Проверка наличия элемента статус-чарта
    if (!document.getElementById('statusChart')) return;
    
    // Круговая диаграмма для статусов сертификатов
    var ctxStatus = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Активные', 'Истекшие', 'Использованные'],
            datasets: [{
                data: [
                    {{ $activeCertificates ?? 0 }}, 
                    {{ $expiredCertificates ?? 0 }}, 
                    {{ $usedCertificates ?? 0 }}
                ],
                backgroundColor: [
                    '#198754',
                    '#ffc107',
                    '#6c757d'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
});
</script>
@endpush
@endsection
