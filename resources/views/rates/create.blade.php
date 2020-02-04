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
                    @include('common.status')
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('rates.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="ido">Nombre</label>
                                    <select name="ido" class="form-control form-control-chosen @error('ido') is-invalid @enderror">
                                        @foreach ($portadores as $portador)
                                            <option value="{{ $portador->id_port }}">{{ $portador->id_port }} - {{ strtoupper($portador->portador) }}</option>
                                        @endforeach
                                    </select>
                                    @error('ido')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="range_date">Rango de fechas</label>
                                    <input class="form-control @error('range_date') is-invalid @enderror" id="range_date" type="text" data-language='es' data-multiple-dates-separator=" al " data-date-format="dd/mm/yyyy" name="range_date"  autocomplete="off">
                                    @error('range_date')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label for="rate_normal">Tarifa normal</label>
                                        <input class="form-control @error('rate_normal') is-invalid @enderror" name="rate_normal" type="number" step="0.0001" min="0">
                                        @error('rate_normal')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="rate_reduced">Tarifa reducida</label>
                                        <input class="form-control @error('rate_reduced') is-invalid @enderror" name="rate_reduced" type="number" step="0.0001" min="0">
                                        @error('rate_reduced')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="rate_night">Tarifa nocturna</label>
                                        <input class="form-control @error('rate_night') is-invalid @enderror" name="rate_night" type="number" step="0.0001" min="0">
                                        @error('rate_night')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
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
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/chosen.min.js')}}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/i18n/datepicker-es.js') }}"></script>
<script>
$(document).ready(function(){
    $('.form-control-chosen').chosen({no_results_text: "No se ha encontrado"})
    $('#range_date').datepicker({
        todayButton: new Date(), 
        range: true, 
        toggleSelected: false
    })
})
</script>
@endpush