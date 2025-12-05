<?php

// src/Siarme/UsuarioBundle/Entity/Usuario.php

namespace Siarme\UsuarioBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
/**
 * Siarme\UsuarioBundle\Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="Siarme\UsuarioBundle\Repository\UsuarioRepository")
 * @DoctrineAssert\UniqueEntity("usuario")
 *
 */
class Usuario implements AdvancedUserInterface, \Serializable

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
     * @ORM\Column(name="usuario", type="string", length=20, unique=true)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
    * @Assert\Length(min = 6)
    */
    private $passwordEnClaro;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /** 
    * @ORM\ManyToOne(targetEntity="Siarme\UsuarioBundle\Entity\Role" , inversedBy="usuario") 
    * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
    */
    private $rol;
      
    /**
     * @var bool
     *
     * @ORM\Column(name="es_activo", type="boolean")
     */
    private $esActivo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_crea", type="datetime")
     */
    private $fechaCrea;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_modifica", type="datetime",  nullable=true)
     */
    private $fechaModifica;
 
    /** 
    * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\DepartamentoRm" , inversedBy="usuario") 
    * @ORM\JoinColumn(name="departamento_rm_id", referencedColumnName="id")
    */
    private $departamentoRm;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\DocumentoBundle\Entity\Documento", mappedBy="usuario") 
     */
    private $documento;

    /** 
     * @ORM\OneToOne(targetEntity="Siarme\AusentismoBundle\Entity\Agente", inversedBy="usuario") 
     * @ORM\JoinColumn(name="agente_id", referencedColumnName="id")
     */
    private $agente;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tarea", mappedBy="usuario") 
     */
    private $tarea;
    
    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Credito", mappedBy="usuario") 
     */
    private $credito;
    
    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Movimiento", mappedBy="usuario") 
     */
    private $movimiento;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Recordatorio", mappedBy="usuario") 
     */
    private $recordatorio;

    /** 
     * 
     * @ORM\ManyToMany(targetEntity="Siarme\AusentismoBundle\Entity\DepartamentoRm", inversedBy="usuarios", cascade={"persist"}) 
     * 
     */
    private $sector;
    
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
     * Set usuario
     *
     * @param string $usuario
     * @return Usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return null;
    }



    public function __toString()
    {
        return (string) $this->getAgente();
    }

 
    /**
     * Set departamentoRm
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm
     * @return Usuario
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
     * Constructor
     */
    public function __construct()
    {
        $this->documento = new \Doctrine\Common\Collections\ArrayCollection();
        $this->movimiento = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sector = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recordatorio = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tarea = new \Doctrine\Common\Collections\ArrayCollection();
        $this->credito = new \Doctrine\Common\Collections\ArrayCollection();
        $this->esActivo=true;
        $this->fechaCrea = new \ Datetime('now'); ;
        $this->esActivo=true;

    }

    /**
     * Add documento
     *
     * @param \Siarme\DocumentoBundle\Entity\Documento $documento
     * @return Usuario
     */
    public function addDocumento(\Siarme\DocumentoBundle\Entity\Documento $documento)
    {
        $this->documento[] = $documento;

        return $this;
    }

    /**
     * Remove documento
     *
     * @param \Siarme\DocumentoBundle\Entity\Documento $documento
     */
    public function removeDocumento(\Siarme\DocumentoBundle\Entity\Documento $documento)
    {
        $this->documento->removeElement($documento);
    }

    /**
     * Get documento
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumento()
    {
        return $this->documento;
    }

   

    /**
     * Método requerido por la interfaz UserInterface
     */
    public function eraseCredentials()
    {
        $this->passwordEnClaro = null;
        return null;

    }
    
    public function getRoles() {
        
        return array($this->getRol()->getRol());
    }

    /**
     * Método requerido por la interfaz UserInterface
     */
    public function getUsername()
    {
        return $this->getUsuario();
    }

    /**
     * Método requerido por la interfaz UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * 
     */
    public function getPasswordEnClaro()
    {
        return $this->passwordEnClaro;
    }

    /**
     * Set passwordEnClaro
     *
     * @param string $passwordEnClaro
     *
     * @return Usuario
     */
    public function setPasswordEnClaro($passwordEnClaro)
    {
        $this->passwordEnClaro = $passwordEnClaro;

        return $this;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->esActivo;
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->usuario,
            $this->password,
            $this->esActivo,
            // see section on salt below
             $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->usuario,
            $this->password,
            $this->esActivo,
            // see section on salt below
             $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Set esActivo
     *
     * @param boolean $esActivo
     *
     * @return Usuario
     */
    public function setEsActivo($esActivo)
    {
        $this->esActivo = $esActivo;

        return $this;
    }

    /**
     * Get esActivo
     *
     * @return boolean
     */
    public function getEsActivo()
    {
        return $this->esActivo;
    }

    /**
     * Set fechaCrea
     *
     * @param \DateTime $fechaCrea
     *
     * @return Usuario
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;

        return $this;
    }

    /**
     * Get fechaCrea
     *
     * @return \DateTime
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Set fechaModifica
     *
     * @param \DateTime $fechaModifica
     *
     * @return Usuario
     */
    public function setFechaModifica($fechaModifica)
    {
        $this->fechaModifica = $fechaModifica;

        return $this;
    }

    /**
     * Get fechaModifica
     *
     * @return \DateTime
     */
    public function getFechaModifica()
    {
        return $this->fechaModifica;
    }


    /**
     * Set rol
     *
     * @param \Siarme\UsuarioBundle\Entity\Role $rol
     *
     * @return Usuario
     */
    public function setRol(\Siarme\UsuarioBundle\Entity\Role $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \Siarme\UsuarioBundle\Entity\Role
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set agente
     *
     * @param \Siarme\AusentismoBundle\Entity\Agente $agente
     * @return Licencia
     */
    public function setAgente(\Siarme\AusentismoBundle\Entity\Agente $agente = null)
    {
        $this->agente = $agente;

        return $this;
    }

    /**
     * Get agente
     *
     * @return \Siarme\AusentismoBundle\Entity\Agente 
     */
    public function getAgente()
    {
        return $this->agente;
    }

    /**
     * Add tarea
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tarea $tarea
     * @return Usuario
     */
    public function addTarea(\Siarme\ExpedienteBundle\Entity\Tarea $tarea)
    {
        $this->tarea[] = $tarea;

        return $this;
    }

    /**
     * Remove tarea
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tarea $tarea
     */
    public function removeTarea(\Siarme\ExpedienteBundle\Entity\Tarea $tarea)
    {
        $this->tarea->removeElement($tarea);
    }

    /**
     * Get tarea
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTarea()
    {
        return $this->tarea;
    }

    /**
     * Add credito
     *
     * @param \Siarme\ExpedienteBundle\Entity\Credito $credito
     * @return Usuario
     */
    public function addCredito(\Siarme\ExpedienteBundle\Entity\Credito $credito)
    {
        $this->credito[] = $credito;

        return $this;
    }

    /**
     * Remove credito
     *
     * @param \Siarme\ExpedienteBundle\Entity\Credito $credito
     */
    public function removeCredito(\Siarme\ExpedienteBundle\Entity\Credito $credito)
    {
        $this->credito->removeElement($credito);
    }

    /**
     * Get credito
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCredito()
    {
        return $this->credito;
    }

    /**
     * Add movimiento
     *
     * @param \Siarme\ExpedienteBundle\Entity\Movimiento $movimiento
     * @return Usuario
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
     * Add recordatorio
     *
     * @param \Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio
     * @return Usuario
     */
    public function addRecordatorio(\Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio)
    {
        $this->recordatorio[] = $recordatorio;

        return $this;
    }

    /**
     * Remove recordatorio
     *
     * @param \Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio
     */
    public function removeRecordatorio(\Siarme\ExpedienteBundle\Entity\Recordatorio $recordatorio)
    {
        $this->recordatorio->removeElement($recordatorio);
    }

    /**
     * Get recordatorio
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecordatorio()
    {
        return $this->recordatorio;
    }
    
    /**
     * Add sector
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $sector
     *
     * @return Usuario
     */
    public function addSector(\Siarme\AusentismoBundle\Entity\DepartamentoRm $sector)
    {
        $this->sector[] = $sector;

        if (!$this->sector->contains($sector)){
            $this->sector[] = $sector;
            $sector->addUsuarios($this);
        }
        return $this;
    }

    /**
     * Remove sector
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $sector
     */
    public function removeSector(\Siarme\AusentismoBundle\Entity\DepartamentoRm $sector)
    {
        $this->sector->removeElement($sector);
        $sector->removeUsuarios($this);
    }

    /**
     * Get sector
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSector()
    {
        return $this->sector;
    }
}
