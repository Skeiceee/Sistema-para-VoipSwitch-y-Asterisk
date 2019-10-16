@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-sort-numeric-down"></i><span class="font-weight-bold ml-2">Agregar nueva numeración</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-stream"></i><span class="font-weight-bold ml-2">Listado de rangos numéricos</span>
                            <hr>
                            <form action="{{ route('clients.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Rango numérico</label>
                                    <div class="input-group">
                                        <input name="number_start" type="number" class="form-control">
                                        <input name="number_end" type="number" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Rango numérico</label>
                                    <div class="input-group">
                                        <input name="number_start" type="number" class="form-control">
                                        <input name="number_end" type="number" class="form-control">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary" style="width: 150px">Agregar</button>
                                    <a href="{{ route('clients.index') }}" class="btn btn-primary" style="width: 150px">Volver</a>
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