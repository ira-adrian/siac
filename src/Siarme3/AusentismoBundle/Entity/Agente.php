<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Siarme\AusentismoBundle\Util\Util;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
/**
 * Agente
 *
 * @ORM\Table(name="agente")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\AgenteRepository")
 *@ORM\HasLifecycleCallbacks()
 * @DoctrineAssert\UniqueEntity("cuil")
 * @DoctrineAssert\UniqueEntity("dni")
 * 
 */
class Agente
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
     * @ORM\Column(name="cuil", type="string", nullable= false, length=11, unique=true)
     * @Assert\Length(min = 3, minMessage = "El CUIL debe tener 11 números")
     */
    private $cuil;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="apellidoNombre", type="string", length=255)
     */
    private $apellidoNombre;

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="dni", type="integer")
     */
    private $dni;

    /**
     * 
     * @var instringt
     * 
     * @ORM\Column(name="obra_social", type="string", nullable= true)
     */
    private $obraSocial;

    /**
     * 
     * @var instringt
     * 
     * @ORM\Column(name="usuario_gde", type="string", nullable= true)
     */
    private $usuarioGde;

    /**
     * @var string
     * 
     * @ORM\Column(name="telefono_fijo", type="string", length=12, nullable= true)
     */
    private $telefonoFijo;

    /**
     * @var string
     * 
     * @ORM\Column(name="telefono_movil", type="string", length=12, nullable= true)
     */
    private $telefonoMovil;

     /**
     * @var string   
     * 
     * @ORM\Column(name="email", type="string", nullable= true)
     */
    private $email;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaNacimiento", type="datetime")
     */
    private $fechaNacimiento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio_laboral", type="datetime")
     */
    private $fechaInicioLaboral;

    /**
     * @var string
     * 
     * @ORM\Column(name="domicilio", type="string", length=255, nullable= true)
     */
    private $domicilio;

    /** 
     * 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Localidad") 
     * @Assert\NotBlank()
     */
    private $localidad;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Licencia", mappedBy="agente", cascade={"persist","remove"}) 
     */
    private $licencia;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Cargo" , mappedBy="agente", cascade={"persist","remove"}) 
     */
    private $cargo;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_modifica", type="datetime")
     */
    private $fechaModifica;

    /**
     * 
     * @var instringt
     * 
     * @ORM\Column(name="escalafon", type="string", nullable= true)
     */
    private $escalafon;

    /** 
     *@ORM\OneToOne(targetEntity="Siarme\UsuarioBundle\Entity\Usuario", mappedBy="agente", cascade={"persist","remove"}) 
     */
    private $usuario;

     /**
     * @Assert\Callback
     */
    public function esCuilValido(ExecutionContextInterface $context, $payload)
    {
        $cuil = $this->getCuil();
        $digits = array();
        if (strlen($cuil) != 11){  
            $context->buildViolation('El CUIL debe tener 11 números')
                    ->atPath('cuil')->addViolation();
        return;}
        for ($i = 0; $i < 11; $i++) {
                if (!ctype_digit($cuil[$i])) {
                    $context->buildViolation('El CUIL no tiene el formato correcto (11 números, sin guiones y sin dejar ningún espacio en blanco)')
                            ->atPath('cuil')->addViolation();
                    return;}
                if ($i < 10) $digits[] = $cuil[$i];
        }           

       if (!ctype_digit($cuil[10])) {
                    $context->buildViolation('El CUIL no tiene el formato correcto (11 números, sin guiones y sin dejar ningún espacio en blanco)')
                            ->atPath('cuil')->addViolation();
                    return;}
        $acum = 0;
        foreach (array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2) as $i => $multiplicador) {
            $acum += $digits[$i] * $multiplicador;
        }
        $cmp = 11 - ($acum % 11);
        if ($cmp == 11) $cmp = 0;
        if ($cmp == 10) $cmp = 9;
       if ($cuil[10] != $cmp)
                    $context->buildViolation('Verifique el CUIL (no corresponde a uno valido)')
                            ->atPath('cuil')->addViolation();
                    return;
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
     * Set apellidoNombre
     *
     * @param string $apellidoNombre
     * @return Agente
     */
    public function setApellidoNombre($apellidoNombre)
    {
        $this->apellidoNombre = $apellidoNombre;

        return $this;
    }

    /**
     * Get apellidoNombre
     *
     * @return string 
     */
    public function getApellidoNombre()
    {
        return $this->apellidoNombre;
    }

    /**
     * Set cuil
     *
     * @param string $cuil
     * @return Agente
     */
    public function setCuil($cuil)
    {
        $this->cuil = $cuil;
        $this->dni = Util::getDni($cuil);
        return $this;
    }

    /**
     * Get cuil
     *
     * @return string 
     */
    public function getCuil()
    {
        return $this->cuil;
    }

    /**
     * Set dni
     *
     * @param integer $dni
     * @return Agente
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return integer 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Agente
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set fechaInicioLaboral
     *
     * @param \DateTime $fechaInicioLaboral
     * @return Agente
     */
    public function setFechaInicioLaboral($fechaInicioLaboral)
    {
        $this->fechaInicioLaboral = $fechaInicioLaboral;

        return $this;
    }

    /**
     * Get fechaInicioLaboral
     *
     * @return \DateTime 
     */
    public function getFechaInicioLaboral()
    {
        return $this->fechaInicioLaboral;
    }

    /**
     * Set domicilio
     *
     * @param string $domicilio
     * @return Agente
     */
    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    /**
     * Get domicilio
     *
     * @return string 
     */
    public function getDomicilio()
    {
        return $this->domicilio;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->licencia = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cargo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fechaModifica= new \Datetime();
        $this->fechaNacimiento= new \Datetime();
        $this->fechaInicioLaboral= new \Datetime();

    }


    /**
     * Add licencia
     *
     * @param \Siarme\AusentismoBundle\Entity\Licencia $licencia
     * @return Agente
     */
    public function addLicencium(\Siarme\AusentismoBundle\Entity\Licencia $licencia)
    {
        $this->licencia[] = $licencia;

        return $this;
    }

    /**
     * Remove licencia
     *
     * @param \Siarme\AusentismoBundle\Entity\Licencia $licencia
     */
    public function removeLicencium(\Siarme\AusentismoBundle\Entity\Licencia $licencia)
    {
        $this->licencia->removeElement($licencia);
    }

    /**
     * Get licencia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLicencia()
    {
        return $this->licencia;
    }

    /**
     * Add cargo
     *
     * @param \Siarme\AusentismoBundle\Entity\Cargo $cargo
     * @return Agente
     */
    public function addCargo(\Siarme\AusentismoBundle\Entity\Cargo $cargo)
    {
        $this->cargo[] = $cargo;

        return $this;
    }

    /**
     * Remove cargo
     *
     * @param \Siarme\AusentismoBundle\Entity\Cargo $cargo
     */
    public function removeCargo(\Siarme\AusentismoBundle\Entity\Cargo $cargo)
    {
        $this->cargo->removeElement($cargo);
    }

    /**
     * Get cargo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCargo()
    {
        return $this->cargo;
    }

 
     public function __toString()

    { 

        //$cuil = Util::getCuil($this->getCuil());
        return (string) $this->getApellidoNombre();
    }


    /**
     * Set localidad
     *
     * @param \Siarme\AusentismoBundle\Entity\Localidad $localidad
     * @return Agente
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
     * Set obraSocial
     *
     * @param string $obraSocial
     *
     * @return Agente
     */
    public function setObraSocial($obraSocial)
    {
        $this->obraSocial = $obraSocial;

        return $this;
    }

    /**
     * Get obraSocial
     *
     * @return string
     */
    public function getObraSocial()
    {
        return $this->obraSocial;
    }

    /**
     * Set usuarioGde
     *
     * @param string $usuarioGde
     *
     * @return Agente
     */
    public function setUsuarioGde($usuarioGde)
    {
        $this->usuarioGde = $usuarioGde;

        return $this;
    }

    /**
     * Get usuarioGde
     *
     * @return string
     */
    public function getUsuarioGde()
    {
        return $this->usuarioGde;
    }

     /**
     * Set escalafon
     *
     * @param string $escalafon
     *
     * @return Agente
     */
    public function setEscalafon($escalafon)
    {
        $this->escalafon = $escalafon;

        return $this;
    }

    /**
     * Get escalafon
     *
     * @return string
     */
    public function getEscalafon()
    {
        return $this->escalafon;
    }

    /**
     * Set telefonoFijo
     *
     * @param string $telefonoFijo
     *
     * @return Agente
     */
    public function setTelefonoFijo($telefonoFijo)
    {
        $this->telefonoFijo = $telefonoFijo;

        return $this;
    }

    /**
     * Get telefonoFijo
     *
     * @return string
     */
    public function getTelefonoFijo()
    {
        $s =$this->telefonoFijo;
        $s= str_replace('-', '', $s); 
        $s= str_replace('(', '', $s); 
        $s= str_replace(')', '', $s); 
         $this->telefonoFijo = $s;
        return $this->telefonoFijo;
    }

    /**
     * Set telefonoMovil
     *
     * @param string $telefonoMovil
     *
     * @return Agente
     */
    public function setTelefonoMovil($telefonoMovil)
    {
        $this->telefonoMovil = $telefonoMovil;

        return $this;
    }

    /**
     * Get telefonoMovil
     *
     * @return string
     */
    public function getTelefonoMovil()
    {
        $s =$this->telefonoMovil;
        $s= str_replace('-', '', $s); 
        $s= str_replace('(', '', $s); 
        $s= str_replace(')', '', $s); 
         $this->telefonoMovil = $s;
        return $this->telefonoMovil;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Agente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * @ORM\PreUpdate
     */
    public function ActualizarFechaModifica()
    {
       $this->setFechaModifica(new \Datetime());
    }

    /**
     * Set usuario
     *
     * @param \Siarme\UsuarioBundle\Entity\Usuario $usuario
     *
     * @return Agente
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

}
