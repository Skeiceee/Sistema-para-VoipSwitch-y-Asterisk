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
                            <div class="table-responsive card-body">
                                <div class="form-group">
                                    <i class="fas fa-user"></i><span class="font-weight-bold ml-2">Información del cliente</span>
                                    <hr>
                                    <label for="id_client">Cliente</label>
                                    <select name="id_client" id="id_client" class="form-control">
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
                                            <li class="list-group-item d-flex border justify-content-between">
                                                <span>dwadawawd</span>
                                                <span>dawdaw</span>
                                            </li>
                                            <li class="list-group-item d-flex border justify-content-between">
                                                <span>dwadawawd</span>
                                                <span>dawdaw</span>
                                            </li>
                                            <li class="list-group-item d-flex border justify-content-between">
                                                <span>dwadawawd</span>
                                                <span>dawdaw</span>
                                            </li>
                                            <li class="list-group-item d-flex border justify-content-between">
                                                <span>dwadawawd</span>
                                                <span>dawdaw</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="card mt-3">
                            <div class="table-responsive card-body">
                                <div class="form-group">
                                    <i class="fas fa-user"></i><span class="font-weight-bold ml-2">Voipswitch</span>
                                    <hr>
                                    
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
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
var SITEURL = '{{ URL::to('').'/' }}'
$(document).ready(function(){
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})
    $('select[name="id_client"]').change(function(){
        $('#info-client-load').removeClass('d-none').addClass('d-flex')
        $('#list_info_client').empty()
        let id_client = $(this).val()
        searchClient({url: SITEURL+'facturas/search/client', id_client})
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
})
</script>
@endpush