@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-boxes"></i><span class="font-weight-bold ml-2">Categorias</span></div>
                        <div>
                            <a id="documents" href="{{ route('documents.index') }}" class="btn btn-primary mr-2" data-placement="left" data-toggle="tooltip" data-original-title="Ver documentos.">
                                <i class="fas fa-folder"></i>
                            </a>
                            <a id="add_category" href="{{ route('categories.documents.create') }}" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nueva categoria.">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="table-responsive card-body">
                            <table id="categories" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                                <thead class="theader-danger">
                                    <tr>
                                        <th>Nombre</th>
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
    
        let categoriesTable = $('#categories').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            language:{ url: SITEURL + 'datatables/spanish' },
            ajax: { url: SITEURL + '/categorias/documentos', type: 'GET' },
            columns: [
                {data: 'name', name: 'name'},
            ]
        })
    })
    
    let addClient = $('#add_category');
    let documents = $('#documents');
    addClient.tooltip()
    documents.tooltip()
</script>
@endpush