@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-server"></i><span class="font-weight-bold ml-2">Crear subred</span></div>
                        <a id="add_subred" href="{{ route('subredes.create') }}" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nueva subred.">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>

                    <hr class="my-3">

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('subredes.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="ip">Dirección IP</label>
                                    <input type="text" class="form-control" name="ip" value="{{ old('ip') }}">
                                </div>
                                <div class="form-group">
                                    <label for="gateway">Puerta de enlace</label>
                                    <input type="text" class="form-control" name="gateway" value="{{ old('gateway') }}">
                                </div>                                
                                <div class="form-group">
                                    <label for="mask">Máscara de red</label>
                                    <input type="text" class="form-control" name="mask" value="{{ old('mask') }}">
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-primary btn-inline" style="width: 150px" type="submit">Crear</button>
                                    <a class="btn btn-primary btn-inline float-right" style="width: 150px" href="{{ route('subredes.index') }}">Volver</a>
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
@endpush