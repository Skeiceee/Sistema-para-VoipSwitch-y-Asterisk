@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-sort-numeric-down"></i><span class="font-weight-bold ml-2">Agregar nueva numeración</span></div>
                        <a href="javascript:void(0);" id="add_range" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nuevo rango numérico.">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-stream"></i><span class="font-weight-bold ml-2">Agregar rango numérico</span>
                            <hr>
                            <div class="form-group">
                                <label>Rango numérico</label>
                                <div class="input-group">
                                    <input id="start_number" type="number" min="0" step="1" class="form-control">
                                    <input id="end_number" type="number" min="0" step="1" class="form-control">
                                    <select id="type" class="form-control">
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <i class="fas fa-stream"></i><span class="font-weight-bold ml-2">Listado de rangos numéricos</span>
                            <hr>
                            <form action="{{ route('numeration.store') }}" method="post">
                                @csrf
                                <div id="range_wrapper">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary" style="width: 150px">Guardar</button>
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
<script>
$(document).ready(function(){
    let addRange = $('#add_range')
    addRange.tooltip()

    $('#add_range').click(function(){
        let startNumber = $('#start_number').val()
        let endNumber = $('#end_number').val()
        let valSelect = $('#type').val()
        let textSelect = $('#type option:selected').text()

        $('#range_wrapper')
        .append(
            $(document.createElement('div'))
            .addClass('form-group')
            .append(
                $(document.createElement('label'))
                .text('Rango numérico')
            )
            .append(
                $(document.createElement('div'))
                .addClass('input-group')
                .append(
                    $(document.createElement('input'))
                    .attr('name', 'start_numbers[]')
                    .attr('type', 'number')
                    .attr('readonly', 'true')
                    .addClass('form-control')
                    .val(startNumber)
                )
                .append(
                    $(document.createElement('input'))
                    .attr('name', 'end_numbers[]')
                    .attr('type', 'number')
                    .attr('readonly', 'true')
                    .addClass('form-control')
                    .val(endNumber)
                )
                .append(
                    $(document.createElement('select'))
                    .attr('name', 'types[]')
                    .attr('readonly', 'true')
                    .addClass('form-control')
                    .append(
                        new Option(textSelect, valSelect)
                    )
                )
            )
        )
    })
})
</script>
@endpush