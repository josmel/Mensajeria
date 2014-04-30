@extends('dashboard::_layout.base')

@section('content')
<div class="container">
  <h3 class="admin-title">Perfil</h3>
  @if (Session::has('msg'))
    <div class="alert success">{{ Session::get('msg') }}</div>
  @endif
  <div class="frm-panel">
    {{ Form::model($user, array('action' => array('App\Modules\Dashboard\Controllers\UserController@updatePerfil', $user->id),'id'=>'frmPerfil')) }}
      <div class="control-group">
        <label class="control-label">Usuario</label>
        <div class="controls">
          {{ Form::text("username") }}
          <label class="error">{{ $errors->first('username') }}</label>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Nombre</label>
        <div class="controls">
          {{ Form::text("name") }}
          <label class="error">{{ $errors->first('name') }}</label>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Apellidos</label>
        <div class="controls">
          {{ Form::text("lastName") }}
          <label class="error">{{ $errors->first('lastName') }}</label>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Fecha de nacimiento</label>
        <div class="controls">
          {{ Form::text("birthday", null, array('class' => 'birthdaypicker')) }}
          <label class="error">{{ $errors->first('birthday') }}</label>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Celular</label>
        <div class="controls">
          {{ Form::text("phone") }}
          <label class="error">{{ $errors->first('phone') }}</label>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Pais</label>
        <div class="controls">
          {{ Form::select('idcountry', $country, 0, array('class'=>'select-large')) }}
          <label class="error">{{ $errors->first('idcountry') }}</label>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Dirección</label>
        <div class="controls">
          {{ Form::text("address") }}
          <label class="error">{{ $errors->first('address') }}</label>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Email</label>
        <div class="controls">
          {{ Form::text("email") }}
          <label class="error">{{ $errors->first('email') }}</label>
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