@extends('dashboard::_layout.base')

@section('content')
      <h3 class="admin-title">Gestión - Reporte por Detalle</h3>
      <div class="frm-panel hideSearchTbl">
        {{ Form::open(array('route' => 'reportconsolidated')) }}
          <div class="frm-head">
            <h2><span class="icTable"></span>Reporte por Detalle</h2>
          </div>
          <div class="cgroup-inline">
            <div class="control-group">
              {{ Form::label('from', 'Desde', array('class' => 'control-label')) }}
              <div class="controls">
                {{ Form::text('from', null, array('id' => 'from', 'data-column'=>'0', 'readonly'=>true, 'class' =>'coltbl-filter')) }}
              </div>
            </div>
            <div class="control-group">
              {{ Form::label('to', 'Hasta', array('class' => 'control-label')) }}
              <div class="controls">
                {{ Form::text('to', null, array('id' => 'to', 'data-column'=>'1', 'readonly'=>true, 'class' =>'coltbl-filter')) }}
              </div>
            </div>
          </div>
          <div class="cgroup-inline">
            <div class="control-group">
              {{-- Form::label('operator', 'Operador', array('class' => 'control-label')) --}}
              {{ Form::label('user', 'Usuarios', array('class' => 'control-label')) }}
              <div class="controls">
                {{-- Form::select('operator', $operator, 0, array('class'=>'select-large coltbl-filter', 'data-column'=>'2')) --}}
                {{ Form::select('user', $user, null, array('class'=>'select-large coltbl-filter', 'data-column'=>'4')) }}
              </div>
            </div>
            <div class="control-group">
              {{ Form::label('incometype', 'Tipo de Ingreso', array('class' => 'control-label')) }}
              <div class="controls">
                {{ Form::select('incometype', $typeentry, null, array('class'=>'select-large coltbl-filter', 'data-column'=>'3')) }}
              </div>
            </div>
          </div>
          {{--<div class="cgroup-inline">
            <div class="control-group">
              {{ Form::label('user', 'Usuarios', array('class' => 'control-label')) }}
              <div class="controls">
                {{ Form::select('user', $user, null, array('class'=>'select-large coltbl-filter', 'data-column'=>'4')) }}
              </div>
            </div>
          </div>--}}
          <table cellspacing="0" cellpadding="0" border="0" id="datatable" class="display">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Despachado</th>
                <!--<th>Confirmado</th>-->
                <th>Fallido</th>
                <!--<th>Rechazado</th>-->
                <th>Pendiente</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        {{ Form::close() }}
      </div>
@stop