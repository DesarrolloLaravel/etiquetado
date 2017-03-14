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
        margin-top: -190px;
        margin-left: 20px;
        font-size: 10px;
    }
    table tbody tr td{
        padding-left: 10px;
    }
    tr.with-border td {           /* this makes borders/margins work */
        border: 1px solid black;
    }
    tr.noBorder td {border: 0;}
    p {
        margin: 0px 0px;
        padding: 0px;
        font-size: 8px
    }
</style>
<body>
<script type="text/javascript">
    this.print();
</script>
<main>
    <table width="290" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td colspan="5">
                    LAKS FILET U/SKIND RÅ
                    <p>DYBFROSSEN - SKAL GENNEMVARMES INDEN FORTÆRING</p>
                    LACHS FILET O/HAUT ROH
                    <p>TIEFGEFROREN - VOR DEM VERZEHR DURCHGAREN</p>
                    SALMON FILLET SKIN-LESS RAW
                    <p>DEEP FROZEN -MUST BE COOKED BEFORE CONSUMPTION</p>
                    FILETTI DI SALMONE S/PELLE CRUDO
                    <p>SURGELATI - CONSUMARSI PREVIA COTTURA</p>
                    <br>
                </td>
                <td valign="top" colspan="1">
                    <p>NETTOVÆGT / NETTOGEWICHT:</p>
                    <p>NET WEIGHT / PESO NETTO:</p>
                    <div style="text-align: right; font-size: 16px">
                        SEE MAINLABEL<br>
                        @if($data['trim'] == "TD")
                            Trim-D<br>
                        @elseif($data['trim'] == "TE")
                            Trim-E<br>
                        @endif
                        {{ $data['calibre'] }}<br>
                        {{ $data['v2'] }}
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <p style="font-size: 7px">INGREDIENSER: LAKS (SALMO SALAR), OPDRÆTTET I CHILE</p>
                    <p style="font-size: 7px">ZUTATEN: LACHS (SALMO SALAR), AUS AQUAKULTUR CHILE</p>
                    <p style="font-size: 7px">INGREDIENTS: SALMON (SALMO SALAR), FARMED IN CHILE</p>
                    <p style="font-size: 7px">INGREDIENTI: SALMONE (SALMO SALAR), ALLEVATO IN CILE</p>
                </td>
                <td colspan="1" style="text-align: right">
                    @if($data['trim'] == "TE 3,5 cm" && $data['calibre'] == "450-900 g")
                        &#187; 1042581 &#171;<br>
                    @elseif($data['trim'] == "TE 3,5 cm" && $data['calibre'] == "900-1350 g")
                        &#187; 1042582 &#171;<br>
                    @elseif($data['trim'] == "TE 3,5 cm" && $data['calibre'] == "1350-1800 g")
                        &#187; 1042583 &#171;<br>
                    @endif
                    LOT: EVR 16/01
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <br>
                    <p style="font-size: 8px">NEDFROSSET DEN / EINGEFROREN AM / FROZEN ON / CONGELATO IL:</p>
                </td>
                <td colspan="1" style="text-align: right; font-size: 14px">
                    {{ $data['fecha_produccion'] }}
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <p style="font-size: 8px">VED -18°C BEDST FØR / BEI -18°C MINDESTENS MALTBARBIS / AT -18°C BEST BEFORE / A -18°C DA CONSUMARSI PREF. ENTRO:</p>
                </td>
                <td colspan="1" style="text-align: right; font-size: 14px">
                    {{ $data['fecha_vencimiento'] }}
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <br>
                    <p>OPBEVARING - AUFBEWAHRUNG - STORAGE - CONSERVAZIONE:</p>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <p style="margin-left: 16px">+5°C: 24 TIMER / STUNDEN / HOURS / ORE</p>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <p>*** -18°C: SE MHT. / SIEHE MHD. / SEE BEST BEFORE / VEDERA SCADENZA</p>
                    <p>MA IKKE GENFRYSES OPTØNING - NACH DEM AUFTAUEN NICHT WIEDER EINFRIEREN</p>
                    <p>DO NOT REFREEZE ONCE DEFROSTED - NON RICONGELARE UNA VOLTA SCONGELATO.</p>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <p>DISTRIBUTION: NORDIC SEAFOOD A/S, DK-9850 HIRT SHALS, DENMARK.</p>
                    <p style="font-size: 8px">www.nordicseafood.com</p>
                </td>
                <td colspan="1" style="text-align: right">
                    <p>CHILE</p>
                    <p>10879</p>
                </td>
            </tr>
        </tbody>
    </table>
</main>
</body>
</html>