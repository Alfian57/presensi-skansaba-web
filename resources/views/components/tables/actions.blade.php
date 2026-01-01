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
        <form action="{{ $deleteRoute }}" method="POST" class="d-inline-block delete-form">
            @csrf
            @method('DELETE')
            <button type="button" 
                    class="btn btn-danger btn-sm btn-action btn-delete" 
                    title="{{ $deleteLabel }}"
                    data-confirm-message="{{ $deleteConfirm }}">
                <img src="/img/delete.png" alt="Delete" class="icon">
            </button>
        </form>
    @endif

    {{ $slot }}
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const message = this.getAttribute('data-confirm-message');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
@endonce
