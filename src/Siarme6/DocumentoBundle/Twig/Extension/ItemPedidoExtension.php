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
            new \Twig_SimpleFilter('items_consolidados', [$this, 'itemsConsolidadosFilter']),
            new \Twig_SimpleFilter('item_proceso', [$this, 'itemProcesoFilter']),
            new \Twig_SimpleFilter('item_igual_puntaje', [$this, 'itemIgualPuntajeFilter']),
            new \Twig_SimpleFilter('item_pedido', [$this, 'itemPedidoFilter']),
            new \Twig_SimpleFilter('tramite_pedido_item_por_rubro', [$this, 'tramitePedidoItemPorRubro']),
            new \Twig_SimpleFilter('tramite_pedido_item_por_oferente', [$this, 'tramitePedidoItemPorOferente']),
            new \Twig_SimpleFilter('item_pedido_por_organismo', [$this, 'ItemPedidoPorOrganismo']),
            new \Twig_SimpleFilter('item_pedido_por_rubro_por_organismo', [$this, 'ItemPedidoPorRubroPorOrganismo']),
            new \Twig_SimpleFilter('item_pedido_por_codigo', [$this, 'ItemPedidoPorCodigo']),
            new \Twig_SimpleFilter('item_oferta', [$this, 'itemOfertaFilter']),
            new \Twig_SimpleFilter('item_oferta_adjudicado', [$this, 'itemOfertaAdjudicadoFilter']),
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
        $sistema = $item->getSistema();
        $saf = $item->getOrganismo()->getSaf();
        $itemDuplicado = $item->getItem();
        //Tomo la fecha del tramite actual y le resto 60 días 
        $fecha = $item->getTramite()->getFechaDestino();
        $fecha->modify('-150 day');
        $query = $repository->createQueryBuilder('i'); 
               $query->innerJoin('i.tramite' , 't')
                    ->addSelect('t')
                    ->innerJoin('t.organismoOrigen' , 'o')
                    ->addSelect('o')
                    ->innerJoin('t.estadoTramite' , 'et')
                    ->addSelect('et')
                    ->Where('i.codigo = :codigo')
                    ->andWhere('o.saf = :saf')
                    ->andWhere('t.fechaDestino >= :fecha')
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('saf', $saf )
                    ->setParameter('fecha', $fecha->format('Y-m-d'))
                    ->orderBy('i.fecha', 'DESC'); 
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
    public function itemConsolidaFilter($item, $consol = false)
    {

       $repository = $this->em->getRepository('AusentismoBundle:ItemPedido');
        $codigo = $item->getCodigo();
        $detalle = $item->getDetalle();
        $expediente = $item->getTramite()->getExpediente();
        if ($consol) {
            $query = $repository->createQueryBuilder('i'); 
            $query->join('i.tramite' , 't')
                    ->addSelect('t')
                    ->Where('i.codigo = :codigo')
                    ->andWhere('t.estado = :estado')
                    ->setParameter('estado',  true);
                    if (empty($item->getDetalle())) {
                        if ($item->getDetalle()=="") {
                            $item->setDetalle(null);
                            $this->em->flush();
                        }
                       $query->andWhere('i.detalle is NULL')
                          ->andWhere('t.expediente = :exp')
                          ->setParameter('codigo',  $codigo)
                          ->setParameter('exp', $expediente );
                    } else {
                            $query->andWhere('i.detalle = :dt')
                            ->andWhere('t.expediente = :exp')
                            ->setParameter('codigo',  $codigo)
                            ->setParameter('exp', $expediente )
                            ->setParameter('dt', $detalle );
                    }
                    
        } else {
            $query = $repository->createQueryBuilder('i'); 
            $query->join('i.tramite' , 't')
                    ->addSelect('t')
                    ->Where('i.codigo = :codigo')
                    ->andWhere('t.estado = :estado')
                    ->setParameter('estado',  true)
                    ->andWhere('t.expediente = :exp')
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('exp', $expediente );
        }
 
                $dql = $query->getQuery();
                $items = $dql->getResult();

                
        return $items;
    }

   /**
     * 
     * @return string
     */
    public function itemsConsolidadosFilter($expediente)
    {
        $dql = 'SELECT i AS item, COUNT(i.id) AS cantidad
                    FROM AusentismoBundle:ItemPedido i 
                    INNER JOIN i.tramite t
                    INNER JOIN t.expediente e
                    WHERE t.expediente = :exp 
                    AND t.estado = :estado
                    GROUP BY i.codigo
                    ORDER BY i.numero ASC';
        $query = $this->em->createQuery($dql);
        $query->setParameter('exp', $expediente)
              ->setParameter('estado', true);
        return $query->getResult();
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
            $proceso = $item->getProceso();
            // busco el itemProceso
            $query = $repository->createQueryBuilder('i'); 
                   $query->innerJoin('i.proceso' , 'p')
                        ->addSelect('p')
                        ->Where('i.numero = :numero')
                        ->andWhere('i.pFTotal = :pFTotal')
                        ->andWhere('p.id = :idp')
                        ->setParameter('numero',  $numero)
                        ->setParameter('pFTotal',  $pFTotal)
                        ->setParameter('idp', $proceso->getId() ); 
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
        if ($todos) {
            // itemProceso obtengo el codigo, cantidad y el expediente
            $codigo = $item->getCodigo();
            $cantidad = $item->getCantidad(); 
            $expediente = $item->getProceso()->getExpediente();

            // busco el itemPedido
            $repository = $this->em->getRepository('AusentismoBundle:ItemPedido');
            $query = $repository->createQueryBuilder('i'); 
                   $query->innerJoin('i.tramite' , 't')
                        ->addSelect('t')
                        ->Where('i.codigo = :codigo')
                        ->andWhere('t.expediente = :exp')
                        ->setParameter('codigo',  $codigo)
                        ->setParameter('exp', $expediente ); 
                    $dql = $query->getQuery();

                    $items1 = $dql->getResult();
                    $items2 = $items1;
            if (count($items2) > 1) {
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

                    $items2 = $dql->getResult();
            }

            if ((count($items2) == 0) or (count($items2) > 1)){
                $items3 = $items1;
            } else {
                $items3 = $items2;
            } 

            $items = array();
                foreach ($items3 as $itemPedido) {
                    if (empty($itemPedido->getItemProceso())) {
                        $items[] = $itemPedido;
                    } elseif (!$itemPedido->getItemProceso()->getAdjudicado() and $itemPedido->getItemProceso() != $item and $itemPedido->getItemProceso()->getProceso() != $item->getProceso()){
                        $items[] = $itemPedido;
                    }
                }

        } else {
            $items = $item->getItemPedido();
        }
                
        return $items;
    }

    /**
     * 
     * @return string
     */
    public function itemOfertaFilter($item, $limite = 1)
    {
        $repository = $this->em->getRepository('AusentismoBundle:ItemOferta');
        $codigo = $item->getCodigo();
        $query = $repository->createQueryBuilder('i'); 
               $query->Where('i.codigo = :codigo')
                    ->orderBy('i.fecha', 'DESC') 
                    ->setParameter('codigo',  $codigo);
                if ($limite == 1) {
                     $dql = $query->setMaxResults($limite)->getQuery();
                     $items = $dql->getOneOrNullResult();
                } else{
                      $dql = $query->setMaxResults($limite)->getQuery();

                    $items = $dql->getResult();
                }
                
        return $items;
    }
    
    /**
     * 
     * @return string
     */
    public function itemOfertaAdjudicadoFilter($item, $limite = 1)
    {
        $repository = $this->em->getRepository('AusentismoBundle:ItemOferta');
        $codigo = $item->getCodigo();
        $query = $repository->createQueryBuilder('i'); 
               $query->leftJoin('i.itemProceso', 'ip')
                    ->addSelect('ip')
                    ->Where('i.codigo = :codigo')
                    ->andWhere('i.adjudicado = :estado')
                    ->orderBy('i.fecha', 'DESC') 
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('estado',  true);
                if ($limite == 1) {
                     $dql = $query->setMaxResults($limite)->getQuery();
                     $items = $dql->getOneOrNullResult();
                } else{
                      $dql = $query->setMaxResults($limite)->getQuery();

                    $items = $dql->getResult();
                }
                
        return $items;
    }

   /**
     * 
     * @return string
     */
    public function tramitePedidoItemPorRubro($pedido, $tipo = "rubro", $adjudicado = false )
    {

        if ($tipo == "ipp" and $adjudicado) {
            // busqueda por itemprocesoadjudicado
                $dql = 'SELECT i.ipp AS ipp, i.rubro AS rubro, COUNT(i.id) AS cantidad, SUM(i.cantidad * i.precio) AS presupuesto, SUM(ip.cantidad * ip.precio) AS adjudicado
                    FROM AusentismoBundle:ItemPedido i 
                    INNER JOIN i.itemProcesoAdjudicado ip
                    WHERE i.tramite = :pedido
                    AND ip.cantidad > :cantidad 
                    GROUP BY i.ipp
                    ORDER BY i.ipp ASC';
            $query = $this->em->createQuery($dql);
            $query->setParameter('pedido', $pedido)
                    ->setParameter('cantidad', 0);
            $items = $query->getResult();
            /// verifico item si es vacio ejecuto consulta normal
            if (empty($items)) {
                // busqueda normal
                $dql = 'SELECT i.ipp AS ipp, i.rubro AS rubro, COUNT(i.id) AS cantidad, SUM(i.cantidad * i.precio) AS presupuesto, SUM(io.cantidadAdjudicada * io.precio) AS adjudicado
                        FROM AusentismoBundle:ItemPedido i 
                        INNER JOIN i.itemProceso ip
                        JOIN  ip.itemOferta io
                        WHERE i.tramite = :pedido 
                        AND io.adjudicado= :estado
                        GROUP BY i.ipp
                        ORDER BY i.ipp ASC';
                $query = $this->em->createQuery($dql);
                $query->setParameter('pedido', $pedido)
                        ->setParameter('estado', $adjudicado);
                $items = $query->getResult();
            }
            
                return $items;

        } elseif ($tipo != "rubro") {

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
     * 
     * @return string
     */
    public function tramitePedidoItemPorOferente($pedido, $oferta, $adjudicado = true )
    {
            // busqueda por itemprocesoadjudicado
             $dql = 'SELECT i AS itemPedido, COUNT(i.id) AS cantidad, SUM(ip.cantidad * ip.precio) AS adjudicado
                    FROM AusentismoBundle:ItemPedido i 
                    INNER JOIN i.itemProcesoAdjudicado ip
                    WHERE i.tramite = :pedido 
                    AND ip.oferta= :oferta
                    AND ip.cantidad > :cantidad';
            $query = $this->em->createQuery($dql);
            $query->setParameter('pedido', $pedido)
            ->setParameter('oferta', $oferta)
            ->setParameter('cantidad', 0);
            $items = $query->getResult();

            if (empty($items['itemPedido'])) {
                $dql = 'SELECT i AS itemPedido, COUNT(i.id) AS cantidad, SUM(io.cantidadAdjudicada * io.precio) AS adjudicado
                        FROM AusentismoBundle:ItemPedido i 
                        INNER JOIN i.itemProceso ip
                        INNER JOIN  ip.itemOferta io
                        WHERE i.tramite = :pedido 
                        AND io.oferta= :oferta
                        AND io.adjudicado= :estado';
                $query = $this->em->createQuery($dql);
                $query->setParameter('pedido', $pedido)
                      ->setParameter('oferta', $oferta)
                      ->setParameter('estado', $adjudicado);
                      $items = $query->getResult();
            }
            return $items;
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
    public function ItemPedidoPorRubroPorOrganismo($organismo, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);

        $fechaHasta = $fechaHasta->format('Y/m/d');

        $dql = 'SELECT i, i.codigo AS codigo, i.unidadMedida AS unidad, t.trimestre AS trimestre, SUM(i.cantidad) AS cantidad
                FROM AusentismoBundle:ItemPedido i 
                INNER JOIN i.tramite t
                INNER JOIN t.organismoOrigen o
                INNER JOIN t.estadoTramite e
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta 
                AND t.organismoOrigen = :saf
                AND t.estado = :estado
                GROUP BY i.codigo, i.unidadMedida, t.trimestre
                ORDER BY t.trimestre ASC, i.codigo ASC';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('saf', $organismo)              
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('fechaHasta', $fechaHasta)
              ->setParameter('estado', true);

        $items = $query->getResult();
        $arrayR = array();

        function in_array_r($needle, $arrayR) {
                foreach ($arrayR as $key=>$itemr) {
                        if (trim($itemr['codigo']) == trim($needle['codigo']) and trim($itemr['unidad']) == trim($needle['unidad']))  {
                            return $key;
                        }
                    }
                return false;
        }

       // $b = array(array("Mac", "NT"), array("Irix", "Linux"));
      //  echo in_array_r("Irix", $b) ? 'found' : 'not found';

        foreach ($items as $item) {
            $key = in_array_r($item, $arrayR);

            if ($item['trimestre'] == 1) {
                
                if ($key !==false) {
                    $arrayR[$key]['t1'] = $item['cantidad'];
                } else{
                    $arrayR[]= ['codigo'=>$item['codigo'],
                            'descripcion'=>$item[0]->getItem(),
                            'unidad'=>$item['unidad'],
                            't1'=>$item['cantidad'],
                            't2'=>null,
                            't3'=>null,
                            't4'=>null,
                            ];
                }
            }
            if ($item['trimestre'] == 2) {
                if ($key !==false) {
                    $arrayR[$key]['t2'] = $item['cantidad'];
                } else{
                    $arrayR[]= ['codigo'=>$item['codigo'],
                            'descripcion'=>$item[0]->getItem(),
                            'unidad'=>$item['unidad'],
                            't1'=>null,
                            't2'=>$item['cantidad'],
                            't3'=>null,
                            't4'=>null,
                            ];
                }
            }

            if ($item['trimestre'] == 3){
                if ($key !==false) {
                    $arrayR[$key]['t3'] = $item['cantidad'];
                } else{
                    $arrayR[]= ['codigo'=>$item['codigo'],
                            'descripcion'=>$item[0]->getItem(),
                            'unidad'=>$item['unidad'],
                            't1'=>null,
                            't2'=>null,
                            't3'=>$item['cantidad'],
                            't4'=>null,
                            ];
                }
            }
            if ($item['trimestre'] == 4) {
                if ($key !==false) {
                    $arrayR[$key]['t4'] = $item['cantidad'];
                } else{
                    $arrayR[]= ['codigo'=>$item['codigo'],
                            'descripcion'=>$item[0]->getItem(),
                            'unidad'=>$item['unidad'],
                            't1'=>null,
                            't2'=>null,
                            't3'=>null,
                            't4'=>$item['cantidad'],
                            ];
                }
            }
        }

        return $arrayR;
    }

    
    /**
     * @param DateTime $dateTime
     * @return int
     */
    public function ItemPedidoPorCodigo($item, $limite =1, $tecnico = false)
    {
        if ($tecnico) {
            $codigo = $item->getCodigo();
            $dql = 'SELECT i
                    FROM AusentismoBundle:ItemPedido i 
                    WHERE i.codigo = :codigo
                    AND i.detalle IS NOT NULL
                    ORDER BY i.detalle DESC';
            $query = $this->em->createQuery($dql);
            $query->setParameter('codigo', $codigo);
            $query->setMaxResults($limite);
                        $items1 = $query->getResult();
            $items = array();
            foreach ($items1 as $item1) {
                    if (empty($item1->getDetalle())) {
                            $item1->setDetalle(null);
                            $this->em->flush();
                    } else{
                       if ($item1->getId()!= $item->getId()) {
                             $items[] = $item1;
                        }     
                    }
            }
            return $items;
        } else {
            $codigo = $item->getCodigo();

            $dql = 'SELECT i
                    FROM AusentismoBundle:ItemPedido i 
                    WHERE i.codigo = :codigo
                    ORDER BY i.id DESC';
         
            $query = $this->em->createQuery($dql);
            $query->setParameter('codigo', $codigo);
            $query->setMaxResults($limite);
            return $query->getResult();
        }        
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