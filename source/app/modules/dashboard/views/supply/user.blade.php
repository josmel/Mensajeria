@extends('dashboard::_layout.base')

@section('content')
      <h3 class="admin-title">Aprovisionar - Usuarios</h3>
      <div class="frm-panel hideSearchTbl">
        {{ Form::open(array('route' => 'sendgroup')) }}
          <div class="frm-head">
            <h2><span class="icTable"></span>Listado de Usuarios</h2><a href="{{ route('suplyuseroperation') }}" title="Agregar Nuevo" class="btn-admin">Agregar Nuevo</a>
          </div>
          <div class="cgroup-inline">
            <div class="control-group">
              {{ Form::label('name', 'Nombre', array('class' => 'control-label')) }}
              <div class="controls">
                {{ Form::text('name', null, array('id'=>'name','class'=>'coltbl-filter','data-column'=>'2')) }}
              </div>
            </div>
            <div class="control-group">
                {{ Form::label('lastname', 'Apellido', array('class' => 'control-label')) }}
              <div class="controls">
                {{ Form::text('lastname', null, array('id'=>'lastname','class'=>'coltbl-filter','data-column'=>'3')) }}
              </div>
            </div>
          </div>
          <div class="cgroup-inline">
            <div class="control-group">
                {{ Form::label('phone', 'Celular', array('class' => 'control-label')) }}
              <div class="controls">
                {{ Form::text('phone', null, array('id'=>'phone','class'=>'coltbl-filter numeric','data-column'=>'0')) }}
              </div>
            </div>
          </div>
          <table cellspacing="0" cellpadding="0" border="0" id="datatable" class="display">
            <thead>
              <tr>
                <th>Móvil</th>
                <th>Operador</th>
                <th>Nombre</th>
                <th>Apellido</th>
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