@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-wallet"></i><span class="font-weight-bold ml-2">Consumos</span></div>
                        <button id="filter_toggle" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Filtrar los consumos.">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                    <hr class="my-3">
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
                            <table id="revenues" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                                <thead class="theade-danger">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Descripcion</th>
                                        <th width="10px">Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><i class="fas fa-trophy"></i><span class="font-weight-bold ml-2">Ranking de clientes</span></div>
                                    <span class="font-weight-bold ">{{ $yesterday }}</span>
                                </div>    
                                <table class="table table-sm table-hover table-responsive-md mt-3 mb-0">
                                    <thead>
                                      <tr>
                                        <th scope="col" class="text-center border-left ">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Maquina</th>
                                        <th scope="col">Minutos reales</th>
                                        <th scope="col">Minutos efectivos</th>
                                        <th scope="col">Venta</th>
                                        <th scope="col">Costo</th>
                                        <th scope="col" class="border-right">Margen</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($dailyRevenues as $key => $dailyRevenue)  
                                        <tr>
                                            <th scope="row" class="text-center">
                                                @if ($key + 1 == 1)
                                                    <div class="bg-dark rounded">
                                                        <i class="fas fa-medal text-white"></i>
                                                    </div>
                                                @else
                                                    {{ $key + 1 }}
                                                @endif
                                            </th> 
                                            <td>{{ $dailyRevenue->login }}</td>
                                            <td>
                                                @if ($dailyRevenue->name != null)
                                                    {{ $dailyRevenue->name }}
                                                @else
                                                    <span>Interconexion directa</span>
                                                @endif    
                                            </td>
                                            <td>{{ $dailyRevenue->minutes_real }}</td>
                                            <td>{{ $dailyRevenue->minutes_effective }}</td>
                                            <td><i class="fas fa-dollar-sign"></i><span class="float-right">{{ round($dailyRevenue->sale, 2) }}</span></td>
                                            <td><i class="fas fa-dollar-sign"></i><span class="float-right">{{ round($dailyRevenue->cost, 2) }}</span></td>
                                            <td><i class="fas fa-dollar-sign"></i><span class="float-right">{{ round($dailyRevenue->margin, 2) }}</span></td>
                                        </tr>
                                    @endforeach
                                     </tbody>        
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div><i class="fas fa-layer-group"></i><span class="font-weight-bold ml-2">Consumos acomulados</span></div>
                                    </div>
                                    <hr class="my-3">
                                    <form action="{{ route('revenues.accomulated.download') }}" method="post">
                                        @csrf
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input id="date_accomulated"
                                            type="text"
                                            data-language="es"
                                            class="form-control"
                                            name="date_accomulated"
                                            data-range="true"
                                            data-multiple-dates-separator=" al "
                                            data-position="top left"
                                            autocomplete="off"
                                            >
                                            <div class="input-group-append" id="button-addon4">
                                                <button class="btn btn-success font-weight-bold" type="submit">Descargar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mt-3 mt-md-0">
                            <div class=card>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div><i class="fas fa-layer-group mr-1"></i><i class="fas fa-user"></i><span class="font-weight-bold ml-2">Consumos del mes por dia de un cliente</span></div>
                                    </div>
                                    <hr class="my-3">
                                    <form action="{{ route('revenues.client.download') }}" method="post">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-user"></i></span>
                                            </div>
                                            <select class="form-control form-control-chosen" style="border-top-left-radius: 0px" name="id_client" id="id_client">
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id_client }};{{ $client->login }};{{ $client->id_voipswitch }}">{{ $client->login }} - {{ $client->name == '' ? 'Interconexion directa' : $client->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input id="date_month"
                                            type="text"
                                            data-language="es"
                                            data-min-view="months"
                                            data-view="months"
                                            data-date-format="MM - mm/yyyy" 
                                            class="form-control"
                                            name="date_month"
                                            data-position="top left"
                                            autocomplete="off"
                                            >

                                            <div class="input-group-append" id="button-addon4">
                                                <button class="btn btn-success font-weight-bold" type="submit">Descargar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
<style>
    #id_client_chosen.chosen-container .chosen-drop {
        border-top: 1px solid #aaa;
        top: auto;
        bottom: 40px;
    }

    #id_client_chosen.chosen-container.chosen-with-drop .chosen-single {
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;

        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;

        background-image: none;
    }

    #id_client_chosen.chosen-container.chosen-with-drop .chosen-drop {
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;

        border-top-color: #80BDFF;
        border-bottom-color: #80BDFF;

        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        
        box-shadow: none;
        margin-bottom: -5px;
    }
</style>
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

    let revenuesTable = $('#revenues').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        language:{ url: SITEURL + 'datatables/spanish' },
        ajax: { url: SITEURL + 'consumos', type: 'GET' },
        columns: [
            {data: 'date', name: 'date'},
            {data: 'description', name: 'description'},
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
                revenuesTable.ajax.url(SITEURL+'consumos?month='+month+'&year='+year).load();
            }
        },
        maxDate: ( d => new Date(d.setDate(d.getDate()-1)) )(new Date)
    })

    $('input[name="date_month"]').datepicker({
        todayButton: new Date(),
        maxDate: ( d => new Date(d.setDate(d.getDate()-1)) )(new Date)
    })

    $('input[name="date_accomulated"]').datepicker({
        todayButton: new Date(),
        maxDate: ( d => new Date(d.setDate(d.getDate()-1)) )(new Date),
        minDate: new Date(2020, 1, 12) {{-- /* TODO: Conseguir este valor directamente desde la base de datos. */ --}}
    })

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

    $('body').on('click', '.download', function () {
        var file_id = $(this).data('id')
        window.location = SITEURL + 'consumos/download/'+ file_id
    });
})
</script>
@endpush