<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoRubro
 *
 * @ORM\Table(name="grupo_rubro")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\GrupoRubroRepository")
 */
class GrupoRubro
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
     * @ORM\Column(name="codigo", type="string", length=10)
     */
    private $codigo;
    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=255)
     */
    private $grupo;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Rubro", mappedBy="grupoRubro") 
     */
    private $rubro;

    /*
    * ToString
    */
    public function __toString()
    {
     return   $this->getGrupo();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rubro = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return grupoRubro
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
     * Set grupo.
     *
     * @param string $grupo
     *
     * @return GrupoRubro
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo.
     *
     * @return string
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Add rubro
     *
     * @param \Siarme\AusentismoBundle\Entity\Rubro $rubro
     *
     * @return GrupoRubro
     */
    public function addRubro(\Siarme\AusentismoBundle\Entity\Rubro $rubro)
    {
        $this->rubro[] = $rubro;

        return $this;
    }

    /**
     * Remove rubro
     *
     * @param \Siarme\AusentismoBundle\Entity\Rubro $rubro
     */
    public function removeRubro(\Siarme\AusentismoBundle\Entity\Rubro $rubro)
    {
        $this->rubro->removeElement($rubro);
    }

    /**
     * Get rubro
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRubro()
    {
        return $this->rubro;
    }

}
