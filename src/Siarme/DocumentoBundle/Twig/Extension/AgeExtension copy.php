<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Translation\TranslatorInterface;
use \DateTime;
use \Twig_Extension;
use \Twig_SimpleFilter;

/**
 * Class AgeExtension
 * @package DocumentoBundle\Twig
 */
class AgeExtension extends Twig_Extension
{
    private $em;

    // Update the type-hint in the constructor:
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('age', [$this, 'age']),
            new Twig_SimpleFilter('sumar_dias', [$this, 'sumarDias']),
        ];
    }
    
    /**
     * @param DateTime $dateTime
     * @return int
     */
    public function age(DateTime $dateTime)
    {
        return $dateTime->diff(new DateTime())->format('%Y');
    }

   /**
     * @param DateTime $dateTime
     * @return int
     */
    public function sumarDias($dateTime, $dias = 0, $habiles = true )
    {
        
        // 1. Obtengo el año de consulta de $datetime.
        $fecha = new \DateTime($dateTime);
        $anio = $fecha->format("Y");

        // Convierto a segundos $datetime. 
        $fechaInicial = date($dateTime);
        $fechaEnSegundos = strtotime($fechaInicial);

        $diasAumentar = $dias + 1;
        $dia = 86400;
        //Establezco la zon horaria 
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $contador = 1;
        // Inicializo el array que contendra los feriado con los que coincide el periodo de plazos.
        $msj = "";

        //Feriados Nacionales
        $feriadosN = $this->getFeriados($anio);
        //Feriados Provinciales
        $feriadosP = $this->em->getRepository('ExpedienteBundle:Evento')->findByAnio($anio);

        $esFeridado = false;
        $esFeridadoP = false;
        $eventoAnterior = null;
        while ($contador <= $diasAumentar) {
            // 2. Compruebo si la  fecha esta en un Feriado Nacional
            if ($feriadosN) {
                $feridado =  $this->getesFeriadoNacional($feriadosN, $fechaEnSegundos);
                if ($feridado['esFeriado']){
                        $fecha = $feridado['dato']['fecha'];
                        $nombre = $feridado['dato']['nombre'];
                        $msj .= "- $fecha | $nombre <br>";
                        $fechaEnSegundos += $dia;
                        $esFeridado = true;
                }
            } 
                
            // 3. Compruebo si la  fecha esta en un feriado Provincial
            if (!$esFeridado) {
                // Iterar sobre cada objeto Evento en el array
                $nombre = "";
                $eventoP = null;
                foreach ($feriadosP as $evento) {
                    // 1. Obtener las fechas de inicio y fin del evento
                    $fechaInicio = $evento->getFechaInicio(); // O acceder directamente a la propiedad si no tienes getters
                    $fechaFin = $evento->getFechaFin();       // O acceder directamente a la propiedad
                    $start = strtotime($fechaInicio->format('Y-m-d'));
                    $end = strtotime($fechaFin->format('Y-m-d'));

                    // La fecha a verificar debe ser MAYOR O IGUAL que la fecha de inicio
                    // Y la fecha a verificar debe ser MENOR O IGUAL que la fecha de fin
                    if ($fechaEnSegundos >= $start && $fechaEnSegundos <= $end) {
                        $nombre = $evento->getTitulo();
                        $esFeridadoP = true;
                        $eventoP = $evento;
                    } 
                }
                if ($esFeridadoP) {  
                    if ($eventoAnterior == null) {
                        $fechaInicio = $eventoP->getFechaInicio()->format('Y-m-d');
                        $fechaFin = $eventoP->getFechaFin()->format('Y-m-d');   
                        $msj .= "- $fechaInicio - $fechaFin: $nombre <br>";
                        $fechaEnSegundos += $dia;
                        $esFeridadoP = false;
                        $esFeridado = true;
                        $eventoAnterior = $eventoP;
                    } elseif ( $eventoAnterior->getId() != $eventoP->getId() ) {
                        $fechaInicio = $eventoP->getFechaInicio()->format('Y-m-d');
                        $fechaFin = $eventoP->getFechaFin()->format('Y-m-d'); 
                        $msj .= "- $fechaInicio - $fechaFin: $nombre <br>";
                        $fechaEnSegundos += $dia;
                        $esFeridadoP = false;
                        $esFeridado = true;
                        $eventoAnterior = $eventoP;
                    } else {
                        $fechaEnSegundos += $dia;
                        $esFeridadoP = false;
                        $esFeridado = true;
                    }
                }
            }

            // 4. Compruebo si la fecha esta en un Fin de Semana
            if (!$esFeridado) {
                $fechaEnSegundos += $dia;
                if (date('N',$fechaEnSegundos) == 6 or date('N',$fechaEnSegundos) == 7)  {
                    $fecha = Date('Y-m-d',$fechaEnSegundos);
                    $msj .= "- $fecha: Fin de Semana <br>";
                } else {
                    $contador +=1;  
                }
            } 
           $esFeridado = false;

            if ($anio != Date('Y',$fechaEnSegundos)) {
                //Feriados Nacionales
                $feriadosN = $this->getFeriados($anio);
                //Feriados Provinciales
                $feriadosP = $this->em->getRepository('ExpedienteBundle:Evento')->findByAnio($anio);
            }
        }

       return  ["fecha" => date('Y-m-d' , $fechaEnSegundos -= $dia),"msj"=>$msj ];
    }

    // Verifica si una fecha en segundos es Feriado
    public function getesFeriadoNacional($feriadosN, $fechaEnSegundos) {
            $esFeridado = false;
            $feriado = null;
            if (($feriadosN)&&($fechaEnSegundos)) {
                foreach ($feriadosN as $dato) {
                    $fecha = $dato['fecha'];
                    $fechaEnSegundos2 = strtotime($fecha);
                    if ($fechaEnSegundos == $fechaEnSegundos2) {
                        $esFeridado = true;
                        $feriado = $dato;
                    }   
                }        
            } 

            return ['esFeriado'=>$esFeridado, 'dato'=>$feriado];
    }

    public function getFeriados($anio = null)
        {
                // 2. Compruebo si la  fecha esta en un feriado nacional
                // 2.1. Inicializar cURL para hacer la solicitud
                $url = "https://api.argentinadatos.com/v1/feriados/".$anio;
                $ch = curl_init($url);

                // 2.2. Configurar cURL para devolver la respuesta como una cadena
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // **IMPORTANTE:** Configuración extra para manejo de errores (opcional pero recomendado)
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);          // Establecer tiempo límite

                // 2.3. Ejecutar la solicitud y guardar la respuesta JSON
                $response = curl_exec($ch);

                // 2.4. Diagnóstico de Errores de cURL
                if (curl_errno($ch)) {
                    $fecha = null;
                }
                // 2.5. Cerrar la sesión cURL
                curl_close($ch);

                // 2.6. Decodificar la respuesta JSON
                $datos = json_decode($response, true);

                // 2.7. Verificar la decodificación y el contenido
                if ($datos === null) {
                    $fecha = null;
                } elseif (is_array($datos) && count($datos) > 0) {
                    return $datos;
                } else {
                    return false;
                }
             }
    /**
     * @return string
     */
    public function getName()
    {
        return 'DocumentoBundle\age';
    }
}