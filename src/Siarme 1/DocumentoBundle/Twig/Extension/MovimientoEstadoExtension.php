<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class MovimientoEstadoExtension extends \Twig_Extension
{

    protected $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('movimiento_estado', [$this, 'estadoFilter']),
            new \Twig_SimpleFilter('estado_expediente', [$this, 'estadoExpedienteFilter']),
            new \Twig_SimpleFilter('movimiento_pendiente', [$this, 'pendienteFilter']),
        ];
    }

    /**
     * 
     * @return string
     */
    public function estadoFilter($expediente, $usuario)
    {
/**
$i = 0;
$len = count($array);
foreach ($array as $item) {
    if ($i == 0) {
        // first
    } else if ($i == $len - 1) {
        // last
    }
    // …
    $i++;
}*/
    $estado = true;
    if ( !empty($expediente) ) {
        $movimientos=$expediente->getMovimiento();
        $estado = true;
        foreach ($movimientos as $movimiento) {
        if ($movimiento->getDepartamentoRm() == $usuario->getDepartamentoRm())
            return $estado = $movimiento->getActivo();
        }
     }
        return $estado;
}


/**
 * 
 * @return string
 */
public function estadoExpedienteFilter($expediente, $usuario)
{

    $estado = true;
    if ( !empty($expediente) ) {
        $movimientos=$expediente->getMovimiento();
        $estado = true;
        foreach ($movimientos as $movimiento) {
        if ($movimiento->getDepartamentoRm() == $usuario->getDepartamentoRm())
            return $estado = $movimiento->getActivo();
        }
     }
        return $estado;
}

    /**
     * 
     * @return bool
     */
    public function pendienteFilter($expediente, $usuario)
    {
        $tramites=$expediente->getTramite();
        $estado= false;
        foreach ($tramites as $tramite) {

                if ( $tramite->getDepartamentoRm() == $usuario->getDepartamentoRm()) {
                    return $estado= false;
                } else{
                      $estado= true;
                }

        }
        return $estado;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'movimientoEstado_extension';
    }
}