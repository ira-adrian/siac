<?php

namespace Siarme\AusentismoBundle\Entity;

use Siarme\AusentismoBundle\Util\Util;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemOferta
 *
 * @ORM\Table(name="item_oferta")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\ItemOfertaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ItemOferta
{
    const TIPO_ENTIDAD = 'IOFE';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=15)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_especial", type="string", length=20, nullable= true)
     */
    private $codigoEspecial;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=50)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="item", type="text", nullable= true)
     */
    private $item;

    /**
     * @var string
     *
     * @ORM\Column(name="marca", type="string", length=30, nullable= true)
     */
    private $marca;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad_medida", type="string", length=20)
     */
    private $unidadMedida;

    /**
     * @var string
     *
     * @ORM\Column(name="calidad", type="string", length=30, nullable= true)
     */
    private $calidad;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer", nullable= true)
     */
    private $cantidad;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad_solicitada", type="integer", nullable= true)
     */
    private $cantidadSolicitada;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=15, scale=2, nullable= true)
     */
    private $precio;

    /**
     * @var int
     *
     * @ORM\Column(name="pf_precio", type="decimal", precision=6, scale=2)
     */
    private $pFPrecio;

    /**
     * @var int
     *
     * @ORM\Column(name="pf_calidad", type="integer")
     */
    private $pFCalidad;

    /**
     * @var int
     *
     * @ORM\Column(name="pf_plazo_entrega", type="decimal", precision=4, scale=2)
     */
    private $pFPlazoEntrega;

    /**
     * @var int
     *
     * @ORM\Column(name="pf_antecedente", type="integer")
     */
    private $pFAntecedente;

    /**
     * @var int
     *
     * @ORM\Column(name="pf_total", type="decimal", precision=6, scale=2)
     */
    private $pFTotal;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad_adjudicada", type="integer")
     */
    private $cantidadAdjudicada;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="es_parcial", type="boolean", nullable= true)
     */
    private $esParcial;

    /**
     * @var bool
     *
     * @ORM\Column(name="adjudicado", type="boolean", nullable= true)
     */
    private $adjudicado;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text", nullable= true)
     */
    private $texto;

   /**
     * @var string
     *
     * @ORM\Column(name="sistema", type="string", length=30)
     */
    private $sistema;

    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite",inversedBy="itemOfertas") 
     * @ORM\JoinColumn(name="proceso_id", referencedColumnName="id")
     * 
     */
    private $proceso;

   /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", inversedBy="itemOferta") 
     * @ORM\JoinColumn(name="oferta_id", referencedColumnName="id")
     * 
     */
    private $oferta;
    
    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\ItemProceso", inversedBy="itemOferta", cascade={"persist"}) 
     * @ORM\JoinColumn(name="item_proceso_id", referencedColumnName="id")
     * 
     */
    private $itemProceso;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado", mappedBy="itemOferta", cascade={"persist","remove"})
    * @ORM\JoinColumn(name="item_proceso_adjudicado_id", referencedColumnName="id")
    */
    private $itemProcesoAdjudicado;

   /*
   * ToString
   */
    public function __toString()
    {
         return   $this->getItem();
    }

    public function __construct()
    {
        $this->calidad = 0;
        $this->pFPrecio = 0;
        $this->pFCalidad = 0;
        $this->pFPlazoEntrega = 0;
        $this->pFAntecedente = 0;
        $this->fecha = new \DateTime();
        $this->cantidadAdjudicada = 0;   
        $this->adjudicado = false;  
        $this->estado = true;  
        $this->sistema = "COMPRAR"; 
        $this->esParcial = false;
        $this->itemProcesoAdjudicado = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setPFTotal() {
        if ($this->calidad == 0 or $this->calidad == 1 or $this->estado == false) {
            $this->pFTotal = 0;
            $this->pFPrecio= 0;  
            $this->pFCalidad = 0; 
            $this->pFAntecedente = 0;
            $this->pFPlazoEntrega = 0;
        } else {
             $this->pFTotal = $this->pFPrecio +  $this->pFCalidad + $this->pFAntecedente + $this->pFPlazoEntrega;
        }
    } 

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setCodigoBionexo() {
        $item = $this->item;
        if ($this->sistema == "BIONEXO") {
            $slug = Util::getSlug(strtoupper(Util::limpiarItem($item)));
            $this->codigo = strtoupper("B-".substr(sha1($slug), 0, 5));
        }
    }

    /**
     * Get total.
     *
     * @return integer
     */
    public function getPFTotal()
    {
        return $this->pFTotal;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return ItemPedido
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
     * Set numero.
     *
     * @param int $numero
     *
     * @return ItemOferta
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero.
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set tipo.
     *
     * @param string $tipo
     *
     * @return ItemOferta
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
     * Set codigoEspecial.
     *
     * @param string $codigoEspecial
     *
     * @return ItemOferta
     */
    public function setCodigoEspecial($codigoEspecial)
    {
        $this->codigoEspecial = $codigoEspecial;

        return $this;
    }

    /**
     * Get codigoEspecial.
     *
     * @return string
     */
    public function getCodigoEspecial()
    {
        return $this->codigoEspecial;
    }

    /**
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return ItemOferta
     */
    public function setCodigo($codigo)
    {

         $this->codigo = strtoupper($codigo);

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set cuit.
     *
     * @param string|null $cuit
     *
     * @return ItemOferta
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
     * Set item.
     *
     * @param string $item
     *
     * @return ItemOferta
     */
    public function setItem($item)
    {

        $this->item = strtoupper(Util::limpiarItem($item));

        return $this;
    }

    /**
     * Get item.
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set marca.
     *
     * @param string $marca
     *
     * @return ItemOferta
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca.
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set unidadMedida.
     *
     * @param string $unidadMedida
     *
     * @return ItemOferta
     */
    public function setUnidadMedida($unidadMedida)
    {
        $this->unidadMedida = $unidadMedida;

        return $this;
    }

    /**
     * Get unidadMedida.
     *
     * @return string
     */
    public function getUnidadMedida()
    {
        return $this->unidadMedida;
    }

    /**
     * Set calidad.
     *
     * @param string $calidad
     *
     * @return ItemOferta
     */
    public function setCalidad($calidad)
    {
        $this->calidad = $calidad;

        return $this;
    }

    /**
     * Get calidad.
     *
     * @return string
     */
    public function getCalidad()
    {
        return $this->calidad;
    }

    /**
     * Set cantidad.
     *
     * @param int $cantidad
     *
     * @return ItemOferta
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad.
     *
     * @return int
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cantidadSolicitada.
     *
     * @param int $cantidadSolicitada
     *
     * @return ItemOferta
     */
    public function setCantidadSolicitada($cantidadSolicitada)
    {
        $this->cantidadSolicitada = $cantidadSolicitada;

        return $this;
    }

    /**
     * Get cantidadSolicitada.
     *
     * @return int
     */
    public function getCantidadSolicitada()
    {
        return $this->cantidadSolicitada;
    }

    /**
     * Set precio.
     *
     * @param string $precio
     *
     * @return ItemOferta
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio.
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set pFPrecio.
     *
     * @param int $pFPrecio
     *
     * @return ItemOferta
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
     * Set pFCalidad.
     *
     * @param int $pFCalidad
     *
     * @return ItemOferta
     */
    public function setPFCalidad($pFCalidad)
    {
        $this->pFCalidad = $pFCalidad;

        return $this;
    }

    /**
     * Get pFCalidad.
     *
     * @return int
     */
    public function getPFCalidad()
    {
        return $this->pFCalidad;
    }

    /**
     * Set pFPlazoEntrega.
     *
     * @param int $pFPlazoEntrega
     *
     * @return ItemOferta
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
     * Set pFAntecedente.
     *
     * @param int $pFAntecedente
     *
     * @return ItemOferta
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
     * Set cantidadAdjudicada.
     *
     * @param int $cantidadAdjudicada
     *
     * @return ItemOferta
     */
    public function setCantidadAdjudicada($cantidadAdjudicada)
    {
        $this->cantidadAdjudicada = $cantidadAdjudicada;

        return $this;
    }

    /**
     * Get cantidadAdjudicada.
     *
     * @return int
     */
    public function getCantidadAdjudicada()
    {
        return $this->cantidadAdjudicada;
    }

    /**
     * Set estado.
     *
     * @param bool $estado
     *
     * @return ItemOferta
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado.
     *
     * @return bool
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set esParcial.
     *
     * @param boolean $esParcial
     *
     * @return ItemOferta
     */
    public function setEsParcial($esParcial)
    {
        $this->esParcial = $esParcial;

        return $this;
    }

    /**
     * Get esParcial.
     *
     * @return boolean
     */
    public function getEsParcial()
    {
        return $this->esParcial;
    }

    /**
     * Set adjudicado.
     *
     * @param bool $adjudicado
     *
     * @return ItemOferta
     */
    public function setAdjudicado($adjudicado)
    {
        $this->adjudicado = $adjudicado;

        return $this;
    }

    /**
     * Get adjudicado.
     *
     * @return bool
     */
    public function getAdjudicado()
    {
        return $this->adjudicado;
    }


    /**
     * Set texto.
     *
     * @param string $texto
     *
     * @return ItemOferta
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
     * Set sistema.
     *
     * @param string $sistema
     *
     * @return ItemPedido
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
     * Set proceso
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $proceso
     * @return ItemOferta
     */
    public function setProceso(\Siarme\ExpedienteBundle\Entity\Tramite $proceso = null)
    {
        $this->proceso = $proceso;
        $this->pFPrecio = 0;

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
     * Set oferta
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $oferta
     * @return ItemOferta
     */
    public function setOferta(\Siarme\ExpedienteBundle\Entity\Tramite $oferta = null)
    {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \Siarme\ExpedienteBundle\Entity\Tramite 
     */
    public function getOferta()
    {
        return $this->oferta;
    }

   /**
     * Set itemProceso
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProceso $itemProceso
     * @return ItemOferta
     */
    public function setItemProceso(\Siarme\AusentismoBundle\Entity\ItemProceso $itemProceso = null)
    {
        $this->itemProceso = $itemProceso;

        return $this;
    }

    /**
     * Get itemProceso
     *
     * @return \Siarme\AusentismoBundle\Entity\ItemProceso 
     */
    public function getItemProceso()
    {
        return $this->itemProceso;
    }

    /**
     * Add itemProcesoAdjudicado
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProcesoAdjudicado $itemProcesoAdjudicado
     *
     * @return ItemProcesoAdjudicado
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
     * Get total.
     *
     * @return string
     */
    public function getTotal()
    {
        return ($this->cantidad *  $this->precio);
    }

    /**
     * Get montoAdjudicado.
     *
     * @return string
     */
    public function getMontoAdjudicado()
    {
        $total = $this->cantidadAdjudicada  * $this->precio;
        return  $total;  //round($total,2);
    }
}