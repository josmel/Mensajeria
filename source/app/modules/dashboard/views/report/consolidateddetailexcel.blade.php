{{
header("Pragma: public");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: pre-check=0, post-check=0, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Transfer-Encoding: none");
header("Content-Type: application/vnd.ms-excel;");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=_" . date('Ymd') . ".xls");}}
<div>
    <table cellspacing="0" cellpadding="0" border="1" id="datatable" class="display">
        <thead>
            <tr>
                <th>Movil</th>
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
        <tbody>
            @foreach($data as $key => $value)
            <tr>  
                <td>{{ $value->phone }}</td>
                <td>{{ $value->oname }}</td>
                <td>{{ $value->statename }}</td>
                <td>{{ $value->dateCreate }}</td>
                <td>{{ $value->tsname }}</td>
                <td>{{ $value->entryname }}</td>
                <td>{{ $value->uname }}</td>
                <td>{{ $value->message }}</td>
                <td>{{ $value->comment }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

