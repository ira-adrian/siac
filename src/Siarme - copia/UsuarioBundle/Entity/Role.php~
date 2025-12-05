<?php
// src/Siarme/UsuarioBundle/Entity/Role.php

namespace Siarme\UsuarioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role {
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="role_name",type="string",length=50,unique=true)
     */
    private $roleName;

     /**
     * @ORM\Column(name="rol",type="string",length=50,unique=true)
     */
    private $rol;

    /**
     * @ORM\Column(name="area_rm",type="string",length=50,unique=true)
     */
    private $areaRm;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\UsuarioBundle\Entity\Usuario", mappedBy="rol")
     */
    private $usuario;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\DocumentoBundle\Entity\TipoDocumento", mappedBy="rol")
    */
    private $tipoDocumento;

     /**
     * ToString
     */         
    public function __toString()
    {
        return (string) $this->getRoleName();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipoDocumento = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set roleName
     *
     * @param string $roleName
     *
     * @return Role
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * Get roleName
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * Set rol
     *
     * @param string $rol
     *
     * @return Role
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return string
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set areaRm
     *
     * @param string $areaRm
     *
     * @return Role
     */
    public function setAreaRm($areaRm)
    {
        $this->areaRm = $areaRm;

        return $this;
    }

    /**
     * Get areaRm
     *
     * @return string
     */
    public function getAreaRm()
    {
        return $this->areaRm;
    }

    /**
     * Add usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     *
     * @return Role
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

    /**
     * Add tipoDocumento
     *
     * @param \Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento
     *
     * @return Role
     */
    public function addTipoDocumento(\Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento)
    {
        $this->tipoDocumento[] = $tipoDocumento;

        return $this;
    }

    /**
     * Remove tipoDocumento
     *
     * @param \Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento
     */
    public function removeTipoDocumento(\Siarme\DocumentoBundle\Entity\TipoDocumento $tipoDocumento)
    {
        $this->tipoDocumento->removeElement($tipoDocumento);
    }

    /**
     * Get tipoDocumento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }
}
