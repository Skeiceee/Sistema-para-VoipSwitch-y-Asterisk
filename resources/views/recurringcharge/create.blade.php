@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-wallet"></i><span class="font-weight-bold ml-2">Agregar nuevo cargo recurrente</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('recurringcharge.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="id_client">Cliente</label>
                                    <select class="form-control" name="id_client" id="id_client">
                                        @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descripción</label>
                                    <textarea class="form-control" name="description" cols="30" rows="3" maxlength="200" onkeydown="cancelar(event)">{{ old('name') }}</textarea>
                                    <div class="text-muted float-right mt-1" id="counter">Quedan 500 caracteres</div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label for="modality">Modalidad del cargo recurrente</label>
                                    <select class="form-control" name="modality" id="modality" onchange="selectCheck(this);">
                                        <option id="unique" value="1">Único</option>
                                        <option value="2">Mensual</option>
                                    </select>
                                </div>

                                <div class="form-group" id="divCheck">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input id="date" 
                                        type="text"
                                        data-language="es"
                                        data-date-format="dd/mm/yyyy" 
                                        class="form-control"
                                        name="date"
                                        autocomplete="off"
                                        >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Cantidad</label>
                                    <input class="form-control" type="number" name="quantity" id="quantity">
                                </div>
                                
                                <div class="form-group">
                                    <label for="cost_unit">Costo unitario</label>
                                    <input class="form-control" type="number" step="0.01" name="cost_unit" id="cost_unit">
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

    $('input[name="date"]').datepicker({
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
</script>
@endpush