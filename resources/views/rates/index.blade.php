@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-money-bill-wave"></i><span class="font-weight-bold ml-2">Tarifas</span></div>
                        <a id="add_rate" href="{{ route('rates.create') }}" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nueva tarifa.">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                    <hr class="my-3">
                    <div class="mb-3"><i class="fas fa-search"></i><span class="font-weight-bold ml-2">Buscar tarifas por el portador</span></div>
                    <div class="form-group">
                        <select name="ido" class="form-control form-control-chosen">
                            @foreach ($portadores as $portador)
                                <option value="{{ $portador->id_port }}">{{ $portador->id_port }} - {{ strtoupper($portador->portador) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('common.status')
                    <div class="card">
                        <div class="table-responsive card-body">
                            <table id="rates" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                                <thead class="theader-danger">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tarifa Normal</th>
                                        <th>Tarifa Reducido</th>
                                        <th>Tarifa Nocturno</th>
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
<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/chosen.min.js')}}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script> 
var SITEURL = '{{ URL::to('').'/' }}'
$(document).ready(function(){
    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let ratesTable = $('#rates').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        language:{ url: SITEURL + 'datatables/spanish' },
        ajax: { url: SITEURL + 'tarifas?ido='+$('select[name="ido"]').val(), type: 'GET' },
        columns: [
            {data: 'date', name: 'rates.date'},
            {data: 'rate_normal', name: 'rates.rate_normal'},
            {data: 'rate_reduced', name: 'rates.rate_reduced'},
            {data: 'rate_night', name: 'rates.rate_night'},
            {data: 'action', name: 'action', orderable: false}
        ]
    })

    $('select[name="ido"]').change(function(){
        ratesTable.ajax.url(SITEURL+'tarifas?ido='+$(this).val()).load()
    })
})
let addRate = $('#add_rate');
addRate.tooltip()
</script>
@endpush