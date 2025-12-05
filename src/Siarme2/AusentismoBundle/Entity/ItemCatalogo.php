<?php

namespace Siarme\AusentismoBundle\Entity;

use Siarme\AusentismoBundle\Util\Util;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemCatalogo
 *
 * @ORM\Table(name="item_catalogo")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\ItemCatalogoRepository")
 */
class ItemCatalogo
{
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
     * @ORM\Column(name="codigo", type="string", length=255)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="rubro", type="text")
     */
    private $rubro;

    /**
     * @var string
     *
     * @ORM\Column(name="clase", type="text")
     */
    private $clase;

    /**
     * @var string
     *
     * @ORM\Column(name="item", type="text")
     */
    private $item;

    /**
     * @var string
     *
     * @ORM\Column(name="ipp", type="string", length=10)
     */
    private $ipp;

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
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return Item
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

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
     * Set rubro.
     *
     * @param text $rubro
     *
     * @return Item
     */
    public function setRubro($rubro)
    {
        $this->rubro = $rubro;

        return $this;
    }

    /**
     * Get rubro.
     *
     * @return text
     */
    public function getRubro()
    {
        return $this->rubro;
    }

    /**
     * Set clase.
     *
     * @param text $clase
     *
     * @return Item
     */
    public function setClase($clase)
    {
        $this->clase = $clase;

        return $this;
    }

    /**
     * Get clase.
     *
     * @return text
     */
    public function getClase()
    {
        return $this->clase;
    }

    /**
     * Set item.
     *
     * @param text $item
     *
     * @return Item
     */
    public function setItem($item)
    {
        $this->item = strtoupper(Util::limpiarItem($item));

        return $this;
    }

    /**
     * Get item.
     *
     * @return text
     */
    public function getItem()
    {
        return $this->item;
    }
    

    /**
     * Set ipp.
     *
     * @param string $ipp
     *
     * @return Item
     */
    public function setIpp($ipp)
    {
        $this->ipp = $ipp;

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
     *  ToString
     */
    public function __toString()
    {
         return $this->getItem();
    }
}
