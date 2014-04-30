    <aside class="sidebar">
      <ul class="admin-menu">
        <li><a title="Dashboard" href="javascript:;" class="active"><span class="icad-dashb"></span>Dashboard</a></li>
        <li><a title="Envío SMS" href="javascript:;"><span class="icad-prom"></span>Envío SMS</a>
          <ul>
            <li><a href="{{ route('sendone') }}" title="SMS individualo">SMS individual</a></li>
            <li><a href="{{ route('sendoneagend') }}" title="SMS agendado">SMS agendado</a></li>
            <li><a href="{{ action('App\Modules\Dashboard\Controllers\DashboardController@sendGroup') }}" title="Envío por Grupos">Envío por Grupos</a></li>
            <li><a href="{{ route('sendgroupagend') }}" title="Grupo Agendado">Grupo Agendado</a></li>
            <li><a href="{{ route('sendfile') }}" title="Desde Archivo">Desde Archivo</a></li>
          </ul>
        </li>
        <li><a title="Aprovisionar" href="javascript:;"><span class="icad-prom"></span>Aprovisionar</a>
          <ul>
            <li><a href="{{ route('suplyuser') }}" title="Usuarios">Usuarios</a></li>
            <li><a href="{{ route('suplygroup') }}" title="Grupos">Grupos</a></li>
          </ul>
        </li>
        <li><a title="Reportes" href="javascript:;"><span class="icad-prom"></span>Reportes</a>
          <ul>
            <li><a href="{{ route('reportdetail') }}" title="Por Detalle">Por Detalle</a></li>
            <li><a href="{{ route('reportconsolidated') }}" title="Consolidado">Consolidado</a></li>
          </ul>
        </li>
      </ul>
    </aside>