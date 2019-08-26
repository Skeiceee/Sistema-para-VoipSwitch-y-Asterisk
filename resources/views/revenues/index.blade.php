@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('layouts.menu')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <span>Consumos</span>
                    <hr class="my-3">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('css')
<link href="{{ asset('css/font-awesome-animation.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
@endpush