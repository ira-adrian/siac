<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Siarme\AusentismoBundle\Util\Util;

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
     * @ORM\Column(name="slug", type="string", length=25, unique=true)
     */
    private $slug;
    /**
     * @var string
     *
     * @ORM\Column(name="clasificacion", type="string", length=25, unique=true)
     */
    private $clasificacion;



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
     * Set slug
     *
     * @param string $slug
     * @return Slug
     */
    public function setSlug($slug)
    {
       
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
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
        $this->slug = Util::getSlug($clasificacion);
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

    }


    public function __toString()
    {
        return (string) $this->getClasificacion();
    }    

}
