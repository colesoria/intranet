<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img class="align-self-center image-logonavbar" src={{ asset('assets/logo.png') }} />
    </a>
    @guest
      @else
        <div class="container navbar-text">
        @if(!isset($status) || $status == 'No trabajando')
            <a class="btn btn-primary" href="/sign">Fichar de entrada</a>
            <span class="ml-2 text-danger">{{$status}}</span>
        @elseif($status == 'Trabajando')
            <a class="btn btn-primary" href="/sign/rest">Descansar</a>
            <span class="ml-2 text-success">{{$status}}</span>
        @elseif($status == 'Descansando')
            <a class="btn btn-primary" href="/sign/unrest">Volver de descanso</a>
            <span class="ml-2 text-warning">{{$status}}</span>
        @else
        <a class="btn btn-primary" href="/sign">Fichar de entrada</a>
            <span class="ml-2 text-muted">{{$status}}</span>
        @endif
        </div>
    @endguest
    <!-- Right Side Of Navbar -->
    <div class="dropdown">
      @guest
      @else
        <a id="navbarDropdown" class="btn dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ Auth::user()->name }}</a>
        @mobile
        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
        @elsemobile
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        @endmobile
          <a class="dropdown-item" href="{{ route('me') }}">{{ __('Mi perfil') }}</a>
          <a class="dropdown-item" href="{{ route('notifications') }}">{{ __('Notificaciones') }} <span id="notifications-counter" data-count={{Auth::user()->unreadNotifications->count()}} class="badge badge-dark">{{Auth::user()->unreadNotifications->count()}}</span></a>
          <div class="dropdown-divider"></div>
          @if(Auth::user()->hasAnyRole(['Administrador','Super administrador']))
          <h6 class="dropdown-header">Administración</h6>
          <a class="dropdown-item" href="/users">Ver usuarios</a>
          <a class="dropdown-item" href="/admin/signs/today">Ver fichajes</a>
          <a class="dropdown-item" href="/admin/holidays">Ver vacaciones</a>
          <a class="dropdown-item" href="/remote/review">Ver teletrabajo</a>
          <a class="dropdown-item" href="/absences/review">Ver ausencias</a>
          <div class="dropdown-divider"></div>
          @endif
          @if(Auth::user()->hasAnyRole(['Director']))
          <h6 class="dropdown-header">Administración</h6>
          <a class="dropdown-item" href="/admin/signs/today">Ver fichajes</a>
          <a class="dropdown-item" href="/admin/holidays">Ver vacaciones</a>
          <a class="dropdown-item" href="/remote/review">Ver teletrabajo</a>
          <div class="dropdown-divider"></div>
          @endif
          <h6 class="dropdown-header">Mis acciones</h6>
          <a class="dropdown-item" href="/sign">Fichaje</a>
          <a class="dropdown-item" href="/sign/today">Ver fichajes</a>
          <a class="dropdown-item" href="/remote">Teletrabajo</a>
          <a class="dropdown-item" href="/holidays">Vacaciones</a>
          <a class="dropdown-item" href="/absences">Ausencias</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      @endguest
    </div>
</div>
  </div>
</nav>
