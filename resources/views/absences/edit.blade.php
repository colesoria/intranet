@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Nueva notificación de ausencia') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('absences.upgrade') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="dayview" class="col-md-4 col-form-label text-md-right">{{ __('Día único') }}</label>
                            <div class="col-md-6">
                            <input id="dayview" type="checkbox" class="form-control @error('dayview') is-invalid @enderror"  name="dayview" {{old('day') && old('day') == 1 || $absence->day && $absence->day == 1  ? 'checked' : null}} autofocus onclick="changedDayValue()">
                            <input id="day" type="hidden" name="day" value={{old('day') ? old('day') : $absence->day}} />
                            <input id="id" type="hidden" name="id" value={{$absence->id}} />
                                @error('dayview')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div id="multi">
                            <div class="form-group row">
                                <label for="fecha_inicio" class="col-md-4 col-form-label text-md-right">{{ __('Día de inicio') }}</label>
                                <div class="col-md-6">
                                <input id="fecha_inicio_multi" type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" name="fecha_inicio" value="{{ old('fecha_inicio') ? old('fecha_inicio') : $absence->fecha_innicio }}" autocomplete="fecha_inicio" autofocus min={{$min}} max={{$max}} onchange="changedInitialValue()">
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
                                <input id="fecha_fin_multi" type="date" class="form-control @error('fecha_fin') is-invalid @enderror" name="fecha_fin" value="{{ old('fecha_fin') ? old('fecha_fin') : null }}" min={{old('fecha_inicio') ? old('fecha_inicio') : $absence->fecha_inicio}} max={{$max}} autocomplete="fecha_fin" autofocus>
                                    @error('fecha_fin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div id="one" class="hide">
                            <div class="form-group row">
                                <label for="fecha_inicio" class="col-md-4 col-form-label text-md-right">{{ __('Día de ausencia') }}</label>
                                <div class="col-md-6">
                                <input id="fecha_inicio_one" type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" name="fecha_inicio" value="{{ old('fecha_inicio') ? old('fecha_inicio') : $absence->fecha_inicio }}" autocomplete="fecha_inicio" autofocus min={{$min}} max={{$max}} onchange="changedInitialValue()">
                                    @error('fecha_inicio')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="hora_inicio" class="col-md-4 col-form-label text-md-right">{{ __('Hora de inicio') }}</label>
                                <div class="col-md-6">
                                <input id="hora_inicio" type="time" class="form-control @error('hora_inicio') is-invalid @enderror" name="hora_inicio" value="{{ old('hora_inicio') ? old('hora_inicio') : $absence->hora_inicio }}" autocomplete="hora_inicio" autofocus>
                                    @error('hora_inicio')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="hora_fin" class="col-md-4 col-form-label text-md-right">{{ __('Hora de vuelta') }}</label>
                                <div class="col-md-6">
                                <input id="hora_fin" type="time" class="form-control @error('hora_fin') is-invalid @enderror" name="hora_fin" value="{{ old('hora_fin') ? old('hora_fin') : $absence->hora_fin }}" autocomplete="hora_fin" autofocus>
                                    @error('hora_fin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label for="comments" class="col-md-4 col-form-label text-md-right">{{ __('Razón de la ausencia') }}</label>
                            <div class="col-md-6">
                                <textarea id="comments" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" autofocus>{{ old('comments') ? old('comments') : $absence->comments }}</textarea>
                                @if ($errors->has('comments'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('comments') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="document" class="col-md-4 col-form-label text-md-right">{{ __('Justificante') }}</label>
                            <div class="col-md-6">
                                <input type="file" id="document" class="form-control-file{{ $errors->has('document') ? ' is-invalid' : '' }}" name="document" value="{{ old('document') ? old('document') : $absence->document }}" accept="image/*,.pdf" autofocus />
                                @if ($errors->has('document'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('document') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Actualizar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .hide{
        display:none;
    }
</style>
<script>
    var oneDay;
    document.addEventListener("DOMContentLoaded", function() {
        oneDay = {{$absence->day && $absence->day == "1" ? "true" : "false"}};
        changedDayValue();
    });
    function changedDayValue(){
        var multi = document.getElementById('multi');
        var one = document.getElementById('one');
        var day = document.getElementById('day');
        var inic_one = document.getElementById('fecha_inicio_one');
        var inic_multi = document.getElementById('fecha_inicio_multi');
        var fin_multi = document.getElementById('fecha_fin_multi');

        if(oneDay){
            multi.classList.add('hide');
            one.classList.remove('hide');
            day.setAttribute('value', 1);
            inic_one.setAttribute('required',true);
            inic_multi.removeAttribute('required');
            fin_multi.removeAttribute('required');
        }else{
            one.classList.add('hide');
            multi.classList.remove('hide');
            day.setAttribute('value', 0);
            inic_one.removeAttribute('required');
            inic_multi.setAttrinute('required',true);
            fin_multi.setAttrinute('required',true);
        }
        oneDay = !oneDay;
    }
</script>
@endsection
