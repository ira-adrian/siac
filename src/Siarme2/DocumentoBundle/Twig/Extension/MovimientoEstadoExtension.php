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
            new \Twig_SimpleFilter('movimiento_pendiente', [$this, 'pendienteFilter']),
        ];
    }

    /**
     * 
     * @return string
     */
    public function estadoFilter($expediente, $usuario)
    {

        if ( !empty($expediente) ) {
           $estado = true;
           $repository = $this->em->getRepository('ExpedienteBundle:Movimiento');
            $query = $repository->createQueryBuilder('mv'); 
                   $query->Where('mv.departamentoRm = :dpto')
                        ->andWhere('mv.expediente = :expt')
                         ->orderBy('mv.id', 'DESC')
                        ->setParameter('expt', $expediente) 
                        ->setParameter('dpto', $usuario->getDepartamentoRm()); 
                    $movimiento =  $query->setMaxResults(1)->getQuery()->getOneOrNullResult();
            if ( !empty($movimiento) ) {
                $estado = $movimiento->getActivo();
                // PARA DEPARTAMENTO PAGOS
          //      if (($estado) and ($usuario->getDepartamentoRm()->getSlug() == "sca")) {
                if ($usuario->getDepartamentoRm()->getSlug() == "sca") {                    
                    $dql = 'SELECT SUM(t.montoAdjudica) AS montoPagado
                    FROM ExpedienteBundle:Tramite t 
                    INNER JOIN t.tipoTramite tt
                    WHERE t.expediente = :ex 
                    AND tt.slug = :slug
                    GROUP BY t.tipoTramite';
                    $query = $this->em->createQuery($dql);
                    $query->setParameter('ex', $expediente)
                          ->setParameter('slug', "tramite_pago");
                    $result = $query->getResult();
                    if (!empty($result[0])) {
                       $montoPagado = $result[0]['montoPagado'];
                    } else {
                        $montoPagado = 0;
                    }
                    if ($expediente->getTipoExpediente()->getSlug()=="exp_acuerdo") {
                        $dql = 'SELECT SUM(t.montoAdjudica) AS montoAdjudicado
                        FROM ExpedienteBundle:Tramite t 
                        INNER JOIN t.tipoTramite tt
                        WHERE t.expediente = :ex 
                        AND t.estado = :estado
                        AND tt.slug = :slug
                        GROUP BY t.tipoTramite';
                        $query = $this->em->createQuery($dql);
                        $query->setParameter('ex', $expediente)
                              ->setParameter('estado', true)
                              ->setParameter('slug', "tramite_solicitud");
                        $result = $query->getResult();
                        if (!empty($result[0])) {
                           $montoAdjudicado = $result[0]['montoAdjudicado'];
                        } else {
                            $montoAdjudicado = 0; 
                        }
                    } else {

                        $dql = 'SELECT SUM(t.montoAdjudica) AS montoAdjudicado
                        FROM ExpedienteBundle:Tramite t 
                        INNER JOIN t.tipoTramite tt
                        WHERE t.expediente = :ex 
                        AND t.estado = :estado
                        AND tt.slug = :slug
                        GROUP BY t.tipoTramite';
                        $query = $this->em->createQuery($dql);
                        $query->setParameter('ex', $expediente)
                              ->setParameter('estado', true)
                              ->setParameter('slug', "tramite_proceso");
                        $result = $query->getResult();
                        if (!empty($result[0])) {
                           $montoAdjudicado = $result[0]['montoAdjudicado'];
                        } else {
                            $montoAdjudicado = 0; 
                        }
                    }
                    if (($montoPagado > 0) and ($montoAdjudicado > 0 )) {
                        if (intval($montoPagado) >= intval($montoAdjudicado)) {
                           $movimiento->setActivo(false);
                           $this->em->flush();
                           $estado = false;
                        } else {
                            $movimiento->setActivo(true);
                            $this->em->flush();
                            $estado = true;
                        }
                    }
                   
                }
            /**    if (($estado) and ($usuario->getDepartamentoRm()->getSlug() == "dppr") and ($expediente->getTipoExpediente()->getSlug() !="exp_acuerdo")) {
                    $dql = 'SELECT t
                    FROM ExpedienteBundle:Tramite t 
                    INNER JOIN t.tipoTramite tt
                    WHERE t.expediente = :ex 
                    AND tt.slug = :slug
                    GROUP BY t.tipoTramite';
                    $query = $this->em->createQuery($dql);
                    $query->setParameter('ex', $expediente)
                          ->setParameter('slug', "tramite_despacho");
                    $result = $query->getResult();
                   
                    if (count($result) > 0 ) {
                        $movimiento->setActivo(false);
                        $this->em->flush();
                        $estado = false;
                    }
                }
                if (($estado) and ($usuario->getDepartamentoRm()->getSlug() == "dpcbs") and ($expediente->getTipoExpediente()->getSlug() !="exp_acuerdo")) {
                    $dql = 'SELECT t
                    FROM ExpedienteBundle:Tramite t 
                    INNER JOIN t.tipoTramite tt
                    WHERE t.expediente = :ex 
                    AND tt.slug = :slug
                    GROUP BY t.tipoTramite';
                    $query = $this->em->createQuery($dql);
                    $query->setParameter('ex', $expediente)
                          ->setParameter('slug', "tramite_pago");
                    $result = $query->getResult();
                   
                    if (count($result) > 0 ) {
                        $movimiento->setActivo(false);
                        $this->em->flush();
                        $estado = false;
                    }
                }    */
            }
            return $estado;
        }
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