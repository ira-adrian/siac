<?php
//src/Siarme/AusentismoBundle/Util/Util.php

namespace Siarme\AusentismoBundle\Util;


class Util
{
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