<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TipoProceso
 *
 * @ORM\Table(name="tipo_proceso")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\TipoProcesoRepository")
 */
class TipoProceso
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
     * @ORM\Column(name="tipo_proceso", type="string", length=255)
     */
    private $tipoProceso;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=20)
     */
    private $codigo;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", mappedBy="tipoProceso")
     */
    private $tramite;

  /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\MarcoLegal", mappedBy="tipoProceso")
     */
    private $marcoLegal;

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
         return   $this->getTipoProceso();
    }
    
   /**
   * Constructor
   */
    public function __construct()
    {
        $this->tramite = new ArrayCollection();
        $this->marcoLegal = new ArrayCollection();
    }

    /**
     * Set tipoProceso.
     *
     * @param string $tipoProceso
     *
     * @return TipoProceso
     */
    public function setTipoProceso($tipoProceso)
    {
        $this->tipoProceso = $tipoProceso;

        return $this;
    }

    /**
     * Get tipoProceso.
     *
     * @return string
     */
    public function getTipoProceso()
    {
        return $this->tipoProceso;
    }

    /**
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return TipoProceso
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
     * Add tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     * @return TipoProceso
     */
    public function addTramite(\Siarme\ExpedienteBundle\Entity\Tramite $tramite )
    {
        $this->tramite[] = $tramite;

        return $this;
    }

    /**
     * Remove tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     */
    public function removeTramite(\Siarme\ExpedienteBundle\Entity\Tramite $tramite)
    {
        $this->tramite->removeElement($tramite);
    }

    /**
     * Get tramite
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTramite()
    {
        return $this->tramite;
    }

    /**
     * Add marcoLegal
     *
     * @param \Siarme\ExpedienteBundle\Entity\MarcoLegal $marcoLegal
     * @return TipoProceso
     */
    public function addMarcoLegal(\Siarme\ExpedienteBundle\Entity\MarcoLegal $marcoLegal )
    {
        $this->marcoLegal[] = $marcoLegal;

        return $this;
    }

    /**
     * Remove marcoLegal
     *
     * @param \Siarme\ExpedienteBundle\Entity\MarcoLegal $marcoLegal
     */
    public function removeMarcoLegal(\Siarme\ExpedienteBundle\Entity\MarcoLegal $marcoLegal)
    {
        $this->marcoLegal->removeElement($marcoLegal);
    }

    /**
     * Get marcoLegal
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMarcoLegal()
    {
        return $this->marcoLegal;
    }
}
