@extends('layouts.lk')

@section('content')
<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('items', [
            ['title' => 'Аналитика', 'url' => '#'],
            ['title' => 'Статистика', 'url' => '#']
        ])
    @endcomponent

    @component('components.page-header')
        @slot('title', 'Статистика')
        @slot('subtitle', 'Показатели эффективности и ключевые метрики')
    @endcomponent

    @if(!isset($totalCertificates) || $totalCertificates == 0)
        <!-- Блок, отображаемый при отсутствии данных -->
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <i class="fa-solid fa-chart-simple fa-4x text-muted opacity-25"></i>
                </div>
                <h4>Данных для анализа пока нет</h4>
                <p class="text-muted mb-4">Для получения статистики необходимо создать и использовать сертификаты.</p>
                <a href="{{ route('entrepreneur.certificates.select-template') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-2"></i>Создать первый сертификат
                </a>
            </div>
        </div>
    @else
        <!-- Вкладки для переключения между типами статистики -->
        <ul class="nav nav-pills nav-justified mb-4" id="statisticsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
                    <i class="fa-solid fa-chart-pie me-2"></i>Обзор
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sales-tab" data-bs-toggle="pill" data-bs-target="#sales" type="button" role="tab" aria-controls="sales" aria-selected="false">
                    <i class="fa-solid fa-money-bill-trend-up me-2"></i>Продажи
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="customers-tab" data-bs-toggle="pill" data-bs-target="#customers" type="button" role="tab" aria-controls="customers" aria-selected="false">
                    <i class="fa-solid fa-users me-2"></i>Получатели
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="templates-tab" data-bs-toggle="pill" data-bs-target="#templates" type="button" role="tab" aria-controls="templates" aria-selected="false">
                    <i class="fa-solid fa-palette me-2"></i>Шаблоны
                </button>
            </li>
        </ul>
        
        <!-- Содержимое вкладок -->
        <div class="tab-content" id="statisticsTabsContent">
            <!-- Вкладка "Обзор" -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="row g-4">
                    <!-- Статистика по сертификатам -->
                    <div class="col-md-6">
                        @component('components.card', ['title' => 'Статистика сертификатов'])
                            @if(isset($totalCertificates) && $totalCertificates > 0)
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <canvas id="certificatesOverviewChart"></canvas>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <p class="text-muted">Недостаточно данных для построения графика</p>
                                </div>
                            @endif
                        @endcomponent
                    </div>
                    
                    <!-- Тепловая карта активности -->
                    <div class="col-md-6">
                        @component('components.card', ['title' => 'Активность по дням недели'])
                            @if(isset($totalCertificates) && $totalCertificates > 5) {{-- Требуется больше данных для тепловой карты --}}
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <canvas id="heatmapChart"></canvas>
                                </div>
                                <div class="small text-muted text-center mt-2">Интенсивность создания сертификатов по дням недели</div>
                            @else
                                <div class="text-center py-5">
                                    <p class="text-muted">Для построения тепловой карты необходимо больше данных (минимум 5 сертификатов)</p>
                                </div>
                            @endif
                        @endcomponent
                    </div>
                    
                    <!-- Эффективность по месяцам -->
                    <div class="col-md-12">
                        @component('components.card', ['title' => 'Эффективность по месяцам'])
                            @if(isset($monthlyStats) && count($monthlyStats) > 1) {{-- Для графика по месяцам нужны данные минимум за 2 месяца --}}
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <canvas id="monthlyPerformanceChart"></canvas>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <p class="text-muted">Для анализа эффективности нужны данные минимум за 2 месяца</p>
                                </div>
                            @endif
                        @endcomponent
                    </div>
                    
                    <!-- Ключевые показатели эффективности -->
                    <div class="col-md-12">
                        @component('components.card', ['title' => 'Ключевые показатели эффективности (KPI)'])
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <div class="border rounded-4 p-3 text-center">
                                        <h6 class="text-muted mb-3">Коэффициент конверсии</h6>
                                        <h2 class="mb-0">
                                            @if(isset($totalCertificates) && $totalCertificates > 0)
                                                {{ number_format(($usedCertificates / $totalCertificates * 100), 1) }}%
                                            @else
                                                0%
                                            @endif
                                        </h2>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                style="width: {{ isset($totalCertificates) && $totalCertificates > 0 ? ($usedCertificates / $totalCertificates * 100) : 0 }}%" 
                                                aria-valuenow="{{ isset($totalCertificates) && $totalCertificates > 0 ? ($usedCertificates / $totalCertificates * 100) : 0 }}" 
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        @if(isset($conversionChange))
                                            <p class="small {{ $conversionChange >= 0 ? 'text-success' : 'text-danger' }} mt-2 mb-0">
                                                <i class="fa-solid fa-arrow-{{ $conversionChange >= 0 ? 'up' : 'down' }} me-1"></i> 
                                                {{ abs($conversionChange) }}% к прошлому периоду
                                            </p>
                                        @else
                                            <p class="small text-muted mt-2 mb-0">Нет данных за предыдущий период</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="border rounded-4 p-3 text-center">
                                        <h6 class="text-muted mb-3">Средний чек</h6>
                                        <h2 class="mb-0">
                                            @if(isset($totalCertificates) && $totalCertificates > 0 && isset($totalAmount))
                                                {{ number_format($totalAmount / $totalCertificates, 0, '.', ' ') }} ₽
                                            @else
                                                0 ₽
                                            @endif
                                        </h2>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="small text-muted mt-2 mb-0">Нет данных за предыдущий период</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="border rounded-4 p-3 text-center">
                                        <h6 class="text-muted mb-3">Кол-во в месяц</h6>
                                        <h2 class="mb-0">
                                            @if(isset($certificatesPerMonth))
                                                {{ number_format($certificatesPerMonth, 1) }}
                                            @else
                                                0
                                            @endif
                                        </h2>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="small text-muted mt-2 mb-0">Недостаточно данных</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="border rounded-4 p-3 text-center">
                                        <h6 class="text-muted mb-3">Ср. время активации</h6>
                                        <h2 class="mb-0">
                                            @if(isset($avgActivationTime))
                                                {{ $avgActivationTime }} дн.
                                            @else
                                                - дн.
                                            @endif
                                        </h2>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="small text-muted mt-2 mb-0">Недостаточно данных</p>
                                    </div>
                                </div>
                            </div>
                        @endcomponent
                    </div>
                </div>
            </div>
            
            <!-- Вкладка "Продажи" -->
            <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                @if(isset($totalCertificates) && $totalCertificates > 0)
                    <div class="row g-4">
                        <div class="col-md-8">
                            @component('components.card', ['title' => 'Динамика продаж'])
                                <div class="chart-container" style="position: relative; height:400px;">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            @endcomponent
                        </div>
                        
                        <div class="col-md-4">
                            @component('components.card', ['title' => 'Распределение по суммам'])
                                <div class="chart-container" style="position: relative; height:400px;">
                                    <canvas id="amountDistributionChart"></canvas>
                                </div>
                            @endcomponent
                        </div>
                        
                        <div class="col-md-12">
                            @component('components.card', ['title' => 'Топ продаж'])
                                @if(isset($topCertificates) && count($topCertificates) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>№ Сертификата</th>
                                                    <th>Дата</th>
                                                    <th>Получатель</th>
                                                    <th>Шаблон</th>
                                                    <th>Сумма</th>
                                                    <th>Статус</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topCertificates as $cert)
                                                <tr>
                                                    <td>{{ $cert->certificate_number }}</td>
                                                    <td>{{ $cert->created_at->format('d.m.Y') }}</td>
                                                    <td>{{ $cert->recipient_name }}</td>
                                                    <td>{{ $cert->template->name }}</td>
                                                    <td>{{ number_format($cert->amount, 0, '.', ' ') }} ₽</td>
                                                    <td>
                                                        @if($cert->status == 'active')
                                                            <span class="badge bg-success">Активен</span>
                                                        @elseif($cert->status == 'used')
                                                            <span class="badge bg-secondary">Использован</span>
                                                        @elseif($cert->status == 'expired')
                                                            <span class="badge bg-warning">Истек</span>
                                                        @elseif($cert->status == 'canceled')
                                                            <span class="badge bg-danger">Отменен</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <p class="text-muted">Данных о сертификатах пока нет</p>
                                    </div>
                                @endif
                            @endcomponent
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-chart-line fa-3x text-muted opacity-25"></i>
                        </div>
                        <h5>Нет данных для анализа продаж</h5>
                        <p class="text-muted">Создайте сертификаты, чтобы увидеть статистику продаж</p>
                    </div>
                @endif
            </div>
            
            <!-- Вкладка "Получатели" -->
            <div class="tab-pane fade" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                @if(isset($totalCertificates) && $totalCertificates > 0)
                    <div class="row g-4">
                        <div class="col-md-6">
                            @component('components.card', ['title' => 'Лояльность клиентов'])
                                @if(isset($recipientStats) && count($recipientStats) > 0)
                                    <div class="chart-container" style="position: relative; height:350px;">
                                        <canvas id="loyaltyChart"></canvas>
                                    </div>
                                    <div class="small text-muted text-center mt-2">Распределение клиентов по количеству покупок</div>
                                @else
                                    <div class="text-center py-5">
                                        <p class="text-muted">Недостаточно данных для анализа лояльности</p>
                                    </div>
                                @endif
                            @endcomponent
                        </div>
                        
                        <div class="col-md-6">
                            @component('components.card', ['title' => 'Демография получателей'])
                                <div class="text-center py-5">
                                    <p class="text-muted">Данные о демографии недоступны</p>
                                </div>
                            @endcomponent
                        </div>
                        
                        <div class="col-md-12">
                            @component('components.card', ['title' => 'Топ получателей'])
                                @if(isset($topRecipients) && count($topRecipients) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Получатель</th>
                                                    <th>Email</th>
                                                    <th>Кол-во сертификатов</th>
                                                    <th>Общая сумма</th>
                                                    <th>Средний чек</th>
                                                    <th>Последняя активность</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topRecipients as $recipient)
                                                <tr>
                                                    <td>{{ $recipient->name }}</td>
                                                    <td>{{ $recipient->email }}</td>
                                                    <td>{{ $recipient->certificates_count }}</td>
                                                    <td>{{ number_format($recipient->total_amount, 0, '.', ' ') }} ₽</td>
                                                    <td>{{ number_format($recipient->average_amount, 0, '.', ' ') }} ₽</td>
                                                    <td>{{ $recipient->last_activity->format('d.m.Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <p class="text-muted">Нет данных о получателях</p>
                                    </div>
                                @endif
                            @endcomponent
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-users fa-3x text-muted opacity-25"></i>
                        </div>
                        <h5>Нет данных о получателях</h5>
                        <p class="text-muted">Создайте сертификаты для получения статистики</p>
                    </div>
                @endif
            </div>
            
            <!-- Вкладка "Шаблоны" -->
            <div class="tab-pane fade" id="templates" role="tabpanel" aria-labelledby="templates-tab">
                @if(isset($templateStats) && count($templateStats) > 0)
                    <div class="row g-4">
                        <div class="col-md-6">
                            @component('components.card', ['title' => 'Популярность шаблонов'])
                                <div class="chart-container" style="position: relative; height:400px;">
                                    <canvas id="templatesPopularityChart"></canvas>
                                </div>
                            @endcomponent
                        </div>
                        
                        <div class="col-md-6">
                            @component('components.card', ['title' => 'Конверсия по шаблонам'])
                                <div class="chart-container" style="position: relative; height:400px;">
                                    <canvas id="templatesConversionChart"></canvas>
                                </div>
                            @endcomponent
                        </div>
                        
                        <div class="col-md-12">
                            @component('components.card', ['title' => 'Эффективность шаблонов'])
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Шаблон</th>
                                                <th>Всего создано</th>
                                                <th>Использовано</th>
                                                <th>Конверсия</th>
                                                <th>Общая сумма</th>
                                                <th>Средний чек</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($templateStats as $stat)
                                            <tr>
                                                <td>{{ $stat['template_name'] }}</td>
                                                <td>{{ $stat['count'] }}</td>
                                                <td>{{ $stat['used'] }}</td>
                                                <td>{{ number_format(($stat['used'] / $stat['count'] * 100), 1) }}%</td>
                                                <td>{{ number_format($stat['amount'], 0, '.', ' ') }} ₽</td>
                                                <td>{{ number_format($stat['amount'] / $stat['count'], 0, '.', ' ') }} ₽</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr class="fw-bold">
                                                <td>Всего</td>
                                                <td>{{ $totalCertificates }}</td>
                                                <td>{{ $usedCertificates }}</td>
                                                <td>{{ number_format(($usedCertificates / $totalCertificates * 100), 1) }}%</td>
                                                <td>{{ number_format($totalAmount, 0, '.', ' ') }} ₽</td>
                                                <td>{{ number_format($totalAmount / $totalCertificates, 0, '.', ' ') }} ₽</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endcomponent
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fa-solid fa-palette fa-3x text-muted opacity-25"></i>
                        </div>
                        <h5>Нет данных для анализа шаблонов</h5>
                        <p class="text-muted">Создайте сертификаты с разными шаблонами для сбора статистики</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, есть ли на странице графики и данные для них
    @if(isset($totalCertificates) && $totalCertificates > 0)
    
    // Данные для графиков из бэкенда
    const certificateData = {
        active: {{ $activeCertificates }},
        used: {{ $usedCertificates }},
        expired: {{ $expiredCertificates }},
        canceled: {{ $canceledCertificates }}
    };

    // Инициализация только тех графиков, для которых у нас есть данные
    if (document.getElementById('certificatesOverviewChart')) {
        const certificatesOverviewCtx = document.getElementById('certificatesOverviewChart').getContext('2d');
        new Chart(certificatesOverviewCtx, {
            type: 'bar',
            data: {
                labels: ['Активные', 'Использованные', 'Истекшие', 'Отмененные'],
                datasets: [{
                    label: 'Количество сертификатов',
                    data: [
                        certificateData.active,
                        certificateData.used,
                        certificateData.expired,
                        certificateData.canceled
                    ],
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.6)',
                        'rgba(108, 117, 125, 0.6)',
                        'rgba(255, 193, 7, 0.6)',
                        'rgba(220, 53, 69, 0.6)'
                    ],
                    borderColor: [
                        'rgba(25, 135, 84, 1)',
                        'rgba(108, 117, 125, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    }

    // Другие графики инициализируются только при наличии данных
    @endif
});
</script>

<style>
/* Стили для индикаторов статуса - сохраняем их */
.status-indicator {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: inline-block;
}

.status-active {
    background-color: #198754;
}

.status-expired {
    background-color: #ffc107;
}

.status-pending {
    background-color: #6c757d;
}

.status-cancelled {
    background-color: #dc3545;
}
</style>
@endpush
@endsection
