<?php

namespace Siarme\ExpedienteBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoTramite
 *
 * @ORM\Table(name="estado_tramite")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\EstadoTramiteRepository")
 */
class EstadoTramite
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
     * @ORM\Column(name="estado_tramite", type="string", length=50)
     */
    private $estadoTramite;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=50)
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=30)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;

    /**
     * @var int
     *
     * @ORM\Column(name="porcentaje", type="integer")
     */
    private $porcentaje;

    /**
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", mappedBy="estadoTramite")
     */
    private $tramite;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\DocumentoBundle\Entity\TipoDocumento", mappedBy="estadoTramite")
    */
    private $tipoDocumento;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\TipoTramite",inversedBy="estadoTramite") 
     * @ORM\JoinColumn(name="tipo_tramite_id", referencedColumnName="id")
     */
    private $tipoTramite;


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
         return  $this->getEstadoTramite();
    }

   /**
   * Constructor
   */
    public function __construct()
    {
        $this->tramite = new ArrayCollection();
        $this->tipoDocumento = new ArrayCollection();
    }

    /**
     * Set estadoTramite.
     *
     * @param string $estadoTramite
     *
     * @return EstadoTramite
     */
    public function setEstadoTramite($estadoTramite)
    {
        $this->estadoTramite = $estadoTramite;

        return $this;
    }

    /**
     * Get estadoTramite.
     *
     * @return string
     */
    public function getEstadoTramite()
    {
        return $this->estadoTramite;
    }

    /**
     * Set class.
     *
     * @param string $class
     *
     * @return EstadoTramite
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return EstadoTramite
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return EstadoTramite
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Add tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     * @return EstadoTramite
     */
    public function addTramite(\Siarme\ExpedienteBundle\Entity\Tramite $tramite)
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
     * Set porcentaje
     *
     * @param integer $porcentaje
     * @return EstadoTramite
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return integer 
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Get tramite
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTramite()
    {
        return  $this->tramite;
    }
    /**
     * Add tipoDocumento
     *
     * @param \Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento
     *
     * @return Tramite
     */
    public function addTipoDocumento(\Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento)
    {
        $this->tipoDocumento[] = $tipoDocumento;

        return $this;
    }

    /**
     * Remove tipoDocumento
     *
     * @param \Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento
     */
    public function removeTipoDocumento(\Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento)
    {
        $this->tipoDocumento->removeElement($TipoDocumento);
    }

    /**
     * Get tipoDocumento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set tipoTramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoTramite $tipoTramite
     * @return EstadoTramite
     */
    public function setTipoTramite(\Siarme\ExpedienteBundle\Entity\TipoTramite $tipoTramite = null)
    {
        $this->tipoTramite = $tipoTramite;

        return $this;
    }

    /**
     * Get tipoTramite
     *
     * @return \Siarme\ExpedienteBundle\Entity\TipoTramite 
     */
    public function getTipoTramite()
    {
        return $this->tipoTramite;
    }

}
