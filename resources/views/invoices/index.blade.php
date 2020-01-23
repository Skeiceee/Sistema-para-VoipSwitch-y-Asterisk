@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-file-invoice"></i><span class="font-weight-bold ml-2">Facturas</span></div>
                    </div>
                    <hr class="my-3">
                    @include('common.status')
                    <form action="{{ route('invoices.download') }}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <i class="fas fa-user"></i><span class="font-weight-bold ml-2">Información del cliente</span>
                                    <hr>
                                    <label for="id_client">Cliente</label>
                                    <select name="id_client" id="id_client" class="form-control form-control-chosen">
                                        @foreach ($clients as $client)
                                        <option value="{{ $client->id}}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="info-client" class="mt-3">
                                    <div id="info-client-load" style="background-color: rgba(0, 0, 0, 0.05); height: 100px;" class="d-flex flex-column justify-content-center align-items-center rounded text-white">
                                        <span class="fa fa-spinner fa-spin" style="font-size: 40px"></span>
                                    </div>
                                    <div id="info">
                                        <ul id="list_info_client" class="list-group list-group-flush">
                                        </ul>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div>
                                    <i class="fas fa-user"></i><span class="font-weight-bold ml-2">Voipswitch</span>
                                    <hr>
                                    <div class="mb-3 border rounded p-2 d-flex justify-content-between align-items-center">
                                        <div class="ml-3">
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input d-none" type="radio" name="vps" id="vps1" value="1" checked>
                                                <label class="form-check-label noselect" for="vps1">Argentina</label>
                                            </div>
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input" type="radio" name="vps" id="vps2" value="2">
                                                <label class="form-check-label noselect" for="vps2">Wholesale</label>
                                            </div>
                                        </div>
                                        <span class="font-weight-bold text-muted p-3 mr-3">Internacionales</span>
                                    </div>
                                    <div class="mb-3 border rounded p-2 d-flex justify-content-between align-items-center">
                                        <div class="ml-3">
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input" type="radio" name="vps" id="vps3" value="3">
                                                <label class="form-check-label noselect" for="vps3">Heavyuser</label>
                                            </div>
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input" type="radio" name="vps" id="vps4" value="4">
                                                <label class="form-check-label noselect" for="vps4">Synergo</label>
                                            </div>
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input" type="radio" name="vps" id="vps5" value="5">
                                                <label class="form-check-label noselect" for="vps5">Retail</label>
                                            </div>
                                        </div>
                                        <span class="font-weight-bold text-muted p-3 mr-3">Chile</span>
                                    </div>
                                    <select class="form-control form-control-chosen" name="vps_client" id="vps_client">
                                    </select>
                                </div>
                            </div>    
                        </div>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div>
                                    <i class="far fa-calendar-alt"></i><span class="font-weight-bold ml-2">Periodo</span>
                                    <hr>
                                    <div class="mb-3">
                                        <label for="range_date">Rango de fechas</label>
                                        <input id="range_date" type="text" data-language='es' data-multiple-dates-separator=" al " data-date-format="dd/mm/yyyy" class="form-control" name="date" autocomplete="off">
                                        <span class="invalid-feedback" role="alert">Porfavor, complete el rango de fechas.</span>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <button class="btn btn-primary btn-block mt-3" type="submit">Descargar</button>
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
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})
    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})
    $('#range_date').datepicker({ todayButton: new Date(), range: true, toggleSelected: false })
    $('select[name="id_client"]').change(function(){
        $('#info-client-load').removeClass('d-none').addClass('d-flex')
        $('#list_info_client').empty()
        let id_client = $(this).val()
        searchClient({url: SITEURL+'/facturas/buscar/cliente', id_client})
    }).trigger('change');

    function searchClient(param){
        $.ajax({
            type: "post",
            url: param.url,
            data: { id_client: param.id_client },
            dataType: "json",
            success: function(data){
                $('#list_info_client').empty()
                $('#info-client-load').removeClass('d-flex').addClass('d-none')
                let listInfoClient = $('#list_info_client')

                let trad = {
                    'id_customer' : 'Identificador del cliente',
                    'address' : 'Dirección',
                    'city' : 'Ciudad',
                    'country' : 'País'
                }

                for (var k in data){
                    if (data.hasOwnProperty(k)) {
                        listInfoClient.append(
                            $(document.createElement('li'))
                            .addClass('list-group-item d-flex border justify-content-between')
                            .append(
                                $(document.createElement('span'))
                                    .append(trad[k]), 
                                $(document.createElement('span'))
                                    .append(data[k])
                            )
                        )
                    }
                }
            }
        })
    }

    $('input[name="vps"]').on( 'click', function() {
        let select = $(this)
        if( select.is(':checked') ){
            let vps = select.val()
            $.ajax({
                type: "post",
                url: SITEURL+'/facturas/buscar/vps',
                data: { vps },
                dataType: "json",
                success: function(data){
                    $('#vps_client').empty()
                    console.log(data)
                    data.forEach(e => {
                        console.log(e.Login)
                        $('#vps_client').append(new Option(e.Login, e.IdClient + ';' + e.Type))
                        $('#vps_client').trigger('chosen:updated');
                    })
                }
            })
        }
    })
    
    $('#vps1').trigger('click');
})
</script>
@endpush