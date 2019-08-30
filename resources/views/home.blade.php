@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('layouts.menu')
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-chart-line"></i><span class="font-weight-bold ml-2">Dashboard</span>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <span class="fa-stack">
                                        <i class="fas fa-fw fa-circle fa-stack-2x"></i>
                                        <i class="fas fa-fw fa-phone-alt fa-stack-1x fa-inverse faa-shake animated"></i>
                                    </span>
                                    <span class="text-muted">Llamadas activas</span>
                                    <div>
                                        <span id="active" class="size-20 mr-3">0</span><span id="indicator"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-3 mt-lg-0">
                            <div class="card">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <span class="fa-stack">
                                        <i class="fas fa-fw fa-circle fa-stack-2x"></i>
                                        <i class="fas fa-fw fa-phone-alt fa-stack-1x fa-inverse fa-rotate-90 faa-shake "></i>
                                    </span>
                                    <span class="text-muted">Llamadas procesadas</span>
                                    <span id="processed" class="size-20">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <canvas id="activeChart" width="400" height="80"></canvas>
                                    <span class="text-muted">Llamadas activas</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <canvas id="sessionsChart" width="400" height="150"></canvas>
                                    <span id="sessions" class="text-muted">0</span>
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
<link href="{{ asset('css/font-awesome-animation.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
<script src="{{ asset('js/menu.js') }}"></script>
<script>
$(document).ready(function(){
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    var ctx = document.getElementById('activeChart').getContext('2d');
    var activeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Llamadas activas',
                    data: [],
                    fill: false,
                    borderColor: "rgb(25, 146, 208)",
                    lineTension: 0
                }
            ]
        },
        options: {legend:{display: false}}
    });
    var ctx = document.getElementById('sessionsChart').getContext('2d');
    var sessionsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Movistar',
                    data: [],
                    fill: false,
                    borderColor: "rgb(204, 25, 144)",
                    lineTension: 0
                },
                {
                    label: 'Entel',
                    data: [],
                    fill: false,
                    borderColor: "rgb(245, 142, 32)",
                    lineTension: 0
                },
                {
                    label: 'Otros',
                    data: [],
                    fill: false,
                    borderColor: "rgb(114, 114, 114)",
                    lineTension: 0
                }
            ]
        }
    });
    $.ajaxSetup({
        timeout: 2000, 
        retryAfter: 1000
    });
    function activeGraph(param){
            $.ajax({
            type: "post",
            url: "{{ url('api/asterisk/status') }}",
            dataType: "json",
            success: function(data){
                let oldData = parseInt($('#active').text());
                let active = $('#active').html(numberWithCommas(data.active))
                $('#processed').html(numberWithCommas(data.processed))
                if(oldData <= parseInt(data.active)){
                    $('#indicator').html('<i class="fas fa-arrow-up text-success"></i>')
                }else{
                    $('#indicator').html('<i class="fas fa-arrow-down text-danger"></i>')
                }
                let limit = 10;
                let today = new Date();
                let time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                activeChart.data.datasets[0].data.push(data.active);
                activeChart.data.labels.push(time)
                while(activeChart.data.datasets[0].data.length > limit && activeChart.data.labels.length > limit){
                    activeChart.data.datasets[0].data.shift()
                    activeChart.data.labels.shift()
                }
                activeChart.update();
                setTimeout(function(){ 
                    activeGraph(param) 
                }, $.ajaxSetup().retryAfter)
            },
            error: function(){
                setTimeout(function(){
                    activeGraph(param) 
                }, $.ajaxSetup().retryAfter)
            }
        })
    }
    function sessionsGraph(param){
            $.ajax({
            type: "post",
            url: "{{ url('api/asterisk/sessions/movistar') }}",
            dataType: "json",
            success: function(data){
                $('#sessions').html(numberWithCommas(parseInt(data.movistar) + parseInt(data.entel) + parseInt(data.other)))
                let limit = 10;
                let today = new Date();
                let time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                sessionsChart.data.datasets[0].data.push(data.movistar);
                sessionsChart.data.datasets[1].data.push(data.entel);
                sessionsChart.data.datasets[2].data.push(data.other);
                sessionsChart.data.labels.push(time)
                while(sessionsChart.data.datasets[0].data.length > limit && sessionsChart.data.datasets[1].data.length > limit && sessionsChart.data.datasets[2].data.length > limit && sessionsChart.data.labels.length > limit){
                    sessionsChart.data.datasets[0].data.shift()
                    sessionsChart.data.datasets[1].data.shift()
                    sessionsChart.data.datasets[2].data.shift()
                    sessionsChart.data.labels.shift()
                }
                sessionsChart.update();
                setTimeout(function(){ 
                    sessionsGraph(param) 
                }, $.ajaxSetup().retryAfter)
            },
            error: function(){
                setTimeout(function(){
                    sessionsGraph(param) 
                }, $.ajaxSetup().retryAfter)
            }
        })
    }
    activeGraph()
    sessionsGraph()
});
</script>
@endpush