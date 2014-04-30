@extends('dashboard::_layout.base')

@section('content')
     <h3 class="admin-title">Aprovisionar - Grupos</h3>
      <div class="frm-panel">
        {{ Form::open(array('route' => 'suplygroup')) }}
          <div class="frm-head">
            <h2><span class="icTable"></span>Listado de Grupos</h2><a href="{{ route('suplygroupoperation') }}" title="Agregar Nuevo" class="btn-admin">Agregar Nuevo</a>
          </div>
          <table cellspacing="0" cellpadding="0" border="0" id="datatable" class="display">
            <thead>
              <tr>
                <th>id</th>
                <th>Nombre</th>
                <th>Usuarios</th>
                <th>Creado por</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        {{ Form::close() }}
      </div>
@stop