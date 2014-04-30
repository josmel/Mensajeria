@extends('dashboard::_layout.base')

@section('content')
<h3 class="admin-title">Gestión - Reporte por Detalle de Consolidado</h3>
<div class="frm-panel hideSearchTbl" style="position: relative">
    {{ Form::open(array('route' => 'reportconsolidateddetaillist')) }}
    <div class="frm-head">
        <h2><span class="icTable"></span>Reporte por Detalle de Consolidado</h2>
    </div><a class="btn-admin" style="position: absolute;display: block;z-index: 1;margin-top:8px;margin-left: 10px" title="Descargar en Excel" href="{{ $urlexcel }}">Descargar Reporte</a>
    <table cellspacing="0" cellpadding="0" border="0" id="datatable" class="display">
        <thead>
            <tr>
                <th>Móvil</th>
                <th>Operador</th>
                <th>Estado</th>
                <th>Fecha Envio</th>
                <th>Tipo de envio</th>
                <th>Tipo de Ingreso</th>
                <th>Creado por</th>
                <th>Mensaje SMS</th>
                <th>Detalle de Envio</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    {{ Form::close() }}
</div>
@stop