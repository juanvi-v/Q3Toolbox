<?php
class ToolsComponent extends Component
{
	var $controller;
	var $name='Tools';


	/**
	 * @method str2num
	 * converts locale string into float number
	 * @param string $str
	 * @return float
	 */
	function str2num($str){
		if(strpos($str, '.') < strpos($str,',')){
			$str = str_replace('.','',$str);
			$str = strtr($str,',','.');
		}
		else{
			$str = str_replace(',','',$str);
		}
		return (float)$str;
	}



	/**
	 * convierte a entidades html, detectando la codificacion.
	 * ¿a prueba de clientes?
	 * @param $cadena string
	 * @return string
	 */
//	function limpia_texto($cadena){
//		return htmlentities($cadena,ENT_COMPAT,mb_detect_encoding($cadena,array('UTF-8','iso-8859-1')));
//	}

	public function formatea_nombre($nick=null){
		//return mb_convert_case(str_replace('-',' ',$nick),MB_CASE_TITLE);

		return ucwords(str_replace('-',' ',$nick));
	}


	public function limpia_texto($cadena){
		$mensaje=strip_tags($cadena);

        $mensaje=htmlentities($mensaje,ENT_COMPAT,mb_detect_encoding($cadena,array('UTF-8','iso-8859-1')));
        $a=array("/([,])([^ ,:;?!\/])/is",
				"/&amp;euro;/is"
		);

		$b=array("$1 $2",
				"&euro;"
		);

		$mensaje=preg_replace($a,$b,$mensaje);

		return $mensaje;
    }

/*
 * genera una clave aleatoria de 8 caracteres por defecto
 */


	public function generarClave($length=8) {
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ";
		$code = "";
		$clen = strlen($chars) - 1;  //a variable with the fixed length of chars correct for the fence post issue
		while (strlen($code) < $length) {
			$code .= $chars[mt_rand(0,$clen)];  //mt_rand's range is inclusive - this is why we need 0 to n-1
		}
		return $code;
	}

	/**
	 * Hash basado en el nombre de usuario para mayor seguridad
	 * Enter description here ...
	 * @param string $usuario
	 * @param string $password
	 */

	public function generar_password($usuario=null, $password=null){
		if(!empty($usuario) && !empty($password)):
			return sha1(strrev(strtoupper(trim($usuario))).trim($password));
		else:
			return false;
		endif;
	}


	public function strposa($haystack=null,$needles=array(),$offset=0){
		if(!is_array($needles)){
			return strpos($haystack,$needles,$offset);
		}
		else{
			$chr=array();
			foreach($needles as $needle) {
				$res = strpos($haystack, $needle, $offset);
				if ($res !== false) {
					$chr[$needle] = $res;
				}
			}
			if(empty($chr)){
				return false;
			}
			return min($chr);
		}
	}

	public function filtro_caracteres($cadena=null){
		return preg_match("/[0-9!@£$%^&*()<>\#=?\[\]\/]/",$cadena);
	}
}//class