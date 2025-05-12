@extends('layouts.lk')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Мои сертификаты</h1>
        <a href="{{ route('entrepreneur.certificates.select-template') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Создать сертификат
        </a>
    </div>

    <!-- Сообщения об успешных операциях -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Фильтры -->
    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('entrepreneur.certificates.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="filter_status" class="form-label small">Статус</label>
                    <select id="filter_status" name="status" class="form-select">
                        <option value="">Все статусы</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                        <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Использованные</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Истекшие</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Отмененные</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filter_search" class="form-label small">Поиск</label>
                    <input type="text" id="filter_search" name="search" class="form-control" placeholder="Поиск по номеру или получателю" value="{{ request('search') }}">
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fa-solid fa-filter me-1"></i>Применить
                    </button>
                    <a href="{{ route('entrepreneur.certificates.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-xmark me-1"></i>Сбросить
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Таблица сертификатов -->
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">Номер</th>
                            <th class="py-3">Получатель</th>
                            <th class="py-3">Шаблон</th>
                            <th class="py-3">Сумма</th>
                            <th class="py-3">Срок действия</th>
                            <th class="py-3">Статус</th>
                            <th class="py-3">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($certificates as $certificate)
                            <tr>
                                <td>{{ $certificate->certificate_number }}</td>
                                <td>{{ $certificate->recipient_name }}</td>
                                <td>{{ $certificate->template->name }}</td>
                                <td>{{ number_format($certificate->amount, 0, '.', ' ') }} ₽</td>
                                <td>
                                    <span class="small">От: {{ $certificate->valid_from->format('d.m.Y') }}</span><br>
                                    <span class="small">До: {{ $certificate->valid_until->format('d.m.Y') }}</span>
                                </td>
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
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('entrepreneur.certificates.show', $certificate) }}" class="btn btn-outline-primary" title="Просмотр">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @if($certificate->status == 'active')
                                        <a href="{{ route('entrepreneur.certificates.edit', $certificate) }}" class="btn btn-outline-secondary" title="Редактировать">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        @endif
                                        <button type="button" class="btn btn-outline-danger" title="Отменить" 
                                                onclick="if(confirm('Вы действительно хотите отменить этот сертификат?')) { document.getElementById('delete-certificate-{{ $certificate->id }}').submit(); }">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                        <form id="delete-certificate-{{ $certificate->id }}" action="{{ route('entrepreneur.certificates.destroy', $certificate) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fa-solid fa-certificate text-muted fa-3x mb-3"></i>
                                        <h5 class="mb-2">У вас еще нет сертификатов</h5>
                                        <p class="text-muted mb-4">Создайте свой первый подарочный сертификат прямо сейчас!</p>
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
    
    <!-- Пагинация -->
    <div class="mt-4">
        {{ $certificates->withQueryString()->links() }}
    </div>

    @if(count($certificates) > 0)
    <!-- Краткая статистика -->
    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm text-center">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-3 mb-3">
                        <i class="fa-solid fa-certificate text-primary fa-lg"></i>
                    </div>
                    <h3>{{ $certificates->total() }}</h3>
                    <p class="text-muted mb-0">Всего сертификатов</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm text-center">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 p-3 mb-3">
                        <i class="fa-solid fa-check-circle text-success fa-lg"></i>
                    </div>
                    <h3>{{ $activeCount ?? 0 }}</h3>
                    <p class="text-muted mb-0">Активных сертификатов</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm text-center">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 p-3 mb-3">
                        <i class="fa-solid fa-coins text-warning fa-lg"></i>
                    </div>
                    <h3>{{ number_format($totalAmount ?? 0, 0, '.', ' ') }} ₽</h3>
                    <p class="text-muted mb-0">Общая сумма сертификатов</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
