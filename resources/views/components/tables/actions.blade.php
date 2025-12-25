@props([
    'showRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'showLabel' => 'Lihat',
    'editLabel' => 'Edit',
    'deleteLabel' => 'Hapus',
    'deleteConfirm' => 'Apakah Anda yakin ingin menghapus data ini?',
])

<div class="d-flex gap-1 flex-wrap">
    @if($showRoute)
        <a href="{{ $showRoute }}" class="btn btn-info btn-sm btn-action" title="{{ $showLabel }}">
            <img src="/img/eye.png" alt="Show" class="icon">
        </a>
    @endif

    @if($editRoute)
        <a href="{{ $editRoute }}" class="btn btn-warning btn-sm btn-action" title="{{ $editLabel }}">
            <img src="/img/edit.png" alt="Edit" class="icon">
        </a>
    @endif

    @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="d-inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="btn btn-danger btn-sm btn-action btn-delete" 
                    title="{{ $deleteLabel }}"
                    onclick="return confirm('{{ $deleteConfirm }}')">
                <img src="/img/delete.png" alt="Delete" class="icon">
            </button>
        </form>
    @endif

    {{ $slot }}
</div>
