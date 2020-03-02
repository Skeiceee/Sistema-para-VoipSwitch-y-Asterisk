@extends('layouts.app')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('./css/dataTables.bootstrap4.min.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
@php
    $range = $sub->getIPAddressRange();
    $range_host = $sub->getAddressableHostRange();
@endphp
<section class="container-fluid mt-4 mb-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-network-wired pr-1"></i> {{ $subred->ip }}</span>
                    <a class="btn btn-primary icon-width float-right" data-placement="left" data-tooltip="true" title="Información de la subred" data-toggle="collapse" href="#collapseExample" aria-controls="collapseExample">
                        <i class="fas fa-info"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row"> 
                        <div class="col-sm-12 col-md-12 col-lg-12">
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
                        </div>                      
                        <div class="col-sm-12 col-md-12 col-lg-12 mb-4">
                            <div class="container-fluid table-responsive">
                                <table id="Hosts" class="table table-bordered table-hover table-striped dt-responsive display nowrap" cellspacing="0" width="100%">
                                    <thead class = "theade-danger">
                                    <tr>
                                        <th>Dirección IP</th>
                                        <th class="no-sort" width=10>Acciones</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a class="btn btn-primary btn-inline float-right" href="{{ route('subredes.index') }}">Volver</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
    <script src="{{asset('./js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('./js/dataTables.bootstrap4.min.js')}}"></script>
    <script>$(function(){$('[data-tooltip="true"]').tooltip()})</script>
    <script>
        $(document).ready(function(){
            let token = $('meta[name=csrf_token]').attr("content")
            $('#Hosts').DataTable({
            "bAutoWidth": false,
            "language":{
                "url": "{{url('api/spanish')}}"
            },
            "destroy": true,
            "responsive": true,
            "serverSide":true,
            "ajax": {
                "url": "{{url('api/hosts/'.auth()->user()->username.'/'.auth()->user()->token.'/'.Crypt::encrypt($subred->id))}}",
                "method": "POST",
                "data": {
                    _token: token,
                }
            },
            "columnDefs": [{
            "targets": 'no-sort',
            "orderable": false,
            "searchable": false,
            }],
            "columns":[
                {data: 'ip', name: 'h.ip'},
                {data: 'btn'},
            ]
            });
        });
    </script>
@endsection