@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Nueva petición de vacaciones') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('holidays.create') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="fecha_inicio" class="col-md-4 col-form-label text-md-right">{{ __('Día de inicio') }}</label>

                            <div class="col-md-6">
                            <input id="fecha_inicio" type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" name="fecha_inicio" value="{{ old('fecha_inicio') ? old('fecha_inicio') : $min }}" required autocomplete="fecha_inicio" autofocus min={{$min}} max={{$max}} onchange="changedInitialValue()">

                                @error('fecha_inicio')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fecha_fin" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de finalización') }}</label>

                            <div class="col-md-6">
                            <input id="fecha_fin" type="date" class="form-control @error('fecha_fin') is-invalid @enderror" name="fecha_fin" value="{{ old('fecha_fin') ? old('fecha_fin') : $minFin }}" min={{old('fecha_inicio') ? old('fecha_inicio') : $min}} max={{$max}} required autocomplete="fecha_fin" autofocus>
                                @error('fecha_fin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="total_days" class="col-md-4 col-form-label text-md-right">{{ __('Días totales') }}</label>
                            <div class="col-md-6">
                                <input type="number" id="total_days" class="form-control{{ $errors->has('total_days') ? ' is-invalid' : '' }}" name="total_days" value="{{ old('total_days') }}" autofocus min="0" step="1" />
                                @if ($errors->has('total_days'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('total_days') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="comments" class="col-md-4 col-form-label text-md-right">{{ __('Comentarios') }}</label>
                            <div class="col-md-6">
                                <textarea id="comments" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" value="{{ old('comments') }}" autofocus></textarea>
                                @if ($errors->has('comments'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('comments') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Realizar petición') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function changedInitialValue(){
        var min = document.querySelector('#fecha_inicio').value;
        document.querySelector('#fecha_fin').setAttribute('min', min);
        document.querySelector('#fecha_fin').setAttribute('value', min);
    }
</script>
@endsection
