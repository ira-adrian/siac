<?php

namespace Siarme\DocumentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Documento
 *
 * @ORM\Table(name="documento")
 * @ORM\Entity(repositoryClass="Siarme\DocumentoBundle\Repository\DocumentoRepository")
 */
class Documento
{
    const TIPO_ENTIDAD = 'DOC';
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
     * @ORM\Column(name="nombre_documento", type="string", length=25)
     */
    private $nombreDocumento;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_documento", type="datetime")
     */
    private $fechaDocumento;

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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\UsuarioBundle\Entity\Usuario", inversedBy="documento") 
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\DocumentoBundle\Entity\TipoDocumento", inversedBy="documento") 
     * @ORM\JoinColumn(name="tipo_documento_id", referencedColumnName="id")
     */
    private $tipoDocumento;
    
    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     */
    private $texto;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;

    /**
     * @var bool
     *
     * @ORM\Column(name="papelera", type="boolean")
     */
    private $papelera;

    /**
     * @ORM\Column(name="archivo", type="string", nullable= true)
     *
     */
    private $archivo;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", inversedBy="documento") 
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     */
    private $tramite;

    /** 
     * @ORM\OneToOne(targetEntity="Siarme\AusentismoBundle\Entity\Licencia", mappedBy="documento", cascade={"persist","remove"}) 
     * 
     */
    private $licencia;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_archivo", type="string", length=255, nullable= true)
     */
    private $nombreArchivo;

    /**
     * @var string
     *
     * @ORM\Column(name="referencia", type="string", length=255, nullable= true)
     */
    private $referencia;


    /**
     *  ToString
     */
    public function __toString()
    {

        if ($this->getSlug() == "licencia" ) {
          return (string) "Lic. ".$this->getLicencia();
        } else {  
        return  $this->getNombreDocumento().' N°: '.$this->getNumero();
         }
       
    }

   public function __construct()
    {
        
        $this->papelera = false;
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
     * Set nombreDocumento
     *
     * @param string $nombreDocumento
     *
     * @return Documento
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
     * Set fechaDocumento
     *
     * @param \DateTime $fechaDocumento
     *
     * @return Documento
     */
    public function setFechaDocumento($fechaDocumento)
    {
        $this->fechaDocumento = $fechaDocumento;

        return $this;
    }

    /**
     * Get fechaDocumento
     *
     * @return \DateTime
     */
    public function getFechaDocumento()
    {
        return $this->fechaDocumento;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Documento
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

      /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return Documento
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Documento
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
     * Set texto
     *
     * @param string $texto
     *
     * @return Documento
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
     * Set estado
     *
     * @param boolean $estado
     * @return Expediente
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
     * Set papelera
     *
     * @param boolean $papelera
     * @return Expediente
     */
    public function setPapelera($papelera)
    {
        $this->papelera = $papelera;

        return $this;
    }

    /**
     * Get papelera
     *
     * @return boolean 
     */
    public function getPapelera()
    {
        return $this->papelera;
    }

    /**
     * Set usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     *
     * @return Documento
     */
    public function setUsuario(\Siarme\UsuarioBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Siarme\UsuarioBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set tipoDocumento
     *
     * @param \Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento
     *
     * @return Documento
     */
    public function setTipoDocumento(\Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento = null)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return \Siarme\DocumentoBundle\Entity\TipoDocumento
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set archivo
     *
     * @param string $archivo
     *
     * @return Documento
     */    
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

     /**
     * Get archivo
     *
     * @return string
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     *
     * @return Documento
     */
    public function setTramite(\Siarme\ExpedienteBundle\Entity\Tramite $tramite = null)
    {
        $this->tramite = $tramite;

        return $this;
    }

    /**
     * Get tramite
     *
     * @return \Siarme\ExpedienteBundle\Entity\Tramite
     */
    public function getTramite()
    {
        return $this->tramite;
    }

    /**
     * Set licencia
     *
     * @param \Siarme\AusentismoBundle\Entity\Licencia $licencia
     *
     * @return Documento
     */
    public function setLicencia(\Siarme\AusentismoBundle\Entity\Licencia $licencia = null)
    {
        $this->licencia = $licencia;

        return $this;
    }

    /**
     * Get licencia
     *
     * @return \Siarme\AusentismoBundle\Entity\Licencia
     */
    public function getLicencia()
    {
        return $this->licencia;
    }


    /**
     * Set nombreArchivo
     *
     * @param string $nombreArchivo
     *
     * @return Documento
     */
    public function setNombreArchivo($nombreArchivo)
    {
        $this->nombreArchivo = $nombreArchivo;
        return $this;
    }

    /**
     * Get nombreArchivo
     *
     * @return string
     */
    public function getNombreArchivo()
    {
        return $this->nombreArchivo;
    }
    /**
     * Set referencia
     *
     * @param string $referencia
     *
     * @return Documento
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
        return $this;
    }

    /**
     * Get referencia
     *
     * @return string
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

}
