<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Security;

/**
 * Movimiento
 *
 * @ORM\Table(name="movimiento")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\MovimientoRepository")
 */
class Movimiento
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
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255,  nullable=true)
     */
    private $texto;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;
    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\DepartamentoRm", inversedBy="movimiento")
     * @ORM\JoinColumn(name="departamento_rm_id", referencedColumnName="id")
     */
    private $departamentoRm;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\UsuarioBundle\Entity\Usuario", inversedBy="movimiento") 
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Expediente", inversedBy="movimiento")
     * @ORM\JoinColumn(name="expediente_id", referencedColumnName="id")
     */
    private $expediente;

   /*
   * ToString
   */
    public function __toString()
    {
         return   (string) $this->getDepartamentoRm();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

   /**
   * Constructor
   */
    public function __construct()
    {
        $this->fecha = new \Datetime(); 
        $this->activo = true; 
    }
    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return Movimiento
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
     * Set texto.
     *
     * @param string $texto
     *
     * @return Movimiento
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
     * Set activo.
     *
     * @param bool $activo
     *
     * @return Movimiento
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo.
     *
     * @return bool
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Get pendiente.
     *
     * @return bool
     */
    public function getPendiente()
    {
        if ($this->getDepartamentoRm()== null)
        return $this->pendiente= true;
    }

    /**
     * Set usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     *
     * @return Movimiento
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
     * Set departamentoRm
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm
     * @return Movimiento
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
     * Set expediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\Expediente $expediente
     * @return Movimiento
     */
    public function setExpediente(\Siarme\ExpedienteBundle\Entity\Expediente $expediente = null)
    {
        $this->expediente = $expediente;

        return $this;
    }

    /**
     * Get expediente
     *
     * @return \Siarme\ExpedienteBundle\Entity\Expediente 
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    /**
     * Get activo.
     *
     * @return string
     */
    public function getEstado()
    { 
        if (is_null($this->departamentoRm)) {
             return $this->estado= '<span  class="label label-warning">Iniciado</span>';
        } elseif ($this->activo){
            return $this->estado= '<span  class="label label-primary">Activo</span>';
        } else{
             return $this->estado= '<span  class="label label-default">Enviado</span>';
        }
    }


}
