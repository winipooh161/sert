@extends('layouts.lk')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <!-- Система папок для сертификатов -->
    @include('user.certificates.partials._folder_system', ['folders' => $folders, 'currentFolder' => $currentFolder ?? null])
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mb-md-4 gap-2">
        <h1 class="fw-bold fs-4 fs-md-3 mb-0">
            @if(request('folder') && isset($currentFolder))
                {{ $currentFolder->name }}
            @else
                Мои сертификаты
            @endif
        </h1>
        
        <button type="button" class="btn btn-sm btn-outline-info" onclick="startUserCertificatesTour()">
            <i class="fa-solid fa-question-circle me-1"></i>Обучение
        </button>
    </div>

    <!-- Сообщения об ошибках/успешных операциях -->
    @include('user.certificates.partials._alerts')

    <!-- Группируем сертификаты по дате создания и сортируем от новых к старым -->
    @php
        $certificatesByDate = $certificates->groupBy(function($certificate) {
            return $certificate->created_at->format('Y-m-d');
        })->sortKeysDesc(); // Сортировка дат по убыванию, чтобы свежие были сверху
    @endphp

    @forelse ($certificatesByDate as $date => $dateGroupCertificates)
        <!-- Заголовок для группы сертификатов по дате -->
        @include('user.certificates.partials._date_heading', ['date' => $date])

        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
            @foreach ($dateGroupCertificates as $certificate)
                <div class="col">
                    @include('user.certificates.partials._certificate_card', ['certificate' => $certificate])
                </div>
            @endforeach
        </div>
    @empty
        @include('user.certificates.partials._empty_state')
    @endforelse

    <!-- Итоговая карточка со статистикой -->
    @if(count($certificates) > 0)
        @include('user.certificates.partials._statistics', ['certificates' => $certificates])
    @endif
    
    <!-- Пагинация -->
    <div class="mt-4 d-flex justify-content-center pagination">
        {{ $certificates->withQueryString()->links() }}
    </div>
</div>

<!-- Подключение модальных окон -->
@include('user.certificates.partials._modals')

<!-- Подключение скриптов -->
@include('user.certificates.partials._scripts')

<!-- Подключение стилей -->
@include('user.certificates.partials._styles')
@endsection
