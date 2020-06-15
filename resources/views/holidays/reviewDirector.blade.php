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
            <th>Día de inicio</th>
            <th>Día de finalización</th>
            <th>Días totales</th>
            <th>Estado</th>
          </tr>
        </thead>
        @foreach ($holidays as $dept=>$employees)
        @foreach($employees as $employee=>$group)
        @if($employee != '0')
        @foreach($group as $holiday)
        <tr>
          <td>{{$employee}}</td>
          <td>{{$dept}}</td>
          <td>{{$holiday->fecha_inicio}}</td>
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
        @endif
        @endforeach
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
