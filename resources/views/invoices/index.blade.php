@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-file-invoice"></i><span class="font-weight-bold ml-2">Facturas</span></div>
                    </div>
                    <hr class="my-3">
                    @include('common.status')
                    <form action="" method="post">
                        <div class="card">
                            <div class="table-responsive card-body">
                                <div class="form-group">
                                    <i class="fas fa-user"></i><span class="font-weight-bold ml-2">Información del cliente</span>
                                    <hr>
                                    <label for="name">Cliente</label>
                                    <select name="client" id="client" class="form-control">
                                        @foreach ($clients as $client)
                                        <option value="{{ $client->id}}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>    
                        </div>
                        <div class="card mt-3">
                            <div class="table-responsive card-body">
                                <div class="form-group">
                                    <i class="fas fa-user"></i><span class="font-weight-bold ml-2">Voipswitch</span>
                                    <hr>
                                    
                                </div>
                            </div>    
                        </div>
                        <button class="btn btn-primary btn-block mt-3" type="submit">Descargar</button>
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
@endpush