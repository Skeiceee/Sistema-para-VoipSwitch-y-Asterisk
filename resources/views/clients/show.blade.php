@extends('layouts.app')
@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-user"></i><span class="font-weight-bold ml-2">{{ $client->name }}</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="card">
                        <div class="card-body">
                            <span class="font-weight-bold">Descripción</span>
                            <hr>
                            {{ $client->description }}
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><i class="fas fa-sort-numeric-down"></i><span class="font-weight-bold ml-2">Numeración asignada.</span></div>
                                <a id="add_numerations" href="{{ route('clients.numerations.add', $client->id) }}" class="btn btn-primary" style="width: 40px" data-placement="left" data-toggle="tooltip" data-original-title="Agregar rango de numeración.">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                            <hr>
                            @forelse ($intervals as $interval)
                                <form action="" method="post" class="mt-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $interval[0] }}" readonly>
                                        <input type="text" class="form-control" value="{{ (isset($interval[1])) ? $interval[1] : $interval[0] }}" readonly>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-danger"><li class="fas fa-times"></li></button>
                                        </div>
                                    </div>
                                </form>
                            @empty
                                <div class="text-center">
                                    <span class="text-muted">Este usuario no tiene numeración asignada.</span>
                                </div>
                            @endforelse
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