<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TipoTramite
 *
 * @ORM\Table(name="tipo_tramite")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\TipoTramiteRepository")
 */
class TipoTramite
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
     * @ORM\Column(name="tipo_tramite", type="string", length=255)
     */
    private $tipoTramite;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=20,  unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", mappedBy="tipoTramite")
     */
    private $tramite;

    /**
     * @ORM\OneToMany(targetEntity="Siarme\DocumentoBundle\Entity\TipoDocumento", mappedBy="tipoTramite")
     */
    private $tipoDocumento;

    /**
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\EstadoTramite", mappedBy="tipoTramite")
     */
    private $estadoTramite;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\DepartamentoRm", inversedBy="tipoTramite")
     * @ORM\JoinColumn(name="departamento_rm_id", referencedColumnName="id")
     */
    private $departamentoRm;

    /**
     * @var string
     *
     * @ORM\Column(name="glyphicon", type="string", length=70)
     */
    private $glyphicon;
    
   /*
   * ToString
   */
    public function __toString()
    {
        return  $this->getTipoTramite();
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tramite = new ArrayCollection();
        $this->tipoDocumento = new ArrayCollection();
        $this->slug = "AG";
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
     * Set tipoTramite
     *
     * @param string $tipoTramite
     * @return TipoTramite
     */
    public function setTipoTramite($tipoTramite)
    {
        $this->tipoTramite = $tipoTramite;

        return $this;
    }

    /**
     * Get tipoTramite
     *
     * @return string 
     */
    public function getTipoTramite()
    {
        return $this->tipoTramite;
    }

    /**
     * Add tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     * @return TipoTramite
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
     * Get tramite
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTramite()
    {
        return  $this->tramite;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return TipoTramite
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
     * Add tipoDocumento
     *
     * @param \Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento
     * @return TipoTramite
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
        $this->tipoDocumento->removeElement($tipoDocumento);
    }

    /**
     * Get tipoDocumento
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTipoDocumento()
    {
        return  $this->tipoDocumento;
    }


    /**
     * Remove estadoTramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\EstadoTramite $estadoTramite
     */  
    public function addEstadoTramite(\Siarme\ExpedienteBundle\Entity\EstadoTramite $estadoTramite)
    {
        $this->estadoTramite[] = $estadoTramite;

        return $this;
    }

    /**
     * Remove estadoTramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\EstadoTramite $estadoTramite
     */
    public function removeEstadoTramite(\Siarme\ExpedienteBundle\Entity\EstadoTramite $estadoTramite)
    {
        $this->estadoTramite->removeElement($estadoTramite);
    }

    /**
     * Get estadoTramite
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstadoTramite()
    {
        return  $this->estadoTramite;
    }

    /**
     * Set departamentoRm
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm
     * @return TipoTramite
     */
    public function setDepartamentoRm(\Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm = null)
    {
        $this->departamentoRm = $departamentoRm;

        return $this;
    }

    /**
     * Get departamentoRm
     *
     * @return \Siarme\AusentismoBundle\Entity\DepartamentoRm 
     */
    public function getDepartamentoRm()
    {
        return $this->departamentoRm;
    }

    /**
     * Set glyphicon
     *
     * @param string $glyphicon
     *
     * @return TipoTramite
     */
    public function setGlyphicon($glyphicon)
    {
        $this->glyphicon = $glyphicon;

        return $this;
    }

    /**
     * Get glyphicon
     *
     * @return string
     */
    public function getGlyphicon()
    {
        return $this->glyphicon;
    }
}
