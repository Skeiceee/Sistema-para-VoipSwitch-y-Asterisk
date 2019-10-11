@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-users"></i><span class="font-weight-bold ml-2">Clientes</span></div>
                        <button id="add_numeration"class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nuevo cliente.">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="table-responsive card-body">
                            <table id="numeration" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                                <thead class="theader-danger">
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Creación</th>
                                        <th>Ultima modificación</th>
                                        <th width="10px">Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script> 
var SITEURL = '{{ URL::to('').'/' }}'
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let numerationTable = $('#numeration').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        language:{ url: SITEURL + 'datatables/spanish' },
        ajax: { url: SITEURL + 'numeracion', type: 'GET' },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'cliente', name: 'cliente'},
            {data: 'descripcion', name: 'descripcion'},
            {data: 'creacion', name: 'creacion'},
            {data: 'ult_modificacion', name: 'ult_modificacion'},
            {data: 'action', name: 'action', orderable: false}
        ]
    })
})

let addNumeration = $('#add_numeration');
addNumeration.tooltip()

</script>
@endpush