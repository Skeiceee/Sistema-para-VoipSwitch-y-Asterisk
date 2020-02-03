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
                            <form action="" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input id="name" name="name" class="form-control" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="category">Categoria</label>
                                    <select id="category" name="category" class="form-control">
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="file">Archivo</label>
                                    <div class="custom-file">
                                        <input id="file" type="file" class="custom-file-input" accept="application/msword, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-powerpoint, application/pdf, text/plain, .csv">
                                        <label class="custom-file-label" for="file">Seleccionar Archivo</label>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-block" type="submit">Guardar</button>
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
    $('#file').on('change',function(){
        var fileName = $(this).val();
        fileName = fileName.replace("C:\\fakepath\\", "");
        $(this).next('.custom-file-label').html(fileName);
    })
})
</script>
@endpush