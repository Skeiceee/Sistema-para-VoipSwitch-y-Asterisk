@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('layouts.menu')
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-cogs"></i><span class="font-weight-bold ml-2">Configuración</span></div>
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @if (is_null(Auth::user()->picture))
                                        <div class="d-flex justify-content-center">
                                            <div class="d-flex flex-column text-center">
                                                <div class="no-avatar-lg">
                                                    <span class="initials">{{ Auth::user()->initials() }}</span>
                                                </div>
                                                <span class="mt-3 font-weight-bold size-20">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</span>
                                                <span>{{ Auth::user()->email }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-center">
                                            <div class="d-flex flex-column text-center">
                                                <div class="avatar-lg">
                                                    <img src="{{ url(Auth::user()->picture) }}">
                                                </div>
                                                <span class="mt-3 font-weight-bold size-20">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</span>
                                                <span>{{ Auth::user()->email }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <hr class="my-3">
                                    <form action="{{ route('configuration.save') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input name="avatar" type="file" class="custom-file-input @error('avatar') is-invalid @enderror" accept=".png,.jpg,.jpeg,.gif">
                                                <label class="custom-file-label" for="customFileLang">Seleccionar imagen...</label>
                                                @error('avatar')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-block mt-3">Guardar cambios</button>
                                    </form>
                                </div>
                            </div>
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
<script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
<script src="{{ asset('js/bs-custom-file-input.min.js') }}"></script>
<script src="{{ asset('js/menu.js') }}"></script>
<script>
$(document).ready(function () {
    bsCustomFileInput.init()
})
</script>
@endpush