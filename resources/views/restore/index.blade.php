@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-upload pr-1"></i> Importar subred</span>
                    </div>

                    <hr class="my-3">
                    
                    @include('common.status')
                    @include('common.error')
                    
                    <form action="{{ route('restore.upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input class="custom-file-input" type="file" name="file_csv" id="file_csv" >
                                    <label class="custom-file-label" for="file_csv" aria-describedby="file_csv">Elegir archivo</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary btn-block" style="width: 150px" type="submit" id="btnRestore">Cargar datos</button>
                            <a class="btn btn-primary btn-inline float-right" style="width: 150px" href="{{ route('subredes.index') }}">Volver</a>
                        </div>
                    </form>

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
<script type="application/javascript">
    $(document).ready(function() {
        $('#file_csv').on('change',function(){
            var fileName = $(this).val();
            fileName = fileName.replace("C:\\fakepath\\", "");
            $(this).next('.custom-file-label').html(fileName);
        })
        $("#btnRestore").click(function() {
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Importando...`
            );
        });
    });
</script>
@endpush