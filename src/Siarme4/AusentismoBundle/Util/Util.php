<?php
//src/Siarme/AusentismoBundle/Util/Util.php

namespace Siarme\AusentismoBundle\Util;


class Util
{
   
		function limpiarParaGSM7($cadena) {
		    // Alfabeto GSM7 (básico + extensión comúnmente aceptada)
		    $gsm7 = '@£$¥èéùìòÇ' . "\n" .
		            'Øø' . "\r" .
		            'ÅåΔ_ΦΓΛΩΠΨΣΘΞÆæÉ ' .
		            "!\"#¤%&'()*+,-./0123456789:;<=>?" .
		            '¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿' .
		            'abcdefghijklmnopqrstuvwxyzäöñüà' .
		            '^{}\\[~]|€';

		    // Mapeo de caracteres no GSM7 -> equivalentes GSM7
		    $map = [
		        'á' => 'a', 'à' => 'à', 'â' => 'a', 'ã' => 'a', 'ä' => 'ä', 'å' => 'å',
		        'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ä', 'Å' => 'Å',
		        'é' => 'é', 'è' => 'è', 'ê' => 'e', 'ë' => 'e',
		        'É' => 'É', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
		        'í' => 'i', 'ì' => 'ì', 'î' => 'i', 'ï' => 'i',
		        'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
		        'ó' => 'o', 'ò' => 'ò', 'ô' => 'o', 'õ' => 'o', 'ö' => 'ö', 'ø' => 'ø',
		        'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'Ö', 'Ø' => 'Ø',
		        'ú' => 'u', 'ù' => 'ù', 'û' => 'u', 'ü' => 'ü',
		        'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'Ü',
		        'ñ' => 'ñ', 'Ñ' => 'Ñ',
		        'ç' => 'c', 'Ç' => 'Ç',
		    ];

		    // Normalización
		    $cadena = strtr($cadena, $map);

		    // Filtra caracteres no pertenecientes a GSM7
		    $cadenaLimpia = '';
		    $len = mb_strlen($cadena, 'UTF-8');
		    for ($i = 0; $i < $len; $i++) {
		        $char = mb_substr($cadena, $i, 1, 'UTF-8');
		        if (strpos($gsm7, $char) !== false) {
		            $cadenaLimpia .= $char;
		        } else {
		            $cadenaLimpia .= '_'; // reemplazo para caracteres no soportados
		        }
		    }

		    return $cadenaLimpia;
		}

		static public function getSlug($cadena, $separador = '-')
		{
		// Código copiado de http://cubiq.org/the-perfect-php-clean-url-generator
		$slug = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
		$slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
		$slug = strtolower(trim($slug, $separador));
		$slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);
		return $slug;
		}

		static public function getDni($cuil)
		{
				$digits = array();
				for ($i = 2; $i < 10; $i++) {
							$digits[] = $cuil[$i];
				}
			return (int) implode('', $digits);
		}

		static public function getCuil($cuil)
		{
				$digits = array();
				for ($i = 2; $i < 10; $i++) {
							$digits[] = $cuil[$i];
				}

				$result=$cuil[0].$cuil[1]."-".implode('', $digits)."-".$cuil[10];
			return (string) $result;
		}
		static public function limpiarItem($s = "")
		{
	        $s = str_replace('á', 'a', $s); 
	        $s = str_replace('Á', 'A', $s); 
	        $s = str_replace('é', 'e', $s); 
	        $s = str_replace('É', 'E', $s); 
	        $s = str_replace('í', 'i', $s); 
	        $s = str_replace('Í', 'I', $s); 
	        $s = str_replace('ó', 'o', $s); 
	        $s = str_replace('Ó', 'O', $s); 
	        $s = str_replace('Ú', 'U', $s); 
	        $s= str_replace('ú', 'u', $s); 

	        //Quitando Caracteres Especiales 
	        //$s= str_replace('#', ' ', $s); 
	        $s= str_replace('  ', ' ', $s); 
	        $s= str_replace('   ', ' ', $s); 
	        $s= trim($s); 
	        return $s; 
		}
		static public function limpiarCadena($s = "")
		{
	        $s = str_replace('á', 'a', $s); 
	        $s = str_replace('Á', 'A', $s); 
	        $s = str_replace('é', 'e', $s); 
	        $s = str_replace('É', 'E', $s); 
	        $s = str_replace('í', 'i', $s); 
	        $s = str_replace('Í', 'I', $s); 
	        $s = str_replace('ó', 'o', $s); 
	        $s = str_replace('Ó', 'O', $s); 
	        $s = str_replace('Ú', 'U', $s); 
	        $s= str_replace('ú', 'u', $s); 

	        //Quitando Caracteres Especiales 
	        //$s= str_replace('#', ' ', $s); 
	        $s= str_replace('  ', ' ', $s); 
	        $s= str_replace('   ', ' ', $s); 
	        $s= str_replace('-', '', $s); 
	        $s= str_replace('(', '', $s); 
	        $s= str_replace(')', '', $s); 
	        $s= trim($s); 
	        return $s; 
		}

}