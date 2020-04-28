<div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('recurringcharge.edit', $id) }}" class="btn-sm btn-primary text-center" style="width: 32px">
        <i class="far fa-edit"></i>
    </a>
    <a class="btn-sm btn-danger text-center ml-2" data-toggle="modal" style="width: 32px" data-target="#RecurrinCharges_{{$id}}" href>
        <i class="fas fa-times"></i>
    </a>
</div>

<div class="modal fade" id="RecurrinCharges_{{$id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-exclamation-triangle text-warning pr-2"></i> Eliminación de la cuenta "{{ $name }}"</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fas fa-times"></i></span>
          </button>
        </div>
        <div class="modal-body">
          ¿Esta seguro que desea eliminar este cargo recurrente?
        </div>
        <div class="modal-footer">
          <form action="{{ route('recurringcharge.destroy', $id) }}" method="post">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger" type="submit">Eliminar</button>
          </form>
          <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>