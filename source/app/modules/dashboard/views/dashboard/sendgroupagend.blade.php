@extends('dashboard::_layout.base')

@section('content')
      <h3 class="admin-title">Envío de SMS - Grupal Agendado</h3>
      @if (Session::has('msg'))
      <div class="alert success">{{ Session::get('msg') }}</div>
      @endif
      <div class="frm-panel">
        {{ Form::open(array('route' => 'savegroup','id'=>'frmSmsGroupA')) }}
          <div class="control-group">
            {{ Form::label('idgroup', 'Grupo', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::hidden('typesend', 2, array('id'=>'typesend')) }}
              {{ Form::select('idgroup', $group, '0') }}
              <label class="error">{{ $errors->first('idgroup') }}</label>
            </div>
          </div>
          <div class="control-group">
            {{ Form::label('dateAgend', 'Fecha de Despacho', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::text('dateAgend', null, array('id'=>'dateAgend','class'=>'datetimepicker','readonly'=>'readonly')) }}
              <label class="error">{{ $errors->first('dateAgend') }}</label>
            </div>
          </div>
          <div class="control-group">
            {{ Form::label('message', 'Mensaje', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::textarea('message', null, array('id'=>'message')) }}
              <label class="error">{{ $errors->first('message') }}</label>
              <span class="count-charts"><span class="countChar" data-count="160">160</span> caracteres disponibles</span>
            </div>
          </div>
          <div class="control-group last">
            <div class="control">
              {{ Form::button('Enviar', array('class'=>'btn-admin', 'type' => 'submit')) }}
            </div>
          </div>
        {{ Form::close() }}
      </div>
@stop