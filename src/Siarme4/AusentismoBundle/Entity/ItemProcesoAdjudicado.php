<?php

namespace Siarme\AusentismoBundle\Entity;

use Siarme\AusentismoBundle\Util\Util;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemProcesoAdjudicado
 *
 * @ORM\Table(name="item_proceso_adjudicado")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\ItemProcesoAdjudicadoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ItemProcesoAdjudicado
{
    const TIPO_ENTIDAD = 'IPROA';
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
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer", nullable= true)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=15, scale=2, nullable= true)
     */
    private $precio;

    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", inversedBy="itemProcesoAdjudicado") 
     * @ORM\JoinColumn(name="proceso_id", referencedColumnName="id")
     * 
     */
    private $proceso;

   /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", inversedBy="itemOfertaAdjudicado") 
     * @ORM\JoinColumn(name="oferta_id", referencedColumnName="id")
     * 
     */
    private $oferta;

    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\ItemProceso", inversedBy="itemProcesoAdjudicado", cascade={"persist"}) 
     * @ORM\JoinColumn(name="item_proceso_id", referencedColumnName="id")
     * 
     */
    private $itemProceso;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\ItemOferta", inversedBy="itemProcesoAdjudicado") 
     */
    private $itemOferta;
    
    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\ItemPedido", inversedBy="itemProcesoAdjudicado") 
     */
    private $itemPedido;

   /*
   * ToString
   */
    public function __toString()
    {
         return   $this->getNumero();
    }

    public function __construct()
    {
        $this->fecha = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setFechaModifica() {
            $this->fecha = new \DateTime();
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
     * @return ItemProcesoAdjudicado
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
     * Set codigoEspecial.
     *
     * @param string $codigoEspecial
     *
     * @return ItemProcesoAdjudicado
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
     * @return ItemProcesoAdjudicado
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
     * @return ItemProcesoAdjudicado
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
     * @return ItemProcesoAdjudicado
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
     * Set unidadMedida.
     *
     * @param string $unidadMedida
     *
     * @return ItemProcesoAdjudicado
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
     * Set cantidad.
     *
     * @param int $cantidad
     *
     * @return ItemProcesoAdjudicado
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
     * Set precio.
     *
     * @param string $precio
     *
     * @return ItemProcesoAdjudicado
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
     * Set estado.
     *
     * @param string $estado
     *
     * @return ItemProcesoAdjudicado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado.
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set texto.
     *
     * @param string $texto
     *
     * @return ItemProcesoAdjudicado
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
     * @return ItemProcesoAdjudicado
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
     * Set oferta
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $oferta
     * @return ItemProcesoAdjudicado
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
     * @return ItemProcesoAdjudicado
     */
    public function setItemProceso(\Siarme\AusentismoBundle\Entity\ItemProceso $itemProceso)
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
     * Set itemOferta
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta
     *
     * @return ItemProcesoAdjudicado
     */
    public function SetItemOferta(\Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta)
    {
        $this->itemOferta = $itemOferta;

        return $this;
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
     * Set itemPedido
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido
     *
     * @return ItemProcesoAdjudicado
     */
    public function SetItemPedido(\Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido)
    {
        $this->itemPedido = $itemPedido;

        return $this;
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
     * Get cantidadAdjudicada.
     *
     * @return string
     */
    public function getCantidadAdjudicada()
    { 
        $items = $this->itemOferta;
        $cantidad = 0;
        foreach ($items as $item) {
            if ($item->getAdjudicado() == true) {
                 $cantidad = $cantidad + $item->getCantidadAdjudicada();
            }
        }
        return $cantidad;
    }

    /**
     * Get montoAdjudicado.
     *
     * @return string
     */
    public function getMontoAdjudicado()
    {
        return $this->cantidad * $this->precio;
    }
    
    /**
     * Get adjudicado.
     *
     * @return string
     */
    public function getAdjudicado()
    {
        $items = $this->itemOferta;
        $estado = false;
        foreach ($items as $item) {
            if ($item->getAdjudicado() == true) {
                 $estado = true;
            }
        }
        return $estado;
    }
    
    /**
     * Get unItemPedido.
     *
     * @return string
     */
    public function getUnItemPedido()
    { 
        $items = $this->itemPedido;

        return  $items[0];
    }

    /**
     * Get cantidadPedida.
     *
     * @return string
     */
    public function getCantidadPedida()
    {
        $items = $this->itemPedido;
        $cantidad = 0;
        foreach ($items as $item) {
            $cantidad = $cantidad + $item->getCantidad();
        }
        return $cantidad;
    }
}