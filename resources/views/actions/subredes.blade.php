<div class="d-flex justify-content-center">
<a class="btn btn-primary btn-sm" href="{{ route('subredes.edit', $id) }}"><i class="fas fa-edit"></i></a>

<a class="btn btn-primary btn-sm ml-2" href="{{ route('subredes.show', $id) }}"><i class="fas fa-eye"></i></a>
<!--Trigger Modal -->
<a class="btn btn-danger btn-sm ml-2" data-toggle="modal" data-target="#Subredes_{{$id}}" href><i class="fas fa-trash-alt text-white"></i></a>
<!-- Modal -->
<div class="modal fade" id="Subredes_{{$id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-exclamation-triangle text-warning pr-2"></i> Eliminación de la subred "{{$ip}}"</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Esta seguro que desea eliminar a esta Subred?
      </div>
      <div class="modal-footer">
        <form action="{{ route('subredes.destroy', $id) }}" method="post">
            @csrf
            @method('DELETE')
            <button class="btn btn-primary" type="submit">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

</div>