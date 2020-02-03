@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-folder"></i></i><span class="font-weight-bold ml-2">Documentos</span></div>
                        <div>
                            <a id="categories" href="{{ route('categories.documents.index') }}" class="btn btn-primary mr-2" style="width: 50px" data-placement="left" data-toggle="tooltip" data-original-title="Ver categorias.">
                                <i class="fas fa-boxes"></i>
                            </a>
                            <a id="add_document" href="{{ route('documents.create') }}" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nuevo documento.">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="card-body">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script> 
    var SITEURL = '{{ URL::to('').'/' }}'
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        let clientTable = $('#').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            language:{ url: SITEURL + 'datatables/spanish' },
            ajax: { url: SITEURL + '', type: 'GET' },
            columns: [
                {data: 'nombre', name: 'name'},
                {data: 'creacion', name: 'created_at'},
                {data: 'ult_modificacion', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false}
            ]
        })
    })
    
    let addClient = $('#add_document');
    let categories = $('#categories');
    addClient.tooltip()
    categories.tooltip()
</script>
@endpush