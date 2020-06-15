@extends('layouts.app')

@section('content')
<div class="container">
  @if(Auth::user()->hasAnyRole(['Administrador', 'Super administrador']))
  <a href="{{ route('holidays.review') }}" class="create"><i class="fas fa-check-square"></i> Revisar vacaciones</a>
  @endif
  <div class="row align-items-center">
    <div class="col-md-12 mx-auto table-responsive">

      <p></p>
      <table width="100%" class="table">
        <thead class="thead-light">
          <tr>
            <th>Empleado</th>
            <th>Departamento</th>
            <th>Día de inicio</th>
            <th>Día de finalización</th>
            <th>Días totales</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        @foreach ($holidays as $holiday)
        <tr>
          <td>{{$holiday['user']->name}}</td>
          <td>{{$holiday['user']->departments[0]->name}}</td>
          <td>{{$holiday['fecha_inicio']}}</td>
          <td>{{$holiday['fecha_fin']}}</td>
          <td>{{$holiday['total_days']}}</td>
          @if($holiday['status'] == 'asked')
          <td><i class="far fa-clock text-primary"></i></td>
          @elseif($holiday['status'] == 'accepted')
          <td><i class="fas fa-check text-success"></i></td>
          @elseif($holiday['status'] == 'modification')
          <td><i class="fas fa-edit text-warning"></i></td>
          @elseif($holiday['status'] == 'rejected')
          <td><i class="fas fa-times-circle text-danger"></i></td>
          @endif
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
