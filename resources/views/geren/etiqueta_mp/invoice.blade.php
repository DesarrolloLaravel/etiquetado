<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Etiqueta Materia Prima</title>
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
    margin-top: -175px;
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
      <table width="500" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr class="with-border">
            <td colspan="10">
              <p>{{ $data['especie_comercial_name'] }}</p>
              <p style="font-size: 13px">{{ $data['producto'] }}</p>
            </td>
            <td colspan="2">
              <p>Box</p>
              {{ $data['caja_number']  }}
            </td>
          </tr>
          <tr class="with-border">
            <td colspan="4">
              <p>Conservation</p>
              {{ $data['condicion'] }}
            </td>
            <td colspan="5">
              <p>Quality</p>
              {{ $data['calidad'] }}
            </td>
            <td colspan="3">
              <p>Size</p>
              {{ $data['calibre'] }}
            </td>
          </tr>
          <tr class="with-border">
            <td colspan="4">
              <p style="font-size: 10px">Packing Date (dd-mm-yyyy):</p>
              <p style="font-size: 10px">Best Before (dd-mm-yyyy):</p>
            </td>
            <td colspan="3" style='border-left-style: hidden; font-size: 12px'>
              {{ $data['fecha_produccion'] }}<br>
              {{ $data['fecha_vencimiento'] }}
            </td>
            <td colspan="2">
                <p>Lot</p>
                {{ $data['lote_number'] }}
            </td>
            <td colspan="2">
                <p>Net Weight</p>
                {{ $data['peso_neto'] }}
            </td>
            <td colspan="1">
                <p>Pieces</p>
                {{ $data['piezas'] }}
            </td>
          </tr>
          <tr class="with-border">
            <td colspan="10">
              <p>Elaborated by: ALIMENTOS & SERVICIOS ACUAFOOD SPA</p>
                <p> La Vara Senda Sur KM 2 S/N,     Puerto Montt, Chile</p>
            </td>
            <td colspan="2">
              <p>Plant N°</p>
              10879
            </td>
          </tr>
          <tr class="noBorder">
            <td colspan="12">
              <p>
                Country of origin Chile * Farm Raised * Processed in Chile * Colored with Astaxanthin * Keep Frozen at -18° C * Processed under HACCP program
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