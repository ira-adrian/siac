<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tarea
 *
 * @ORM\Table(name="tarea")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\TareaRepository")
 */
class Tarea
{
    const TIPO_ENTIDAD = 'TAR';
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
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_realizada", type="date", nullable= true)
     */
    private $fechaRealizada;

    /**
     * @var bool
     *
     * @ORM\Column(name="realizada", type="boolean")
     */
    private $realizada;

    /**
     * @var bool
     *
     * @ORM\Column(name="destacar", type="boolean")
     */
    private $destacar;

    /**
     * @var string
     *
     * @ORM\Column(name="prioridad", type="string", length=25)
     */
    private $prioridad;

    /**
     * @var string
     *
     * @ORM\Column(name="es_colaborador", type="boolean")
     */
    private $esColaborador;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255,  nullable=true)
     */
    private $texto;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\UsuarioBundle\Entity\Usuario", inversedBy="tarea") 
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

     /** 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite",inversedBy="tarea") 
     * @ORM\JoinColumn(name="tramite_id", referencedColumnName="id")
     */
    private $tramite;


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
         return  (string) $this->getUsuario();
    }

   /**
   * Constructor
   */
    public function __construct()
    {
        $this->fecha = new \Datetime(); 
        $this->esColaborador = false;
        $this->realizada = false;
        $this->destacar = false;
        $this->prioridad = "Normal";
        // $this->texto = Tarea::TIPO_ENTIDAD;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return Tarea
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
        return $this->fecha;
    }


    /**
     * Set fechaAgenda.
     *
     * @param \DateTime $fechaAgenda
     *
     * @return Tarea
     */
    public function setFechaAgenda($fechaAgenda)
    {
        $this->fechaAgenda = $fechaAgenda;

        return $this;
    }

    /**
     * Get fechaAgenda.
     *
     * @return \DateTime
     */
    public function getFechaAgenda()
    {
        return $this->fechaAgenda;
    }


    /**
     * Set fechaRealizada.
     *
     * @param \DateTime $fechaRealizada
     *
     * @return Tarea
     */
    public function setFechaRealizada($fechaRealizada)
    {
        $this->fechaRealizada = $fechaRealizada;

        return $this;
    }

    /**
     * Get fechaRealizada.
     *
     * @return \DateTime
     */
    public function getFechaRealizada()
    {
        return $this->fechaRealizada;
    }

    /**
     * Set realizada.
     *
     * @param bool $realizada
     *
     * @return Tarea
     */
    public function setRealizada($realizada)
    {
        $this->realizada = $realizada;

        return $this;
    }

    /**
     * Get realizada.
     *
     * @return bool
     */
    public function getRealizada()
    {
        return $this->realizada;
    }
    /**
     * Set destacar.
     *
     * @param bool $destacar
     *
     * @return Tarea
     */
    public function setDestacar($destacar)
    {
        $this->destacar = $destacar;

        return $this;
    }

    /**
     * Get destacar.
     *
     * @return bool
     */
    public function getDestacar()
    {
        return $this->destacar;
    }
    /**
     * Set prioridad.
     *
     * @param string $prioridad
     *
     * @return Tarea
     */
    public function setPrioridad($prioridad)
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    /**
     * Get prioridad.
     *
     * @return string
     */
    public function getPrioridad()
    {
        return $this->prioridad;
    }

    /**
     * Set esColaborador.
     *
     * @param boolean $esColaborador
     *
     * @return TipoTarea
     */
    public function setEsColaborador($esColaborador)
    {
        $this->esColaborador = $esColaborador;

        return $this;
    }

    /**
     * Get esColaborador.
     *
     * @return boolean
     */
    public function getEsColaborador()
    {
        return $this->esColaborador;
    }

    /**
     * Set texto.
     *
     * @param string $texto
     *
     * @return Tarea
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
     * Get esResponsable.
     *
     * @return boolean
     */
    public function getEsResponsable()
    {
        return $this->esResponsable = !$this->esColaborador;
    }
    
    /**
     * Set usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     *
     * @return Tarea
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
     * Set tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     *
     * @return Tarea
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
     * Add recordatorio
     *
     * @param \Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio
     * @return Tarea
     */
    public function addRecordatorio(\Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio)
    {
        $this->recordatorio[] = $recordatorio;

        return $this;
    }


}
