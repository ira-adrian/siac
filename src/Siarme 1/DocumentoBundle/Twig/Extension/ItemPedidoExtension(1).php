<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class ItemPedidoExtension extends \Twig_Extension
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
            new \Twig_SimpleFilter('rubro', [$this, 'rubroFilter']),
            new \Twig_SimpleFilter('item_duplicado', [$this, 'itemDuplicadoFilter']),
            new \Twig_SimpleFilter('item_duplicado_pedido', [$this, 'itemDuplicadoPedidoFilter']),
            new \Twig_SimpleFilter('item_duplicado_solicitado', [$this, 'itemDuplicadoSolicitadoFilter']),
            new \Twig_SimpleFilter('item_acuerdo', [$this, 'itemAcuerdoFilter']),
            new \Twig_SimpleFilter('item_catalogo', [$this, 'itemCatalogoFilter']),
            new \Twig_SimpleFilter('item_consolida', [$this, 'itemConsolidaFilter']),
            new \Twig_SimpleFilter('item_proceso', [$this, 'itemProcesoFilter']),
            new \Twig_SimpleFilter('item_igual_puntaje', [$this, 'itemIgualPuntajeFilter']),
            new \Twig_SimpleFilter('item_pedido', [$this, 'itemPedidoFilter']),
            new \Twig_SimpleFilter('tramite_pedido_item_por_rubro', [$this, 'tramitePedidoItemPorRubro']),
            new \Twig_SimpleFilter('item_pedido_por_organismo', [$this, 'ItemPedidoPorOrganismo']),
            new \Twig_SimpleFilter('item_pedido_por_codigo', [$this, 'ItemPedidoPorCodigo']),

        ];
    }

    /**
     * 
     * @return string
     */
    public function rubroFilter($ipp)
    {
       $repository = $this->em->getRepository('AusentismoBundle:Rubro');
        $query = $repository->createQueryBuilder('r'); 
               $query->Where('r.ipp = :ipp')
                    ->setParameter('ipp', $ipp); 
                $rubro =  $query->setMaxResults(1)->getQuery()->getOneOrNullResult();
        return $rubro;
    }
    
    /**
     * 
     * @return string
     */
    public function itemDuplicadoFilter($item)
    {

       $repository = $this->em->getRepository('AusentismoBundle:ItemPedido');
        $codigo = $item->getCodigo();
        $saf = $item->getOrganismo()->getSaf();

        $query = $repository->createQueryBuilder('i'); 
               $query->innerJoin('i.tramite' , 't')
                    ->addSelect('t')
                    ->innerJoin('t.organismoOrigen' , 'o')
                    ->addSelect('o')
                    ->Where('i.codigo = :codigo')
                    ->andWhere('o.saf = :saf')
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('saf', $saf); 
                $dql = $query->getQuery();

                $items = $dql->getResult();
                
        return $items;
    }
    
    /**
     * 
     * @return string
     */
    public function itemAcuerdoFilter($item)
    {
       $repository = $this->em->getRepository('GeneralBundle:ItemAcuerdoMarco');
        $codigo = $item->getCodigo();
        $query = $repository->createQueryBuilder('i'); 
               $query->Join('i.expediente' , 'e')
                    ->Where('i.codigo = :codigo')
                    ->setParameter('codigo',  $codigo); 
                $dql = $query->getQuery();

                $items = $dql->getResult();
        return $items;
    }
    
    /**
     * 
     * @return string
     */
    public function itemDuplicadoPedidoFilter($item)
    {

       $repository = $this->em->getRepository('AusentismoBundle:ItemPedido');
        $codigo = $item->getCodigo();

        $saf = $item->getOrganismo()->getSaf();
        $id = $item->getTramite()->getId();

        $query = $repository->createQueryBuilder('i'); 
               $query->innerJoin('i.tramite' , 't')
                    ->addSelect('t')
                    ->innerJoin('t.organismoOrigen' , 'o')
                    ->addSelect('o')
                    ->innerJoin('t.estadoTramite' , 'et')
                    ->addSelect('et')
                    ->Where('i.codigo = :codigo')
                    ->andWhere('o.saf = :saf')
                    ->andWhere('t.id = :id')
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('saf', $saf )
                    ->setParameter('id', $id)
                    ->orderBy('i.fecha', 'DESC'); 
                $dql = $query->getQuery();

                $items = $dql->getResult();

                
        return $items;
    }
    
    /**
     * 
     * @return string
     */
    public function itemDuplicadoSolicitadoFilter($item)
    {

       $repository = $this->em->getRepository('GeneralBundle:ItemSolicitado');
        $codigo = $item->getCodigo();
        $saf = $item->getTramite()->getOrganismoOrigen()->getSaf();
        $id = $item->getTramite()->getId();

        $query = $repository->createQueryBuilder('i'); 
               $query->innerJoin('i.tramite' , 't')
                    ->addSelect('t')
                    ->innerJoin('t.organismoOrigen' , 'o')
                    ->addSelect('o')
                    ->innerJoin('t.estadoTramite' , 'et')
                    ->addSelect('et')
                    ->Where('i.codigo = :codigo')
                    ->andWhere('o.saf = :saf')
                    ->andWhere('t.id = :id')
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('saf', $saf )
                    ->setParameter('id', $id)
                    ->orderBy('i.fecha', 'DESC'); 
                $dql = $query->getQuery();

                $items = $dql->getResult();

                
        return $items;
    }
    
    /**
     * 
     * @return string
     */
    public function itemCatalogoFilter($item)
    {
       $repository = $this->em->getRepository('AusentismoBundle:ItemCatalogo');
        $codigo = $item->getCodigo();
        $query = $repository->createQueryBuilder('i'); 
               $query->Where('i.codigo = :codigo')
                    ->setParameter('codigo',  $codigo); 
                $item =  $query->setMaxResults(1)->getQuery()->getOneOrNullResult();
                
        return $item;
    }
    
   /**
     * 
     * @return string
     */
    public function itemConsolidaFilter($item)
    {

       $repository = $this->em->getRepository('AusentismoBundle:ItemPedido');
        $codigo = $item->getCodigo();
        $expediente = $item->getTramite()->getExpediente();

        $query = $repository->createQueryBuilder('i'); 
               $query->join('i.tramite' , 't')
                    ->addSelect('t')
                    ->Where('i.codigo = :codigo')
                    ->andWhere('t.expediente = :exp')
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('exp', $expediente ); 
                $dql = $query->getQuery();

                $items = $dql->getResult();

                
        return $items;
    }

   /**
     * 
     * @return string
     */
    public function itemProcesoFilter($item)
    {
        if (empty($item->getItemProceso())) {
            // borrar este codigo en el 2023
            $repository = $this->em->getRepository('AusentismoBundle:ItemProceso');
            // itemPedido obtengo el codigo y el expediente
            $codigo = $item->getCodigo();
            $cantidad = $item->getCantidad();
            $expediente = $item->getTramite()->getExpediente();
            // busco el itemProceso
            $query = $repository->createQueryBuilder('i'); 
                   $query->innerJoin('i.proceso' , 'p')
                        ->addSelect('p')
                        ->Where('i.codigo = :codigo')
                        ->andWhere('p.expediente = :exp')
                        ->setParameter('codigo',  $codigo)
                        ->setParameter('exp', $expediente ); 
                    $dql = $query->getQuery();

                    $items = $dql->getResult();

            if (count($items) > 1) {
                   $query = $repository->createQueryBuilder('i'); 
                   $query->innerJoin('i.proceso' , 'p')
                        ->addSelect('p')
                        ->Where('i.codigo = :codigo')
                        ->andWhere('i.cantidad = :cant')
                        ->andWhere('p.expediente = :exp')
                        ->setParameter('codigo',  $codigo)
                        ->setParameter('cant',  $cantidad)
                        ->setParameter('exp', $expediente ); 
                    $dql = $query->getQuery();

                    $items = $dql->getResult();
            }
            if (count($items) > 1) {
                $items = null;
            }
        } else {
            // se debe dejar este codigo en el 2023
            $items = [$item->getItemProceso()];
        }

        return $items;
    }

    /**
     * 
     * @return string
     */
    public function itemIgualPuntajeFilter($item)
    {
        $result = false;
        if (($item->getCalidad() == 2 or $item->getCalidad() == 3) and ($item->getEstado() == true)) {
           $repository = $this->em->getRepository('AusentismoBundle:ItemOferta');
            // itemPedido obtengo el codigo y el expediente
            $numero = $item->getNumero();
            $pFTotal = $item->getPFTotal();
            $expediente = $item->getProceso()->getExpediente();
            // busco el itemProceso
            $query = $repository->createQueryBuilder('i'); 
                   $query->innerJoin('i.proceso' , 'p')
                        ->addSelect('p')
                        ->Where('i.numero = :numero')
                        ->andWhere('i.pFTotal = :pFTotal')
                        ->andWhere('p.expediente = :exp')
                        ->setParameter('numero',  $numero)
                        ->setParameter('pFTotal',  $pFTotal)
                        ->setParameter('exp', $expediente ); 
                    $dql = $query->getQuery();
                    $items = $dql->getResult();
            if (count($items) > 1) {
               $result = true;
            }
        }

        return $result;
    }
    
   /**
     * 
     * @return objet
     */
    public function itemPedidoFilter($item, $todos = false)
    {
        if ($item::TIPO_ENTIDAD == 'IOFE') {
                 $cantidad = $item->getItemProceso()->getCantidad();  
                 $items= $item->getItemProceso()->getItemPedido();
        } else{
                 $cantidad = $item->getCantidad();  
                 $items= $item->getItemPedido();
        }
        if ($items->isEmpty()) {
            $repository = $this->em->getRepository('AusentismoBundle:ItemPedido');
            // itemProceso obtengo el codigo y el expediente
            $codigo = $item->getCodigo();

            $expediente = $item->getProceso()->getExpediente();
            // busco el itemProceso
            $query = $repository->createQueryBuilder('i'); 
                   $query->innerJoin('i.tramite' , 't')
                        ->addSelect('t')
                        ->Where('i.codigo = :codigo')
                        ->andWhere('t.expediente = :exp')
                        ->setParameter('codigo',  $codigo)
                        ->setParameter('exp', $expediente ); 
                    $dql = $query->getQuery();

                    $items1 = $dql->getResult();

            if (count($items1) > 1) {
                 $query = $repository->createQueryBuilder('i'); 
                   $query->innerJoin('i.tramite' , 't')
                        ->addSelect('t')
                        ->Where('i.codigo = :codigo')
                        ->andWhere('i.cantidad = :cantidad')
                        ->andWhere('t.expediente = :exp')
                        ->setParameter('codigo',  $codigo)
                        ->setParameter('cantidad',  $cantidad)
                        ->setParameter('exp', $expediente ); 
                    $dql = $query->getQuery();

                    $items = $dql->getResult();
            }

            if ((count($items) > 1) and !$todos){
                $items = null;
            } elseif ((count($items) == 0) and $todos) {
                $items = $items1;
            }

        } else {
            // se debe dejar este codigo en el 2023
            $items = $item->getItemPedido();
        }
                
        return $items;
    }

   /**
     * 
     * @return string
     */
    public function tramitePedidoItemPorRubro($pedido, $tipo = "rubro")
    {

        if ($tipo == "ipp") {
            $dql = 'SELECT i.ipp AS ipp, i.rubro AS rubro, COUNT(i.id) AS cantidad, SUM(i.cantidad * i.precio) AS presupuesto
                    FROM AusentismoBundle:ItemPedido i 
                    WHERE i.tramite = :pedido 
                    GROUP BY i.ipp
                    ORDER BY i.ipp ASC';
            $query = $this->em->createQuery($dql);
            $query->setParameter('pedido', $pedido);

            return $query->getResult();
        } 

        if ($tipo == "rubro") {
            $dql = 'SELECT i.ipp AS ipp, i.rubro AS rubro, COUNT(i.id) AS cantidad, SUM(i.cantidad * i.precio) AS presupuesto
                    FROM AusentismoBundle:ItemPedido i 
                    WHERE i.tramite = :pedido 
                    GROUP BY i.rubro';
            $query = $this->em->createQuery($dql);
            $query->setParameter('pedido', $pedido);

            return $query->getResult();
        }
    }

    /**
     * @param DateTime $dateTime
     * @return int
     */
    public function ItemPedidoPorOrganismo($organismo, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);

        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT i
                FROM AusentismoBundle:ItemPedido i 
                INNER JOIN i.tramite t
                INNER JOIN t.organismoOrigen o
                INNER JOIN t.estadoTramite e
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta 
                AND t.organismoOrigen = :saf';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('saf', $organismo)              
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('fechaHasta', $fechaHasta);

        return $query->getResult();
               
    }

    /**
     * @param DateTime $dateTime
     * @return int
     */
    public function ItemPedidoPorCodigo($item)
    {
        $codigo = $item->getCodigo();

        $dql = 'SELECT i
                FROM AusentismoBundle:ItemPedido i 
                WHERE i.codigo = :codigo';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('codigo', $codigo);

        return $query->getResult();
               
    }
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            /** items_por_trimestre parametros (reparticion, año)
            RETORNA:
                    array:3 [▼
                      0 => array:4 [▼
                        "tramite" => Tramite {#1744 ▶}
                        "trimestre" => "1"
                        "cantidad" => "3"
                        "presupuesto" => "73"
                      ]
                      1 => array:4 [▶]
                    ] 
            */
            new \Twig_SimpleFunction('items_por_trimestre', array($this, 'itemsPorTrimeste')),

            /** items_por_rubro parametros (reparticion, año)
             * array:11 [▼
                          0 => array:4 [▼
                            "tramite" => Tramite {#3142 ▶}
                            "rubro_id" => "1"
                            "cantidad" => "6"
                            "presupuesto" => "129"
                          ]
                          1 => array:4 [▶]
                        ]
             * 
             * */
            new \Twig_SimpleFunction('items_por_rubro', array($this, 'itemsPorRubro')),

            /**items_por_saf parametros (reparticion, año)
             * array:13 [▼
                          0 => array:4 [▼
                            "tramite" => Tramite {#3317 ▶}
                            "organismo_id" => "3"
                            "cantidad" => "2"
                            "presupuesto" => "58"
                          ]
                          1 => array:4 [▶]
                        ]
             * */
            new \Twig_SimpleFunction('items_por_saf', array($this, 'itemsPorSaf')),

            /**
             * PARA ESTADISTICA POR SAF U ORGANISMO
             * */
            new \Twig_SimpleFunction('items_por_trimestre_saf', array($this, 'itemsPorTrimesteSaf')),
            new \Twig_SimpleFunction('items_por_rubro_saf', array($this, 'itemsPorRubroSaf')),
            new \Twig_SimpleFunction('items_ac_por_expediente_organismo', array($this, 'itemsAcPorExpedienteOrganismo')),
        );
    }


    /**
     *
     *
     * @param int       $reparticion_id
     * @param int       $anio
     *
     * @return string
     */
    public function itemsPorTrimeste($reparticion_id, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT  (t.trimestre) AS trimestre, COUNT(ip.id) AS cantidad, SUM( DISTINCT t.presupuestoOficial) AS presupuesto
                FROM AusentismoBundle:ItemPedido ip
                INNER JOIN ip.tramite t 
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
     *
     *
     * @param int       $reparticion_id
     * @param int       $anio
     *
     * @return string
     */
    public function itemsPorRubro($reparticion_id, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT  ip AS item, COUNT(ip.id) AS cantidad, SUM( DISTINCT t.presupuestoOficial) AS presupuesto
                FROM AusentismoBundle:ItemPedido ip
                INNER JOIN ip.tramite t 
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
     *
     *
     * @param int       $reparticion_id
     * @param int       $anio
     *
     * @return string
     */
    public function itemsPorSaf($reparticion_id, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT  ip AS item, COUNT(ip.id) AS cantidad, SUM( DISTINCT t.presupuestoOficial) AS presupuesto
                FROM AusentismoBundle:ItemPedido ip
                INNER JOIN ip.tramite t 
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.departamentoRm = :id
                GROUP BY t.organismoOrigen
                ORDER BY t.organismoOrigen';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('fechaHasta', $fechaHasta);
         
        return $query->getResult();
    }

    /**
     *
     *
     * @param int       $reparticion_id
     * @param int       $anio
     * @param int       $saf_id
     *
     * @return string
     */
    public function itemsPorTrimesteSaf($reparticion_id, $anio, $saf_id)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT  (t.trimestre) AS trimestre, COUNT(ip.id) AS cantidad, SUM( DISTINCT t.presupuestoOficial) AS presupuesto
                FROM AusentismoBundle:ItemPedido ip
                INNER JOIN ip.tramite t 
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.departamentoRm = :id
                AND t.organismoOrigen = :saf_id
                GROUP BY t.trimestre
                ORDER BY t.trimestre';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('saf_id', $saf_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('fechaHasta', $fechaHasta);

        return $query->getResult();
    }

    /**
     *
     *
     * @param int       $reparticion_id
     * @param int       $anio
     * @param int       $saf_id
     *
     * @return string
     */
    public function itemsPorRubroSaf($reparticion_id, $anio, $saf_id)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT  ip AS item, COUNT(ip.id) AS cantidad, SUM( DISTINCT t.presupuestoOficial) AS presupuesto
                FROM AusentismoBundle:ItemPedido ip
                INNER JOIN ip.tramite t 
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.departamentoRm = :id
                AND t.organismoOrigen = :saf_id
                GROUP BY t.rubro
                ORDER BY t.rubro';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('saf_id', $saf_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('fechaHasta', $fechaHasta);

        return $query->getResult();
    }

    /**
     *
     *
     * @param int       $reparticion_id
     * @param int       $anio
     * @param int       $saf_id
     *
     * @return string
     */
    public function itemsAcPorExpedienteOrganismo($expediente, $organismo)
    {

        $dql = 'SELECT i
                FROM GeneralBundle:ItemSolicitado i
                JOIN i.tramite t 
                WHERE t.estado = :estado 
                AND t.organismoOrigen = :organismo
                AND t.expediente = :expediente';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('estado', true)
              ->setParameter('organismo', $organismo)
              ->setParameter('expediente', $expediente);

        return $query->getResult();
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'item_pedido_extension';
    }
}