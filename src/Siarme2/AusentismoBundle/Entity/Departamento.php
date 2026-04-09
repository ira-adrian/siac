<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departamento
 *
 * @ORM\Table(name="departamento")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\DepartamentoRepository")
 */
class Departamento
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
     * @ORM\Column(name="departamento", type="string", length=50)
     */
    private $departamento;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Localidad", mappedBy="departamento") 
     */
    private $localidad;

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
     * Set departamento
     *
     * @param string $departamento
     * @return Departamento
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return string 
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->localidad = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add localidad
     *
     * @param \Siarme\AusentismoBundle\Entity\Localidad $localidad
     * @return Departamento
     */
    public function addLocalidad(\Siarme\AusentismoBundle\Entity\Localidad $localidad)
    {
        $this->localidad[] = $localidad;

        return $this;
    }

    /**
     * Remove localidad
     *
     * @param \Siarme\AusentismoBundle\Entity\Localidad $localidad
     */
    public function removeLocalidad(\Siarme\AusentismoBundle\Entity\Localidad $localidad)
    {
        $this->localidad->removeElement($localidad);
    }

    /**
     * Get localidad
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    public function __toString()
    {
        return $this->getDepartamento();
    }
}
