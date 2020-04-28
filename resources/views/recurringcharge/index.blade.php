@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-wallet"></i><span class="font-weight-bold ml-2">Cargos recurrentes</span></div>
                        <div>
                            <button id="filter_toggle" class="btn btn-primary mr-2" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Filtrar los cargos recurrentes.">
                                <i class="fas fa-filter"></i>
                            </button>
                            <a id="add_recurring_charge" href="{{ route('recurringcharge.create') }}" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nuevo cargo recurrente.">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <hr class="my-3">
                    @include('common.status')
                    <div id="filter_wrapper" class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input id="date" 
                            type="text"
                            data-language="es"
                            data-min-view="months"
                            data-view="months"
                            data-date-format="MM - mm/yyyy" 
                            class="form-control"
                            name="date"
                            autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="card">
                        <div class="table-responsive card-body">
                            <table id="recurring_charges" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                                <thead class="theade-danger">
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Descripcion</th>
                                        <th>Modalidad</th>
                                        <th>Costo unitario</th>
                                        <th>Cantidad</th>
                                        <th>Costo total</th>
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
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/font-awesome-animation.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/chosen.min.js')}}"></script>
<script src="{{ asset('js/moment/moment.js') }}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/i18n/datepicker-es.js') }}"></script>
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
    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})

    let revenuesTable = $('#recurring_charges').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        language:{ url: SITEURL + 'datatables/spanish' },
        ajax: { url: SITEURL + 'cargosrecurrentes', type: 'GET' },
        columns: [
            {data: 'id', name: 'recurring_charges.id'},
            {data: 'name', name: 'clients.name'},
            {data: 'date', name: 'recurring_charges.date'},
            {data: 'description', name: 'recurring_charges.description'},
            {data: 'isPerMonth', name: 'recurring_charges.isPerMonth'},
            {data: 'cost_unit', name: 'recurring_charges.cost_unit'},
            {data: 'quantity', name: 'recurring_charges.quantity'},
            {data: 'cost_total', name: 'recurring_charges.cost_total'},
            {data: 'action', name: 'action', orderable: false}
        ],
        order: [[0, 'desc']]
    })

    $('input[name="date"]').datepicker({
        todayButton: new Date(),
        onSelect: function(fd, date){
            if(typeof date === 'object' && date !== null){
                let month = date.getMonth() + 1
                let year = date.getFullYear()
                revenuesTable.ajax.url(SITEURL+'cargosrecurrentes?month='+month+'&year='+year).load();
            }
        }
    })

    $("#add_recurring_charge").tooltip()

    let filterToggle = $("#filter_toggle")
    let filterWrapper = $("#filter_wrapper")
    filterToggle.tooltip()
    filterToggle.click(function(){
        if(filterWrapper.attr('data') === undefined){
            filterWrapper.hide()
                .slideToggle(150)
                .attr('data','hide')
        }else{
            filterWrapper.show()
                .slideToggle(150)
                .removeAttr('data').css('display: inline')
        }
    }) 
})
</script>
@endpush