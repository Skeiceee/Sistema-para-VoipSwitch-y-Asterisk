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
                    <span><i class="fas fa-edit"></i> Modificar subred</span>
                </div>
                <div class="card-body">
                <form class="form-group" action="{{ url('subredes/'.$subred->id) }}" method="post">
                    @method('PUT')
                    @csrf
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-inline" type="submit">Modificar</button>
                    <a class="btn btn-primary btn-inline float-right" href="{{ route('subredes.index') }}">Volver</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection