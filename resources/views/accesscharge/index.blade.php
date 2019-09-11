@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('layouts.menu')
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-money-check"></i><span class="font-weight-bold ml-2">Cargos de acceso</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <i class="far fa-building"></i><span class="font-weight-bold ml-2">Compañia</span>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <div class="form-group">
                                <label for="ido">Nombre</label>
                                <select name="ido" class="form-control form-control-chosen">
                                    @foreach ($portadores as $portador)
                                        <option value="{{ $portador->id_port }}">{{ $portador->id_port }} - {{ strtoupper($portador->portador) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card my-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div><i class="far fa-calendar-alt"></i><span class="font-weight-bold ml-2">Agregar período</span></div>
                                <a id="add_period" href="javascript:void(0);" class="btn btn-primary" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nuevo periodo."><i class="fas fa-plus"></i></a>
                            </div>
                            <hr class="mt-0">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="range_date">Rango de fecha</label>
                                    <input id="range_date" type="text" data-language='es' data-multiple-dates-separator=" al " data-date-format="dd/mm/yyyy" class="form-control" name="date">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="rate_normal">Tarifa normal</label>
                                    <input name="rate_normal" type="number" step="0.0001" min="0" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="rate_reduced">Tarifa reducida</label>
                                    <input name="rate_reduced" type="number" step="0.0001" min="0" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="rate_night">Tarifa nocturna</label>
                                    <input name="rate_night" type="number" step="0.0001" min="0" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="table-responsive card-body">
                            <table id="periods" class="table table-bordered table-hover table-striped dt-responsive display nowrap mb-0" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="1px" rowspan="2">#</th>
                                        <th colspan="2">Fechas</th>
                                        <th colspan="3">Tarifas</th>
                                        <th width="10px" rowspan="2">Acciones</th>
                                    </tr>
                                    <tr>
                                        <th>Inicio</th>
                                        <th>Termino</th>
                                        <th>Normal</th>
                                        <th>Reducida</th>
                                        <th class=" border-right">Nocturna</th>
                                    </tr>
                                </thead>
                                <tbody id="list_periods">
                                    {{-- <tr>
                                        <td>1</td>
                                        <td>2019-09-06 13:13:13</td>
                                        <td>2019-09-06 13:13:13</td>
                                        <td>0,345</td>
                                        <td>0,345</td>
                                        <td>0,345</td>
                                        <td><a href="#" class="btn-sm btn-block btn-danger text-center"><i class="fas fa-times"></i></a></td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <form id="accesscharge" action="{{ route('cargosdeacceso.store') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-block btn-primary mt-3">Calcular</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
<script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
<script src="{{ asset('js/menu.js') }}"></script>
<script> 
var SITEURL = '{{ URL::to('').'/' }}'
var counter = 1
$(document).ready(function(){
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})
    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})
    $('#range_date').datepicker({ todayButton: new Date(), range: true, toggleSelected: false })

    let periodsList = []

    var periodsTable = $('#periods').DataTable({
        scrollX: true,
        paging: false,
        searching: false,
        info: false,
        language: {url: SITEURL + 'datatables/spanish'},
        columnDefs: [
            {targets: [1,2,3,4,5,6], orderable: false},
            {targets: 6, data:null, defaultContent:'<button class="btn btn-sm btn-block btn-danger"><i class="fas fa-times"></i></button>'}
        ],
        createdRow: function (nRow, aData){$(nRow).attr('id', aData[0])},
        order: [[0, 'asc']]
    })

    let addPeriod = $("#add_period")
    addPeriod.click(function(){
        let arrDate = $('#range_date').val().split($('#range_date').attr('data-multiple-dates-separator'))
        let rateNormal = $('input[name="rate_normal"]').val()
        let rateReduced = $('input[name="rate_reduced"]').val()
        let rateNight = $('input[name="rate_night"]').val()
        row = {
            0 : counter,
            1 : arrDate[0],
            2 : arrDate[1],
            3 : rateNormal,
            4 : rateReduced,
            5 : rateNight
        }
        periodsList = [...periodsList, row]
        periodsTable.row.add(row).draw(false)
        counter++
    })
    addPeriod.tooltip()

    $('#periods tbody').on('click', 'button', function () {
        let id = parseInt($(this).parents('tr').attr('id'))
        var filtered = periodsList.filter(function(value){return id !== value[0]})
        periodsList = filtered
        periodsTable.row($(this).parents('tr')).remove().draw()
    });

    $("#accesscharge").submit(function(e) {
        e.preventDefault()
        var form = $(this)
        var select = $('select[name="ido"]')
        var url = form.attr('action')
        var start_dates = []
        var end_dates = []
        var reduced_rates = []
        var normal_rates = []
        var night_rates = []
        periodsList.forEach(function(e){
            start_dates = [...start_dates, e[1]]
            end_dates = [...end_dates, e[2]]
            normal_rates = [...normal_rates, e[3]]
            reduced_rates = [...reduced_rates, e[4]]
            night_rates = [...night_rates, e[5]]
        })
        console.log(periodsList)
        $.ajax({
            type: "POST",
            url: url,
            data: {
                start_dates,
                end_dates,
                normal_rates,
                reduced_rates,
                night_rates,
                ido : parseInt(select.val())
            },
            success: function(data)
            {
                console.log(data)
            }
        });
    });
})
</script>
@endpush