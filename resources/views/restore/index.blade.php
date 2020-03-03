@extends('Layouts.template')
@section('title', ' - Importar contraseñas')
@section('content')
<section class="container-fluid mt-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-5 mx-auto">
            @include('common.status')
            @include('common.error')
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-upload pr-1"></i> Importar contraseñas</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('restore.upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input class="custom-file-input" type="file" name="file_csv" id="file_csv" >
                                <label class="custom-file-label" for="file_csv" aria-describedby="file_csv">Elegir archivo</label>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block" type="submit" id="btnRestore">Cargar datos</button>
                    </form>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
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
@endsection