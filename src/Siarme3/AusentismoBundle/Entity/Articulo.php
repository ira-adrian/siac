<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articulo
 *
 * @ORM\Table(name="articulo")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\ArticuloRepository")
 */
class Articulo
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
     * @ORM\Column(name="codigoArticulo", type="string", length=5, unique=true)
     */
    private $codigoArticulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, unique=true)
     */
    private $descripcion;

    /**
     * @var dias
     * 
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Licencia", mappedBy="articulo") 
     */
    private $licencia;



    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\GrupoArticulo", inversedBy="articulo") 
     * @ORM\JoinColumn(name="grupo_articulo_id", referencedColumnName="id")
     */
    private $grupoArticulo;

    public function __toString()
    {
         return (string) $this->getDescripcion();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->licencia = new \Doctrine\Common\Collections\ArrayCollection();

    }

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
     * Set codigoArticulo
     *
     * @param string $codigoArticulo
     *
     * @return Articulo
     */
    public function setCodigoArticulo($codigoArticulo)
    {
        $this->codigoArticulo = $codigoArticulo;

        return $this;
    }

    /**
     * Get codigoArticulo
     *
     * @return string
     */
    public function getCodigoArticulo()
    {
        return $this->codigoArticulo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Articulo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     * @return Articulo
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer 
     */
    public function getDias()
    {
        return $this->dias;
    }
    /**
     * Add licencium
     *
     * @param \Siarme\AusentismoBundle\Entity\Licencia $licencium
     *
     * @return Articulo
     */
    public function addLicencium(\Siarme\AusentismoBundle\Entity\Licencia $licencium)
    {
        $this->licencia[] = $licencium;

        return $this;
    }

    /**
     * Remove licencium
     *
     * @param \Siarme\AusentismoBundle\Entity\Licencia $licencium
     */
    public function removeLicencium(\Siarme\AusentismoBundle\Entity\Licencia $licencium)
    {
        $this->licencia->removeElement($licencium);
    }

    /**
     * Get licencia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicencia()
    {
        return $this->licencia;
    }



    /**
     * Set grupoArticulo
     *
     * @param \Siarme\AusentismoBundle\Entity\GrupoArticulo $grupoArticulo
     *
     * @return Articulo
     */
    public function setGrupoArticulo(\Siarme\AusentismoBundle\Entity\GrupoArticulo $grupoArticulo = null)
    {
        $this->grupoArticulo = $grupoArticulo;

        return $this;
    }

    /**
     * Get grupoArticulo
     *
     * @return \Siarme\AusentismoBundle\Entity\GrupoArticulo
     */
    public function getGrupoArticulo()
    {
        return $this->grupoArticulo;
    }
}
