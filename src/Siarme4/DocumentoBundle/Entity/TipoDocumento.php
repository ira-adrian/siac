<?php

namespace Siarme\DocumentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoDocumento
 *
 * @ORM\Table(name="tipo_documento")
 * @ORM\Entity(repositoryClass="Siarme\DocumentoBundle\Repository\TipoDocumentoRepository")
 */
class TipoDocumento
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
     * @ORM\Column(name="slug", type="string", length=20)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_documento", type="string", length=255)
     */
    private $nombreDocumento;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

   /**
     * @var int
     *
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio;
    
    /** 
    * @ORM\ManyToOne(targetEntity="Siarme\UsuarioBundle\Entity\Role" , inversedBy="tipoDocumento") 
    * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
    */
    private $rol;


    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var bool
     *
     * @ORM\Column(name="es_archivo", type="boolean")
     */
    private $esArchivo;

    /**
     * @var bool
     *
     * @ORM\Column(name="membrete", type="boolean")
     */
    private $membrete;

    /**
     * @var string
     *
     * @ORM\Column(name="glyphicon", type="string", length=70)
     */
    private $glyphicon;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text", nullable= true)
     */
    private $texto;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\DocumentoBundle\Entity\Documento", mappedBy="tipoDocumento")
    */
    private $documento;

     /** 
     *@ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\TipoTramite", inversedBy="tipoDocumento")
     * @ORM\JoinColumn(name="tipo_tramite_id", referencedColumnName="id")
     */
    private $tipoTramite;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\EstadoTramite", inversedBy="tipoDocumento")
     * @ORM\JoinColumn(name="estado_tramite_id", referencedColumnName="id")
     */
    private $estadoTramite;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documento = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estadoTramite = new \Doctrine\Common\Collections\ArrayCollection();
        $this->visible = true;
        $this->membrete = true;
        $this->esArchivo = true;
        $anio= (new \DateTime('now'))->format('Y');
        $this->anio = $anio;

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
     * Set slug
     *
     * @param string $slug
     *
     * @return TipoDocumento
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
     * Set nombreDocumento
     *
     * @param string $nombreDocumento
     *
     * @return TipoDocumento
     */
    public function setNombreDocumento($nombreDocumento)
    {
        $this->nombreDocumento = $nombreDocumento;

        return $this;
    }

    /**
     * Get nombreDocumento
     *
     * @return string
     */
    public function getNombreDocumento()
    {
        return $this->nombreDocumento;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return TipoDocumento
     */
    public function setNumero($numero)
    {
        $anio= (new \DateTime('now'))->format('Y');
        if ($this->getAnio() == $anio) {
            $this->numero = $this->numero + 1; 
        } else{
             $this->anio = $anio;
            $this->numero = 1;
        } 
        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        $anio= (new \DateTime('now'))->format('Y');
        if ($this->getAnio() == $anio) {
            return $this->numero + 1;
        } else{
           return 1;
        }       
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TipoDocumento
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set rol
     *
     * @param \Siarme\UsuarioBundle\Entity\Role $rol
     *
     * @return TipoDocumento
     */
    public function setRol(\Siarme\UsuarioBundle\Entity\Role $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \Siarme\UsuarioBundle\Entity\Role
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return TipoDocumento
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set esArchivo
     *
     * @param boolean $esArchivo
     * @return TipoDocumento
     */
    public function setEsArchivo($esArchivo)
    {
        $this->esArchivo = $esArchivo;

        return $this;
    }

    /**
     * Get esArchivo
     *
     * @return boolean 
     */
    public function getEsArchivo()
    {
        return $this->esArchivo;
    }

  /**
     * Set membrete
     *
     * @param boolean $membrete
     * @return TipoDocumento
     */
    public function setMembrete($membrete)
    {
        $this->membrete = $membrete;

        return $this;
    }

    /**
     * Get membrete
     *
     * @return boolean 
     */
    public function getMembrete()
    {
        return $this->membrete;
    }

    /**
     * Set texto
     *
     * @param string $texto
     *
     * @return TipoDocumento
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Add documento
     *
     * @param \Siarme\DocumentoBundle\Entity\Documento $documento
     *
     * @return TipoDocumento
     */
    public function addDocumento(\Siarme\DocumentoBundle\Entity\Documento $documento)
    {
        $this->documento[] = $documento;

        return $this;
    }

    /**
     * Remove documento
     *
     * @param \Siarme\DocumentoBundle\Entity\Documento $documento
     */
    public function removeDocumento(\Siarme\DocumentoBundle\Entity\Documento $documento)
    {
        $this->documento->removeElement($documento);
    }

    /**
     * Get documento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Set glyphicon
     *
     * @param string $glyphicon
     *
     * @return TipoDocumento
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

    /**
     *  ToString
     */
    public function __toString()
    {
       return  $this->getRol()->getRoleName();
    }

    /**
     * Set tipoTramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoTramite $tipoTramite
     * @return TipoDocumento
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

    /**
     * Set estadoTramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoTramite $estadoTramite
     * @return TipoDocumento
     */
    public function setEstadoTramite(\Siarme\ExpedienteBundle\Entity\EstadoTramite $estadoTramite = null)
    {
        $this->estadoTramite = $estadoTramite;

        return $this;
    }

    /**
     * Get estadoTramite
     *
     * @return \Siarme\ExpedienteBundle\Entity\EstadoTramite 
     */
    public function getEstadoTramite()
    {
        return $this->estadoTramite;
    }


}
