@extends('dashboard::_layout.base')

@section('content')
      <h3 class="admin-title">Aprovisionar - Crear Grupo</h3>
        @if (Session::has('msg'))
          <div class="alert success">{{ Session::get('msg') }}</div>
        @endif
      <div class="frm-panel">
            @if(isset($group->id))
            {{ Form::model($group, array('action' => array('App\Modules\Dashboard\Controllers\SuplyController@saveSupplyGroup', $group->id), 'id' => 'frmNewUser')) }}
                {{ Form::hidden('id') }}
            @else
                {{ Form::open(array('route' => 'supplysavegroup')) }}
            @endif
          <div class="control-group">
            {{ Form::label('name', 'Nombre', array('class' => 'control-label')) }}
            <div class="controls">
              {{ Form::text('name', null, array('id'=>'name')) }}
              <label class="error">{{ $errors->first('name') }}</label>
            </div>
          </div>
          <div class="control-group">
            {{ Form::label('groupassociate', '¿Asociar Usuario?', array('class' => 'control-label ctrl-txt')) }}
            <div class="controls">
                @if(isset($customerSelect) && count($customerSelect) > 0)
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
            {{ Form::label('customer', 'Usuarios', array('class' => 'control-label')) }}
            <div class="controls">
             @if(isset($customerSelect) && count($customerSelect) > 0)
                {{ Form::select('customer[]', $customer, $customerSelect, array('multiple')); }}
             @else
                {{ Form::select('customer[]', $customer, array(null), array('multiple')); }}
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