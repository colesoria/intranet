@extends('layouts.app')

@section('content')
<div class="container">
  <a href="{{ route('holidays.new') }}" class="create"><i class="fas fa-plus-square"></i> Hacer nueva petición de vacaciones</a>
  <p></p>
  <div class="row align-items-center">
    <div class="col-md-12 mx-auto table-responsive">
      <table width="100%" class="table">
        <thead class="thead-light">
          <tr>
            <th>Día de inicio</th>
            <th>Día de finalización</th>
            <th>Días totales</th>
            <th>Comentarios del administrador</th>
            <th>Estado/Acciones</th>
          </tr>
        </thead>
        @foreach ($holidays as $holiday)
        <tr>
          <td>{{$holiday['fecha_inicio']}}</td>
          <td>{{$holiday['fecha_fin']}}</td>
          <td>{{$holiday['total_days']}}</td>
          <td>{{$holiday['comments_to_modify']}}</td>
          @if($holiday['status'] == 'asked')
          <td><i class="far fa-clock text-primary" title="Pendiente"></i></td>
          @elseif($holiday['status'] == 'accepted')
          <td><i class="fas fa-check text-success" title="Aceptado"></i></td>
          @elseif($holiday['status'] == 'modification')
          <td><a href={{"/holidays/edit/".$holiday['id']}}><i class="fas fa-edit text-warning" title="Pendiente de modificaciones"></i></a></td>
          @elseif($holiday['status'] == 'rejected')
          <td><i class="fas fa-times-circle text-danger" title="Rechazado"></i></td>
          @endif
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
@endsection
