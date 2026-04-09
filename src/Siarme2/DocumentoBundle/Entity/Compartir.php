<?php

namespace Siarme\DocumentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Compartir
 *
 * @ORM\Table(name="compartir")
 * @ORM\Entity(repositoryClass="Siarme\DocumentoBundle\Repository\CompartirRepository")
 */
class Compartir
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
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

        /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_visto", type="datetime")
     */
    private $fechaVisto;

    /**
     *
     * @ORM\ManyToOne(targetEntity="\Siarme\UsuarioBundle\Entity\Usuario")
     * 
     */
    private $usuarioEnvia;

    /**
     *
     * @ORM\ManyToOne(targetEntity="\Siarme\UsuarioBundle\Entity\Usuario")
     * 
     */
    private $usuarioRecive;


    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="string", length=255)
     */
    private $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var int
     *
     * @ORM\Column(name="tipo_id", type="integer")
     */
    private $tipoId;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;


   public function __construct()
    {
        $this->estado = false;
        $this->fechaVisto = new \Datetime(Date('d-m-Y'));
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
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return Compartir
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
     * Set fechaVisto.
     *
     * @param \DateTime $fechaVisto
     *
     * @return Compartir
     */
    public function setFechaVisto($fechaVisto)
    {
        $this->fechaVisto = $fechaVisto;

        return $this;
    }

    /**
     * Get fechaVisto.
     *
     * @return \DateTime
     */
    public function getFechaVisto()
    {
        return $this->fechaVisto;
    }

    /**
     * Set texto.
     *
     * @param string $texto
     *
     * @return Compartir
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
     * Set tipo.
     *
     * @param string $tipo
     *
     * @return Compartir
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set tipoId
     *
     * @param integer $tipoId
     *
     * @return Compartir
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;

        return $this;
    }

    /**
     * Get tipoId
     *
     * @return integer
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * Set usuarioEnvia
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuarioEnvia
     *
     * @return Compartir
     */
    public function setUsuarioEnvia(\Siarme\UsuarioBundle\Entity\Usuario $usuarioEnvia = null)
    {
        $this->usuarioEnvia = $usuarioEnvia;

        return $this;
    }

    /**
     * Get usuarioEnvia
     *
     * @return \Siarme\UsuarioBundle\Entity\Usuario
     */
    public function getUsuarioEnvia()
    {
        return $this->usuarioEnvia;
    }

        /**
     * Set usuarioRecive
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuarioRecive
     *
     * @return Compartir
     */
    public function setUsuarioRecive(\Siarme\UsuarioBundle\Entity\Usuario $usuarioRecive = null)
    {
        $this->usuarioRecive = $usuarioRecive;

        return $this;
    }

    /**
     * Get usuarioRecive
     *
     * @return \Siarme\UsuarioBundle\Entity\Usuario
     */
    public function getUsuarioRecive()
    {
        return $this->usuarioRecive;
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

}
