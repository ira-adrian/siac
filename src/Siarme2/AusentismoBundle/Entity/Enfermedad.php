<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enfermedad
 *
 * @ORM\Table(name="enfermedad")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\EnfermedadRepository")
 */
class Enfermedad
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
     * @ORM\Column(name="codigoEnfermedad", type="string", length=4, unique=true)
     */
    private $codigoEnfermedad;

    /**
     * @var string
     *
     * @ORM\Column(name="enfermedad", type="string", length=150)
     */
    private $enfermedad;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=2)
     */
    private $grupo;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Licencia", mappedBy="enfermedad") 
     */
    private $licencia;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\GrupoEnfermedad",inversedBy="enfermedad") 
     * @ORM\JoinColumn(name="grupo_enfermedad_id", referencedColumnName="id")
     */
    private $grupoEnfermedad;

     public function __toString()
    {
         return $this->getCodigoEnfermedad().' - '.$this->getEnfermedad();
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
     * Set codigoEnfermedad
     *
     * @param string $codigoEnfermedad
     *
     * @return Enfermedad
     */
    public function setCodigoEnfermedad($codigoEnfermedad)
    {
        $this->codigoEnfermedad = $codigoEnfermedad;

        return $this;
    }

    /**
     * Get codigoEnfermedad
     *
     * @return string
     */
    public function getCodigoEnfermedad()
    {
        return $this->codigoEnfermedad;
    }

    /**
     * Set enfermedad
     *
     * @param string $enfermedad
     *
     * @return Enfermedad
     */
    public function setEnfermedad($enfermedad)
    {
        $this->enfermedad = $enfermedad;

        return $this;
    }

    /**
     * Get enfermedad
     *
     * @return string
     */
    public function getEnfermedad()
    {
        return $this->enfermedad;
    }

    /**
     * Set grupo
     *
     * @param string $grupo
     *
     * @return Enfermedad
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
     * Add licencium
     *
     * @param \Siarme\AusentismoBundle\Entity\Licencia $licencium
     *
     * @return Enfermedad
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
     * Set grupoEnfermedad
     *
     * @param \Siarme\AusentismoBundle\Entity\GrupoEnfermedad $grupoEnfermedad
     *
     * @return Enfermedad
     */
    public function setGrupoEnfermedad(\Siarme\AusentismoBundle\Entity\GrupoEnfermedad $grupoEnfermedad = null)
    {
        $this->grupoEnfermedad = $grupoEnfermedad;

        return $this;
    }

    /**
     * Get grupoEnfermedad
     *
     * @return \Siarme\AusentismoBundle\Entity\GrupoEnfermedad
     */
    public function getGrupoEnfermedad()
    {
        return $this->grupoEnfermedad;
    }
}
