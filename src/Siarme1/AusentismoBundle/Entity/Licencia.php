<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Licencia
 *
 * @ORM\Table(name="licencia")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\LicenciaRepository")
 */
class Licencia
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_documento", type="datetime")
     */

    private $fechaDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="diagnostico", type="string", nullable= true ,length=255)
     */
    private $diagnostico;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_desde", type="datetime")
     */
    private $fechaDesde;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_hasta", type="datetime")
     */
    private $fechaHasta;

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias;
    
    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Articulo",inversedBy="licencia") 
     * @ORM\JoinColumn(name="articulo_id", referencedColumnName="id")
     * 
     */
    private $articulo;

    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Enfermedad", inversedBy="licencia") 
     * @ORM\JoinColumn(name="enfermedad_id", referencedColumnName="id")
     *
     */
    private $enfermedad;

    /** 
     * 
     * @ORM\OneToOne(targetEntity="Siarme\DocumentoBundle\Entity\Documento", inversedBy="licencia") 
     * @ORM\JoinColumn(name="documento_id", referencedColumnName="id")
     */
    private $documento;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Agente", inversedBy="licencia") 
     * @ORM\JoinColumn(name="agente_id", referencedColumnName="id")
     */
    private $agente;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;


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
     * Set fechaDocumento
     *
     * @param \DateTime $fechaDocumento
     * @return Licencia
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
     * Set diagnostico
     *
     * @param string $diagnostico
     * @return Licencia
     */
    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    /**
     * Get diagnostico
     *
     * @return string 
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     * @return Licencia
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime 
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;

    }


    /**
     * Set dias
     *
     * @param integer $dias
     * @return Agente
     */
    public function setDias($dias)
    {

        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer 
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     * @return Licencia
     */
    public function setFechaHasta($fechaHasta)
    {
 
            $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime 
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set articulo
     *
     * @param \Siarme\AusentismoBundle\Entity\Articulo $articulo
     * @return Licencia
     */
    public function setArticulo(\Siarme\AusentismoBundle\Entity\Articulo $articulo = null)
    {
        $this->articulo = $articulo;

        return $this;
    }

    /**
     * Get articulo
     *
     * @return \Siarme\AusentismoBundle\Entity\Articulo 
     */
    public function getArticulo()
    {
        return $this->articulo;
    }

    /**
     * Set enfermedad
     *
     * @param \Siarme\AusentismoBundle\Entity\Enfermedad $enfermedad
     * @return Licencia
     */
    public function setEnfermedad(\Siarme\AusentismoBundle\Entity\Enfermedad $enfermedad = null)
    {
        $this->enfermedad = $enfermedad;

        return $this;
    }

    /**
     * Get enfermedad
     *
     * @return \Siarme\AusentismoBundle\Entity\Enfermedad 
     */
    public function getEnfermedad()
    {
        return $this->enfermedad;
    }

    /**
     * Set documento
     *
     * @param \Siarme\DocumentoBundle\Entity\Documento $documento
     * @return Licencia
     */
    public function setDocumento(\Siarme\DocumentoBundle\Entity\Documento $documento = null)
    {
        $this->documento = $documento;

        return $this;
    }

    /**
     * Get documento
     *
     * @return \Siarme\DocumentoBundle\Entity\Documento 
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Set agente
     *
     * @param \Siarme\AusentismoBundle\Entity\Agente $agente
     * @return Licencia
     */
    public function setAgente(\Siarme\AusentismoBundle\Entity\Agente $agente = null)
    {
        $this->agente = $agente;

        return $this;
    }

    /**
     * Get agente
     *
     * @return \Siarme\AusentismoBundle\Entity\Agente 
     */
    public function getAgente()
    {
        return $this->agente;
    }

    public function __construct()
    {
        $this->fechaDocumento = new \DateTime();
        $this->dias = 1;
       // $this->fechaDesde = new \DateTime();

      
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

    public function __toString()
    {
        if ($this->getDias() > 1) {
            $dia= strval($this->getDias())." días";
        } else { $dia=strval($this->getDias())." día"; }
       
       if ($this->getArticulo()->getGrupoArticulo() != null ) {
            $grupoAticulo= $this->getArticulo()->getGrupoArticulo()->getGrupo();
        } else { $grupoAticulo= "00";}

       // return (string) $this->getArticulo()." dias: ".$this->getDias();
        return (string) $this->getArticulo()->getCodigoArticulo()."-".$grupoAticulo." | ".($this->getFechaDesde())->format('d-m-Y')." | ".$dia;

        // return (string) $this->getArticulo()->getCodigoArticulo()." - ".$this->getArticulo()->getGrupoArticulo()->getGrupo();
    }  
    
    /**
     * Get vigente
     *
     */
    public function getVigente()
    {
        $fechaHasta = ($this->fechaHasta)->format('Y-m-d');
        $hoy = (new \DateTime("now"))->format('Y-m-d');
        if ($hoy > $fechaHasta ) {
                return false;
        } else {
                return true;
        }
    }
}
