<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recordatorio
 *
 * @ORM\Table(name="recordatorio")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\RecordatorioRepository")
 */
class Recordatorio
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
     * @ORM\Column(name="recordatorio", type="string", length=30)
     */
    private $recordatorio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora", type="time",  nullable=true)
     */
    private $hora;

    /**
     * @var bool
     *
     * @ORM\Column(name="publico", type="boolean")
     */
    private $publico;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255,  nullable=true)
     */
    private $texto;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite",inversedBy="recordatorio") 
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     */
    private $tramite;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\UsuarioBundle\Entity\Usuario",inversedBy="recordatorio") 
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

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
         return  $this->recordatorio;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->publico=true;
        $this->fecha = new \DateTime(date('d-m-Y')); 
        $this->hora = new \Datetime('00:00:00'); 
    }

    /**
     * Set recordatorio.
     *
     * @param string $recordatorio
     *
     * @return Recordatorio
     */
    public function setRecordatorio($recordatorio)
    {
        $this->recordatorio = $recordatorio;

        return $this;
    }

    /**
     * Get recordatorio.
     *
     * @return string
     */
    public function getRecordatorio()
    {
        return $this->recordatorio;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return Recordatorio
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        $fecha = $this->fecha;
        $fecha->setTime($this->hora->format('H'),$this->hora->format('i'));
        return $fecha;
    }

    /**
     * Set hora.
     *
     * @param \DateTime $hora
     *
     * @return Recordatorio
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora.
     *
     * @return \DateTime
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set publico.
     *
     * @param bool $publico
     *
     * @return Recordatorio
     */
    public function setPublico($publico)
    {
        $this->publico = $publico;

        return $this;
    }

    /**
     * Get publico.
     *
     * @return bool
     */
    public function getPublico()
    {
        return $this->publico;
    }

    /**
     * Set texto.
     *
     * @param string $texto
     *
     * @return Recordatorio
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto.
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     *
     * @return Recordatorio
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
     * Set usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     *
     * @return Recordatorio
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
     * Get vigente.
     *
     * @return bool
     */
    public function getVigente()
    {
        $fecha=  (new \DateTime('now'))->format('Y-m-d');
        return $this->vigente= ($this->fecha->format('Y-m-d') >= $fecha);
    }

    /**
     * Get hoy.
     *
     * @return bool
     */
    public function getHoy()
    {
        $fecha=  (new \DateTime('now'))->format('Y-m-d');
        return $this->hoy= ($this->fecha->format('Y-m-d') == $fecha);
    }


}
