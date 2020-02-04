@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-boxes"></i><span class="font-weight-bold ml-2">Agregar una categoria</span></div>
                    </div>
                    <hr class="my-3">
                    @include('common.status')
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('categories.documents.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input id="name" name="name" type="text" class="form-control">
                                    <span class="invalid-feedback" role="alert">El campo tarifa nocturna es obligatorio.</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-primary">Agregar</button>
                                    <a href="{{ route('categories.documents.index') }}" class="btn btn-primary">Volver</a>
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
    var SITEURL = '{{ URL::to('').'/' }}'
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
    })
</script>
@endpush