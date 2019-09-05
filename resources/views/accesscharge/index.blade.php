@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('layouts.menu')
        <div class="col-md-9">
            <div class="card">
                <form id="accesscharge" action="" method="get">
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
                                        <label for="">Fecha de inicio</label>
                                        <input id="date" type="text" data-language='es' data-min-view="months"
                                            data-view="months" data-date-format="MM - mm/yyyy" class="form-control" name="date">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Fecha de termino</label>
                                        <input id="date" type="text" data-language='es' data-min-view="months"
                                            data-view="months" data-date-format="MM - mm/yyyy" class="form-control" name="date">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label for="rate_period_1">Tarifa normal</label>
                                        <input name="rate_period_1" type="number" step="0.0001" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="rate_period_1">Tarifa reducida</label>
                                        <input name="rate_period_1" type="number" step="0.0001" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="rate_period_1">Tarifa nocturna</label>
                                        <input name="rate_period_1" type="number" step="0.0001" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body text-center">
                                <span class="text-muted">La lista de períodos esta vacia.</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary mt-3">Calcular</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/chosen.min.js')}}"></script>
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