@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row align-items-center">
    <div class="col-md-12 mx-auto">
        <h1>Notificaciones</h1>
        @if(isset($unread_notifications))
            @foreach ($unread_notifications as $notification)
                <div class="alert alert-info">
                <h4 class="alert-heading">{{$notification['data']['title']}}</h4>
                <p>{{$notification['data']['message']}}</p>
                @if(isset($notification['data']['link']))
                    <a class="btn btn-primary btn-lg" href="{{$notification['data']['link']}}" role="button">Enlace</a>
                @endif
                </div>
                <p></p>
            @endforeach
        @endif
        <hr />
        @if(isset($read_notifications))
        <p>
            <button class="btn btn-secondary btn-sm" type="button" data-toggle="collapse" data-target="#old_notifications" aria-expanded="false" aria-controls="old_notifications">
                Notificaciones vistas
              </button>
        </p>
        <div class="collapse" id="old_notifications">
            @foreach ($read_notifications as $notification)
                <div class="alert alert-secondary">
                    <h4 class="alert-heading">{{$notification['data']['title']}}</h4>
                    <p>{{$notification['data']['message']}}</p>
                    @if(isset($notification['data']['link']))
                        <a class="btn btn-primary btn-lg" href="{{$notification['data']['link']}}" role="button">Enlace</a>
                    @endif
                </div>
                <p></p>
            @endforeach
        </div>
        @endif
    </div>
  </div>
</div>
@endsection
