<?php

namespace Siarme\GeneralBundle\Entity;

use Siarme\AusentismoBundle\Util\Util;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemSolicitado
 *
 * @ORM\Table(name="item_solicitado")
 * @ORM\Entity(repositoryClass="Siarme\GeneralBundle\Repository\ItemSolicitadoRepository")
 * @UniqueEntity(fields = { "numero", "tramite" }, message="Ya existe el numero de orden (ID) del ítem")
 */
class ItemSolicitado
{
    const TIPO_ENTIDAD = "ITS";

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
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codigo", type="string", length=50, nullable=true)
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
     * @var string
     *
     * @ORM\Column(name="rubro", type="string", length=255)
     */
    private $rubro;

    /**
     * @var string
     *
     * @ORM\Column(name="unidadMedida", type="string", length=255)
     */
    private $unidadMedida;

    /**
     * @var string|null
     *
     * @ORM\Column(name="precio", type="decimal", precision=13, scale=2, nullable=true)
     */
    private $precio;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="decimal", precision=13, scale=2)
     */
    private $cantidad;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidadAutorizada", type="decimal", precision=13 )
     */
    private $cantidadAutorizada;

    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite",inversedBy="itemSolicitado") 
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     * 
     */
    private $tramite;

    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\GeneralBundle\Entity\ItemAcuerdoMarco", inversedBy="itemSolicitado") 
     * @ORM\JoinColumn(name="item_acuerdo_id", referencedColumnName="id")
     * 
     */
    private $itemAcuerdoMarco;

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
        $this->precio = 0; 
        $this->cantidad = 0; 
        $this->ipp = "0"; 
        $this->rubro = "-"; 
        $this->unidadMedida = "UNIDAD"; 
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
     * @return ItemSolicitado
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
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return ItemSolicitado
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigo.
     *
     * @param string|null $codigo
     *
     * @return ItemSolicitado
     */
    public function setCodigo($codigo = null)
    {
        $codigo =str_replace(' ', '', $codigo);
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string|null
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
     * @return ItemSolicitado
     */
    public function setIpp($ipp)
    {
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
     * @return ItemSolicitado
     */
    public function setItem($item)
    {
        $item = preg_replace("/,/", ", ", $item);
        $item = preg_replace("/:/", ": ", $item);
        $item = preg_replace("/[\r\n|\n|\r]+/", " ", $item);
        $item= str_replace(', 0', ',0', $item); 
        $item= str_replace(', 1', ',1', $item); 
        $item= str_replace(', 2', ',2', $item); 
        $item= str_replace(', 3', ',3', $item); 
        $item= str_replace(', 4', ',4', $item); 
        $item= str_replace(', 5', ',5', $item); 
        $item= str_replace(', 6', ',6', $item); 
        $item= str_replace(', 7', ',7', $item); 
        $item= str_replace(', 8', ',8', $item); 
        $item= str_replace(', 9', ',9', $item); 
        $item= str_replace('    ', ' ', $item); 
        $item= str_replace('   ', ' ', $item); 
        $item= str_replace('  ', ' ', $item); 
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
     * Set rubro.
     *
     * @param string $rubro
     *
     * @return ItemSolicitado
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
     * @return ItemSolicitado
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
     * @param string|null $precio
     *
     * @return ItemSolicitado
     */
    public function setPrecio($precio = null)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio.
     *
     * @return string|null
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set estado.
     *
     * @param bool $estado
     *
     * @return ItemSolicitado
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
     * Set cantidad.
     *
     * @param int $cantidad
     *
     * @return ItemSolicitado
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
     * Set tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     * @return ItemSolicitado
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
     * Set cantidadAutorizada.
     *
     * @param int $cantidadAutorizada
     *
     * @return ItemSolicitado
     */
    public function setCantidadAutorizada($cantidadAutorizada)
    {
        $this->cantidadAutorizada = $cantidadAutorizada;

        return $this;
    }

    /**
     * Get cantidadAutorizada.
     *
     * @return int
     */
    public function getCantidadAutorizada()
    {
        return $this->cantidadAutorizada;
    }
    

    /**
     * Set itemAcuerdoMarco
     *
     * @param \Siarme\GeneralBundle\Entity\ItemAcuerdoMarco $itemAcuerdoMarco
     * @return ItemSolicitado
     */
    public function setItemAcuerdoMarco(\Siarme\GeneralBundle\Entity\ItemAcuerdoMarco $itemAcuerdoMarco = null)
    {
        $this->itemAcuerdoMarco = $itemAcuerdoMarco;

        return $this;
    }

    /**
     * Get itemAcuerdoMarco
     *
     * @return \Siarme\GeneralBundle\Entity\ItemAcuerdoMarco 
     */
    public function getItemAcuerdoMarco()
    {
        return $this->itemAcuerdoMarco;
    }
}
