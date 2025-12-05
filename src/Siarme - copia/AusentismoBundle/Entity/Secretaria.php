<?php

namespace Siarme\AusentismoBundle\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * Secretaria
 *
 * @ORM\Table(name="secretaria")
 * @ORM\Entity
 */
class Secretaria
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
     * @ORM\Column(name="saf", type="integer")
     */
    private $saf;

    /**
     * @var string
     *
     * @ORM\Column(name="secretaria", type="string", length=100, nullable=true)
     */
    private $secretaria;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\AusentismoBundle\Entity\Organismo", mappedBy="secretaria") 
     */
    private $organismo;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Ministerio",inversedBy="secretaria") 
     * @ORM\JoinColumn(name="ministerio_id", referencedColumnName="id")
     */
    private $ministerio;

   /*
   * ToString
   */
    public function __toString()
    {
         return  (string) "SAF ".$this->getSaf()." - ".$this->getSecretaria();
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
     * Set saf
     *
     * @param integer $saf
     *
     * @return Secretaria
     */
    public function setSaf($saf)
    {
        $this->saf = $saf;

        return $this;
    }

    /**
     * Get saf
     *
     * @return integer
     */
    public function getSaf()
    {
        return $this->saf;
    }

    /**
     * Set secretaria
     *
     * @param string $secretaria
     *
     * @return Secretaria
     */
    public function setSecretaria($secretaria)
    {
        $this->secretaria = $secretaria;

        return $this;
    }

    /**
     * Get secretaria
     *
     * @return string
     */
    public function getSecretaria()
    {
        return $this->secretaria;
    }


    /**
     * Add organismo
     *
     * @param \Siarme\AusentismoBundle\Entity\Organismo $organismo
     *
     * @return Secretaria
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
     * @return Secretaria
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
