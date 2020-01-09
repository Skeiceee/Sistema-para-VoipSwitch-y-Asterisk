@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-user-edit"></i><span class="font-weight-bold ml-2">Modificar cliente</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('clients.update', $client->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ empty(old('name')) ? $client->name : old('name') }}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="id_customer">Identificador del cliente</label>
                                    <input type="text" name="id_customer" id="id_customer" class="form-control @error('id_customer') is-invalid @enderror" value="{{ empty(old('id_customer')) ? $client->id_customer : old('id_customer') }}">
                                    @error('id_customer')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Dirección</label>
                                    <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ empty(old('address')) ? $client->address : old('address') }}">
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">País</label>
                                    <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ empty(old('country')) ? $client->country : old('country') }}">
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Ciudad</label>
                                    <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ empty(old('city')) ? $client->city : old('city') }}">
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">Descripción</label>
                                    
                                    <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ empty(old('description')) ? $client->description : old('description')}}</textarea>
                                    
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
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
@endpush