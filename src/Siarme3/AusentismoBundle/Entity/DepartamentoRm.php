<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Siarme\AusentismoBundle\Util\Util;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * DepartamentoRm
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="departamento_rm")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\DepartamentoRmRepository")
 */
class DepartamentoRm
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
     * @ORM\Column(name="slug", type="string", length=255,  unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="departamento_nombre", type="string", length=50,  unique=true)
     */    
    private $departamentoRm;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", mappedBy="departamentoRm") 
     */
    private $tramite;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\TipoTramite", mappedBy="departamentoRm") 
     */
    private $tipoTramite;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\TipoExpediente", mappedBy="departamentoRm") 
     */
    private $tipoExpediente;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Expediente", mappedBy="departamentoRm") 
     */
    private $expediente;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Organismo",inversedBy="departamentoRm") 
     * @ORM\JoinColumn(name="organismo_id", referencedColumnName="id")
     */
    private $organismo;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\UsuarioBundle\Entity\Usuario", mappedBy="departamentoRm") 
     */
    private $usuario;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Movimiento", mappedBy="departamentoRm") 
     */
    private $movimiento;

    /** 
     *@ORM\ManyToMany(targetEntity="Siarme\UsuarioBundle\Entity\Usuario", mappedBy="sector", cascade={"persist"}) 
     */
    private $usuarios;
    
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
     * Set slug
     *
     * @param string $slug
     * @return DepartamentoRm
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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

    public function __construct()
    {
        $this->tramite = new ArrayCollection();
        $this->movimiento = new ArrayCollection();
        $this->expediente = new ArrayCollection();
        $this->tipoTramite = new ArrayCollection();
        $this->tipoExpediente = new ArrayCollection();
        $this->usuarios = new ArrayCollection();
    }

    /**
     * Add tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     * @return DepartamentoRm
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
     * Add usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     * @return DepartamentoRm
     */
    public function addUsuario(\Siarme\UsuarioBundle\Entity\Usuario $usuario)
    {
        $this->usuario[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\Siarme\UsuarioBundle\Entity\Usuario $usuario)
    {
        $this->usuario->removeElement($usuario);
    }

    /**
     * Get usuario
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

  

    public function __toString()
    {
      return (string) $this->getDepartamentoRm();
    }
    

    /**
     * Set departamentoRm
     *
     * @param string $departamentoRm
     *
     * @return DepartamentoRm
     */
    public function setDepartamentoRm($departamentoRm)
    {
        $this->departamentoRm = $departamentoRm;

        return $this;
    }

    /**
     * Get departamentoRm
     *
     * @return string
     */
    public function getDepartamentoRm()
    {
        return $this->departamentoRm;
    }

    /**
     * Set organismo
     *
     * @param \Siarme\AusentismoBundle\Entity\Organismo $organismo
     *
     * @return DepartamentoRm
     */
    public function setOrganismo(\Siarme\AusentismoBundle\Entity\Organismo $organismo = null)
    {
        $this->organismo = $organismo;

        return $this;
    }

    /**
     * Get organismo
     *
     * @return \Siarme\AusentismoBundle\Entity\Organismo
     */
    public function getOrganismo()
    {
        return $this->organismo;
    }
    /**
     * Add expediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\Expediente $expediente
     *
     * @return DepartamentoRm
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
        return $this->expediente;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        $this->slug = Util::getSlug($this->getDepartamentoRm());
    }

    /**
     * Add movimiento
     *
     * @param \Siarme\ExpedienteBundle\Entity\Movimiento $movimiento
     * @return DepartamentoRm
     */
    public function addMovimiento(\Siarme\ExpedienteBundle\Entity\Movimiento $movimiento)
    {
        $this->movimiento[] = $movimiento;

        return $this;
    }

    /**
     * Remove movimiento
     *
     * @param \Siarme\ExpedienteBundle\Entity\Movimiento $movimiento
     */
    public function removeMovimiento(\Siarme\ExpedienteBundle\Entity\Movimiento $movimiento)
    {
        $this->movimiento->removeElement($movimiento);
    }

    /**
     * Get movimiento
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovimiento()
    {
        return $this->movimiento;
    }
    /**
     * Add tipoTramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoTramite $tipoTramite
     * @return DepartamentoRm
     */
    public function addTipoTramite(\Siarme\ExpedienteBundle\Entity\TipoTramite $tipoTramite)
    {
        $this->tipoTramite[] = $tipoTramite;

        return $this;
    }

    /**
     * Remove tipoTramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoTramite $tipoTramite
     */
    public function removeTipoTramite(\Siarme\ExpedienteBundle\Entity\TipoTramite $tipoTramite)
    {
        $this->tipoTramite->removeElement($tipoTramite);
    }

    /**
     * Get tipoTramite
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTipoTramite()
    {
        return $this->tipoTramite;
    }

    /**
     * Add tipoExpediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoExpediente $tipoExpediente
     * @return DepartamentoRm
     */
    public function addTipoExpediente(\Siarme\ExpedienteBundle\Entity\TipoExpediente $tipoExpediente)
    {
        $this->tipoExpediente[] = $tipoExpediente;

        return $this;
    }

    /**
     * Remove tipoExpediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoExpediente $tipoExpediente
     */
    public function removeTipoExpediente(\Siarme\ExpedienteBundle\Entity\TipoExpediente $tipoExpediente)
    {
        $this->tipoExpediente->removeElement($tipoExpediente);
    }

    /**
     * Get tipoExpediente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTipoExpediente()
    {
        return $this->tipoExpediente;
    }

    /**
     * Add usuarios
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuarios
     *
     * @return DepartamentoRm
     */
    public function addusUarios(\Siarme\UsuarioBundle\Entity\Usuario $usuarios)
    {

        if (!$this->usuarios->contains($usuarios)){
            $this->usuarios[] = $usuarios;
            $usuarios->addItemOferta($this);
        }
        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuarios
     */
    public function removeUsuarios(\Siarme\UsuarioBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
        $usuarios->removeItemOferta($this);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }
}
