@extends('dashboard::_layout.base')

@section('content')
    <h3 class="admin-title">Envío de SMS - Desde Archivo</h3>
    @if (Session::has('msg'))
      <div class="alert success">{{ Session::get('msg') }}</div>
    @endif
      <div style="display: none;">{{ $command }}</div>
      <div class="frm-panel">
        {{ Form::open(array('route' => 'savefile', 'files'=> true)) }}
          <div class="control-group">
            {{ Form::label('filetype', 'Tipo de archivo', array('class' => 'control-label ctrl-txt')) }}
            <div class="controls">TXT o CSV</div>
          </div>
          <div class="control-group">
            {{ Form::label('fileformat', 'Formato de archivo', array('class' => 'control-label ctrl-txt')) }}
            <div class="controls">Celular;Mensaje;Fecha (La fecha sólo se ingresa en caso de ser un envío agendado)</div>
          </div>
          <div class="control-group">
            {{ Form::label('file', 'Formato de archivo', array('class' => 'control-label ctrl-txt')) }}
            <div class="controls">
              {{ Form::file('file', array('id' => 'fileformat')) }}
              <label class="error">{{ $errors->first('file') }}</label>
              @if (Session::has('errorResult'))
      <div class="alert success">{{ Session::get('errorResult') }}</div>
             @endif
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