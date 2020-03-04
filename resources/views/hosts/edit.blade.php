@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    @include('Common.status')
                    @include('Common.error')
                    
                    <div>
                        <i class="fas fa-edit"></i><span class="font-weight-bold ml-2">Modificar host</span>
                    </div>

                    <hr class="my-3">

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('hosts/'.$host->id) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="username">Nombre de usuario </label>
                                            <input class="form-control" type="text" name="username" value="{{ $host->username }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Contraseña </label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button id="viewPassword" class="btn btn-default" type="button"><span id="icon"><i class="fas fa-eye"></i></span></button>
                                                </span>
                                                <input class="form-control" type="password" name="password" id="password" value="{{ ($host->password) ? Crypt::decrypt($host->password) : ""}}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="port">Puerto</label>
                                            <input class="form-control" type="number" name="port" value="{{ $host->port }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="name_server">Server</label>
                                            <input class="form-control" type="text" name="name_server" value="{{ $host->server }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="hostname">Hostname</label>
                                            <input class="form-control" type="text" name="hostname" value="{{ $host->hostname }}">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="ipvmware">IP VMware</label>
                                            <input class="form-control" type="text" name="ipvmware" value="{{ $host->ipvmware }}">
                                        </div>

                                    </div>
                                    <div class="col-sm col-md col-lg">
                                        <label for="obs">Observación</label>
                                        <textarea class="form-control" name="obs" cols="30" rows="15" maxlength="500" onkeydown="cancelar(event)">{{ $host->obs }}</textarea>
                                        <div class="text-muted float-right mt-1" id="counter">Quedan 500 caracteres</div>
                                    </div>
                                    
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <button class="btn btn-primary" style="width: 150px" type="submit">Modificar</button>
                                        <a class="btn btn-primary" style="width: 150px" href="{{ route('subredes.show', $host->id_sub) }}">Volver</a>
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
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
    var textarea = document.querySelector("textarea");
    var counter = document.querySelector("#counter")
    textarea.addEventListener("input", function(){
        var maxlength = this.getAttribute("maxlength");
        var currentLength = this.value.length;
        if( currentLength >= maxlength ){
            counter.innerHTML = "Has alcanzado el número máximo de caracteres.";
        }else{
            counter.innerHTML = "Quedan " + (maxlength - currentLength) + " caracteres";
        }
    });
    function cancelar(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            console.log('prevented');
            return false;
        }
    }
</script>
<script>$(document).ready(function(){$("#viewPassword").click(function(){let t=$("#password"),s=t.attr("type"),a=$("#icon");"password"===s?(t.attr("type","text"),a.html('<i class="fas fa-eye-slash"></i>')):"text"===s&&(t.attr("type","password"),a.html('<i class="fas fa-eye"></i>'))})})</script>
@endpush
