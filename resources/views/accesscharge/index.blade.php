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
                                <label for="">Nombre</label>
                                <select name="" class="form-control form-control-chosen" data-placeholder="Selecciona una compañia...">
                                    {{-- <option value="1">Adwadaw</option>
                                    <option value="2">Bdwadaw</option>
                                    <option value="3">Cdwadaw</option>
                                    <option value="4">Ddwadawdaw</option>
                                    <option value="5">Edwadwadaw</option> --}}
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
                                <div class="col-md-6">
                                    <label for="start_date">Fecha de inicio</label>
                                    <input id="start_date" type="text" data-language='es' data-min-view="months"
                                        data-view="months" data-date-format="MM - mm/yyyy" class="form-control" name="date">
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date">Fecha de termino</label>
                                    <input id="end_date" type="text" data-language='es' data-min-view="months"
                                        data-view="months" data-date-format="MM - mm/yyyy" class="form-control" name="date">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="rate_normal">Tarifa normal</label>
                                    <input name="rate_normal" type="number" step="0.0001" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="rate_reduced">Tarifa reducida</label>
                                    <input name="rate_reduced" type="number" step="0.0001" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="rate_night">Tarifa nocturna</label>
                                    <input name="rate_night" type="number" step="0.0001" class="form-control">
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
                                <tbody>
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
                    <form id="accesscharge" action="" method="get">
                        <button type="submit" class="btn btn-block btn-primary mt-3">Calcular</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/chosen.min.js')}}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
<script src="{{ asset('js/menu.js') }}"></script>
<script> 
var SITEURL = '{{ URL::to('').'/' }}'
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    let periodsTable = $('#periods').DataTable({
        // scrollX: true,
        paging: false,
        searching: false,
        info: false,
        language:{
            url: SITEURL + 'datatables/spanish'
        },
        columnDefs: [
            {targets: [1,2,3,4,5,6], orderable: false} 
        ],
        order: [[0, 'desc']]
    })

    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})

    let addPeriod = $("#add_period")
    addPeriod.tooltip()
    addPeriod.click(function(){
        alert('Wena!')
    })

    $("#accesscharge").submit(function(e) {
        e.preventDefault()
        var form = $(this)
        console.log(form.serialize())
        var url = form.attr('action')
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(data)
            {
                console.log(data)
            }
        });
    });
})
</script>
@endpush