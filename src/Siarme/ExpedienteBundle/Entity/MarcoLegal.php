<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MarcoLegal
 *
 * @ORM\Table(name="marco_legal")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\MarcoLegalRepository")
 */
class MarcoLegal
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
     * @ORM\Column(name="marco_legal", type="string", length=255)
     */
    private $marcoLegal;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\TipoProceso", inversedBy="marcoLegal") 
     *
     */
    private $tipoProceso;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /*
   * ToString
   */
    public function __toString()
    {
         return   $this->getMarcoLegal();
    }

    /**
     * Set marcoLegal.
     *
     * @param string $marcoLegal
     *
     * @return MarcoLegal
     */
    public function setMarcoLegal($marcoLegal)
    {
        $this->marcoLegal = $marcoLegal;

        return $this;
    }

    /**
     * Get marcoLegal.
     *
     * @return string
     */
    public function getMarcoLegal()
    {
        return $this->marcoLegal;
    }


    /**
     * Set tipoProceso
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoProceso $tipoProceso
     * @return MarcoLegal
     */
    public function setTipoProceso(\Siarme\ExpedienteBundle\Entity\TipoProceso $tipoProceso = null)
    {
        $this->tipoProceso = $tipoProceso;

        return $this;
    }

    /**
     * Get tipoProceso
     *
     * @return \Siarme\ExpedienteBundle\Entity\TipoProceso 
     */
    public function getTipoProceso()
    {
        return $this->tipoProceso;
    }
}
