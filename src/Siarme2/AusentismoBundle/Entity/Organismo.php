<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Organismo
 *
 * @ORM\Table(name="organismo")
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\OrganismoRepository")
 *
 */
class Organismo
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
     * @ORM\Column(name="organismo", type="string", length=255)
     */
    private $organismo;

     /**
     * @var string
     *
     * @ORM\Column(name="clasificacion", type="string", length=255, nullable=true)
     */
    private $clasificacion;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Ministerio",inversedBy="organismo") 
     * @ORM\JoinColumn(name="ministerio_id", referencedColumnName="id")
     */
    private $ministerio;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Saf",inversedBy="organismo") 
     * @ORM\JoinColumn(name="saf_id", referencedColumnName="id")
     */
    private $saf;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Secretaria",inversedBy="organismo") 
     * @ORM\JoinColumn(name="secretaria_id", referencedColumnName="id")
     */
    private $secretaria;

    /** 
    * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Localidad", inversedBy="organismo") 
    * @ORM\JoinColumn(name="localidad_id", referencedColumnName="id")
    */
    private $localidad;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_gde", type="string", length=20, nullable=true)
     */
    private $codigoGde;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;
    
    /**
     * One Organismo has Many Cargo.
     * @ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Cargo", mappedBy="organismo")
     */
    private $cargo;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\DepartamentoRm", mappedBy="organismo") 
     */
    private $departamentoRm;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", mappedBy="organismoOrigen")
    */
    private $tramite;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\ItemPedido", mappedBy="organismo")
    */
    private $itemPedido;

    /*
    * ToString
    */
    public function __toString()
    {
     return (string) "SAF ".$this->getSaf()." - ".$this->getOrganismo();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
         $this->cargo = new ArrayCollection();
         $this->tramite = new ArrayCollection();
         $this->itemPedido = new ArrayCollection();
         $this->departamentoRm = new ArrayCollection();
         $this->clasificacion = "GENERAL";
    }
    
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
     * Set organismo
     *
     * @param string $organismo
     *
     * @return Organismo
     */
    public function setOrganismo($organismo)
    {
        $this->organismo = $organismo;

        return $this;
    }

    /**
     * Get organismo
     *
     * @return string
     */
    public function getOrganismo()
    {
        return $this->organismo;
    }


    /**
     * Set clasificacion
     *
     * @param string $clasificacion
     *
     * @return Organismo
     */
    public function setClasificacion($clasificacion)
    {
        $this->clasificacion = $clasificacion;

        return $this;
    }

    /**
     * Get clasificacion
     *
     * @return string
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * Set ministerio
     *
     * @param \Siarme\AusentismoBundle\Entity\Ministerio $ministerio
     *
     * @return Organismo
     */
    public function setMinisterio(\Siarme\AusentismoBundle\Entity\Ministerio $ministerio = null)
    {
        $this->ministerio = $ministerio;

        return $this;
    }

    /**
     * Get ministerio
     *
     * @return \Siarme\AusentismoBundle\Entity\Ministerio
     */
    public function getMinisterio()
    {
        return $this->ministerio;
    }

    /**
     * Set saf
     *
     * @param \Siarme\AusentismoBundle\Entity\Saf $saf
     *
     * @return Organismo
     */
    public function setSaf(\Siarme\AusentismoBundle\Entity\Saf $saf = null)
    {
        $this->saf = $saf;

        return $this;
    }

    /**
     * Get saf
     *
     * @return \Siarme\AusentismoBundle\Entity\Saf
     */
    public function getSaf()
    {
        return $this->saf;
    }

    /**
     * Set secretaria
     *
     * @param \Siarme\AusentismoBundle\Entity\Secretaria $secretaria
     *
     * @return Organismo
     */
    public function setSecretaria(\Siarme\AusentismoBundle\Entity\Secretaria $secretaria = null)
    {
        $this->secretaria = $secretaria;

        return $this;
    }

    /**
     * Get secretaria
     *
     * @return \Siarme\AusentismoBundle\Entity\Secretaria
     */
    public function getSecretaria()
    {
        return $this->secretaria;
    }
    /**
     * Set localidad
     *
     * @param \Siarme\AusentismoBundle\Entity\Localidad $localidad
     *
     * @return Organismo
     */
    public function setLocalidad(\Siarme\AusentismoBundle\Entity\Localidad $localidad = null)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return \Siarme\AusentismoBundle\Entity\Localidad
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set codigoGde
     *
     * @param string $codigoGde
     *
     * @return Organismo
     */
    public function setCodigoGde($codigoGde)
    {
        $this->codigoGde = $codigoGde;

        return $this;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Expediente
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Get codigoGde
     *
     * @return string
     */
    public function getCodigoGde()
    {
        return $this->codigoGde;
    }

    /**
     * Add cargo.
     *
     * @param \Siarme\AusentismoBundle\Entity\Cargo $cargo
     *
     * @return Organismo
     */
    public function addCargo(\Siarme\AusentismoBundle\Entity\Cargo $cargo)
    {
        $this->cargo[] = $cargo;

        return $this;
    }

    /**
     * Remove cargo.
     *
     * @param \Siarme\AusentismoBundle\Entity\Cargo $cargo
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCargo(\Siarme\AusentismoBundle\Entity\Cargo $cargo)
    {
        return $this->cargo->removeElement($cargo);
    }

    /**
     * Get cargo.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Add tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     * @return Organismo
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
        return  $this->tramite;
    }

    /**
     * Add departamentoRm
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm
     *
     * @return Organismo
     */
    public function addDepartamentoRm(\Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm)
    {
        $this->departamentoRm[] = $departamentoRm;

        return $this;
    }

    /**
     * Remove departamentoRm
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm
     */
    public function removeDepartamentoRm(\Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm)
    {
        $this->departamentoRm->removeElement($departamentoRm);
    }

    /**
     * Get departamentoRm
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartamentoRm()
    {
        return $this->departamentoRm;
    }

    /**
     * Add itemPedido
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido
     *
     * @return Oganismo
     */
    public function addItemPedido(\Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido)
    {
        $this->itemPedido[] = $itemPedido;

        return $this;
    }

    /**
     * Remove itemPedido
     *
     * @param \Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido
     */
    public function removeItemPedido(\Siarme\AusentismoBundle\Entity\ItemPedido $itemPedido)
    {
        $this->itemPedido->removeElement($itemPedido);
    }

    /**
     * Get itemPedido
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemPedido()
    {
        return $this->itemPedido;
    }

}
