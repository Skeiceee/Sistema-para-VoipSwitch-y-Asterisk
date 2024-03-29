@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-sort-numeric-down"></i><span class="font-weight-bold ml-2">Agregar nueva numeración a {{ $client->name }}</span></div>
                    </div>
                    <hr class="my-3">
                    @include('common.status')
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><i class="fas fa-stream"></i><span class="font-weight-bold ml-2">Agregar rango numérico</span></div>
                                <a href="javascript:void(0);" id="add_range" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar nuevo rango numérico.">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>Rango numérico</label>
                                <div class="input-group">
                                    <input id="start_number" type="number" min="0" step="1" class="form-control">
                                    <input id="end_number" type="number" min="0" step="1" class="form-control">
                                    <span class="invalid-feedback">Debe ingresar los dos rangos numéricos.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <i class="fas fa-stream"></i><span class="font-weight-bold ml-2">Listado de rangos numéricos</span>
                            <div id="errors" class="mt-3">
                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                        </button>
                                        <span>{{ $errors->first() }}</span>
                                    </div>
                                @endif
                            </div>
                            <hr>
                            <form action="{{ route('clients.numerations.save', $client->id) }}" method="post">
                                @csrf
                                <div id="range_wrapper">
                                    <div id="empty" class="text-center">
                                        <span class="text-muted">No hay rangos numéricos en la lista.</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <button id="btnSave" type="submit" class="btn btn-primary" style="width: 150px">Guardar</button>
                                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-primary" style="width: 150px">Volver</a>
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
function removeRange(e) {
    $(e).parent().parent().parent().closest('div').remove()
    if($('div[id="range"]').length === 0){
        $('#empty').show()
    }
    return false
}
$(document).ready(function(){
    let addRange = $('#add_range')
    addRange.tooltip()

    $('#add_range').click(function(){
        let startNumber = $('#start_number')
        let endNumber = $('#end_number')
        startNumber.removeClass('is-invalid')
        endNumber.removeClass('is-invalid')
        if(startNumber.val()!='' && endNumber.val()!=''){
            $('#range_wrapper')
            .append(
                $(document.createElement('div'))
                .attr('id', 'range')
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
                        .val(startNumber.val())
                    )
                    .append(
                        $(document.createElement('input'))
                        .attr('name', 'end_numbers[]')
                        .attr('type', 'number')
                        .attr('readonly', 'true')
                        .addClass('form-control')
                        .val(endNumber.val())
                    )
                    .append(
                        $(document.createElement('div'))
                        .addClass('input-group-append')
                        .append(
                            $(document.createElement('button'))
                            .addClass('btn btn-danger')
                            .attr('onclick', 'return removeRange(this);')
                            .append(
                                $(document.createElement('li'))
                                .addClass('fas fa-times')
                            )
                        )
                    )
                )
            )
            $('#empty').hide()
        }else{
            if(startNumber.val()==''){
                startNumber.addClass('is-invalid')
            }
            if(endNumber.val()==''){
                endNumber.addClass('is-invalid')
            }
        }
    })
})
</script>
@endpush