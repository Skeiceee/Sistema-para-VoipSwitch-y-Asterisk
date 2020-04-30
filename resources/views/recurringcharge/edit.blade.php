@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-wallet"></i><span class="font-weight-bold ml-2">Modificar cargo recurrente</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('recurringcharge.update', $recurringCharge->id) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <label for="id_client">Cliente</label>
                                    <select class="form-control" name="id_client" id="id_client">
                                        @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" @if($recurringCharge->id_client == $client->id) selected @endif>{{ $client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="date_service_start">Fecha de inicio de servicio</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input id="date_service_start" 
                                        type="text"
                                        data-language="es"
                                        data-date-format="dd/mm/yyyy" 
                                        class="form-control @error('date_service_start') is-invalid @enderror"
                                        name="date_service_start"
                                        autocomplete="off"
                                        @if ($recurringCharge->date_service_start)
                                          value="{{ $recurringCharge->getCarbonDateServiceStart()->format('d/m/Y') }}"
                                        @endif
                                        >
                                        @error('date_service_start')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Descripción</label>
                                    <textarea class="form-control" name="description" cols="30" rows="3" maxlength="200" onkeydown="cancelar(event)">{{ empty(old('description')) ? $recurringCharge->description : old('description') }}</textarea>
                                    <div class="text-muted float-right mt-1" id="counter">Quedan 500 caracteres</div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label for="modality">Modalidad del cargo recurrente</label>
                                    <select class="form-control" name="modality" id="modality" onchange="selectCheck(this);">
                                        @foreach ($modalities as $key => $modality)
                                            <option @if($key == 0) id="unique" @endif value="{{ $key }}" @if($recurringCharge->isPerMonth == $key) selected @endif>{{ $modality }}</option>                                         
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group" id="divCheck" @if($recurringCharge->isPerMonth) style="display: none;" @endif>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input id="date" 
                                        type="text"
                                        data-language="es"
                                        data-date-format="dd/mm/yyyy" 
                                        class="form-control @error('date') is-invalid @enderror"
                                        name="date"
                                        autocomplete="off"
                                        @if ($recurringCharge->date)
                                            value="{{ $recurringCharge->getCarbonDate()->format('d/m/Y') }}"
                                        @endif
                                        >
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Cantidad</label>
                                    <input class="form-control @error('quantity') is-invalid @enderror" type="number" name="quantity" id="quantity" value="{{ empty(old('quantity')) ? $recurringCharge->quantity : old('quantity') }}">
                                    @error('quantity')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="cost_unit">Costo unitario</label>
                                    <input class="form-control @error('cost_unit') is-invalid @enderror" type="number" step="0.01" name="cost_unit" id="cost_unit" value="{{ empty(old('cost_unit')) ? $recurringCharge->cost_unit : old('cost_unit') }}">
                                    @error('cost_unit')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="money_type">Tipo de moneda</label>
                                    <select class="form-control" name="money_type" id="money_type" value="{{ empty(old('money_type')) ? $recurringCharge->money_type : old('money_type') }}">
                                        @foreach ($money_types as $money_type)
                                            <option value="{{ $money_type }}" @if($recurringCharge->money_type == $money_type) selected @endif>{{ $money_type }}</option>   
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary" style="width: 150px">Agregar</button>
                                    <a href="{{ route('recurringcharge.index') }}" class="btn btn-primary" style="width: 150px">Volver</a>
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
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/i18n/datepicker-es.js') }}"></script>
<script> // !!! BLADE Y JAVASCRIPT COMBINADOS !!!! //
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

    $('input[name="date"]').datepicker({
        todayButton: new Date()
    })
    
    $('input[name="date_service_start"]').datepicker({
        todayButton: new Date()
    })

    function cancelar(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            console.log('prevented');
            return false;
        }
    }

    function selectCheck(nameSelect){
        if(nameSelect){
            uniqueOptionValue = document.getElementById("unique").value;
            if(uniqueOptionValue == nameSelect.value){
                document.getElementById("divCheck").style.display = "block";
            }
            else{
                document.getElementById("divCheck").style.display = "none";
            }
        }
        else{
            document.getElementById("divCheck").style.display = "none";
        }
    }

    @if ($recurringCharge->date)
        var dp_date = $('input[name="date"]').datepicker().data('datepicker');
        dp_date.selectDate(
            new Date(
                    {{ $recurringCharge->getCarbonDate()->year }}, 
                    {{ $recurringCharge->getCarbonDate()->month - 1}}, 
                    {{$recurringCharge->getCarbonDate()->day}}
                )
        );
    @endif
    
    @if ($recurringCharge->date_service_start)
        var dp_date_service_start = $('input[name="date_service_start"]').datepicker().data('datepicker');
        dp_date_service_start.selectDate(
            new Date(
                {{ $recurringCharge->getCarbonDateServiceStart()->year }}, 
                {{ $recurringCharge->getCarbonDateServiceStart()->month - 1}}, 
                {{$recurringCharge->getCarbonDateServiceStart()->day}}
            )
        );
    @endif
</script>
@endpush