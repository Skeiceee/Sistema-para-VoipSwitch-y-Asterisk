@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('layouts.menu')
        <div class="col-md-9">
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
                                    <span class="text-muted">Promedio de llamadas activas por hora</span>
                                    <hr>
                                    <canvas id="avgPerHourChart" width="400" height="200"></canvas>
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
    function avgPerHourGraph(){
        $.ajax({
            type: "post",
            url: "{{ route('reports.avgperhrcalls') }}",
            dataType: "json",
            success: function(data){
                avgPerHourChart.data.datasets = data
                avgPerHourChart.update()
            }
        })
    }
    avgPerHourGraph()
});
</script>
@endpush