<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Licencia
 *
 * @ORM\Table(name="licencia")
 * @ORM\Entity(repositoryClass="Siarme\DocumentoBundle\Repository\LicenciaRepository")
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
     * @ORM\Column(name="diagnostico", type="string", length=255)
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
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Articulo",inversedBy="licencia") 
     * @ORM\JoinColumn(name="articulo_id", referencedColumnName="id")
     */
    private $articulo;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Enfermedad", inversedBy="licencia") 
     * @ORM\JoinColumn(name="enfermedad_id", referencedColumnName="id")
     */
    private $enfermedad;

    /** 
     * @ORM\OneToOne(targetEntity="Siarme\DocumentoBundle\Entity\DocMedico", inversedBy="licencia") 
     * @ORM\JoinColumn(name="doc_medico_id", referencedColumnName="id")
     */
    private $docMedico;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Agente", inversedBy="licencia") 
     * @ORM\JoinColumn(name="agente_id", referencedColumnName="id")
     */
    private $agente;


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
     * Set docMedico
     *
     * @param \Siarme\DocumentoBundle\Entity\DocMedico $docMedico
     * @return Licencia
     */
    public function setDocMedico(\Siarme\DocumentoBundle\Entity\DocMedico $docMedico = null)
    {
        $this->docMedico = $docMedico;

        return $this;
    }

    /**
     * Get docMedico
     *
     * @return \Siarme\DocumentoBundle\Entity\DocMedico 
     */
    public function getDocMedico()
    {
        return $this->docMedico;
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
      
    }
    public function __toString()
    {
         return $this->getEnfermedad();
    }
  
}
