@extends('layouts.lk')

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4 fw-bold ">Выберите шаблон для сертификата</h1>
    
    <div class="mb-5">
        <p class="lead">Выберите дизайн для вашего подарочного сертификата</p>
    </div>
    
    <div class="row g-4">
        @forelse ($templates as $template)
            <div class="col-md-6 col-lg-4">
                <div class="card template-card shadow-sm h-100">
                    <div class="card-img-wrapper">
                        @if ($template->image)
                            <img src="{{ asset('storage/' . $template->image) }}" class="card-img-top" alt="{{ $template->name }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                <i class="fa-solid fa-image text-muted fa-3x"></i>
                            </div>
                        @endif
                        
                        @if ($template->is_premium)
                            <span class="badge badge-premium">Премиум</span>
                        @endif
                        
                        <div class="template-overlay d-flex align-items-center justify-content-center">
                            <a href="#" class="btn btn-sm btn-light me-2" data-bs-toggle="modal" data-bs-target="#previewModal-{{ $template->id }}">
                                <i class="fa-solid fa-eye me-1"></i>Предпросмотр
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ $template->name }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($template->description, 80) }}</p>
                        <a href="{{ route('entrepreneur.certificates.create', $template) }}" class="btn btn-primary">
                            <i class="fa-solid fa-plus me-2"></i>Выбрать этот шаблон
                        </a>
                    </div>
                </div>
                
                <!-- Модальное окно с предпросмотром -->
                <div class="modal fade" id="previewModal-{{ $template->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Предпросмотр "{{ $template->name }}"</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="preview-container border p-0 rounded">
                                    <iframe src="{{ route('template.preview', $template) }}" class="template-preview-iframe" frameborder="0" width="100%" height="500"></iframe>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                <a href="{{ route('entrepreneur.certificates.create', $template) }}" class="btn btn-primary">
                                    Выбрать шаблон
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center p-5">
                    <i class="fa-solid fa-info-circle fa-2x mb-3"></i>
                    <h5>Шаблоны сертификатов отсутствуют</h5>
                    <p class="mb-0">Пожалуйста, свяжитесь с администратором для добавления шаблонов.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
/* Стилизация для iframe */
.template-preview-iframe {
    width: 100%;
    height: 500px;
    border: none;
    overflow: auto;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Удаляем старый код, который пытался модифицировать HTML шаблона
    // Теперь мы отображаем шаблоны через iframe
});
</script>
@endsection
