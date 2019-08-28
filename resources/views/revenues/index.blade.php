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
                                <th>Descripcion</th>
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
var SITEURL = '{{ URL::to('') }}'
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    $('#display-filter').click(function (){
        $('#filter-wrapper').hide();
    })

    $('#revenues').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: SITEURL + "/consumos",
            type: 'GET',
        },
        columns: [
            {data: 'date', name: 'date'},
            {data: 'description', name: 'description'},
            {data: 'action', name: 'action', orderable: false}
        ],
        order: [[0, 'desc']]
    })

    /* When click download excel*/
    $('body').on('click', '.download', function () {
        var file_id = $(this).data('id')
        window.location = SITEURL + '/consumos/download/'+ file_id
    });
})
</script>
@endpush