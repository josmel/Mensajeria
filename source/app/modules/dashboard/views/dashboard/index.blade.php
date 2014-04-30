@extends('dashboard::_layout.base')

@section('content')
      <h3 class="admin-title">Dashboard</h3>
      <div class="row-well">
        <p>Bienvenido admin, que operación desea realizar:</p>
        <ul class="list-opt">
          <li><a title="Envío SMS" href="javascript:;">Envío SMS</a></li>
          <li><a title="Aprovisionar" href="javascript:;">Aprovisionar</a></li>
          <li><a title="Reportes" href="javascript:;">Reportes</a></li>
        </ul>
      </div>
@stop