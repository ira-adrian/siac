<?php

namespace Siarme\AusentismoBundle\Entity;

use Siarme\AusentismoBundle\Util\Util;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedor
 *
 * @ORM\Table(name="proveedor")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\ProveedorRepository")
 * @DoctrineAssert\UniqueEntity("cuit", message="El CUIT ya Existe")
 */
class Proveedor
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
     * @ORM\Column(name="cuit", type="string", nullable= false, length=11)
     * @Assert\Length(min = 3, minMessage = "El CUIL debe tener 11 números")
     */
    private $cuit;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroEnte", type="string", length=2, nullable=true)
     */
    private $numeroEnte;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor", type="string", length=255)
     */
    private $proveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=50, nullable=true)
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInscribe", type="date", nullable=true)
     */
    private $fechaInscribe;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=50, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="clase", type="string", length=255, nullable=true)
     */
    private $clase;

    /**
     * @var string
     *
     * @ORM\Column(name="rubro", type="string", length=255, nullable=true)
     */
    private $rubro;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaModifica", type="date", nullable=true)
     */
    private $fechaModifica;

    /**
     * @var string
     *
     * @ORM\Column(name="antecedente", type="string", length=30, nullable=true)
     */
    private $antecedente;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255,  unique=true, nullable=true)
     */
    private $slug;
    
    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", mappedBy="proveedor")
    */
    private $oferta;

   /*
   * ToString
   */
    public function __toString()
    {
         return   $this->getProveedor();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->oferta = new ArrayCollection();
        $this->antecedente= 0;

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
     * Set cuit.
     *
     * @param string $cuit
     *
     * @return Proveedor
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit.
     *
     * @return string
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set numeroEnte.
     *
     * @param string $numeroEnte
     *
     * @return Proveedor
     */
    public function setNumeroEnte($numeroEnte)
    {
        $this->numeroEnte = $numeroEnte;

        return $this;
    }

    /**
     * Get numeroEnte.
     *
     * @return string
     */
    public function getNumeroEnte()
    {
        return $this->numeroEnte;
    }

    /**
     * Set proveedor.
     *
     * @param string $proveedor
     *
     * @return Proveedor
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
        $this->slug = Util::getSlug($this->proveedor);
         
        return $this;
    }

    /**
     * Get proveedor.
     *
     * @return string
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set estado.
     *
     * @param string $estado
     *
     * @return Proveedor
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado.
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set fechaInscribe.
     *
     * @param \DateTime $fechaInscribe
     *
     * @return Proveedor
     */
    public function setFechaInscribe($fechaInscribe)
    {
        $this->fechaInscribe = $fechaInscribe;

        return $this;
    }

    /**
     * Get fechaInscribe.
     *
     * @return \DateTime
     */
    public function getFechaInscribe()
    {
        return $this->fechaInscribe;
    }

    /**
     * Set direccion.
     *
     * @param string $direccion
     *
     * @return Proveedor
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion.
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono.
     *
     * @param string $telefono
     *
     * @return Proveedor
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono.
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set clase.
     *
     * @param string $clase
     *
     * @return Proveedor
     */
    public function setClase($clase)
    {
        $this->clase = $clase;

        return $this;
    }

    /**
     * Get clase.
     *
     * @return string
     */
    public function getClase()
    {
        return $this->clase;
    }

    /**
     * Set rubro.
     *
     * @param string $rubro
     *
     * @return Proveedor
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
     * Set email.
     *
     * @param string $email
     *
     * @return Proveedor
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fechaModifica.
     *
     * @param \DateTime $fechaModifica
     *
     * @return Proveedor
     */
    public function setFechaModifica($fechaModifica)
    {
        $this->fechaModifica = $fechaModifica;

        return $this;
    }

    /**
     * Get fechaModifica.
     *
     * @return \DateTime
     */
    public function getFechaModifica()
    {
        return $this->fechaModifica;
    }

    /**
     * Set antecedente.
     *
     * @param string $antecedente
     *
     * @return Proveedor
     */
    public function setAntecedente($antecedente)
    {
        $this->antecedente = $antecedente;
        $this->fechaModifica= new \Datetime();
        return $this;
    }

    /**
     * Get antecedente.
     *
     * @return string
     */
    public function getAntecedente()
    {
        return $this->antecedente;
    }
 
    /**
     * Set slug
     *
     * @param string $slug
     * @return Proveedor
     */
    public function setSlug($slug)
    {
        $this->slug = Util::getSlug($this->proveedor);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * Add oferta
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $oferta
     * @return Proveedor
     */
    public function addOferta(\Siarme\ExpedienteBundle\Entity\Tramite $oferta)
    {
        $this->oferta[] = $oferta;

        return $this;
    }

    /**
     * Remove oferta
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $oferta
     */
    public function removeOferta(\Siarme\ExpedienteBundle\Entity\Tramite $oferta)
    {
        $this->oferta->removeElement($oferta);
    }

    /**
     * Get oferta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOferta()
    {
        return  $this->oferta;
    }
}
