@extends('dashboard::_layout.base')

@section('content')
      <h3 class="admin-title">Aprovisionar - Crear Usuario</h3>
        @if (Session::has('msg'))
          <div class="alert success">{{ Session::get('msg') }}</div>
        @endif
      <div class="frm-panel">
            @if(isset($customer->id))
            {{ Form::model($customer, array('action' => array('App\Modules\Dashboard\Controllers\SuplyController@saveSupplyUser', $customer->id), 'id' => 'frmNewUser')) }}
                {{ Form::hidden('id') }}
            @else
                {{ Form::open(array('route' => 'supplysaveuser','id'=>'frmNewUser')) }}
            @endif
        {{-- Form::open(array('route' => 'supplysaveuser','id'=>'frmNewUser')) --}}
         <div class="control-group">
           {{ Form::label('idoperator', 'Operadores', array('class' => 'control-label')) }}
           <div class="controls">
             @if(isset($customer->idoperator))
                @if($idoperator = $customer->idoperator) @endif
             @else
                @if($idoperator = null) @endif
             @endif
             {{ Form::select('idoperator', $operator, $idoperator, array('class'=>'select-large')) }}
             <label class="error">{{ $errors->first('idoperator') }}</label>
           </div>
         </div>
          <div class="control-group">
            {{ Form::label('phone', 'Móvil', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::text('phone', null, array('id'=>'phone','class'=>'numeric','maxlength'=>'9')) }}
              <label class="error">{{ $errors->first('phone') }}</label>
            </div>
          </div>
          <div class="control-group">
            {{ Form::label('name', 'Nombre', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::text('name', null, array('id'=>'name')) }}
              <label class="error">{{ $errors->first('name') }}</label>
            </div>
          </div>
          <div class="control-group">
            {{ Form::label('lastName', 'Apellido', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::text('lastName', null, array('id'=>'lastName')) }}
              <label class="error">{{ $errors->first('lastName') }}</label>
            </div>
          </div>
          <div class="control-group">
            {{ Form::label('birthday', 'Fecha de Nacimiento', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::text('birthday', null, array('id'=>'birthday','class'=>'birthdaypicker','readonly'=>'readonly')) }}
              <label class="error">{{ $errors->first('birthday') }}</label>
            </div>
          </div>
          <div class="control-group">
            {{ Form::label('groupassociate', '¿Asociar Grupo?', array('class' => 'control-label ctrl-txt')) }}
            <div class="controls">
             @if(isset($groupSelect) && count($groupSelect) > 0)
              <label class="ioption">
                {{ Form::radio('groupassociate', '1', true) }}Si
              </label>
              <label class="ioption">
                {{ Form::radio('groupassociate', '0') }}No
              </label>
             @else
              <label class="ioption">
                {{ Form::radio('groupassociate', '1') }}Si
              </label>
              <label class="ioption">
                {{ Form::radio('groupassociate', '0', true) }}No
              </label>
             @endif
                <label class="error">{{ $errors->first('groupassociate') }}</label>
            </div>
          </div>
          <div class="control-group">
            {{ Form::label('group', 'Grupos', array('class' => 'control-label')) }}
            <div class="controls">
             @if(isset($groupSelect) && count($groupSelect) > 0)
                {{ Form::select('group[]', $group, $groupSelect, array('multiple')); }}
             @else
                {{ Form::select('group[]', $group, array(null), array('multiple')); }}
             @endif
              <label class="error">{{ $errors->first('group') }}</label>
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