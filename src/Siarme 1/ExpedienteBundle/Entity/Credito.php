<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Credito
 *
 * @ORM\Table(name="credito")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\CreditoRepository")
 */
class Credito
{
    const TIPO_ENTIDAD = 'CRE';
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
     * @ORM\Column(name="fechaRealizado", type="date")
     */
    private $fechaRealizado;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255)
     */
    private $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="monto", type="decimal", precision=12, scale=2)
     */
    private $monto;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\UsuarioBundle\Entity\Usuario", inversedBy="credito") 
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

     /** 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite",inversedBy="credito") 
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
         return  (string) $this->getTexto();
    }

   /**
   * Constructor
   */
    public function __construct()
    {
        $this->fecha = new \Datetime(); 
        $this->fechaRealizado = new \Datetime();
        $this->estado = false;
        $this->monto = 0;
        $this->prioridad = "Normal";
        // $this->texto = Tarea::TIPO_ENTIDAD;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return Credito
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
     * Set fechaRealizado.
     *
     * @param \DateTime $fechaRealizado
     *
     * @return Credito
     */
    public function setFechaRealizado($fechaRealizado)
    {
        $this->fechaRealizado = $fechaRealizado;

        return $this;
    }

    /**
     * Get fechaRealizado.
     *
     * @return \DateTime
     */
    public function getFechaRealizado()
    {
        return $this->fechaRealizado;
    }

    /**
     * Set texto.
     *
     * @param string $texto
     *
     * @return Credito
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
     * Set monto.
     *
     * @param string $monto
     *
     * @return Credito
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto.
     *
     * @return string
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set estado.
     *
     * @param bool $estado
     *
     * @return Credito
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado.
     *
     * @return bool
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     *
     * @return Credito
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
     * @return Credito
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

}
