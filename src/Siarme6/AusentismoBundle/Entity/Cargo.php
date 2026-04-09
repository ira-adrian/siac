<?php

namespace Siarme\AusentismoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cargo
 *
 * @ORM\Table(name="cargo")
 * @ORM\Entity(repositoryClass="Siarme\AusentismoBundle\Repository\CargoRepository")
 */
class Cargo
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
     * @ORM\Column(name="funcion", type="string", length=255)
     */
    private $funcion;

    /**
     * Many Cargo have One Organismo.
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Organismo", inversedBy="cargo")
     * @ORM\JoinColumn(name="organismo_id", referencedColumnName="id")
     */
    private $organismo;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\Agente", inversedBy="cargo") 
     * @ORM\JoinColumn(name="agente_id", referencedColumnName="id")
     */
    private $agente;

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
     * Set funcion
     *
     * @param string $funcion
     * @return Cargo
     */
    public function setFuncion($funcion)
    {
        $this->funcion = $funcion;

        return $this;
    }

    /**
     * Get funcion
     *
     * @return string 
     */
    public function getFuncion()
    {
        return $this->funcion;
    }

    /**
     * Set organismo
     *
     * @param \Siarme\AusentismoBundle\Entity\Organismo $organismo
     * @return Cargo
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
     * Set agente
     *
     * @param \Siarme\AusentismoBundle\Entity\Agente $agente
     * @return Cargo
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


    /*
     * ToString
     */
    public function __toString()
    {
        return (string) $this->getOrganismo();
    }
}
