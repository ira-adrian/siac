<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rubro
 *
 * @ORM\Table(name="rubro")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\RubroRepository")
 */
class Rubro
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
     * @ORM\Column(name="ipp", type="string", length=10)
     */
    private $ipp;

    /**
     * @var string
     *
     * @ORM\Column(name="rubro", type="string", length=255)
     */
    private $rubro;

    /**
     * @var string
     *
     * @ORM\Column(name="periodo", type="string", length=30)
     */
    private $periodo;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", mappedBy="rubro") 
     */
    private $tramite;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\GrupoRubro",inversedBy="rubro") 
     * @ORM\JoinColumn(name="grupo_rubro_id", referencedColumnName="id")
     */
    private $grupoRubro;

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
     return   (string) $this->getIpp()." - ".$this->getRubro();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tramite = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ipp = 0;
    }

    /**
     * Set ipp.
     *
     * @param string $ipp
     *
     * @return Rubro
     */
    public function setIpp($ipp)
    {
        $this->ipp = $ipp;

        return $this;
    }

    /**
     * Get ipp.
     *
     * @return string
     */
    public function getIpp()
    {
        return $this->ipp;
    }

    /**
     * Set rubro.
     *
     * @param string $rubro
     *
     * @return Rubro
     */
    public function setRubro($rubro)
    {
        $this->rubro = $rubro;

        return $this;
    }

    /**
     * Get rubro.
     *
     * @return string
     */
    public function getRubro()
    {
        return $this->rubro;
    }

    /**
     * Set periodo.
     *
     * @param string $periodo
     *
     * @return Rubro
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo.
     *
     * @return string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Add tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     *
     * @return Rubro
     */
    public function addTramite(\Siarme\ExpedienteBundle\Entity\Tramite $tramite)
    {
        $this->tramite[] = $tramite;

        return $this;
    }

    /**
     * Remove tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     */
    public function removeTramite(\Siarme\ExpedienteBundle\Entity\Tramite $tramite)
    {
        $this->tramite->removeElement($tramite);
    }

    /**
     * Get tramite
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTramite()
    {
        return $this->tramite;
    }

    /**
     * Set grupoRubro
     *
     * @param \Siarme\AusentismoBundle\Entity\GrupoRubro $grupoRubro
     *
     * @return Rubro
     */
    public function setGrupoRubro(\Siarme\AusentismoBundle\Entity\GrupoRubro $grupoRubro = null)
    {
        $this->grupoRubro = $grupoRubro;

        return $this;
    }

    /**
     * Get grupoRubro
     *
     * @return \Siarme\AusentismoBundle\Entity\GrupoRubro
     */
    public function getGrupoRubro()
    {
        return $this->grupoRubro;
    }
}
