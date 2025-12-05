<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;
use \Twig_Extension;
use \Twig_SimpleFilter;

class TramiteExtension  extends \Twig_Extension
{

    protected $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**------------------------------FILTROS ---------------------------------*/

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('tramite_por_expediente_tipo', [$this, 'tramitePorTipo']),
            new Twig_SimpleFilter('tramite_por_expediente_slug', [$this, 'tramitePorSlug']),
            new Twig_SimpleFilter('tramite_editar', [$this, 'tramiteEditar']),
            new Twig_SimpleFilter('tramite_por_organismo_slug', [$this, 'tramitePorOrganismoSlug']),
            new Twig_SimpleFilter('tipo_tramite_por_usuario', [$this, 'tipoTramitePorUsuario']),
            new Twig_SimpleFilter('documento_palabra_clave', [$this, 'documentoPalabraClave']),
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

    /**
     * @param DateTime $dateTime
     * @return int
     */
    public function tramitePorSlug($expediente, $slug)
    {
        $dql = 'SELECT t
                FROM ExpedienteBundle:Tramite t 
                INNER JOIN t.tipoTramite tt
                WHERE t.expediente = :ex 
                AND tt.slug = :slug';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('ex', $expediente)
              ->setParameter('slug', $slug);

        return $query->getResult();
               
    }

    /**
     * @param DateTime $dateTime
     * @return int
     */
    public function tramitePorOrganismoSlug($organismo, $slug, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);

        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT t, tt
                FROM ExpedienteBundle:Tramite t 
                INNER JOIN t.tipoTramite tt
                INNER JOIN t.organismoOrigen o
                INNER JOIN t.estadoTramite e
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta 
                AND t.organismoOrigen = :saf 
                AND tt.slug = :slug';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('saf', $organismo)
              ->setParameter('slug', $slug)              
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('fechaHasta', $fechaHasta);

