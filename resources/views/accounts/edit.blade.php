@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-user-circle"></i><span class="font-weight-bold ml-2">Modificar la cuenta</span></div>
                    </div>
                    
                    <hr class="my-3">
                    @include('common.status')
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('accounts.update', $account->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <label for="username">Titulo</label>
                                    <input class="form-control @error('title') is-invalid @enderror" name="title" id="title" type="text" value="{{ old('title')}}">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="username">Usuario</label>
                                    <input class="form-control @error('username') is-invalid @enderror" name="username" id="username" type="text" value="{{ old('username')}}">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input class="form-control @error('password') is-invalid @enderror" name="password" id="password" type="text" value="{{ old('password')}}">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group d-flex flex-column">
                                    <label for="description">Descripción</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" cols="30" rows="5" maxlength=300>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-1"><span class="text-muted" id="counter">Quedan 300 caracteres</span> </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-primary" style="width: 150px" >Agregar</button>
                                    <a class="btn btn-primary" style="width: 150px" href="{{ route('accounts.index') }}">Volver</a>
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
<script src="{{ asset('js/textareaLimit.js') }}"></script>
<script>
$(document).ready(function(){
    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})
    $('#file').on('change',function(){
        var fileName = $(this).val();
        fileName = fileName.replace("C:\\fakepath\\", "");
        $(this).next('.custom-file-label').html(fileName);
    })
})
</script>
@endpush