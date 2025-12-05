<?php

namespace Siarme\ExpedienteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoExpediente
 *
 * @ORM\Table(name="tipo_expediente")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\TipoExpedienteRepository")
 */
class TipoExpediente
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
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=25,  nullable= true)
     */
    private $slug;

    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\DepartamentoRm", inversedBy="tipoExpediente")
     * @ORM\JoinColumn(name="departamento_rm_id", referencedColumnName="id")
     */
    private $departamentoRm;

    /**
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Expediente", mappedBy="tipoExpediente")
     */
    private $expediente;

   /*
   * ToString
   */
    public function __toString()
    {
        return  $this->getTipo();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tramite = new ArrayCollection();
        $this->tipoDocumento = new ArrayCollection();
        $this->slug = "AG";
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
     * Set tipo.
     *
     * @param string $tipo
     *
     * @return Tipo
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return tipoExpediente
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set departamentoRm
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm
     * @return tipoExpediente
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
     * Add expediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\Expediente $expediente
     * @return TipoExpediente
     */
    public function addExpediente(\Siarme\ExpedienteBundle\Entity\Expediente $expediente)
    {
        $this->expediente[] = $expediente;

        return $this;
    }

    /**
     * Remove expediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\Expediente $expediente
     */
    public function removeExpediente(\Siarme\ExpedienteBundle\Entity\Expediente $expediente)
    {
        $this->expediente->removeElement($expediente);
    }

    /**
     * Get expediente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExpediente()
    {
        return  $this->expediente;
    }

}
