@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-network-wired pr-1"></i><span class="font-weight-bold ml-2">{{ $host->ip }}</span>
                    </div>
                </div>
                
                <hr class="my-3">

                @include('common.status')
                @include('common.error')

                <div class="card">
                    <div class="card-body">
                    <section class="landing">
                        <div class="container mx-auto">
                            <dl class="row text-center mb-1">
                            
                            <div class="card-text w-100 rounded opacity pt-2" style="transform: rotate(0);">
                                <span class="top-right"><i class="far fa-copy"></i></span>
                                <a class="stretched-link deco-none" id="copy" data-clipboard-text="{{ $host->ip }}">
                                    <dt class="col-sm-12 col-md-12 col-lg-12">Dirección IP</dt>
                                    <dd class="col-sm-12 col-md-12 col-lg-12">{{ $host->ip }}</dd>
                                </a>
                            </div>
                            @isset($host->ipvmware)
                                <div class="card-text w-100 rounded opacity mt-3 pt-2" style="transform: rotate(0);">
                                    <span class="top-right"><i class="far fa-copy"></i></span>
                                    <a class="stretched-link deco-none pt-4" id="copy" data-clipboard-text="{{ $host->ipvmware }}">
                                        <dt class="col-sm-12 col-md-12 col-lg-12">IP VMware</dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $host->ipvmware }}</dd>
                                    </a>
                                </div>
                            @endisset
                            @isset($host->port)
                                <div class="card-text w-100 rounded opacity mt-3 pt-2" style="transform: rotate(0);">
                                    <span class="top-right"><i class="far fa-copy"></i></span>
                                    <a class="stretched-link deco-none pt-4" id="copy" data-clipboard-text="{{ $host->port }}">
                                        <dt class="col-sm-12 col-md-12 col-lg-12">Puerto</dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $host->port }}</dd>
                                    </a>
                                </div>
                            @endisset  
                            @isset($host->username)
                                <div class="card-text w-100 rounded opacity mt-3 pt-2" style="transform: rotate(0);">
                                    <span class="top-right"><i class="far fa-copy"></i></span>
                                    <a class="stretched-link deco-none pt-4" id="copy" data-clipboard-text="{{ $host->username }}">
                                        <dt class="col-sm-12 col-md-12 col-lg-12">Usuario</dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $host->username }}</dd>
                                    </a>
                                </div>
                            @endisset
                            @isset($host->password)
                                <div class="card-text w-100 rounded opacity mt-3 pt-2" style="transform: rotate(0);">
                                    <span class="top-right"><i class="far fa-copy"></i></span>
                                    <a class="stretched-link deco-none pt-4" id="copy" data-clipboard-text="{{ Crypt::decrypt($host->password) }}">
                                        <dt class="col-sm-12 col-md-12 col-lg-12">Contraseña</dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ Crypt::decrypt($host->password) }}</dd>
                                    </a>
                                </div>
                            @endisset
                            @isset($host->hostname)
                                <div class="card-text w-100 rounded opacity mt-3 pt-2" style="transform: rotate(0);">
                                    <span class="top-right"><i class="far fa-copy"></i></span>
                                    <a class="stretched-link deco-none pt-4" id="copy" data-clipboard-text="{{ $host->hostname }}">
                                        <dt class="col-sm-12 col-md-12 col-lg-12">Hostname</dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $host->hostname }}</dd>
                                    </a>
                                </div>
                            @endisset
                            @isset($host->server)
                                <div class="card-text w-100 rounded opacity mt-3 pt-2" style="transform: rotate(0);">
                                    <span class="top-right"><i class="far fa-copy"></i></span>
                                    <a class="stretched-link deco-none pt-4" id="copy" data-clipboard-text="{{ $host->server }}">
                                        <dt class="col-sm-12 col-md-12 col-lg-12">Servidor</dt>
                                        <dd class="col-sm-12 col-md-12 col-lg-12">{{ $host->server }}</dd>
                                    </a>
                                </div>
                            @endisset
                            </dl>
                        </div>
                    </section>
                    @isset($host->obs)
                        <hr class="my-4">
                        <div>
                            <pre>{{ $host->obs }}</pre>
                        </div>
                    @endisset
                                            
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a class="btn btn-primary btn-block" href="{{ route('subredes.show', $host->id_sub) }}">Volver</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<style>
    .top-right{
        position:absolute;
        right: 15px;
        top:7px;
    }
    a.deco-none {
        color: inherit;
        text-decoration:none;
        cursor: pointer;
    }
    div.opacity:hover{
        background-color: rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/clipboard.min.js') }}"></script>
<script>
    var clipboard = new ClipboardJS('#copy')
</script>
@endpush