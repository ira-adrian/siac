<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ministerio
 *
 * @ORM\Table(name="ministerio")
 * @ORM\Entity
 */
class Ministerio
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
     * @var int
     *
     * @ORM\Column(name="codigo_juridiccion", type="integer")
     */
    private $codigoJuridiccion;

    /**
     * @var string
     *
     * @ORM\Column(name="ministerio", type="string", length=60, nullable=true)
     */
    private $ministerio;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_gde", type="string", length=12, nullable=true)
     */
    private $codigoGde;
    
    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Organismo", mappedBy="ministerio") 
     */
    private $organismo;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Secretaria", mappedBy="ministerio") 
     */
    private $secretaria;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Saf", mappedBy="ministerio") 
     */
    private $saf;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organismo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->secretaria = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set codigoJuridiccion
     *
     * @param integer $codigoJuridiccion
     *
     * @return Ministerio
     */
    public function setCodigoJuridiccion($codigoJuridiccion)
    {
        $this->codigoJuridiccion = $codigoJuridiccion;

        return $this;
    }

    /**
     * Get codigoJuridiccion
     *
     * @return integer
     */
    public function getCodigoJuridiccion()
    {
        return $this->codigoJuridiccion;
    }

    /**
     * Set ministerio
     *
     * @param string $ministerio
     *
     * @return Ministerio
     */
    public function setMinisterio($ministerio)
    {
        $this->ministerio = $ministerio;

        return $this;
    }

    /**
     * Get ministerio
     *
     * @return string
     */
    public function getMinisterio()
    {
        return $this->ministerio;
    }

    /**
     * Set codigoGde
     *
     * @param string $codigoGde
     *
     * @return Ministerio
     */
    public function setCodigoGde($codigoGde)
    {
        $this->codigoGde = $codigoGde;

        return $this;
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
     * Add organismo
     *
     * @param \Siarme\AusentismoBundle\Entity\Organismo $organismo
     *
     * @return Ministerio
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
     * Add secretaria
     *
     * @param \Siarme\AusentismoBundle\Entity\Secretaria $secretaria
     *
     * @return Ministerio
     */
    public function addSecretaria(\Siarme\AusentismoBundle\Entity\Secretaria $secretaria)
    {
        $this->secretaria[] = $secretaria;

        return $this;
    }

    /**
     * Remove secretaria
     *
     * @param \Siarme\AusentismoBundle\Entity\Secretaria $secretaria
     */
    public function removeSecretaria(\Siarme\AusentismoBundle\Entity\Secretaria $secretaria)
    {
        $this->secretaria->removeElement($secretaria);
    }

    /**
     * Get secretaria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecretaria()
    {
        return $this->secretaria;
    }

    /**
     * Add saf
     *
     * @param \Siarme\AusentismoBundle\Entity\Saf $saf
     *
     * @return Ministerio
     */
    public function addSaf(\Siarme\AusentismoBundle\Entity\Saf $saf)
    {
        $this->saf[] = $saf;

        return $this;
    }

    /**
     * Remove saf
     *
     * @param \Siarme\AusentismoBundle\Entity\Saf $saf
     */
    public function removeSaf(\Siarme\AusentismoBundle\Entity\Saf $saf)
    {
        $this->saf->removeElement($saf);
    }

    /**
     * Get saf
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSaf()
    {
        return $this->saf;
    }

    /*
    * ToString
    */
    public function __toString()
    {
     return   $this->getMinisterio();
    }
}
