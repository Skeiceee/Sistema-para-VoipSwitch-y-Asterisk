@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div id="stadistic" class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-chart-line"></i><span class="font-weight-bold ml-2">Dashboard</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <span class="mx-3 mt-3 text-muted">Interconexión 1</span>
                                <hr class="mt-1 mx-3">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <span class="fa-stack">
                                        <i class="fas fa-fw fa-circle fa-stack-2x"></i>
                                        <i class="fas fa-fw fa-phone-alt fa-stack-1x fa-inverse faa-shake animated"></i>
                                    </span>
                                    <span class="text-muted">Llamadas activas</span>
                                    <div class="d-flex align-items-center">
                                        <span id="active" style="font-size: 30px;">0</span><span class="ml-3" id="indicator"></span>
                                    </div>
                                </div>
                                <span class="mx-3 mt-3 text-muted">Interconexión 2</span>
                                <hr class="mt-1 mb-3 mx-3">
                                <div class="card-body d-flex justify-content-between align-items-center mb-3">
                                    <span class="fa-stack">
                                        <i class="fas fa-fw fa-circle fa-stack-2x"></i>
                                        <i class="fas fa-fw fa-phone-alt fa-stack-1x fa-inverse faa-shake animated"></i>
                                    </span>
                                    <span class="text-muted">Llamadas activas</span>
                                    <div class="d-flex align-items-center">
                                        <span id="active_2" style="font-size: 30px;">0</span><span class="ml-3" id="indicator_2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mt-3 mt-lg-0">
                            <div class="card">
                                <span class="mx-3 mt-3 text-muted">Interconexión 1</span>
                                <hr class="mt-1 mx-3">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <span class="fa-stack">
                                        <i class="fas fa-fw fa-circle fa-stack-2x"></i>
                                        <i class="fas fa-fw fa-phone-alt fa-stack-1x fa-inverse fa-rotate-90 faa-shake "></i>
                                    </span>
                                    <span class="text-muted">Llamadas procesadas</span>
                                    <span id="processed" style="font-size: 30px;">0</span>
                                </div>
                                <span class="mx-3 mt-3 text-muted">Interconexión 2</span>
                                <hr class="mt-1 mb-3 mx-3">
                                <div class="card-body d-flex justify-content-between align-items-center mb-3">
                                    <span class="fa-stack">
                                        <i class="fas fa-fw fa-circle fa-stack-2x"></i>
                                        <i class="fas fa-fw fa-phone-alt fa-stack-1x fa-inverse fa-rotate-90 faa-shake "></i>
                                    </span>
                                    <span class="text-muted">Llamadas procesadas</span>
                                    <span id="processed_2" style="font-size: 30px;">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <span class="text-muted">Llamadas activas</span>
                                    <hr>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <canvas id="activeChart" width="400" height="80"></canvas>
                                        </div>
                                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <canvas id="activeChart_2" width="400" height="80"></canvas>
                                        </div>
                                    </div>
                                    <ul class="nav nav-pills d-flex justify-content-center aling-items-center mt-3" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Interconexion 1</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Interconexion 2</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-body">

                                    <div class="tab-content" id="pills-tabContent-2">
                                        <div class="tab-pane fade show active" id="pills-sessions-movistar-1" role="tabpanel">
                                            <div class="d-flex justify-content-between aling-items-center">
                                                <span class="text-muted">Llamadas por movistar</span>
                                                <div class="text-muted">
                                                    <span id="sessions">0</span>
                                                    <span>sesiones</span>
                                                </div>
                                            </div>
                                            <hr>
                                            <canvas id="sessionsChart" width="400" height="150"></canvas>
                                        </div>
                                        <div class="tab-pane fade" id="pills-sessions-movistar-2" role="tabpanel">
                                            <div class="d-flex justify-content-between aling-items-center">
                                                <span class="text-muted">Llamadas por movistar</span>
                                                <div class="text-muted">
                                                    <span id="sessions_2">0</span>
                                                    <span>sesiones</span>
                                                </div>
                                            </div>
                                            <hr>
                                            <canvas id="sessionsChart_2" width="400" height="150"></canvas>
                                        </div>
                                    </div>
                                    
                                    <ul class="nav nav-pills d-flex justify-content-center aling-items-center mt-3" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-sessions-movistar-1" role="tab">Interconexion 1</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-sessions-movistar-2" role="tab">Interconexion 2</a>
                                        </li>
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
<link href="{{ asset('css/font-awesome-animation.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
$(document).ready(function(){
    var i = null;

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
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Interconexión 1'
            }
        }
    });

    var ctx = document.getElementById('activeChart_2').getContext('2d');
    var activeChart_2 = new Chart(ctx, {
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
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Interconexión 2'
            }
        }
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
        },
        options: {
            title: {
                display: true,
                text: 'Interconexión 1'
            }
        }
    });

    var ctx = document.getElementById('sessionsChart_2').getContext('2d');
    var sessionsChart_2 = new Chart(ctx, {
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
        },
        options: {
            title: {
                display: true,
                text: 'Interconexión 2'
            }
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

    function activeGraph_2(param){
            $.ajax({
            type: "post",
            url: "{{ url('api/asterisk2/status') }}",
            dataType: "json",
            success: function(data){
                let oldData = parseInt($('#active_2').text());
                let active = $('#active_2').html(numberWithCommas(data.active))
                $('#processed_2').html(numberWithCommas(data.processed))
                if(oldData <= parseInt(data.active)){
                    $('#indicator_2').html('<i class="fas fa-arrow-up text-success"></i>')
                }else{
                    $('#indicator_2').html('<i class="fas fa-arrow-down text-danger"></i>')
                }
                let limit = 10;
                let today = new Date();
                let time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                activeChart_2.data.datasets[0].data.push(data.active);
                activeChart_2.data.labels.push(time)
                while(activeChart_2.data.datasets[0].data.length > limit && activeChart_2.data.labels.length > limit){
                    activeChart_2.data.datasets[0].data.shift()
                    activeChart_2.data.labels.shift()
                }
                activeChart_2.update();
                setTimeout(function(){ 
                    activeGraph_2(param) 
                }, $.ajaxSetup().retryAfter)
            },
            error: function(){
                setTimeout(function(){
                    activeGraph_2(param) 
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
    
    function sessionsGraph_2(param){
            $.ajax({
            type: "post",
            url: "{{ url('api/asterisk2/sessions/movistar') }}",
            dataType: "json",
            success: function(data){
                $('#sessions_2').html(numberWithCommas(parseInt(data.movistar) + parseInt(data.entel) + parseInt(data.other)))
                let limit = 10;
                let today = new Date();
                let time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                sessionsChart_2.data.datasets[0].data.push(data.movistar);
                sessionsChart_2.data.datasets[1].data.push(data.entel);
                sessionsChart_2.data.datasets[2].data.push(data.other);
                sessionsChart_2.data.labels.push(time)
                while(sessionsChart_2.data.datasets[0].data.length > limit && sessionsChart_2.data.datasets[1].data.length > limit && sessionsChart_2.data.datasets[2].data.length > limit && sessionsChart_2.data.labels.length > limit){
                    sessionsChart_2.data.datasets[0].data.shift()
                    sessionsChart_2.data.datasets[1].data.shift()
                    sessionsChart_2.data.datasets[2].data.shift()
                    sessionsChart_2.data.labels.shift()
                }
                sessionsChart_2.update();
                setTimeout(function(){ 
                    sessionsGraph_2(param) 
                }, $.ajaxSetup().retryAfter)
            },
            error: function(){
                setTimeout(function(){
                    sessionsGraph_2(param) 
                }, $.ajaxSetup().retryAfter)
            }
        })
    }

    activeGraph()
    activeGraph_2()
    sessionsGraph()
    sessionsGraph_2()
});
</script>
@endpush