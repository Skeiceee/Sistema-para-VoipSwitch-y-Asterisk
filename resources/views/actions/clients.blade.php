<div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('clients.show', $id) }}" class="btn-sm btn-primary text-center" style="width: 32px">
        <i class="far fa-eye"></i>
    </a>
    <a href="{{ route('clients.edit', $id) }}" class="btn-sm btn-primary text-center ml-2" style="width: 32px">
        <i class="far fa-edit"></i>
    </a>
</div>