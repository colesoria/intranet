@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Vacaciones</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="container">
                        <div class="row align-items-center">
                          <div class="col-md-12 mx-auto">
                            <div class="jumbotron">
                                <h3 class="display-4">{{$holidays->user->name}}</h3>
                                <h5 class="display-4">{{$holidays->user->departments[0]->name}}</h5>
                                <p>Fecha de inicio: <strong>{{$holidays->fecha_inicio}}</strong></p>
                                <p>Fecha de fin: <strong>{{$holidays->fecha_fin}}</strong></p>
                                <p>Días de vacaciones: <strong>{{$holidays->total_days}}</strong></p>
                                <p>Comentarios: {{$holidays->comments}}</p>
                                <hr>
                                <button type="button" class="btn btn-success" data-slidetoggle="#accept">Aprobar</button>
                                <button type="button" class="btn btn-warning" data-slidetoggle="#modification">Pedir modificación</button>
                                <button type="button" class="btn btn-danger" data-slidetoggle="#rejection">Rechazar</button>
                                <p></p>
                                <form method="POST" id="accept" style="height:0;overflow:hidden;hidden;transition:height 0.5s" action={{"/holidays/review/".$holidays->id."/approve"}}>
                                    @csrf
                                    <div class="form-group row">
                                        <label for="comments_to_modify" class="col-md-4 col-form-label text-md-right">{{ __('Comentarios') }}</label>
                                        <div class="col-md-6">
                                            <textarea id="comments_to_modify" class="form-control{{ $errors->has('comments_to_modify') ? ' is-invalid' : '' }}" name="comments_to_modify" value="{{ old('comments_to_modify') }}" autofocus></textarea>
                                            @if ($errors->has('comments_to_modify'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('comments_to_modify') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </form>
                                <form method="POST" id="modification" style="height:0;overflow:hidden;hidden;transition:height 0.5s" action={{"/holidays/review/".$holidays->id."/modificate"}}>
                                    @csrf
                                    <div class="form-group row">
                                        <label for="comments_to_modify" class="col-md-4 col-form-label text-md-right">{{ __('Comentarios') }}</label>
                                        <div class="col-md-6">
                                            <textarea id="comments_to_modify" class="form-control{{ $errors->has('comments_to_modify') ? ' is-invalid' : '' }}" name="comments_to_modify" value="{{ old('comments_to_modify') }}" autofocus></textarea>
                                            @if ($errors->has('comments_to_modify'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('comments_to_modify') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </form>
                                <form method="POST" id="rejection" style="height:0;overflow:hidden;transition:height 0.5s" action={{"/holidays/review/".$holidays->id."/reject"}}>
                                    @csrf
                                    <div class="form-group row">
                                        <label for="comments_to_modify" class="col-md-4 col-form-label text-md-right">{{ __('Comentarios') }}</label>
                                        <div class="col-md-6">
                                            <textarea id="comments_to_modify" class="form-control{{ $errors->has('comments_to_modify') ? ' is-invalid' : '' }}" name="comments_to_modify" value="{{ old('comments_to_modify') }}" autofocus></textarea>
                                            @if ($errors->has('comments_to_modify'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('comments_to_modify') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </form>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function slidetoggle() {
        document.querySelectorAll(this.getAttribute('data-slidetoggle')).forEach(el => {
            if(el.getAttribute('id') === 'modification'){
                document.querySelector('#rejection').style.height = 0;
                document.querySelector('#accept').style.height = 0;
            }else if(el.getAttribute('id') === 'rejection'){
                document.querySelector('#modification').style.height = 0;
                document.querySelector('#accept').style.height = 0;
            }else{
                document.querySelector('#modification').style.height = 0;
                document.querySelector('#rejection').style.height = 0;
            }
            const ch = el.clientHeight,
            sh = el.scrollHeight,
            isCollapsed = !ch,
            noHeightSet = !el.style.height;
            el.style.height = (isCollapsed || noHeightSet ? sh : 0) + "px";
            if (noHeightSet) return slidetoggle.call(this);
        });
    }
    document.querySelectorAll("[data-slidetoggle]").forEach(el => el.addEventListener('click', slidetoggle));
</script>
@endsection

