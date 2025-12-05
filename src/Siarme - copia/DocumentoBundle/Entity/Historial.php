<?php

namespace Siarme\DocumentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Historial
 *
 * @ORM\Table(name="historial")
 * @ORM\Entity(repositoryClass="Siarme\DocumentoBundle\Repository\HistorialRepository")
 */
class Historial
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
     *
     * @ORM\ManyToOne(targetEntity="\Siarme\UsuarioBundle\Entity\Usuario")
     * 
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="accion", type="string", length=30)
     */
    private $accion;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
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

   public function __construct()
    {
        $this->fecha = new \Datetime();
    }

   /**
     *  ToString
     */
    public function __toString()
    {
       return  $this->getUsuario().' - Texto: '.$this->getTexto();
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
     * @return Historial
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
     * Set usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     *
     * @return Historial
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
     * Set accion.
     *
     * @param string $accion
     *
     * @return Historial
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion.
     *
     * @return string
     */
    public function getAccion()
    {
        return $this->accion;
    }


    /**
     * Set texto.
     *
     * @param string $texto
     *
     * @return Historial
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
     * @return Historial
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
     * Set tipoId.
     *
     * @param int $tipoId
     *
     * @return Historial
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;

        return $this;
    }

    /**
     * Get tipoId.
     *
     * @return int
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }
    
}
