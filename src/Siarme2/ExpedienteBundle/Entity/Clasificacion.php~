<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Clasificacion
 *
 * @ORM\Table(name="clasificacion")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\ClasificacionRepository")
 */
class Clasificacion
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
     * @ORM\Column(name="clasificacion", type="string", length=25, unique=true)
     */
    private $clasificacion;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Expediente", mappedBy="clasificacion") 
     */
    private $expediente;


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
     * Set clasificacion
     *
     * @param string $clasificacion
     * @return Clasificacion
     */
    public function setClasificacion($clasificacion)
    {
        $this->clasificacion = $clasificacion;

        return $this;
    }

    /**
     * Get clasificacion
     *
     * @return string 
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->expediente = new ArrayCollection();
    }

    /**
     * Add expediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\Expediente $expediente
     * @return Clasificacion
     */
    public function addExpediente(\Siarme\ExpedienteBundle\Entity\Expediente $expediente)
    {
        $this->expediente[] = $expediente;

        return $this;
    }

    /**
     * Remove expediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\Expediente $expediente
     */
    public function removeExpediente(\Siarme\ExpedienteBundle\Entity\Expediente $expediente)
    {
        $this->expediente->removeElement($expediente);
    }

    /**
     * Get expediente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    public function __toString()
    {
        return $this->getClasificacion();
    }    

}
