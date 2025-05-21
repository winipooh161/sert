<div class="folder-system mb-4">
    <div class="d-flex align-items-center mb-3">
        <button type="button" class="btn btn-sm btn-outline-primary me-2 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#createFolderModal">
            <i class="fa-solid fa-folder-plus me-1" style="margin:0 !important;"></i> 
        </button>
        
        <div class="folder-navigation overflow-hidden">
            <div class="btn-group d-flex flex-nowrap" style="min-width: max-content;">
                <a href="{{ route('user.certificates.index') }}" class="btn btn-sm {{ !request('folder') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fa-solid fa-certificate me-1"></i>Все
                </a>
                
                @forelse($folders ?? [] as $folder)
                <a href="{{ route('user.certificates.index', ['folder' => $folder->id]) }}" 
                    class="btn btn-sm {{ request('folder') == $folder->id ? 'btn-primary' : 'btn-outline-secondary' }} folder-btn"
                    data-folder-id="{{ $folder->id }}" data-folder-name="{{ $folder->name }}"
                    data-folder-color="{{ $folder->color }}">
                    <i class="fa-solid fa-folder me-1 text-{{ $folder->color }}"></i>{{ $folder->name }}
                </a>
                @empty
                <span class="btn btn-sm btn-outline-secondary disabled">
                    <i class="fa-solid fa-folder-open me-1"></i>Нет папок
                </span>
                @endforelse
            </div>
        </div>
    </div>
</div>
