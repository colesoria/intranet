@extends('layouts.app')

@section('content')
<div class="container">
  <a href="{{ route('absences.new') }}" class="create"><i class="fas fa-plus-square"></i> Hacer nueva notificación de ausencia</a>
  <p></p>
  <div class="row align-items-center">
    <div class="col-md-12 mx-auto table-responsive">
      <table width="100%" class="table">
        <thead class="thead-light">
          <tr>
            <th>Día de inicio</th>
            <th>Día de finalización</th>
            <th>Día único</th>
            <th>Hora de inicio</th>
            <th>Hora de finalización</th>
            <th>Razón</th>
            <th>Justificante</th>
            <th>Acciones</th>
          </tr>
        </thead>
        @foreach ($absences as $absence)
        <tr>
          <td>{{$absence['fecha_inicio']}}</td>
          <td>{{$absence['fecha_fin']}}</td>
          <td>{{$absence['day'] == 1 ? "Sí" : "No"}}</td>
          <td>{{$absence['hora_inicio']}}</td>
          <td>{{$absence['hora_fin']}}</td>
          <td>{{$absence['comments']}}</td>
          <td>@if($absence['document']) 
          <a href="data:{{$absence['mimetype']}};base64,{{$absence['document']}}" download="{{$absence['id']}}.{{$absence['extension']}}">Justificante</a>
          @endif</td>
          <td><a href={{"/absences/edit/".$absence['id']}}><i class="fas fa-edit text-warning" title="Editar"></i></a></td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
