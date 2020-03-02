@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-folder"></i><span class="font-weight-bold ml-2">Agregar un documento</span></div>
                    </div>
                    
                    <hr class="my-3">

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('documents.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input class="form-control @error('name') is-invalid @enderror" name="name" id="name" type="text" value="{{ old('name')}}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="category">Categoria</label>
                                    <select class="form-control form-control-chosen @error('category') is-invalid @enderror" id="category" name="category">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if(old('category') == $category->id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="file">Archivo</label>
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('file') is-invalid @enderror" name="file" id="file" type="file" accept="application/msword, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-powerpoint, application/pdf, text/plain, .csv">
                                        <label class="custom-file-label" for="file">Seleccionar Archivo</label>
                                    </div>
                                    @error('file')
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
                                    <a class="btn btn-primary" style="width: 150px" href="{{ route('documents.index') }}">Volver</a>
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