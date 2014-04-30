@extends('dashboard::_layout.base')

@section('content')
     <h3 class="admin-title">Envío de SMS - Individual Inmediato</h3>
        @if (Session::has('msg'))
          <div class="alert success">{{ Session::get('msg') }}</div>
        @endif
      <div class="frm-panel">
        {{ Form::open(array('route' => 'saveone','id'=>'frmSmsSingle')) }}
          <div class="control-group">
            {{ Form::label('phone', 'Celular', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::hidden('typesend', 1, array('id'=>'typesend')) }}
              {{ Form::text('phone', null, array('id'=>'phone')) }}
              <label class="error">{{ $errors->first('phone') }}</label>
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
