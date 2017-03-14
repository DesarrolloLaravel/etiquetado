<?php
namespace App\FakerExtend;
/**
* Clase encargada de generar rut Chilenos válidos
* A la espera de su anexion a la libreria faker
*/

class RutGenerator{
	/**
	* genera un rut chileno válido
	*/
	public static function generar()
	{
	  $num_aleatorio = round(RutGenerator::random()*(25000000-5000000))+5000000;
	  return $num_aleatorio . RutGenerator::getDigito($num_aleatorio."");
	}
	/**
	* genera un arreglo de ruts Chileno válidos
	*/
	public static function generarVarios($numero_ruts){
		$res = [];
		for($i=0; $i<$numero_ruts; $i++){
			array_push($res, RutGenerator::generar());
		}
		return $res;
	}
	/**
	* Dado un número, devuelve el dígito validador
	*/
	public static function getDigito($r)
	{
		$r=strtoupper(preg_replace('|,|','',$r));
		$s=1;
		for($m=0;$r!=0;$r/=10)
			$s=($s+$r%10*(9-$m++%6))%11;
		return ( chr($s?$s+47:75));
	}
	/**
	* Genera un número entre 0 y 1
	*/
	public static function random() {
	  return (float)rand()/(float)getrandmax();
	}
	/**
	* Valida si un Rut es apropiado o no
	*/
	public static function valida_rut($r)
	{
		$r=strtoupper(preg_replace('|,|','',$r));
		$r=substr($r,0,strlen($r)-1);
		$sub_dv=substr($r,-1);
		$s=1;
		for($m=0;$r!=0;$r/=10)
			$s=($s+$r%10*(9-$m++%6))%11;
		return ( chr($s?$s+47:75)==$sub_dv );
	}

	public static function validarRut($r = false){
        if((!$r) or (is_array($r)))
            return false; /* Hace falta el rut */
     
        if(!$r = preg_replace('|[^0-9kK]|i', '', $r))
            return false; /* Era código basura */
     
        if(!((strlen($r) == 8) or (strlen($r) == 9)))
            return false; /* La cantidad de carácteres no es válida. */
     
        $v = strtoupper(substr($r, -1));
        if(!$r = substr($r, 0, -1))
            return false;
     
        if(!((int)$r > 0))
            return false; /* No es un valor numérico */
     
        $x = 2; $s = 0;
        for($i = (strlen($r) - 1); $i >= 0; $i--){
            if($x > 7)
                $x = 2;
            $s += ($r[$i] * $x);
            $x++;
        }
        $dv=11-($s % 11);
        if($dv == 10)
            $dv = 'K';
        if($dv == 11)
            $dv = '0';
        if($dv == $v)
            return number_format($r, 0, '', '.').'-'.$v; /* Formatea el RUT */
        return false;
    }
}