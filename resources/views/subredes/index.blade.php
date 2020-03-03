@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-server"></i><span class="font-weight-bold ml-2">Subredes</span></div>
                        <div>
                            <a id="add_subred" href="{{ route('subredes.create') }}" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nueva subred.">
                                <i class="fas fa-plus"></i>
                            </a>
                            <a id="add_subred" href="{{ route('restore.index') }}" class="btn btn-primary" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nueva subred.">
                                <span class="font-weight-bold">Importar Subred</span>
                            </a>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="table-responsive card-body">
                            <table id="Subredes" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                                <thead class="theade-danger">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Dirección IP</th>
                                    <th>Puerta de enlace</th>
                                    <th>Máscara de red</th>
                                    <th class="no-sort" width=10>Acciones</th>
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
<script></script>
<script>
    var SITEURL = '{{ URL::to('').'/' }}'
    $(document).ready(function(){
        let token = $('meta[name=csrf_token]').attr("content")
        $('#Subredes').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        language:{ url: SITEURL + 'datatables/spanish' },
        ajax: { url: SITEURL + '/subredes', type: 'GET' },
        columns:[
            {data: 'name', name: 'name'},
            {data: 'ip', name: 'ip'},
            {data: 'gateway', name: 'gateway'},
            {data: 'mask', name: 'mask'},
            {data: 'btn', orderable: false, searchable: false},
        ]
        });
    });

    let addSubred = $("#add_subred")
    addSubred.tooltip()
</script>
@endpush