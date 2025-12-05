<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class ExpedienteExtension  extends \Twig_Extension
{

    protected $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**------------------------------NO ESTA HABILITADO  ---------------------------------*/
    /**------------------------------FILTROS ---------------------------------*/

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('tramit-_tipo', [$this, 'tramitePorTipo']),
        ];
    }

    /**
     * @param DateTime $dateTime
     * @return int
     */
    public function tramitePorTipo($expediente, $tipo)
    {
    
        $dql = 'SELECT t
                FROM ExpedienteBundle:Tramite t 
                WHERE t.expediente = :ex 
                AND t.tipoTramite = :tipo';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('ex', $expediente)
              ->setParameter('tipo', $tipo);

        return $query->getResult();
               
    }


    /**------------------------------FUNCIONES ---------------------------------*/
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(

            new \Twig_SimpleFunction('tramites_por_trimestre', array($this, 'reportePorTrimeste')),


            new \Twig_SimpleFunction('tramites_por_rubro', array($this, 'reportePorRubro')),

        );
    }

    /**
     * "reparticion_id" se refiere a la reparticionRm que seria el sector del Usuario
     * al que pertenece y a su vez el tramite.
     * @param int       $reparticion_id
     * @param int       $anio
     *
     * @return string
     */
    public function reportePorTrimeste($reparticion_id, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT t AS tramite, t.trimestre AS trimestre, COUNT(t.id) AS cantidad, SUM(t.presupuestoOficial) AS presupuesto
                FROM ExpedienteBundle:Tramite t 
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.departamentoRm = :id
                GROUP BY t.trimestre
                ORDER BY t.trimestre';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('fechaHasta', $fechaHasta);

        return $query->getResult();
    }

    /**
     * "reparticion_id" se refiere a la reparticionRm que seria el sector del Usuario
     * al que pertenece y a su vez el tramite.
     *
     * @param int       $reparticion_id
     * @param int       $anio
     *
     * @return string
     */
    public function reportePorRubro($reparticion_id, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT t AS tramite, (t.rubro) AS rubro_id, COUNT(t.id) AS cantidad, SUM(t.presupuestoOficial) AS presupuesto
                FROM ExpedienteBundle:Tramite t
                JOIN t.rubro r 
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.departamentoRm = :id
                GROUP BY t.rubro
                ORDER BY t.rubro';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('fechaHasta', $fechaHasta);
 
        return $query->getResult();
    }

 
    /**
     * @return string
     */
    public function getName()
    {
        return 'tramite_extension';
    }
}