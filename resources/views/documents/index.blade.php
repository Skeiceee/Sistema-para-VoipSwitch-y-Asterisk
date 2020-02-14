@extends('layouts.app')
@section('content')
<div class="px-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-folder"></i><span class="font-weight-bold ml-2">Documentos</span></div>
                        <div>
                            <a id="categories" href="{{ route('categories.documents.index') }}" class="btn btn-primary mr-2" data-placement="left" data-toggle="tooltip" data-original-title="Ver categorias.">
                                <i class="fas fa-boxes"></i>
                            </a>
                            <a id="add_document" href="{{ route('documents.create') }}" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nuevo documento.">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="table-responsive card-body">
                            <table id="documents" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                                <thead class="theader-danger">
                                    <tr>
                                        <th>Nombre</th> 
                                        <th>Descripción</th>
                                        <th>Categoria</th>
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
    
        let documentsTable = $('#documents').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            language:{ url: SITEURL + 'datatables/spanish' },
            ajax: { url: SITEURL + '/documentos', type: 'GET' },
            columns: [
                {data: 'nombre', name: 'documents.name'},
                {data: 'descripcion', name: 'documents.description'} ,
                {data: 'name_category', name: 'documents_categories.name'},
                {data: 'action', name: 'action', orderable: false}
            ]
        })
        $('body').on('click', '.download', function () {
            var file_id = $(this).data('id')
            window.location = SITEURL + 'documentos/download/'+ file_id
        }); 
    })
    
    let addClient = $('#add_document');
    let categories = $('#categories');
    addClient.tooltip()
    categories.tooltip()
</script>
@endpush