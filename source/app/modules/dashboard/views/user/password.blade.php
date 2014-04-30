@extends('dashboard::_layout.base')

@section('content')
<div class="container">
  <h3 class="admin-title">Password</h3>
  @if (Session::has('msg'))
    <div class="alert success">{{ Session::get('msg') }}</div>
  @endif
  <div class="frm-panel">
    {{ Form::model($user, array('action' => array('App\Modules\Dashboard\Controllers\UserController@updatePassword', $user->id),'id'=>'frmPerfil')) }}
      <div class="control-group">
        <label class="control-label">Contraseña actual</label>
        <div class="controls">
          {{ Form::password("password") }}
          <label class="error">{{ $errors->first('password') }}</label>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Nueva Contraseña</label>
        <div class="controls">
          {{ Form::password("newpassword") }}
          <label class="error">{{ $errors->first('newpassword') }}</label>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Confirmar Nueva Contraseña</label>
        <div class="controls">
          {{ Form::password("newpasswordconfirm") }}
          <label class="error">{{ $errors->first('newpasswordconfirm') }}</label>
        </div>
      </div>
      <div class="control-group">
        <div class="control">
          <button type="submit" class="btn-admin">Guardar</button>
        </div>
      </div>
    {{ Form::close() }}
  </div>
</div>
@stop