@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Notificación</div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="container">
            <div class="row align-items-center">
              <div class="col-md-12 mx-auto table-responsive">
                <table width="100%" class="table">
                  <tr>
                    <td>{{$notification->data['title']}}</td>
                    <td>{{$notification->data['message']}}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
