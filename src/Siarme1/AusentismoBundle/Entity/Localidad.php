<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Localidad
 *
 * @ORM\Table(name="localidad")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\LocalidadRepository")
 */
class Localidad
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
     * @var integer
     *
     * @ORM\Column(name="codigo_postal", type="integer", nullable=true)
     */
    private $codigoPostal;

    /**
     * @var string
     *
     * @ORM\Column(name="localidad", type="string", length=255)
     */
    private $localidad;

    /** 
    * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Departamento", inversedBy="localidad") 
    * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")
    */
    private $departamento;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Organismo", mappedBy="localidad") 
     */
    private $organismo;

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
     * Set localidad
     *
     * @param string $localidad
     * @return Localidad
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    public function __toString()
    {
        return $this->getLocalidad()." ( ".$this->getDepartamento()." )";
    }

    /**
     * Set departamento
     *
     * @param \Siarme\AusentismoBundle\Entity\Departamento $departamento
     * @return Localidad
     */
    public function setDepartamento(\Siarme\AusentismoBundle\Entity\Departamento $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return \Siarme\AusentismoBundle\Entity\Departamento 
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set codigoPostal
     *
     * @param integer $codigoPostal
     *
     * @return Localidad
     */
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    /**
     * Get codigoPostal
     *
     * @return integer
     */
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organismo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add organismo
     *
     * @param \Siarme\AusentismoBundle\Entity\Organismo $organismo
     *
     * @return Localidad
     */
    public function addOrganismo(\Siarme\AusentismoBundle\Entity\Organismo $organismo)
    {
        $this->organismo[] = $organismo;

        return $this;
    }

    /**
     * Remove organismo
     *
     * @param \Siarme\AusentismoBundle\Entity\Organismo $organismo
     */
    public function removeOrganismo(\Siarme\AusentismoBundle\Entity\Organismo $organismo)
    {
        $this->organismo->removeElement($organismo);
    }

    /**
     * Get organismo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganismo()
    {
        return $this->organismo;
    }
}