        return $query->getResult();
               
    }

    /**
     * @param Tramite $tramite
     * @return boolean
     */
    public function tramiteEditar($tramite, $usuario)
    {
        $editar = false; 

        if (($tramite->getDepartamentoRm() == $usuario->getDepartamentoRm()) and ($tramite->getEstado() == false) and ($usuario->getRol()->getRoleName()=="admin" || $usuario->getRol()->getRoleName()=="supervisor")) {
             $editar = true; 

        } elseif (($tramite->getDepartamentoRm() == $usuario->getDepartamentoRm()) and ($tramite->getEstado() == false)) {
            
            $dql = 'SELECT tar
                    FROM ExpedienteBundle:Tarea tar 
                    WHERE tar.tramite = :tramite 
                    AND tar.usuario = :usuario';
            $query = $this->em->createQuery($dql);
            $query->setParameter('tramite', $tramite)
                  ->setParameter('usuario', $usuario);
                  
            $tareas = $query->getResult();
            
            if (count($tareas) >= 1) {
                $editar = true; 
            }
        }
        return $editar;     
    }
    
    /**
     *  Valores para $estado
     *  NULL(devuelve todos los tramites), 
     *  false(por defecto devuelve los tramites sin realizar ),
     *  true(devuelve los tramites realizados) 
     * @param DateTime $dateTime
     * @return int
     */
    public function tipoTramitePorUsuario($usuario, $anio= null, $estado = false )
    {
        if ($estado === NULL) {
                $fechaDesde = $anio."/01/01";
                $fechaDesde = new \DateTime($fechaDesde);
                $fechaDesde = $fechaDesde->format('Y/m/d');

                $fechaHasta = $anio."/12/31";
                $fechaHasta = new \DateTime($fechaHasta);
                $fechaHasta = $fechaHasta->format('Y/m/d');

                $dql = 'SELECT tar 
                        FROM ExpedienteBundle:Tarea tar
                        INNER JOIN tar.tramite t
                        WHERE tar.usuario = :usuario
                        AND t.fechaDestino >= :fechaDesde 
                        AND t.fechaDestino <= :fechaHasta 
                        ORDER BY t.tipoTramite ASC';
             
                $query = $this->em->createQuery($dql);
                $query->setParameter('usuario', $usuario)
                      ->setParameter('fechaDesde', $fechaDesde)
                      ->setParameter('fechaHasta', $fechaHasta);
        } else {
                $fechaDesde = $anio."/01/01";
                $fechaDesde = new \DateTime($fechaDesde);
                $fechaDesde = $fechaDesde->format('Y/m/d');

                $fechaHasta = $anio."/12/31";
                $fechaHasta = new \DateTime($fechaHasta);
                $fechaHasta = $fechaHasta->format('Y/m/d');

                $dql = 'SELECT tar 
                        FROM ExpedienteBundle:Tarea tar
                        INNER JOIN tar.tramite t
                        WHERE tar.usuario = :usuario
                        AND tar.realizada = :estado  
                        AND t.fechaDestino >= :fechaDesde 
                        AND t.fechaDestino <= :fechaHasta 
                        ORDER BY t.tipoTramite ASC';
             
                $query = $this->em->createQuery($dql);
                $query->setParameter('usuario', $usuario)
                      ->setParameter('estado', $estado)
                      ->setParameter('fechaDesde', $fechaDesde)
                      ->setParameter('fechaHasta', $fechaHasta);
        }
        return $query->getResult();
               
    }

    /**
     * @param DateTime $dateTime
     * @return int
     */
    public function documentoPalabraClave($tramite, $searchString)
    {
    
        $dql = 'SELECT doc
                FROM DocumentoBundle:Documento doc 
                WHERE doc.tramite = :tramite 
                AND doc.nombreArchivo LIKE :searchString';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('tramite', $tramite)
              ->setParameter('searchString', '%' . $searchString . '%');

        return $query->setMaxResults(1)->getOneOrNullResult();
               
    }

    /**------------------------------FUNCIONES ---------------------------------*/
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            /** tramites_por_trimestre parametros (reparticion, año)
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
            new \Twig_SimpleFunction('tramites_por_trimestre', array($this, 'reportePorTrimeste')),

            /** tramites_por_trimestre parametros (reparticion, año)
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
            new \Twig_SimpleFunction('tramites_por_trimestre_saf', array($this, 'reportePorTrimesteSaf')),

            /** tramites_por_rubro parametros (reparticion, año)
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
            new \Twig_SimpleFunction('tramites_por_rubro', array($this, 'reportePorRubro')),

            /** tramites_por_rubro parametros (reparticion, año)
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
            new \Twig_SimpleFunction('tramites_por_rubro_saf', array($this, 'reportePorRubroSaf')),

            /** por finalidad
             array:3 [▼
              0 => array:4 [▼
                "grupoRubro" => GrupoRubro {#3240 ▶}
                "cantidad" => "201"
                "presupuesto" => "2350154851.83"
              ]
              1 => array:4 [▶]
              2 => array:4 [▶]
            ] */
            new \Twig_SimpleFunction('tramites_por_grupo_rubro', array($this, 'reportePorGrupoRubro')),

            /** DEVUELVE LOS RUBROS DE EL GRUPO*/
            new \Twig_SimpleFunction('tramites_por_grupo_rubro_y_rubro', array($this, 'reportePorGrupoRubroYRubro')),

            /**tramites_por_saf parametros (reparticion, año)
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
            new \Twig_SimpleFunction('tramites_por_saf', array($this, 'reportePorSaf')),

            new \Twig_SimpleFunction('tramite_credito', array($this, 'tramiteCredito')),


            /**------------------------------EXPEDIENTES---------------------------------*/

            new \Twig_SimpleFunction('tipo_expediente', array($this, 'tipoExpediente')),

            /**------------------------------PROCESOS---------------------------------*/
            new \Twig_SimpleFunction('procesos_por_mes', array($this, 'reporteProcesosPorMes')),
            /**------------------------------PROCESOS---------------------------------*/
            new \Twig_SimpleFunction('despachos_por_estado', array($this, 'reporteDespachoPorEstado')),
            /**------------------------------RECORDATORIOS AGENDA---------------------------------*/
             new \Twig_SimpleFunction('agenda_ultima', array($this, 'agendaUltima')),
             
            /**------------------------------PAGOS---------------------------------*/
            new \Twig_SimpleFunction('pagos_por_proveedor_organismo', array($this, 'reportePagosProveedorOrganismo')),
            /**------------------------------TRAMITE DEL USUARIO---------------------------------*/
            new \Twig_SimpleFunction('tramite_usuario', array($this, 'reporteTramiteUsuario')),
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
                AND t.estado = :estado
                GROUP BY t.trimestre
                ORDER BY t.trimestre';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('estado', true)
              ->setParameter('fechaHasta', $fechaHasta);

        return $query->getResult();
    }

    /**
     * "reparticion_id" se refiere a la reparticionRm que seria el sector del Usuario
     * al que pertenece y a su vez el tramite.
     * @param int       $reparticion_id
     * @param int       $anio
     *
     * @return string
     */
    public function reportePorTrimesteSaf($reparticion_id, $anio, $saf_id)
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
                AND t.organismoOrigen = :saf_id
                AND t.estado = :estado
                GROUP BY t.trimestre
                ORDER BY t.trimestre';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('saf_id', $saf_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('estado', true)
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
                INNER JOIN t.rubro r 
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.departamentoRm = :id
                AND t.estado = :estado
                GROUP BY t.rubro
                ORDER BY t.rubro';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('estado', true)
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
    public function reportePorRubroSaf($reparticion_id, $anio, $saf_id)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT t AS tramite, (t.rubro) AS rubro_id, COUNT(t.id) AS cantidad, SUM(t.presupuestoOficial) AS presupuesto
                FROM ExpedienteBundle:Tramite t
                INNER JOIN t.rubro r 
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.organismoOrigen = :saf_id
                AND t.departamentoRm = :id
                AND t.estado = :estado
                GROUP BY t.rubro
                ORDER BY t.rubro';
                    
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('saf_id', $saf_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('estado', true)
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
    public function reportePorGrupoRubro($reparticion_id, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT gr AS grupoRubro, COUNT(t.id) AS cantidad, SUM(t.presupuestoOficial) AS presupuesto
                FROM AusentismoBundle:GrupoRubro gr
                INNER JOIN gr.rubro r 
                INNER JOIN r.tramite t
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.departamentoRm = :id
                AND t.estado = :estado
                GROUP BY gr';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('estado', true)
              ->setParameter('fechaHasta', $fechaHasta);
 
        return $query->getResult();
    }

    /**
     * "reparticion_id" se refiere a la reparticionRm que seria el sector del Usuario
     * al que pertenece y a su vez el tramite.
     *
     * @param int       $rubro
     * @param int       $anio
     *
     * @return string
     */
    public function reportePorGrupoRubroYRubro($rubro, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT t AS tramite, r, (t.rubro) AS rubro_id, COUNT(t.id) AS cantidad, SUM(t.presupuestoOficial) AS presupuesto
                FROM ExpedienteBundle:Tramite t
                INNER JOIN t.rubro r 
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND r.grupoRubro = :id
                AND t.estado = :estado
                GROUP BY t.rubro
                ORDER BY t.rubro';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $rubro)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('estado', true)
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
    public function reportePorSaf($reparticion_id, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT t AS tramite, (t.organismoOrigen) AS organismo_id, COUNT(t.id) AS cantidad, SUM(t.presupuestoOficial) AS presupuesto
                FROM ExpedienteBundle:Tramite t 
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.departamentoRm = :id
                AND t.estado = :estado
                GROUP BY t.organismoOrigen
                ORDER BY t.organismoOrigen';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('estado', true)
              ->setParameter('fechaHasta', $fechaHasta);
         
        return $query->getResult();
    }
           
           /**------------------------------PROCESOS---------------------------------*/
    /**
     * "reparticion_id" se refiere a la reparticionRm que seria el sector del Usuario
     * al que pertenece y a su vez el tramite.
     * @param int       $reparticion_id
     * @param int       $anio
     *
     * @return string
     */
    public function reporteProcesosPorMes($reparticion_id, $anio)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT t 
                FROM ExpedienteBundle:Tramite t 
                JOIN t.tipoTramite tt
                JOIN t.expediente e
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.estado = :estado
                AND tt.slug = :slug
                ORDER BY t.mes';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('slug', "tramite_proceso")
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('estado', true)
              ->setParameter('fechaHasta', $fechaHasta);
       // dump( $query->getResult());
        //exit();
        return $query->getResult();
    }

/**---------------------------------------------------------------*/
    /**
     * 
     *
     * @param objet       $tramite
     *
     * @return string
     */
    public function tramiteCredito($tramite)
    {
        $creditos = $tramite->getCredito();
        $estado = "-";
        foreach ($creditos  as $credito) {
             if ( !($credito->getEstado())  and ($estado != "&#10004;  SÍ") ) {
                 $estado = "&#10008; NO";
             } elseif (($credito->getEstado()) and ($estado != "&#10008; NO")) {
                 $estado = "&#10004;  SÍ";
             } else {
                 $estado = "* PARCIAL";
             }
        }
        return $estado;
    }

/**------------------------------EXPEDIENTES---------------------------------*/
    /**
     * 
     *
     * @param objet       $tramite
     *
     * @return string
     */
    public function tipoExpediente($reparticion_id)
    {

        $dql = 'SELECT te
                FROM ExpedienteBundle:TipoExpediente te
                WHERE te.departamentoRm = :id';
    
        $query = $this->em->createQuery($dql);
        $query->setParameter('id', $reparticion_id);
 
        return $query->getResult();
    }
/**------------------------------DESPACHO---------------------------------*/
    /**
     * "reparticion_id" se refiere a la reparticionRm que seria el sector del Usuario
     * al que pertenece y a su vez el tramite.
     * @param int       $anio
     * @param int       $estado
     *
     * @return objet
     */
    public function reporteDespachoPorEstado($anio, $estado)
    {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');
        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT t 
                FROM ExpedienteBundle:Tramite t 
                JOIN t.tipoTramite tt
                JOIN t.expediente e
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.estado = :estado
                AND tt.slug = :slug
                ORDER BY t.organismoDestino';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('slug', "tramite_despacho")
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('estado', $estado)
              ->setParameter('fechaHasta', $fechaHasta);
       // dump( $query->getResult());
        //exit();
        return $query->getResult();
    }
/**------------------------------RECORDATORIO AGENDA---------------------------------*/
    /**
     * 
     *
     * @param objet       $recordatorio
     *
     * @return string
     */
    public function agendaUltima($tramite, $slug)
    {

        $dql = 'SELECT r
                FROM ExpedienteBundle:Recordatorio r
                WHERE r.recordatorio = :slug
                AND r.tramite = :tramite
                ORDER BY r.id DESC';
    
        $query = $this->em->createQuery($dql);
        $query->setParameter('slug', $slug)
              ->setParameter('tramite', $tramite);
 
        return $query->setMaxResults(1)->getOneOrNullResult();
    }
 /**------------------------------PAGOS---------------------------------*/
    /**
     * "reparticion_id" se refiere a la reparticionRm que seria el sector del Usuario
     * al que pertenece y a su vez el tramite.
     * @param int       $anio
     * @param int       $estado
     *
     * @return objet
     */
    public function reportePagosProveedorOrganismo($proveedor, $organismo, $expediente = null, $anio = null )
    {
    if (!empty($expediente)) {
                $dql = 'SELECT t 
                FROM ExpedienteBundle:Tramite t 
                JOIN t.tipoTramite tt
                WHERE t.expediente = :e
                AND t.proveedor = :p
                AND t.organismoOrigen = :o
                AND tt.slug = :slug';
                     
        $query = $this->em->createQuery($dql);
        $query->setParameter('slug', "tramite_pago")
              ->setParameter('e', $expediente)
              ->setParameter('p', $proveedor)
              ->setParameter('o', $organismo);
    } else {
        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');
        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');
        $dql = 'SELECT t 
                FROM ExpedienteBundle:Tramite t 
                JOIN t.tipoTramite tt
                WHERE t.fechaDestino >= :fechaDesde 
                AND t.fechaDestino <= :fechaHasta
                AND t.proveedor = :p
                AND t.organismoOrigen = :o
                AND tt.slug = :slug';
                     
        $query = $this->em->createQuery($dql);
        $query->setParameter('slug', "tramite_pago")
              ->setParameter('p', $proveedor)
              ->setParameter('o', $organismo)
              ->setParameter('fechaDesde', $fechaDesde)
              ->setParameter('fechaHasta', $fechaHasta);

    }

        return $query->getResult();
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'tramite_extension';
    }
    
/**------------------------------TRAMITE DEL USUARIO---------------------------------*/
    /**
     * "reparticion_id" se refiere a la reparticionRm que seria el sector del Usuario
     * al que pertenece y a su vez el tramite.
     * @param int       $usuario
     * @param int       $tramite
     * @return objet
     */
    public function reporteTramiteUsuario($usuario, $tramite)
    {

        $dql = 'SELECT t 
                FROM ExpedienteBundle:Tramite t 
                JOIN t.tarea tar
                WHERE tar.usuario = :usuario 
                AND t.id = :id';
     
        $query = $this->em->createQuery($dql);
        $query->setParameter('usuario', $usuario)
              ->setParameter('id', $tramite->getId());

        return $query->getResult();
    }
}