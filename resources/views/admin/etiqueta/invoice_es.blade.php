<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Etiqueta</title>
    <style type="text/css"></style>
</head>
<style type="text/css">
    table {
        -ms-transform: rotate(90deg); /* IE 9 */
        -ms-transform-origin: 16% 65%; /* IE 9 */
        -webkit-transform: rotate(90deg); /* Chrome, Safari, Opera */
        -webkit-transform-origin: 16% 65%; /* Chrome, Safari, Opera */
        transform: rotate(90deg);
        transform-origin: 16% 65%;
        margin-top: -200px;
        margin-left: 10px;
        font-size: 14px;
    }
    table tbody tr td{
        padding-left: 10px;
    }
    tr.with-border td {           /* this makes borders/margins work */
        border: 1px solid black;
    }
    tr.noBorder td {border: 0;}
    p {
        margin: 3px -5px;
        padding: 0px;
        font-size: 12px;
    }
</style>
<body>
<script type="text/javascript">
    this.print();
</script>
<main>
    <table width="377" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr class="with-border">
            <td colspan="10">
                <p>{{ $data['especie_comercial_name'] }}</p>
                <p style="font-size: 13px">{{ $data['producto'] }}</p>
            </td>
            <td colspan="2">
                <p>Caja</p>
                {{ $data['caja_number']  }}
            </td>
        </tr>
        <tr class="with-border">
            <td colspan="4">
                <p>Conservaci&oacute;n</p>
                {{ $data['condicion'] }}
            </td>
            <td colspan="5">
                <p>Calidad</p>
                {{ $data['calidad'] }}
            </td>
            <td colspan="3">
                <p>Tama&ntilde;o</p>
                {{ $data['calibre'] }}
            </td>
        </tr>
        <tr class="with-border">
            <td colspan="4">
                <p style="font-size: 10px">Fecha Empaque
                    <br>(dd-mm-yyyy):</p>
                <p style="font-size: 10px">Fecha Expiraci&oacute;n
                    <br>(dd-mm-yyyy):</p>
            </td>
            <td colspan="3" style='border-left-style: hidden; font-size: 12px'>
                {{ $data['fecha_produccion'] }}<br>
                {{ $data['fecha_vencimiento'] }}
            </td>
            <td colspan="2" style="text-align: center">
                <p style="margin: 0px 0px">Lote</p>
                {{ $data['lote_number'] }}
            </td>
            <td colspan="2">
                <p>Peso Neto</p>
                {{ $data['peso_neto'] }}
            </td>
            <td colspan="1">
                <p>Piezas</p>
                {{ $data['piezas'] }}
            </td>
        </tr>
        <tr class="with-border">
            <td colspan="10">
                <p>Elaborado por: ALIMENTOS & SERVICIOS ACUAFOOD SPA</p>
                <p> La Vara Senda Sur KM 2 S/N,     Puerto Montt, Chile</p>
            </td>
            <td colspan="2">
                <p>Planta N°</p>
                10879
            </td>
        </tr>
        <tr class="noBorder">
            <td colspan="12">
                <p>
                    Pais de Origen Chile * Salmon de Cultivo * Procesado en Chile * Coloreado con Astaxantina * Mantener congelado a -18° C * Procesado de acuerdo al programa HACCP
                </p>
            </td>
        </tr>
        <tr class="noBorder">
            <td colspan="12" style="text-align:center">
                <p style="font-size: 12px">{{ $data['code'] }}</p>
                <img height="50" width="200" style="margin-left:10px;" src="{{ 'data:image/png;base64,'. $data['barcode'] }}" alt="barcode"   /><br>
                <br>
            </td>
        </tr>
        </tbody>
    </table>
</main>
</body>
</html>