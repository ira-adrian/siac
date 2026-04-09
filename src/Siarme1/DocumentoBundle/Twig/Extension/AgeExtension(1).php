<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

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
        $fechaInicial = date($dateTime);
        $fechaEnSegundos = strtotime($fechaInicial);
        $diasAumentar = $dias;
        $dia = 86400;

        $contador = 1;

        while ($contador <= $diasAumentar) {
            if (date('N',$fechaEnSegundos) == 6 or date('N',$fechaEnSegundos) == 7) {
                $fechaEnSegundos += $dia;
                if (!$habiles) {

                }
            }else {
                $fechaEnSegundos += $dia;
                $contador +=1;  
            }   
        }


        return  date('Y-m-d' , $fechaEnSegundos);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'DocumentoBundle\age';
    }
}