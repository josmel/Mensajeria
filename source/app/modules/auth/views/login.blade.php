@extends('auth::_layout.base')

@section('content')
    @if(Session::has('msj'))
    <div class='alert'>
        {{ Session::get('msj') }}
    </div>
    @endif
    <div class="login-panel frm-panel">
      {{ Form::open(array('action' => 'App\Modules\Auth\Controllers\AuthController@postLogin','id'=>'frmLogin')) }}
        <div class="frm-head">
          <h2><span></span>Ingresa a tu Cuenta</h2>
        </div>
        <div class="control-group">
          {{ Form::label('username', 'Username', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::text('username', null, array('id'=>'username')) }}
          </div>
        </div>
        <div class="control-group">
          {{ Form::label('password', 'Password', array('class' => 'control-label')) }}
          <div class="controls">
            {{ Form::password('password', null, array('id'=>'password')) }}
          </div>
        </div>
        <div class="control-group last">
          <div class="control">
            {{ Form::button('Ingresa', array('class'=>'btn-admin', 'type' => 'submit')) }}
          </div>
        </div>
      {{ Form::close() }}
    </div>
@stop
