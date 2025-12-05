<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evento
 *
 * @ORM\Table(name="evento")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\EventoRepository")
 */
class Evento
{
/**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $tipo;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $fechaInicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaFin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

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
     * Set titulo.
     *
     * @param string $titulo
     *
     * @return Evento
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo.
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set tipo.
     *
     * @param string $tipo
     *
     * @return Evento
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
     * Set fechaInicio.
     *
     * @param \DateTime $fechaInicio
     *
     * @return Evento
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio.
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin.
     *
     * @param \DateTime|null $fechaFin
     *
     * @return Evento
     */
    public function setFechaFin($fechaFin = null)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin.
     *
     * @return \DateTime|null
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return Evento
     */
    public function setDescripcion($descripcion = null)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string|null
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
}
