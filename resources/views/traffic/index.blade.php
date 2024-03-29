@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-chart-bar"></i><span class="font-weight-bold ml-2">Tráfico</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Promedio de llamadas activas por hora</span>
                                        <button id="filter_toggle" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Filtrar promedios de llamadas activas por hora.">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                    </div>
                                    <hr>
                                    <div id="filter_wrapper" class="form-group">
                                        <label for="date">Fecha</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input id="date" 
                                            type="text"
                                            data-language='es'
                                            data-min-view="months"
                                            data-view="months"
                                            data-date-format="MM - mm/yyyy" 
                                            class="form-control"
                                            name="date"
                                            autocomplete="off">
                                        </div>

                                        <div class="from-group mt-3">
                                            <label for="itx">Interconexión</label>
                                            <select id="itx" name="itx" class="form-control form-control-chosen">
                                                @foreach ($interconnections as $interconnection)
                                                    <option value="{{ $interconnection->id }}">{{ $interconnection->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <canvas id="avgPerHourChart" width="400" height="250"></canvas>
                                    <div class="d-flex justify-content-center mt-3">
                                        <button id='btnHide' class="btn btn-sm btn-primary mr-3"><i class="fas fa-eye-slash mr-2"></i>Ocultar todo</button>
                                        <button id='btnShow' class="btn btn-sm btn-primary"><i class="fas fa-eye mr-2"></i>Mostrar todo</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Llamadas procesadas por dia</span>
                                    </div>
                                    <hr class="my-3">
                                    <div id="info-client-load" style="background-color: rgba(0, 0, 0, 0.05); height: 100px;" class="d-flex flex-column justify-content-center align-items-center rounded text-white">
                                        <span class="fa fa-spinner fa-spin" style="font-size: 40px"></span>
                                    </div>
                                    <ul id="list_processeed_calls" class="list-group list-group-flush">
                                    </ul>
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
<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/chosen.min.js')}}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/i18n/datepicker-es.js') }}"></script>
<script>
var SITEURL = '{{ URL::to('').'/' }}'
$(document).ready(function(){
    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})

    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})
    var ctx = document.getElementById('avgPerHourChart').getContext('2d');
    var avgPerHourChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                '0:00', '1:00', '2:00', '3:00', '4:00', 
                '5:00', '6:00', '7:00', '8:00', '9:00', 
                '10:00','11:00','12:00', '13:00', '14:00',
                '15:00','16:00','17:00', '18:00', '19:00',
                '20:00', '21:00', '22:00', '23:00'
            ],
            datasets: []
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    function dynamicColors() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgba(" + r + "," + g + "," + b + ", 0.5)";
    }

    function numberWithDot(x){return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");}
    function maxProcessedCalls(param){
        $.ajax({
            type: "post",
            url: param.url,
            data: { month: param.month, year: param.year, itx: param.itx},
            dataType: "json",
            beforeSend : function(){
                $("#filter_toggle").prop("disabled", true);
                $("#date").prop("disabled", true);
                $("#itx").attr('disabled', true).trigger("chosen:updated")
            },
        }).done(function(data){
            $("#filter_toggle").prop("disabled", false);
            $("#date").prop("disabled", false);
            $("#itx").attr('disabled', false).trigger("chosen:updated")

            $('#info-client-load').removeClass('d-flex').addClass('d-none')
                let listProcesseedCalls = $('#list_processeed_calls').empty()
                let total = 0
                data.forEach(function(e){
                    listProcesseedCalls.append(
                        $(document.createElement('li'))
                        .addClass('list-group-item d-flex border justify-content-between')
                        .append(
                            $(document.createElement('span'))
                                .append(e.date), 
                            $(document.createElement('span'))
                                .append(numberWithDot(e.processed_calls)+' llamadas')
                        )
                    )
                    total+=e.processed_calls
                })
                listProcesseedCalls.append(
                    $(document.createElement('li'))
                    .addClass('list-group-item list-group-item-info border border-info d-flex justify-content-between')
                    .append(
                        $(document.createElement('span'))
                            .addClass('font-weight-bold')
                            .append('Total'), 
                        $(document.createElement('span'))
                            .addClass('font-weight-bold')
                            .append(numberWithDot(total)+' llamadas')
                    )
                )
        });
    }

    function avgPerHourGraph(param){
        $.ajax({
            type: "post",
            url: param.url,
            data: { month: param.month, year: param.year, itx: param.itx},
            dataType: "json",
            success: function(data){
                let datasets = []
                data.forEach(function(e){
                    color = dynamicColors()
                    e = {...e, backgroundColor : color}
                    if(e.data.length == 0){
                        e = {...e, hidden: true}
                    }
                    datasets.push(e)
                });
                avgPerHourChart.data.datasets = datasets
                avgPerHourChart.update()
            }
        })
    }

    var datepicker = $('input[name="date"]').datepicker({
        todayButton: new Date(),
        onSelect: function(fd, date){
            if(typeof date === 'object' && date !== null){
                $('#info-client-load').removeClass('d-none').addClass('d-flex')
                $('#list_processeed_calls').empty()
                let itx = $('#itx').val()
                let month = date.getMonth() + 1
                let year = date.getFullYear()
                maxProcessedCalls({url : SITEURL+'trafico/processed/calls', month, year, itx})
                avgPerHourGraph({url : SITEURL+'trafico/avg/hr/calls', month, year, itx})
            }
        }
    })

    datepicker.datepicker().data('datepicker').selectDate(new Date());

    $('#itx').change(function(){
        var date = datepicker.datepicker().data('datepicker').selectedDates[0];
        if(typeof date === 'object' && date !== null){
            $('#info-client-load').removeClass('d-none').addClass('d-flex')
            $('#list_processeed_calls').empty()
            let itx = $('#itx').val()
            let month = date.getMonth() + 1
            let year = date.getFullYear()
            maxProcessedCalls({url : SITEURL+'trafico/processed/calls', month, year, itx})
            avgPerHourGraph({url : SITEURL+'trafico/avg/hr/calls', month, year, itx})
        }
    })

    $('#btnHide').click(function(){
        avgPerHourChart.data.datasets.forEach(function(ds) {
            ds.hidden = true
        })
        avgPerHourChart.update();
    })

    $('#btnShow').click(function(){
        avgPerHourChart.data.datasets.forEach(function(ds) {
            ds.hidden = false
        })
        avgPerHourChart.update();
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
});
</script>
@endpush