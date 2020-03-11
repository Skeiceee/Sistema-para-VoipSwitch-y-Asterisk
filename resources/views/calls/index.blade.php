@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-phone-alt"></i><span class="font-weight-bold ml-2">Llamadas</span></div>
                    </div>
                    
                    <hr class="my-3">

                    <div class="card">
                        <div class="card-body">
                            <div><i class="fas fa-user"></i><span class="font-weight-bold ml-2">Usuarios</span></div>

                            <hr class="my-3">
                            
                            <form action="{{ route('calls.download') }}" method="post">
                            @csrf
                                <div class="mb-3 border rounded p-2 d-flex justify-content-between align-items-center">
                                    <div class="ml-3">
                                        @foreach ($voipswitchs as $voipswitch)
                                            @if (strpos($voipswitch->conn_name,'condell') === false)
                                                <div class="form-check form-check-inline custom-radio">
                                                    <input class="form-check-input" type="radio" name="id_vps" id="{{ 'id_vps'.$voipswitch->id }}" value="{{ $voipswitch->id }}">
                                                    <label class="form-check-label noselect" for="{{ 'id_vps'.$voipswitch->id }}">{{ $voipswitch->name }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <span class="font-weight-bold text-muted p-3 mr-3">Internacionales</span>
                                </div>

                                <div class="mb-3 border rounded p-2 d-flex justify-content-between align-items-center">
                                    <div class="ml-3">
                                        @foreach ($voipswitchs as $voipswitch)
                                            @if (strpos($voipswitch->conn_name,'condell') !== false)
                                                <div class="form-check form-check-inline custom-radio">
                                                    <input class="form-check-input" type="radio" name="id_vps" id="{{ 'id_vps'.$voipswitch->id }}" value="{{ $voipswitch->id }}">
                                                    <label class="form-check-label noselect" for="{{ 'id_vps'.$voipswitch->id }}">{{ $voipswitch->name }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <span class="font-weight-bold text-muted p-3 mr-3">Chile</span>
                                </div>
                                <select class="form-control form-control-chosen" name="vps_client" id="vps_client">
                                </select>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <div><i class="fas fa-download"></i><span class="font-weight-bold ml-2">Descargar llamadas</span></div>

                            <hr class="my-3">

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input id="date"
                                    type="text"
                                    data-language="es"
                                    class="form-control"
                                    name="date"
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
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/chosen.min.js')}}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/i18n/datepicker-es.js') }}"></script>
<script>
var SITEURL = '{{ URL::to('').'/' }}'
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})

    $('input[name="date"]').datepicker({
        todayButton: new Date(),
        maxDate: ( d => new Date(d.setDate(d.getDate()-1)) )(new Date),
        minDate: new Date(2020, 2, 9) {{-- /* TODO: Conseguir este valor directamente desde la base de datos. */ --}}
    })

    $('input[name="id_vps"]').on( 'click', function() {
        let select = $(this)
        select.empty()
        if( select.is(':checked') ){
            let vps = select.val()
            $.ajax({
                type: "post",
                url: SITEURL+'/llamadas/buscar/vps',
                data: { vps },
                dataType: "json",
                success: function(data){
                    $('#vps_client').empty()
                    data.forEach(e => {
                        console.log(e.Login)
                        $('#vps_client').append(new Option(e.Login, e.IdClient + ';' + e.Type))
                        $('#vps_client').trigger('chosen:updated');
                    })
                }
            })
        }
    })
    
    $('input[id^="id_vps"]:first').trigger('click');
})
</script>
@endpush