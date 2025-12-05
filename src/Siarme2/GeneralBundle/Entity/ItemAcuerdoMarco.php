<?php

namespace Siarme\GeneralBundle\Entity;

use Siarme\AusentismoBundle\Util\Util;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemAcuerdoMarco
 *
 * @ORM\Table(name="item_acuerdo_marco")
 * @ORM\Entity(repositoryClass="Siarme\GeneralBundle\Repository\ItemAcuerdoMarcoRepository")
 * @UniqueEntity(fields = { "numero", "expediente" }, message="Ya existe el numero de orden (ID) del ítem")
 */
class ItemAcuerdoMarco
{
	const TIPO_ENTIDAD = 'IAC';
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
	 * @ORM\Column(name="cantidad", type="integer")
	 */
	private $cantidad;

	/** 
	 * 
	 * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Expediente",inversedBy="itemAcuerdoMarco") 
	 * @ORM\JoinColumn(name="expediente_id", referencedColumnName="id")
	 * 
	 */
	private $expediente;

	/** 
	 *@ORM\OneToMany(targetEntity="Siarme\GeneralBundle\Entity\ItemSolicitado", mappedBy="itemAcuerdoMarco", cascade={"persist","remove"})
	*/
	private $itemSolicitado;

	/** 
	 * 
	 * @ORM\OneToOne(targetEntity="Siarme\AusentismoBundle\Entity\ItemProceso") 
	 * 
	 */
	private $itemProceso;

   /*
   * ToString
   */
	public function __toString()
	{
		 return   $this->getCodigo()." - ".$this->getItem();
	}

	public function __construct()
	{
		$this->itemSolicitado = new ArrayCollection();
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
	 * @return ItemAcuerdoMarco
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
	 * @return ItemAcuerdoMarco
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
	 * @return ItemAcuerdoMarco
	 */
	public function setCodigo($codigo = null)
	{
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
	 * @return ItemAcuerdoMarco
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
	 * @return ItemAcuerdoMarco
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
	 * Set rubro.
	 *
	 * @param string $rubro
	 *
	 * @return ItemAcuerdoMarco
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
	 * @return ItemAcuerdoMarco
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
	 * @return ItemAcuerdoMarco
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
	 * @return ItemAcuerdoMarco
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
	 * @return ItemAcuerdoMarco
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
	 * Set expediente
	 *
	 * @param \Siarme\ExpedienteBundle\Entity\Expediente $expediente
	 * @return ItemAcuerdoMarco
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
	 * Add itemSolicitado
	 *
	 * @param \Siarme\GeneralBundle\Entity\ItemSolicitado $itemSolicitado
	 *
	 * @return itemAcuerdoMarco
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
	 * Get cantidadAutorizada.
	 *
	 * @return string
	 */
	public function getCantidadAutorizada()
	{
		$total = 0;
		foreach ($this->getItemSolicitado() as $item) {
			if ($item->getTramite()->getEstado() ) {
				if ($item->getEstado() ) {
					$total = $total + $item->getCantidadAutorizada();
				}
					
			}
		}
		return $total;
	}

	/**
	 * Get cantidadAutorizada.
	 *
	 * @return string
	 */
	public function getCantidadSolicitada()
	{
		$total = 0;
		foreach ($this->getItemSolicitado() as $item) {
			if ( in_array($item->getTramite()->getEstadoTramite()->getSlug() , ['ingresado','rectificacion'] )) {
				if ($item->getEstado() ) {
					$total = $total + $item->getCantidadAutorizada();
				}	
			}
		}
		return $total;
	}

	/**
	 * Get cantidadRestante.
	 *
	 * @return string
	 */
	public function getCantidadRestante()
	{

		$total = $this->getCantidad() - $this->getCantidadAutorizada();

		return $total;
	}

    /**
     * Set itemProceso
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemProceso $itemProceso
     * @return ItemAcuerdoMarco
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

}
