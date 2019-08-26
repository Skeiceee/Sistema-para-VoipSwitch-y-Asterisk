@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('layouts.menu')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-wallet"></i><span class="font-weight-bold ml-2">Consumos</span></div>
                        <button id="filter_toggle" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Filtrar consumos.">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                    <hr class="my-3">

                    <div id="filter_wrapper" class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text"
                                data-language='es'
                                data-min-view="months"
                                data-view="months"
                                data-date-format="MM - mm/yyyy" 
                                class="form-control datepicker-here"
                                name="month">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">Buscar</button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="revenues" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                            <thead class = "theade-danger">
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Costo</th>
                                <th>Duracion</th>
                                <th class="no-sort" width="10">Acciones</th>
                            </tr>
                            </thead>
                        </table>
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
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/moment/moment.js') }}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/i18n/datepicker-es.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script> 
    $(document).ready(function(){
        $('input[name="month"]').datepicker({
            todayButton: new Date()
        })
        let filterToggle = $("#filter_toggle")
        let filterWrapper = $("#filter_wrapper")
        filterToggle.tooltip()
        filterToggle.click(function(){
            if(filterWrapper.attr('data') === undefined){
                console.log(filterWrapper.attr('data'))
                filterWrapper.hide()
                    .slideToggle(150)
                    .attr('data','hide')
            }else{
                filterWrapper.show()
                    .slideToggle(150)
                    .removeAttr('data').css('display: inline')
            }
        }) 
        let csrfToken = $('meta[name="csrf-token"]').attr('content')
        var table = $('#revenues').DataTable({
            "bAutoWidth": false,
            "language":{
                "url": '{{ route('datatables.spanish') }}'
            },
            "destroy": true,
            "responsive": true,
            "serverSide":true,
            "processing": 'Procesando',
            "deferLoading": 0,
            "ajax": {
                "url": '{{ url('api/cargosdeacceso') }}',
                "method": "POST",
                "data": function(d){
                    d.username = '{{ auth()->user()->username }}'
                    d.token = '{{ auth()->user()->token }}'
                    d.dates = $('input[name="dates"]').val()
                    d._token = csrfToken
                    d._type = true
                }
            },
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false,
                "searchable": false,
            }],
            "columns":[
                {data: 'fecha', name: 'cda.fecha'},
                {data: 'fecha_emision', name: 'cda.fecha_emision'},
                {data: 'descripcion', name: 'cda.descripcion'},
                {data: 'tarifa', name: 'cda.tarifa'},
                {data: 'lala', name: 'lala'},
                {data: 'btn'},
            ]
        })

        $('#display-filter').click(function (){
            $('#filter-wrapper').hide();
        })
    })
</script>
@endpush