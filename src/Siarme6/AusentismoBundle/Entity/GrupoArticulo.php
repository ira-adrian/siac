<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoArticulo
 *
 * @ORM\Table(name="grupo_articulo")
 * @ORM\Entity()
 */
class GrupoArticulo
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
     * @ORM\Column(name="grupo", type="string", length=3, unique=true)
     */
    private $grupo;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo_articulo", type="string", length=30, unique=true)
     */
    private $grupoArticulo;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Articulo", mappedBy="grupoArticulo") 
     */
    private $articulo;

  
    public function __toString()
    {
         return $this->getGrupo().' - '.$this->getGrupoArticulo();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articulo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set grupo
     *
     * @param string $grupo
     *
     * @return GrupoArticulo
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return string
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set grupoArticulo
     *
     * @param string $grupoArticulo
     *
     * @return GrupoArticulo
     */
    public function setGrupoArticulo($grupoArticulo)
    {
        $this->grupoArticulo = $grupoArticulo;

        return $this;
    }

    /**
     * Get grupoArticulo
     *
     * @return string
     */
    public function getGrupoArticulo()
    {
        return $this->grupoArticulo;
    }

    /**
     * Add articulo
     *
     * @param \Siarme\AusentismoBundle\Entity\Articulo $articulo
     *
     * @return GrupoArticulo
     */
    public function addArticulo(\Siarme\AusentismoBundle\Entity\Articulo $articulo)
    {
        $this->articulo[] = $articulo;

        return $this;
    }

    /**
     * Remove articulo
     *
     * @param \Siarme\AusentismoBundle\Entity\Articulo $articulo
     */
    public function removeArticulo(\Siarme\AusentismoBundle\Entity\Articulo $articulo)
    {
        $this->articulo->removeElement($articulo);
    }

    /**
     * Get articulo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticulo()
    {
        return $this->articulo;
    }
}
