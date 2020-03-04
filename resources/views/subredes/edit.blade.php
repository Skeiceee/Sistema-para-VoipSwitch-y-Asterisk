@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-edit "></i><span class="font-weight-bold ml-2">Modificar subred</span></div>
                    </div>

                    <hr class="my-3">

                    @include('common.status')
                    @include('common.error')

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('subredes.update', $subred->id) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-primary btn-inline" style="width: 150px" type="submit">Modificar</button>
                                    <a class="btn btn-primary btn-inline float-right" style="width: 150px" href="{{ route('subredes.index') }}">Volver</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
@endpush