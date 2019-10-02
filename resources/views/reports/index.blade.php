@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('layouts.menu')
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-chart-bar"></i><span class="font-weight-bold ml-2">Informes</span></div>
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
                                                name="date">
                                        </div>
                                    </div>
                                    <canvas id="avgPerHourChart" width="400" height="250"></canvas>
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
@endpush
@push('scripts')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/i18n/datepicker-es.js') }}"></script>
<script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
<script src="{{ asset('js/menu.js') }}"></script>
<script>
var SITEURL = '{{ URL::to('').'/' }}'
$(document).ready(function(){
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

    function maxProcessedCalls(param){
        $.ajax({
            type: "post",
            url: param.url,
            data: { month: param.month, year: param.year },
            dataType: "json",
            success: function(data){
                console.log(data)
            }
        })
    }

    function avgPerHourGraph(param){
        $.ajax({
            type: "post",
            url: param.url,
            data: { month: param.month, year: param.year },
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

    avgPerHourGraph({ url : SITEURL+'informe/avg/hr/calls' })
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})
    maxProcessedCalls({ url : SITEURL+'informe/processed/calls/' })

    $('input[name="date"]').datepicker({
        todayButton: new Date(),
        onSelect: function(fd, date){
            if(typeof date === 'object' && date !== null){
                let month = date.getMonth() + 1
                let year = date.getFullYear()
                maxProcessedCalls({url : SITEURL+'informe/processed/calls', month, year})
                avgPerHourGraph({url : SITEURL+'informe/avg/hr/calls', month, year})
            }
        }
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