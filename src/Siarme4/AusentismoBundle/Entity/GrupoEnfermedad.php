<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoEnfermedad
 *
 * @ORM\Table(name="grupo_enfermedad")
 * @ORM\Entity
 */
class GrupoEnfermedad
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
     * @ORM\Column(name="grupo", type="string", length=4)
     */
    private $Grupo;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo_enfermedad", type="string", length=150)
     */
    private $GrupoEnfermedad;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Enfermedad", mappedBy="grupoEnfermedad") 
     */
    private $enfermedad;

    /*
     * ToString
     */
    public function __toString()
    {
         return   $this->getMinisterio();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enfermedad = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return GrupoEnfermedad
     */
    public function setGrupo($grupo)
    {
        $this->Grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return string
     */
    public function getGrupo()
    {
        return $this->Grupo;
    }

    /**
     * Set grupoEnfermedad
     *
     * @param string $grupoEnfermedad
     *
     * @return GrupoEnfermedad
     */
    public function setGrupoEnfermedad($grupoEnfermedad)
    {
        $this->GrupoEnfermedad = $grupoEnfermedad;

        return $this;
    }

    /**
     * Get grupoEnfermedad
     *
     * @return string
     */
    public function getGrupoEnfermedad()
    {
        return $this->GrupoEnfermedad;
    }

    /**
     * Add enfermedad
     *
     * @param \Siarme\AusentismoBundle\Entity\Enfermedad $enfermedad
     *
     * @return GrupoEnfermedad
     */
    public function addEnfermedad(\Siarme\AusentismoBundle\Entity\Enfermedad $enfermedad)
    {
        $this->enfermedad[] = $enfermedad;

        return $this;
    }

    /**
     * Remove enfermedad
     *
     * @param \Siarme\AusentismoBundle\Entity\Enfermedad $enfermedad
     */
    public function removeEnfermedad(\Siarme\AusentismoBundle\Entity\Enfermedad $enfermedad)
    {
        $this->enfermedad->removeElement($enfermedad);
    }

    /**
     * Get enfermedad
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnfermedad()
    {
        return $this->enfermedad;
    }

}
