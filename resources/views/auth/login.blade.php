@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-sign-in-alt"></i><span class="font-weight-bold ml-2">Iniciar sesión</span>
                    <hr class="my-3">
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="text-md-right">Correo electrónico</label>
                            <div>
                                <input id="email" type="email" class="form-control was-validated @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="text-md-right">Contraseña</label>
                            <div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0 text-center">
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    Iniciar sesión
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        ¿Has olvidado tu contraseña?
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<footer class="text-center">
    <span class="text-muted">Copyright &copy; 2019 - Vozdigital</span>
</footer>
@endpush