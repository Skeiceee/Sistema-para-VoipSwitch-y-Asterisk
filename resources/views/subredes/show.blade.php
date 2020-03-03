@extends('layouts.app')
@section('content')
@php
    $range = $sub->getIPAddressRange();
    $range_host = $sub->getAddressableHostRange();
@endphp
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-network-wired pr-1"></i> {{ $subred->ip }}</span>
                        <a class="btn btn-primary icon-width float-right" style="width: 40px" data-placement="left" data-tooltip="true" title="Información de la subred" data-toggle="collapse" href="#collapseExample" aria-controls="collapseExample">
                            <i class="fas fa-info"></i>
                        </a>
                    </div>

                    <hr class="my-3">

                    <div class="collapse" id="collapseExample">
                        <div class="card card-body mb-4">
                            <section class="landing">
                                <div class="container mx-auto">
                                    <dl class="row text-center">
                                        <dt class="col-sm-12 col-md-12 col-lg-12">Dirección IP</dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $subred->ip }}</dd>

                                        <dt class="col-sm-12 col-md-12 col-lg-12">Puerta de enlace</dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $subred->gateway }}</dd>

                                        <dt class="col-sm-12 col-md-12 col-lg-12">Máscara de red</dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $subred->mask }} / {{ $subred->mask2cdr() }}</dd>

                                        <dt class="col-sm-12 col-md-12 col-lg-12 d-sm-block">Rango de direcciones IP <span class="badge badge-pill badge-info">{{ $sub->getNumberIPAddresses() }}</span></dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $range[0] }} - {{ $range[1] }} </dd>

                                        <dt class="col-sm-12 col-md-12 col-lg-12">Rango de host direccionable <span class="badge badge-pill badge-info">{{ $sub->getNumberAddressableHosts() }}</span></dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $range_host[0] }} - {{ $range_host[1] }} </dd>
                                    </dl>
                                </div>
                            </section>
                        </div>
                    </div>      

                    <div class="card">
                        <div class="container-fluid table-responsive card-body">
                            <table id="hosts" class="table table-bordered table-hover table-striped dt-responsive display nowrap" cellspacing="0" width="100%">
                                <thead class = "theade-danger">
                                <tr>
                                    <th>Dirección IP</th>
                                    <th class="no-sort" width=10>Acciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a class="btn btn-primary btn-inline float-right" style="width: 150px" href="{{ route('subredes.index') }}">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script>$(function(){$('[data-tooltip="true"]').tooltip()})</script>
<script>
$(document).ready(function(){
    var SITEURL = '{{ URL::to('').'/' }}'
    let token = $('meta[name=csrf_token]').attr("content")
    var table = $('#hosts').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        deferLoading: 0,
        language:{ url: SITEURL + 'datatables/spanish' },
        ajax: { url: SITEURL + '/hosts?subred=' + "{{ $subred->id }}", type: 'GET' },
        columns:[
            {data: 'ip', name: 'ip'},
            {data: 'btn', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush