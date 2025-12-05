<?php

namespace Siarme\AusentismoBundle\Entity;

use Siarme\AusentismoBundle\Util\Util;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemPedido
 *
 * @ORM\Table(name="item_pedido")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\ItemPedidoRepository")
 */
class ItemPedido
{
    const TIPO_ENTIDAD = 'IPED';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="trimestre", type="string", length=30)
     */
    private $trimestre;

    /**
     * @var string
     *
     * @ORM\Column(name="sistema", type="string", length=30)
     */
    private $sistema;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=30)
     * @Assert\Length(min = 3, minMessage = "Verifique el código del item ")
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="ipp", type="string", length=10)
     */
    private $ipp;

    /**
     * @var string
     *
     * @ORM\Column(name="item", type="text")
     */
    private $item;

    /**
     * @var bool
     *
     * @ORM\Column(name="conDetalle", type="string", length=5, nullable= true)
     */
    private $conDetalle;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle", type="text", nullable= true)
     */
    private $detalle;

    /**
     * @var string
     *
     * @ORM\Column(name="rubro", type="string", length=255, nullable= true)
     */
    private $rubro;

    /**
     * @var string
     *
     * @ORM\Column(name="unidadMedida", type="string", length=50)
     */
    private $unidadMedida;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=15, scale=2, nullable= true)
     */
    private $precio;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="consolida", type="boolean")
     */
    private $consolida;

    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite",inversedBy="itemPedido") 
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     * 
     */
    private $tramite;

    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Organismo",inversedBy="itemPedido") 
     * @ORM\JoinColumn(name="organismo_id", referencedColumnName="id")
     * 
     */
    private $organismo;
    
    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\ItemProceso", inversedBy="itemPedido") 
     * @ORM\JoinColumn(name="item_proceso_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * 
     */
    private $itemProceso;
    
   /*
   * ToString
   */
    public function __toString()
    {
         return   $this->getItem();
    }

    public function __construct()
    {
        $this->fecha = new \DateTime();
        $this->estado = true; 
        $this->consolida = true; 
        $this->cantidad = 0; 
        $this->ipp = "0"; 
        $this->unidadMedida = "UNIDAD"; 
        $this->sistema = "COMPRAR"; 
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
     * Set numero.
     *
     * @param int $numero
     *
     * @return ItemPedido
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
     * Set trimestre.
     *
     * @param string $trimestre
     *
     * @return ItemPedido
     */
    public function setTrimestre($trimestre)
    {
        $this->trimestre = $trimestre;

        return $this;
    }

    /**
     * Get trimestre.
     *
     * @return string
     */
    public function getTrimestre()
    {
        return $this->trimestre;
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
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return ItemPedido
     */
    public function setCodigo($codigo)
    {   
        $codigo =str_replace(' ', '', $codigo);
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
     * Set ipp.
     *
     * @param string $ipp
     *
     * @return ItemPedido
     */
    public function setIpp($ipp)
    {
        $ipp =  str_replace(',', '', $ipp);
        $ipp =  str_replace(' ', '', $ipp);
        $this->ipp = str_replace('.', '', $ipp);

        return $this;
    }

    /**
     * Get ipp.
     *
     * @return string
     */
    public function getIpp()
    {
        return $this->ipp;
    }

    /**
     * Set item.
     *
     * @param string $item
     *
     * @return ItemPedido
     */
    public function setItem($item)
    {
        $this->item = strtoupper(Util::limpiarItem($item));

        if ($this->sistema == "BIONEXO") {
            $slug = Util::getSlug(strtoupper(Util::limpiarItem($item)));
            $this->codigo = strtoupper("B-".substr(sha1($slug), 0, 5));
        }
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
     * Set conDetalle.
     *
     * @param string $conDetalle
     *
     * @return ItemPedido
     */
    public function setConDetalle($conDetalle)
    {
        $this->conDetalle = $conDetalle;

        return $this;
    }

    /**
     * Get conDetalle.
     *
     * @return string
     */
    public function getConDetalle()
    {
        return $this->conDetalle;
    }

    /**
     * Set detalle.
     *
     * @param string $detalle
     *
     * @return ItemPedido
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle.
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set rubro.
     *
     * @param string $rubro
     *
     * @return ItemPedido
     */
    public function setRubro($rubro)
    {
        $this->rubro = $rubro;

        return $this;
    }

    /**
     * Get rubro.
     *
     * @return string
     */
    public function getRubro()
    {
        return $this->rubro;
    }
    
    /**
     * Set unidadMedida.
     *
     * @param string $unidadMedida
     *
     * @return ItemPedido
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
     * Set precio.
     *
     * @param string $precio
     *
     * @return ItemPedido
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
     * Set cantidad.
     *
     * @param int $cantidad
     *
     * @return ItemPedido
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
     * @param bool $estado
     *
     * @return ItemPedido
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
     * Set consolida.
     *
     * @param bool $consolida
     *
     * @return ItemPedido
     */
    public function setConsolida($consolida)
    {
        $this->consolida = $consolida;

        return $this;
    }

    /**
     * Get consolida.
     *
     * @return bool
     */
    public function getConsolida()
    {
        return $this->consolida;
    }

    /**
     * Set tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     * @return ItemPedido
     */
    public function setTramite(\Siarme\ExpedienteBundle\Entity\Tramite $tramite = null)
    {
        $this->tramite = $tramite;

        return $this;
    }

    /**
     * Get tramite
     *
     * @return \Siarme\ExpedienteBundle\Entity\Tramite 
     */
    public function getTramite()
    {
        return $this->tramite;
    }

    /**
     * Set organismo
     *
     * @param \Siarme\AusentismoBundle\Entity\Organismo $organismo
     * @return ItemPedido
     */
    public function setOrganismo(\Siarme\AusentismoBundle\Entity\Organismo $organismo = null)
    {
        $this->organismo = $organismo;

        return $this;
    }

    /**
     * Get organismo
     *
     * @return \Siarme\AusentismoBundle\Entity\Organismo 
     */
    public function getOrganismo()
    {
        return $this->organismo;
    }
    
    /**
     * Set itemProceso
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProceso $itemProceso
     * @return ItemPedido
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
     * Get total
     *
     * @return \Siarme\AusentismoBundle\Entity\Organismo 
     */
    public function getTotal()
    {
        return $this->total = $this->cantidad * $this->precio;
    }
}