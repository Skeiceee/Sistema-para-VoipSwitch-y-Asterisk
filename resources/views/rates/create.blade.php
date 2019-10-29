@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-money-bill-wave"></i><span class="font-weight-bold ml-2">Agregar nueva tarifa</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('clients.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="ido">Nombre</label>
                                    <select name="ido" class="form-control form-control-chosen">
                                        @foreach ($portadores as $portador)
                                            <option value="{{ $portador->id_port }}">{{ $portador->id_port }} - {{ strtoupper($portador->portador) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label for="rate_normal">Tarifa normal</label>
                                        <input name="rate_normal" type="number" step="0.0001" min="0" class="form-control">
                                        <span class="invalid-feedback" role="alert">El campo tarifa normal es obligatorio.</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="rate_reduced">Tarifa reducida</label>
                                        <input name="rate_reduced" type="number" step="0.0001" min="0" class="form-control">
                                        <span class="invalid-feedback" role="alert">El campo tarifa reducida es obligatorio.</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="rate_night">Tarifa nocturna</label>
                                        <input name="rate_night" type="number" step="0.0001" min="0" class="form-control">
                                        <span class="invalid-feedback" role="alert">El campo tarifa nocturna es obligatorio.</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary" style="width: 150px">Agregar</button>
                                    <a href="{{ route('rates.index') }}" class="btn btn-primary" style="width: 150px">Volver</a>
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
@push('css')
<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/chosen.min.js')}}"></script>
<script>
$(document).ready(function(){
    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})
})
</script>
@endpush