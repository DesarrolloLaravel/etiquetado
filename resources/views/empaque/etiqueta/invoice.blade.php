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
    margin-top: -160px;
    margin-left: 20px;
  }
  table tbody tr td{
    padding-left: 10px;
  }
  </style>
  <body>
    <main>
      <table width="420" border="1" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td width="40%"></td>
            <td width="60%">
              FECHA DE PRODUCCION : {{ $data['fecha_produccion'] }}
              <br>
              FECHA DE VENCIMIENTO : {{ $data['fecha_vencimiento'] }}
            </td>
          </tr>
          <tr>
            <td>
              Especie<br>
              {{ $data['especie'] }}
            </td>
            <td>
              Producto<br>
              {{ $data['producto'] }}
            </td>
          </tr>
          <tr>
            <td>
              Calibre<br>
              {{ $data['calibre'] }}
            </td>
            <td>
              Calidad<br>
              {{ $data['calidad'] }}
            </td>
          </tr>
          <tr>
            <td>
              Piezas<br>
              {{ $data['piezas'] }}
            </td>
            <td>
              Peso Neto<br>
              {{ $data['peso_neto'] }}
            </td>
          </tr>
          <tr>
            <td>
              Numero Caja<br>
              {{ $data['caja_number'] }}
            </td>
            <td>
              NUMBER AUTHORIZED C.E.E./10879 R.S. N° 1199 DEL 24/05/2012 S.S. LOS LAGOS
            </td>
          </tr>
          <tr>
            <td>
              Elaborado por : ALIMENTOS & SERVICIOS ACUAFOOD SPA<br>
              Direccion : La Vara Senda Sur KM 2 S/N, Pasaje Don Justo<br>
              <br>
              Conservar a -18° C
            </td>
            <td style="text-align:center">
              <br>
              <img height="50" width="200" style="margin-left:10px;" src="{{ 'data:image/png;base64,'. $data['barcode'] }}" alt="barcode"   /><br>
              {{ $data['code'] }}
              <br>
              <br>
              LOTE N° ICH {{ $data['lote_number'] }} CHILE
            </td>
          </tr>
        </tbody>
      </table>
      <br>
      <br>
      <br>
  </body>
</html>