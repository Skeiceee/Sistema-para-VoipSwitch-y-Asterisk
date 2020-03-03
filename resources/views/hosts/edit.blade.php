@extends('layouts.app')
@section('content')
<section class="container-fluid mt-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-10 mx-auto">
            @include('Common.status')
            @include('Common.error')
            @include('Common.requestError')
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-edit"></i> Modificar host</span>
                </div>
                <div class="card-body">
                <form class="form-group" action="{{ url('hosts/'.$host->id) }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <label for="username">Nombre de usuario </label>
                            <input class="form-control" type="text" name="username" value="{{ $host->username }}">

                            <label for="password">Contraseña </label>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button id="viewPassword" class="btn btn-default" type="button"><span id="icon"><i class="fas fa-eye"></i></span></button>
                                </span>
                                <input class="form-control" type="password" name="password" id="password" value="{{ ($host->password) ? Crypt::decrypt($host->password) : ""}}">
                            </div>

                            <label for="port">Puerto</label>
                            <input class="form-control" type="number" name="port" value="{{ $host->port }}">

                            <label for="name_server">Server</label>
                            <input class="form-control" type="text" name="name_server" value="{{ $host->server }}">

                            <label for="hostname">Hostname</label>
                            <input class="form-control" type="text" name="hostname" value="{{ $host->hostname }}">

                            <label for="ipvmware">IP VMware</label>
                            <input class="form-control" type="text" name="ipvmware" value="{{ $host->ipvmware }}">
                        </div>
                        <div class="col-sm col-md col-lg">
                            <label for="obs">Observación</label>
                            <textarea class="form-control" name="obs" cols="30" rows="9" maxlength="500" onkeydown="cancelar(event)">{{ $host->obs }}</textarea>
                            <div class="text-muted float-right mt-1" id="counter">Quedan 500 caracteres</div>
                        </div>
                        
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-inline" type="submit">Modificar</button>
                    <a class="btn btn-primary btn-inline float-right" href="{{ route('hosts.index') }}">Volver</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
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
@endsection
