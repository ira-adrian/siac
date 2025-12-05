<?php

namespace Siarme\ExpedienteBundle\Entity;

use Siarme\AusentismoBundle\Util\Util;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tramite
 *
 * @ORM\Table(name="tramite")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\TramiteRepository")
 * @UniqueEntity(fields = { "ccoo", "departamentoRm" }, message="Ya EXISTE  en otro registro")
 * @UniqueEntity(fields = { "numeroComprar", "departamentoRm" },  message="Ya EXISTE en otro registro")
 * @ORM\HasLifecycleCallbacks()
 */
class Tramite
{
    const TIPO_ENTIDAD = 'TRA';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_tramite", type="string")
     */
    private $numeroTramite;

    /**
     * @var string
     *
     * @ORM\Column(name="ccoo", type="text", nullable= true)
     */
    private $ccoo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="numero_comprar", type="string", nullable= true)
     */
    private $numeroComprar;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_nota", type="text", nullable= true)
     */
    private $numeroNota;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Organismo", inversedBy="tramite")
     * 
     */
    private $organismoOrigen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Organismo")
     * 
     */
    private $organismoDestino;

    /**
     * @var int
     *
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_destino", type="datetime", nullable=true)
     */
    private $fechaDestino;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_estado", type="datetime", nullable=true)
     */
    private $fechaEstado;

    /**
     * @var string
     *
     * @ORM\Column(name="trimestre", type="string", length=25, nullable= true)
     */
    private $trimestre;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;

    /**
     * @var bool
     *
     * @ORM\Column(name="adjudicado", type="boolean")
     */
    private $adjudicado;

    /**
     * @var int
     *
     * @ORM\Column(name="monto_adjudica", type="decimal", precision=14, scale=2, nullable= true)
     */
    private $montoAdjudica;

    /**
     * @var string
     *
     * @ORM\Column(name="moneda", type="string", length=25, nullable= true)
     */
    private $moneda;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="presupuesto_oficial", type="decimal", precision=14, scale=2, nullable= true)
     * 
     */
    private $presupuestoOficial;

    /**
     * @var string|null
     *
     * @ORM\Column(name="oferente", type="string", nullable=true)
     */
    private $oferente;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cuit", type="string", length=20, nullable=true)
     */
    private $cuit;

   /**
     * @var string
     *
     * @ORM\Column(name="sistema", type="string", length=30)
     */
    private $sistema;

    /**
     * @var int
     *
     * @ORM\Column(name="pf_precio", type="integer")
     */
    private $pFPrecio;

    /**
     * @var int
     *
     * @ORM\Column(name="pf_calidad_bueno", type="integer")
     */
    private $pFCalidadBueno;

    /**
     * @var int
     *
     * @ORM\Column(name="pf_calidad_muy_bueno", type="integer")
     */
    private $pFCalidadMuyBueno;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="oferta_alternativa", type="boolean")
     */
    private $ofertaAlternativa;
    
    /**
     * @var int
     *
     * @ORM\Column(name="pf_plazo_entrega", type="decimal", precision=4, scale=2)
     */
    private $pFPlazoEntrega;

   /**
     * @var int
     *
     * @ORM\Column(name="plazo", type="integer")
     */
    private $plazo;

    /**
     * @var int
     *
     * @ORM\Column(name="pf_antecedente", type="integer")
     */
    private $pFAntecedente;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Proveedor", inversedBy="oferta")
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     */
    private $proveedor;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\TipoTramite",inversedBy="tramite") 
     * @ORM\JoinColumn(name="tipo_tramite_id", referencedColumnName="id")
     */
    private $tipoTramite;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\EstadoTramite",inversedBy="tramite") 
     * @ORM\JoinColumn(name="estado_tramite_id", referencedColumnName="id")
     */
    private $estadoTramite;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\DepartamentoRm", inversedBy="tramite")
     * @ORM\JoinColumn(name="departamento_rm_id", referencedColumnName="id")
     */
    private $departamentoRm;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Expediente", inversedBy="tramite") 
     *@ORM\JoinColumn(name="expediente_id", referencedColumnName="id")
     */
    private $expediente;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\DocumentoBundle\Entity\Documento", mappedBy="tramite", cascade={"persist","remove"}) 
     */
    private $documento;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Rubro",inversedBy="tramite") 
     * @ORM\JoinColumn(name="rubro_id", referencedColumnName="id")
     */
    private $rubro;

    /**
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tarea", mappedBy="tramite", cascade={"persist","remove"})
     */
    private $tarea;

    /**
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Credito", mappedBy="tramite", cascade={"persist","remove"})
     */
    private $credito;
    /**
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Recordatorio", mappedBy="tramite", cascade={"persist","remove"})
     */
    private $recordatorio;

   /**
     * @var string tipo de modaliad procesos
     *
     * @ORM\Column(name="tipo", type="string", length=100, nullable= true)
     */
    private $tipo;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Modalidad", inversedBy="tramite") 
     *@ORM\JoinColumn(name="modalidad_id", referencedColumnName="id")
     */
    private $modalidad;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\TipoProceso", inversedBy="tramite") 
     *@ORM\JoinColumn(name="tipo_proceso_id", referencedColumnName="id")
     */
    private $tipoProceso;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text", nullable= true)
     */
    private $texto;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemPedido", mappedBy="tramite", cascade={"persist","remove"})
    */
    private $itemPedido;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemProceso", mappedBy="proceso", cascade={"persist","remove"})
    */
    private $itemProceso;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemOferta", mappedBy="proceso", cascade={"persist","remove"})
    */
    private $itemOfertas;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemOferta", mappedBy="oferta", cascade={"persist","remove"})
    */
    private $itemOferta;

    /**
     * One Tramite has Many Tramites.
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", mappedBy="proceso", cascade={"persist","remove"})
     */
    private $oferta;

    /**
     * Many Tramites have One Tramite.
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", inversedBy="oferta")
     */
    private $proceso;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\GeneralBundle\Entity\ItemSolicitado", mappedBy="tramite", cascade={"persist","remove"})
    */
    private $itemSolicitado;

    /**
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Planificacion", mappedBy="tramite", cascade={"persist","remove"})
     */
    private $planificacion;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado", mappedBy="proceso", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="item_proceso_adjudicado_id", referencedColumnName="id")
    */
    private $itemProcesoAdjudicado;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado", mappedBy="oferta", cascade={"persist","remove"})
    */
    private $itemOfertaAdjudicado;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


   /**
   * Constructor
   */
    public function __construct()
    {
        $this->oferta = new ArrayCollection();
        $this->itemPedido = new ArrayCollection();
        $this->itemSolicitado = new ArrayCollection();
        $this->itemProceso = new ArrayCollection();
        $this->itemOfertas = new ArrayCollection();
        $this->itemOferta = new ArrayCollection();
        $this->documento = new ArrayCollection();
        $this->tarea = new ArrayCollection();
        $this->credito = new ArrayCollection();
        $this->recordatorio = new ArrayCollection();
        $this->fecha = new \Datetime(); 
        $this->fechaDestino = new \Datetime(); 
        $this->fechaEstado = new \Datetime(); 
        $this->mes = date("n"); 
        $this->estado = true; 
        $this->adjudicado = false; 
        $this->ofertaAlternativa = true; 
        $this->sistema = "COMPRAR"; 
        $this->moneda = "PESOS"; 
        $this->pFPrecio = 80;
        $this->pFCalidadBueno = 5;
        $this->pFCalidadMuyBueno = 12;
        $this->pFPlazoEntrega = 5;
        $this->plazo= 2; //dos días por defecto
        $this->pFAntecedente = 3;
        $this->montoAdjudica = 0;
        $this->planificacion = new ArrayCollection();
        $this->itemProcesoAdjudicado = new ArrayCollection();
        $this->itemOfertaAdjudicado = new ArrayCollection();
    }
    
    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setTareaRealizada() {
        if ($this->estado or $this->getEstadoTramite()->getPorcentaje() == 100 ) {
            foreach ($this->tarea as $tarea) {
                $tarea->setRealizada(true);
            }
        } else {
            foreach ($this->tarea as $tarea) {
                $tarea->setRealizada(false);
            }
        }
    }
    
    /**
     * Set organismoOrigen
     *
     * @param string $organismoOrigen
     * @return Tramite
     */
    public function setOrganismoOrigen($organismoOrigen)
    {
        $this->organismoOrigen = $organismoOrigen;

        return $this;
    }

    /**
     * Get organismoOrigen
     *
     * @return string 
     */
    public function getOrganismoOrigen()
    {
        return $this->organismoOrigen;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Tramite
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set organismoDestino
     *
     * @param string $organismoDestino
     * @return Tramite
     */
    public function setOrganismoDestino($organismoDestino)
    {
        $this->organismoDestino = $organismoDestino;

        return $this;
    }

    /**
     * Get organismoDestino
     *
     * @return string 
     */
    public function getOrganismoDestino()
    {
        return   $this->organismoDestino;
    }

    /**
     * Set fechaDestino
     *
     * @param \DateTime $fechaDestino
     * @return Tramite
     */
    public function setFechaDestino($fechaDestino)
    {
        $this->fechaDestino = $fechaDestino;

        return $this;
    }

    /**
     * Get fechaDestino
     *
     * @return \DateTime 
     */
    public function getFechaDestino()
    {
        return $this->fechaDestino;
    }

    /**
     * Set fechaEstado
     *
     * @param \DateTime $fechaEstado
     * @return Tramite
     */
    public function setFechaEstado($fechaEstado)
    {
        $this->fechaEstado = $fechaEstado;

        return $this;
    }

    /**
     * Get fechaEstado
     *
     * @return \DateTime 
     */
    public function getFechaEstado()
    {
        return $this->fechaEstado;
    }

    /**
     * Get mes
     *
     * @return \DateTime 
     */
    public function getMes()
    {
        return $this->mes;
    }

   /*
   * ToString
   */
    public function __toString()
    {
         return   (string) $this->getTipoTramite();
    }

    /**
     * Set sistema.
     *
     * @param string $sistema
     *
     * @return Tramite
     */
    public function setSistema($sistema)
    {
        $this->sistema = $sistema;

        return $this;
    }

    /**
     * Get sistema.
     *
     * @return string
     */
    public function getSistema()
    {
        return $this->sistema;
    }

    /**
     * Set pFPrecio.
     *
     * @param int $pFPrecio
     *
     * @return Tramite
     */
    public function setPFPrecio($pFPrecio)
    {
        $this->pFPrecio = $pFPrecio;

        return $this;
    }

    /**
     * Get pFPrecio.
     *
     * @return int
     */
    public function getPFPrecio()
    {
        return $this->pFPrecio;
    }

    /**
     * Set pFCalidadBueno.
     *
     * @param int $pFCalidadBueno
     *
     * @return Tramite
     */
    public function setPFCalidadBueno($pFCalidadBueno)
    {
        $this->pFCalidadBueno = $pFCalidadBueno;

        return $this;
    }

    /**
     * Get pFCalidadBueno.
     *
     * @return int
     */
    public function getPFCalidadBueno()
    {
        return $this->pFCalidadBueno;
    }

        /**
     * Set pFCalidadMuyBueno.
     *
     * @param int $pFCalidadMuyBueno
     *
     * @return Tramite
     */
    public function setPFCalidadMuyBueno($pFCalidadMuyBueno)
    {
        $this->pFCalidadMuyBueno = $pFCalidadMuyBueno;

        return $this;
    }

    /**
     * Get pFCalidadMuyBueno.
     *
     * @return int
     */
    public function getPFCalidadMuyBueno()
    {
        return $this->pFCalidadMuyBueno;
    }

    /**
     * Set ofertaAlternativa
     *
     * @param boolean $ofertaAlternativa
     * @return Tramite
     */
    public function setOfertaAlternativa($ofertaAlternativa)
    {
        $this->ofertaAlternativa = $ofertaAlternativa;

        $items = $this->itemOfertas;
        if (!$ofertaAlternativa) {
            foreach ($items as $item) {
                if ($item->getTipo() != 1) {
                    $item->setEstado(false);
                }
            }
        } else {
            foreach ($items as $item) {
         
                    $item->setEstado(true);
              
            }

        }

        return $this;
    }

    /**
     * Get ofertaAlternativa
     *
     * @return boolean 
     */
    public function getOfertaAlternativa()
    {
        return $this->ofertaAlternativa;
    }
    /**
     * Set pFPlazoEntrega.
     *
     * @param int $pFPlazoEntrega
     *
     * @return Tramite
     */
    public function setPFPlazoEntrega($pFPlazoEntrega)
    {
        $this->pFPlazoEntrega = $pFPlazoEntrega;

        return $this;
    }

    /**
     * Get pFPlazoEntrega.
     *
     * @return int
     */
    public function getPFPlazoEntrega()
    {
        return $this->pFPlazoEntrega;
    }

    /**
     * Set plazo.
     *
     * @param int $plazo
     *
     * @return Tramite
     */
    public function setPlazo($plazo)
    {
        $this->plazo = $plazo;

        return $this;
    }

    /**
     * Get plazo.
     *
     * @return int
     */
    public function getPlazo()
    {
        return $this->plazo;
    }

    /**
     * Set pFAntecedente.
     *
     * @param int $pFAntecedente
     *
     * @return Tramite
     */
    public function setPFAntecedente($pFAntecedente)
    {
        $this->pFAntecedente = $pFAntecedente;

        return $this;
    }

    /**
     * Get pFAntecedente.
     *
     * @return int
     */
    public function getPFAntecedente()
    {
        return $this->pFAntecedente;
    }

    /**
     * Set departamentoRm
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm
     * @return Tramite
     */
    public function setDepartamentoRm(\Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm = null)
    {
        $this->departamentoRm = $departamentoRm;

        return $this;
    }

    /**
     * Get departamentoRm
     *
     * @return \Siarme\AusentismoBundle\Entity\DepartamentoRm 
     */
    public function getDepartamentoRm()
    {
        return $this->departamentoRm;
    }

    /**
     * Add documento
     *
     * @param \Siarme\DocumentoBundle\Entity\Documento $documento
     * @return Tramite
     */
    public function addDocumento(\Siarme\DocumentoBundle\Entity\Documento $documento)
    {
        $this->documento[] = $documento;

        return $this;
    }

    /**
     * Remove documento
     *
     * @param \Siarme\DocumentoBundle\Entity\Documento $documento
     */
    public function removeDocumento(\Siarme\DocumentoBundle\Entity\Documento $documento)
    {
        $this->documento->removeElement($documento);
    }

    /**
     * Get documento
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Set proveedor
     *
     * @param \Siarme\AusentismoBundle\Entity\Proveedor $proveedor
     * @return Tramite
     */
    public function setProveedor(\Siarme\AusentismoBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \Siarme\AusentismoBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }


    /**
     * Set tipoTramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoTramite $tipoTramite
     *
     * @return Tramite
     */
    public function setTipoTramite(\Siarme\ExpedienteBundle\Entity\TipoTramite $tipoTramite = null)
    {
        $this->tipoTramite = $tipoTramite;
        return $this;
    }

    /**
     * Get tipoTramite
     *
     * @return \Siarme\ExpedienteBundle\Entity\TipoTramite
     */
    public function getTipoTramite()
    {
        return $this->tipoTramite;
    }

    /**
     * Set estadoTramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\EstadoTramite $estadoTramite
     * @return Tramite
     */
    public function setEstadoTramite(\Siarme\ExpedienteBundle\Entity\EstadoTramite $estadoTramite = null)
    {
        $this->estadoTramite = $estadoTramite;
        $this->fechaEstado = new \Datetime();
        
        return $this;
    }

    /**
     * Get estadoTramite
     *
     * @return \Siarme\ExpedienteBundle\Entity\EstadoTramite 
     */
    public function getEstadoTramite()
    {
        return $this->estadoTramite;
    }

   /**
     * Set trimestre
     *
     * @param string $trimestre
     *
     * @return Tramite
     */
    public function setTrimestre($trimestre)
    {
        $this->trimestre = $trimestre;

        return $this;
    }

    /**
     * Get trimestre
     *
     * @return string
     */
    public function getTrimestre()
    {
        return $this->trimestre;
    }

    /**
     * Set expediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\Expediente $expediente
     * @return Tramite
     */
    public function setExpediente(\Siarme\ExpedienteBundle\Entity\Expediente $expediente = null)
    {
        $this->expediente = $expediente;

        return $this;
    }

    /**
     * Get expediente
     *
     * @return \Siarme\ExpedienteBundle\Entity\Expediente 
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return Tramite
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set adjudicado
     *
     * @param boolean $adjudicado
     * @return Tramite
     */
    public function setAdjudicado($adjudicado)
    {
        $this->adjudicado = $adjudicado;

        /** if (!$adjudicado) {
            $items = $this->itemOfertas;
            foreach ($items as $item) {
                $item->setAdjudicado($adjudicado);
                $item->setCantidadAdjudicada(0);
            }
        } */

        return $this;
    }

    /**
     * Get adjudicado
     *
     * @return boolean 
     */
    public function getAdjudicado()
    {
        return $this->adjudicado;
    }

    /**
     * Set montoAdjudica.
     *
     * @param int $montoAdjudica
     *
     * @return Tramite
     */
    public function setMontoAdjudica($montoAdjudica)
    {
        $this->montoAdjudica = $montoAdjudica;

        return $this;
    }

    /**
     * Get montoAdjudica.
     *
     * @return int
     */
    public function getMontoAdjudica()
    {
        return $this->montoAdjudica;
    }


   /**
     * Set moneda
     *
     * @param string $moneda
     *
     * @return Tramite
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;

        return $this;
    }

    /**
     * Get moneda
     *
     * @return string
     */
    public function getMoneda()
    {
        return $this->moneda;
    }


   /**
     * Set numeroTramite
     *
     * @param string $numeroTramite
     * @return Tramite
     */
    public function setNumeroTramite($numeroTramite)
    {
        $this->numeroTramite = $numeroTramite;

        return $this;
    }

    /**
     * Get numeroTramite
     *
     * @return string 
     */
    public function getNumeroTramite()
    {
        return $this->numeroTramite;
    }

    /**
     * Set ccoo
     *
     * @param string $ccoo
     * @return Expediente
     */
    public function setCcoo($ccoo)
    {
        $this->ccoo = $ccoo;

        return $this;
    }

    /**
     * Get ccoo
     *
     * @return string 
     */
    public function getCcoo()
    {
        return $this->ccoo;
    }

   /**
     * Set numeroComprar
     *
     * @param string $numeroComprar
     * @return Tramite
     */
    public function setNumeroComprar($numeroComprar)
    {
        $this->numeroComprar = $numeroComprar;

        return $this;
    }

    /**
     * Get numeroComprar
     *
     * @return string 
     */
    public function getNumeroComprar()
    {
        return $this->numeroComprar;
    }

   /**
     * Set numeroNota
     *
     * @param string $numeroNota
     * @return Tramite
     */
    public function setNumeroNota($numeroNota)
    {
        $this->numeroNota = $numeroNota;

        return $this;
    }

    /**
     * Get numeroNota
     *
     * @return string 
     */
    public function getNumeroNota()
    {
        return $this->numeroNota;
    }

    /**
     * Set presupuestoOficial
     *
     * @param decimal $presupuestoOficial
     * @return Tramite
     */
    public function setPresupuestoOficial($presupuestoOficial)
    {
        $this->presupuestoOficial = $presupuestoOficial;

        return $this;
    }

    /**
     * Get presupuestoOficial
     *
     * @return decimal 
     */
    public function getPresupuestoOficial()
    {
        return $this->presupuestoOficial;
    }



    /**
     * Set cuit.
     *
     * @param string|null $cuit
     *
     * @return Tramite
     */
    public function setCuit($cuit = null)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit.
     *
     * @return string|null
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set oferente.
     *
     * @param string|null $oferente
     *
     * @return Tramite
     */
    public function setOferente($oferente = null)
    {
        $this->oferente = $oferente;

        return $this;
    }

    /**
     * Get oferente.
     *
     * @return string|null
     */
    public function getOferente()
    {
        return $this->oferente;
    }

    /**
     * Set rubro
     *
     * @param \Siarme\AusentismoBundle\Entity\Rubro $rubro
     *
     * @return Tramite
     */
    public function setRubro(\Siarme\AusentismoBundle\Entity\Rubro $rubro = null)
    {
        $this->rubro = $rubro;

        return $this;
    }

    /**
     * Get rubro
     *
     * @return \Siarme\AusentismoBundle\Entity\Rubro
     */
    public function getRubro()
    {
        return $this->rubro;
    }

    
    /**
     * Add tarea
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tarea $tarea
     * @return Tramite
     */
    public function addTarea(\Siarme\ExpedienteBundle\Entity\Tarea $tarea)
    {
        $this->tarea[] = $tarea;

        return $this;
    }

    /**
     * Remove tarea
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tarea $tarea
     */
    public function removeTarea(\Siarme\ExpedienteBundle\Entity\Tarea $tarea)
    {
        $this->tarea->removeElement($tarea);
    }

    /**
     * Get tarea
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTarea()
    {
        return  $this->tarea;
    }
    
    /**
     * Add credito
     *
     * @param \Siarme\ExpedienteBundle\Entity\Credito $credito
     * @return Tramite
     */
    public function addCredito(\Siarme\ExpedienteBundle\Entity\Credito $credito)
    {
        $this->credito[] = $credito;

        return $this;
    }

    /**
     * Remove credito
     *
     * @param \Siarme\ExpedienteBundle\Entity\Credito $credito
     */
    public function removeCredito(\Siarme\ExpedienteBundle\Entity\Credito $credito)
    {
        $this->credito->removeElement($credito);
    }

    /**
     * Get credito
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCredito()
    {
        return  $this->credito;
    }

    /**
     * Add recordatorio
     *
     * @param \Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio
     * @return Tramite
     */
    public function addRecordatorio(\Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio)
    {
        $this->recordatorio[] = $recordatorio;

        return $this;
    }

    /**
     * Remove recordatorio
     *
     * @param \Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio
     */
    public function removeRecordatorio(\Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio)
    {
        $this->recordatorio->removeElement($recordatorio);
    }

    /**
     * Get recordatorio
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecordatorio()
    {
        return  $this->recordatorio;
    }

    /**
     * Set tipo.
     *
     * @param string $tipo
     *
     * @return Tramite
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set modalidad
     *
     * @param \Siarme\ExpedienteBundle\Entity\Modalidad $modalidad
     * @return Tramite
     */
    public function setModalidad(\Siarme\ExpedienteBundle\Entity\Modalidad $modalidad = null)
    {
        $this->modalidad = $modalidad;

        return $this;
    }

    /**
     * Get modalidad
     *
     * @return \Siarme\ExpedienteBundle\Entity\Modalidad 
     */
    public function getModalidad()
    {
        return $this->modalidad;
    }

    /**
     * Set tipoProceso
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoProceso $tipoProceso
     * @return Tramite
     */
    public function setTipoProceso(\Siarme\ExpedienteBundle\Entity\TipoProceso $tipoProceso = null)
    {
        $this->tipoProceso = $tipoProceso;

        return $this;
    }

    /**
     * Get tipoProceso
     *
     * @return \Siarme\ExpedienteBundle\Entity\TipoProceso 
     */
    public function getTipoProceso()
    {
        return $this->tipoProceso;
    }
    /**
     * Set texto.
     *
     * @param string $texto
     *
     * @return Tramite
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto.
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Get tareaActiva
     *
     * @return boolean  
     */
    public function getTareaActiva()
    {
        $activa= false;
        $tareas = $this->getTarea();
        foreach ($tareas as $tarea ) {
            if ($tarea->getRealizada() == false) {
                $activa = true;
            }
        }

        return  $this->tareaActiva= $activa;
    }

    /**
     * Get cantidadAdjudicada
     *
     * @return integer  
     */
    public function getCantidadAdjudicada()
    {
        $items = $this->itemOferta;
        $total = 0;
        foreach ($items as $item) {
            $total = $total + $item->getCantidadAdjudicada();
        }
        return  $total;
    }

    /**
     * Get montoAdjudicado
     *
     * @return string  
     */
    public function getMontoAdjudicado()
    {
        $total = 0;
        if ($this->getTipoTramite()->getSlug() == "tramite_proceso") {
           $ofertas =  $this->oferta;
           foreach ($ofertas as $oferta) {
                 $items = $oferta->getItemOferta();
                 foreach ($items as $item) {
                      $total = $total + $item->getMontoAdjudicado();
                      //$total = round($total,2);
                 }
           }
        }
        if ($this->getTipoTramite()->getSlug() == "tramite_oferta") {
            $items = $this->itemOferta;
            foreach ($items as $item) {
              $total = $total + $item->getMontoAdjudicado();
              //$total = round($total,2);
            }
        }

        return  $total;

    }


    /**
     * Get cantidadOfortada
     *
     * @return integer  
     */
    public function getCantidadOfertada()
    {
        $items = $this->itemOferta;
        $total = 0;
        foreach ($items as $item) {
            $total = $total + $item->getCantidad();
        }
        return  $total;
    }

    /**
     * Get montoOfertado
     *
     * @return string  
     */
    public function getMontoOfertado()
    {
        /** 
         if ((!$this->ofertaAlternativa) or (!$this->getProceso()->getOfertaAlternativa())) {
        
            $items = $this->itemOferta;
            $total = 0;
            foreach ($items as $item) {
                if ($item->getTipo() == 1) {
                    $total = $total + $item->getTotal();
                }
            }
        
            
           
           
        } else {
            $items = $this->itemOferta;
            $total = 0;
            foreach ($items as $item) {
                $total = $total + $item->getTotal();
            }
        } 
        */
        $items = $this->itemOferta;
        $total = 0;
        $i=0;
        $item1 = 0;
        foreach ($items as $item) {
            if (($item->getTipo() == 1) or (($item->getTipo() == 2) and ($this->getProceso()->getOfertaAlternativa)))  {
                $i++;
                if ($i == 1) {
                    $item1 = $item;
                } elseif (($item1->getNumero() == $item->getNumero()) and ($item1->getOferta() == $item->getOferta()) and ($this->getProceso()->getOfertaAlternativa)) {
                    if ($item1->getPrecio() > $item->getPrecio()) {
                          $total = $total + $item1->getTotal();
                    } else{
                        $total = $total + $item->getTotal();
                    }
                } else {
                     $total = $total + $item1->getTotal();
                    
                    $item1 = $item;
                }
            }
        }

        $total = $total + $item1->getTotal();
            
        return  $total;
    }

    /**
     * Get presupuestoOficialItem
     *
     * @return string  
     */
    public function getPresupuestoOficialItem()
    {
        $total = 0;
        $items = $this->itemPedido;
        foreach ($items as $item) {
            $total = $total + $item->getTotal();
        }

        return  $total;
    }
    
    /**
     * Get cantidadPedida
     *
     * @return string  
     */
    public function getCantidadPedida()
    {
        $total = 0;
        $items = $this->itemPedido;
        foreach ($items as $item) {
            $total = $total + $item->getCantidad();
        }

        return  $total;
    }
    
    /**
     * Add itemPedido
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido
     *
     * @return Tramite
     */
    public function addItemPedido(\Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido)
    {
        $this->itemPedido[] = $itemPedido;

        return $this;
    }

    /**
     * Remove itemPedido
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido
     */
    public function removeItemPedido(\Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido)
    {
        $this->itemPedido->removeElement($itemPedido);
    }

    /**
     * Get itemPedido
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemPedido()
    {
        return $this->itemPedido;
    }


  /**
     * Add itemProceso
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProceso $itemProceso
     *
     * @return Tramite
     */
    public function addItemProceso(\Siarme\AusentismoBundle\Entity\ItemProceso $itemProceso)
    {
        $this->itemProceso[] = $itemProceso;

        return $this;
    }

    /**
     * Remove itemProceso
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProceso $itemProceso
     */
    public function removeItemProceso(\Siarme\AusentismoBundle\Entity\ItemProceso $itemProceso)
    {
        $this->itemProceso->removeElement($itemProceso);
    }

    /**
     * Get itemProceso
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemProceso()
    {
        return $this->itemProceso;
    }

  /**
     * Add itemOferta
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta
     *
     * @return Tramite
     */
    public function addItemOfertas(\Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta)
    {
        $this->itemOferta[] = $itemOferta;

        return $this;
    }

    /**
     * Remove itemOferta
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta
     */
    public function removeItemOfertas(\Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta)
    {
        $this->itemOferta->removeElement($itemOferta);
    }

    /**
     * Get itemOferta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemOfertas()
    {
        return $this->itemOfertas;
    }


  /**
     * Add itemOferta
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta
     *
     * @return Tramite
     */
    public function addItemOferta(\Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta)
    {
        $this->itemOferta[] = $itemOferta;

        return $this;
    }

    /**
     * Remove itemOferta
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta
     */
    public function removeItemOferta(\Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta)
    {
        $this->itemOferta->removeElement($itemOferta);
    }

    /**
     * Get itemOferta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemOferta()
    {
        return $this->itemOferta;
    }


    /**
     * Get oferta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOferta()
    {
        return $this->oferta;
    }

    /**
     * Set proceso
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $proceso
     * @return Tramite
     */
    public function setProceso(\Siarme\ExpedienteBundle\Entity\Tramite $proceso = null)
    {
        $this->proceso = $proceso;

        return $this;
    }

    /**
     * Get proceso
     *
     * @return \Siarme\ExpedienteBundle\Entity\Tramite 
     */
    public function getProceso()
    {
        return $this->proceso;
    }

    /**
     * Add itemSolicitado
     *
     * @param \Siarme\GeneralBundle\Entity\ItemSolicitado $itemSolicitado
     *
     * @return Tramite
     */
    public function addItemSolicitado(\Siarme\GeneralBundle\Entity\ItemSolicitado $itemSolicitado)
    {
        $this->itemSolicitado[] = $itemSolicitado;

        return $this;
    }

    /**
     * Remove itemSolicitado
     *
     * @param \Siarme\GeneralBundle\Entity\ItemSolicitado $itemSolicitado
     */
    public function removeItemSolicitado(\Siarme\GeneralBundle\Entity\ItemSolicitado $itemSolicitado)
    {
        $this->itemSolicitado->removeElement($itemSolicitado);
    }

    /**
     * Get itemSolicitado
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemSolicitado()
    {
        return $this->itemSolicitado;
    }
    
    /**
     * Get montoAutorizado de Solicitud de Provision
     *
     * @return string  
     */
    public function getMontoAutorizado()
    {
           $total = 0;
           $itemSolicitados =  $this->itemSolicitado;
           foreach ($itemSolicitados as $item) {
                $itemAcuerdo = $item->getItemAcuerdoMarco();
                if (is_object( $itemAcuerdo )) {
                    $total = $total + $item->getCantidadAutorizada() * $itemAcuerdo->getPrecio();
                }
           }
        return  $total;
    }
    
    /**
     * Obtiene el Slug del oferente
     *
     * @return string  
     */
    public function getOferenteSlug()
    {
        return  Util::getSlug(trim($this->oferente));
    }
    
    /**
     * Add planificacion
     *
     * @param \Siarme\ExpedienteBundle\Entity\Planificacion $planificacion
     *
     * @return Tramite
     */
    public function addPlanificacion(\Siarme\ExpedienteBundle\Entity\Planificacion $planificacion)
    {
        $this->planificacion[] = $planificacion;

        return $this;
    }

    /**
     * Remove planificacion
     *
     * @param \Siarme\ExpedienteBundle\Entity\Planificacion $planificacion
     */
    public function removePlanificacion(\Siarme\ExpedienteBundle\Entity\Planificacion $planificacion)
    {
        $this->planificacion->removeElement($planificacion);
    }

    /**
     * Get planificacion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanificacion()
    {
        return $this->planificacion;
    }
    
    /**
     * Get presupuestoPlanificado
     *
     * @return int  
     */
    public function getPresupuestoPlanificado()
    {
        $total = 0;
        $items = $this->planificacion;
        foreach ($items as $item) {
            $total = $total + $item->getImporte();
        }

        return  $total;
    }

    /**
     * Add itemProcesoAdjudicado
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado $itemProcesoAdjudicado
     *
     * @return Tramite
     */
    public function addItemProcesoAdjudicado(\Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado $itemProcesoAdjudicado)
    {
        $this->itemProcesoAdjudicado[] = $itemProcesoAdjudicado;

        return $this;
    }

    /**
     * Remove itemProcesoAdjudicado
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado $itemProcesoAdjudicado
     */
    public function removeItemProcesoAdjudicado(\Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado $itemProcesoAdjudicado)
    {
        $this->itemProcesoAdjudicado->removeElement($itemProcesoAdjudicado);
    }

    /**
     * Get itemProcesoAdjudicado
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemProcesoAdjudicado()
    {
        return $this->itemProcesoAdjudicado;
    }

    /**
     * Add itemOfertaAdjudicado
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado $itemOfertaAdjudicado
     *
     * @return Tramite
     */
    public function addItemOfertaAdjudicado(\Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado $itemOfertaAdjudicado)
    {
        $this->itemOfertaAdjudicado[] = $itemOfertaAdjudicado;

        return $this;
    }

    /**
     * Remove itemOfertaAdjudicado
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado $itemOfertaAdjudicado
     */
    public function removeItemOfertaAdjudicado(\Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado $itemOfertaAdjudicado)
    {
        $this->itemOfertaAdjudicado->removeElement($itemOfertaAdjudicado);
    }

    /**
     * Get itemOfertaAdjudicado
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemOfertaAdjudicado()
    {
        return $this->itemOfertaAdjudicado;
    }
    
}