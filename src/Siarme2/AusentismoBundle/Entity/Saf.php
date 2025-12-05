<?php

namespace Siarme\AusentismoBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Siarme\AusentismoBundle\Util\Util;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Saf
 *
 * @ORM\Table(name="saf")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @DoctrineAssert\UniqueEntity("cuil")
 */
class Saf
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
     * @var int
     *
     * @ORM\Column(name="numero_saf", type="integer")
     */
    private $numeroSaf;

    /**
     * @var string
     *
     * @ORM\Column(name="saf", type="string", length=255, nullable=true)
     */
    private $saf;

    /**
     * @var string
     * 
     * @ORM\Column(name="domicilio", type="string", length=255, nullable= true)
     */
    private $domicilio;

    /**
     * @var string   
     * 
     * @ORM\Column(name="email", type="string", nullable= true)
     */
    private $email;

    /**
     * @var string
     * 
     * @ORM\Column(name="telefono_movil", type="string", length=12, nullable= true)
     */
    private $telefonoMovil;
    
    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Organismo", mappedBy="saf") 
     */
    private $organismo;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Ministerio",inversedBy="saf") 
     * @ORM\JoinColumn(name="ministerio_id", referencedColumnName="id")
     */
    private $ministerio;


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

   /*
   * ToString
   */
    public function __toString()
    {
         return  (string) $this->getNumeroSaf();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organismo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set cuil
     *
     * @param string $cuil
     * @return Saf
     */
    public function setCuil($cuil)
    {
        $this->cuil = $cuil;
        return $this;
    }

    /**
     * Get cuil
     *
     * @return string 
     */
    public function getCuil()
    {

        // Util::getCuil($this->getCuil());
        return $this->cuil;
    }

    /**
     * Set numeroSaf
     *
     * @param integer $numeroSaf
     *
     * @return Saf
     */
    public function setNumeroSaf($numeroSaf)
    {
        $this->numeroSaf = $numeroSaf;

        return $this;
    }

    /**
     * Get numeroSaf
     *
     * @return integer
     */
    public function getNumeroSaf()
    {
        return $this->numeroSaf;
    }

    /**
     * Set saf
     *
     * @param string $saf
     *
     * @return Saf
     */
    public function setSaf($saf)
    {
        $this->saf = $saf;

        return $this;
    }

    /**
     * Get saf
     *
     * @return string
     */
    public function getSaf()
    {
        return $this->saf;
    }

     /**
     * Set domicilio
     *
     * @param string $domicilio
     * @return Saf
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
     * Set email
     *
     * @param string $email
     *
     * @return Saf
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
     * Set telefonoMovil
     *
     * @param string $telefonoMovil
     *
     * @return Saf
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
     * Add organismo
     *
     * @param \Siarme\AusentismoBundle\Entity\Organismo $organismo
     *
     * @return Saf
     */
    public function addOrganismo(\Siarme\AusentismoBundle\Entity\Organismo $organismo)
    {
        $this->organismo[] = $organismo;

        return $this;
    }

    /**
     * Remove organismo
     *
     * @param \Siarme\AusentismoBundle\Entity\Organismo $organismo
     */
    public function removeOrganismo(\Siarme\AusentismoBundle\Entity\Organismo $organismo)
    {
        $this->organismo->removeElement($organismo);
    }

    /**
     * Get organismo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganismo()
    {
        return $this->organismo;
    }
   
    /**
     * Set ministerio
     *
     * @param \Siarme\AusentismoBundle\Entity\Ministerio $ministerio
     *
     * @return Saf
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
}
