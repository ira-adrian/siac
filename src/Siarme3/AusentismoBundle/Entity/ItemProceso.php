<?php

namespace Siarme\AusentismoBundle\Entity;

use Siarme\AusentismoBundle\Util\Util;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemProceso
 *
 * @ORM\Table(name="item_proceso")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\ItemProcesoRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ItemProceso
{
    const TIPO_ENTIDAD = 'IPRO';
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
     * @ORM\Column(name="item", type="string", length=255, nullable= true)
     */
    private $item;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad_medida", type="string", length=20)
     */
    private $unidadMedida;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer", nullable= true)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=15)
     */
    private $estado;

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
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite",inversedBy="itemProceso") 
     * @ORM\JoinColumn(name="proceso_id", referencedColumnName="id")
     * 
     */
    private $proceso;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemOferta", mappedBy="itemProceso", cascade={"persist","remove"}) 
     */
    private $itemOferta;
    
    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemPedido", mappedBy="itemProceso") 
     */
    private $itemPedido;

   /*
   * ToString
   */
    public function __toString()
    {
         return   $this->getItem();
    }

    public function __construct()
    {
        $this->itemOferta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->itemPedido = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fecha = new \DateTime();
        $this->estado = true;  
        $this->sistema = "COMPRAR"; 
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
     * @return ItemProceso
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
     * @return ItemProceso
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
     * @return ItemProceso
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
     * @return ItemProceso
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
     * @return ItemProceso
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
     * @return ItemProceso
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
     * @return ItemProceso
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
     * Set estado.
     *
     * @param string $estado
     *
     * @return ItemProceso
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
     * @return ItemProceso
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
     * @return ItemProceso
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
     * Add itemOferta
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemOferta $itemOferta
     *
     * @return ItemProceso
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
     * Add itemPedido
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido
     *
     * @return ItemProceso
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
        $items = $this->itemOferta;
        $monto = 0;
        foreach ($items as $item) {
            if ($item->getAdjudicado() == true) {
                 $monto1 = $item->getCantidadAdjudicada() * $item->getPrecio();
                 $monto = $monto + $monto1;
            }
        }
        return $monto;
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