@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row align-items-center">
    <div class="col-md-12 mx-auto table-responsive">
      <table width="100%" class="table">
        <thead class="thead-light">
          <tr>
            <th>Empleado</th>
            <th>Departamento</th>
            <th>Un día</th>
            <th>Día de inicio</th>
            <th>Día de finalización</th>
            <th>Hora de inicio</th>
            <th>Hora de finalización</th>
            <th>Razón</th>
            <th>Justificante</th>
          </tr>
        </thead>
        @foreach ($absences as $absence)
        <tr>
          <td>{{$absence['user']->name}}</td>
          <td>{{$absence['user']->departments[0]->name}}</td>
          <td>{{$absence['day'] == 1 ? "Sí" : "No"}}</td>
          <td>{{$absence['fecha_inicio']}}</td>
          <td>{{$absence['fecha_fin']}}</td>
          <td>{{$absence['hora_inicio']}}</td>
          <td>{{$absence['hora_fin']}}</td>
          <td>{{$absence['comments']}}</td>
          <td>@if($absence['document']) 
            <a href="data:{{$absence['mimetype']}};base64,{{$absence['document']}}" download="{{$absence['id']}}.{{$absence['extension']}}">Justificante</a>
            @endif</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
