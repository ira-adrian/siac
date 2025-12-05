<?php

namespace Siarme\ExpedienteBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Expediente
 *
 * @ORM\Table(name="expediente")
 * 
 * @ORM\Entity(repositoryClass="Siarme\ExpedienteBundle\Repository\ExpedienteRepository")
 * @UniqueEntity("ccoo", message="Ya existe la Nota CCOO")
 * @UniqueEntity("numeroGde", message="Ya existe el Numero de Expediente")
 */
class Expediente
{
    const TIPO_ENTIDAD = 'EXP';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaDesde", type="date", nullable= true)
     */
    private $fechaDesde;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaHasta", type="date", nullable= true)
     */
    private $fechaHasta;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroInterno", type="string")
     */
    private $numeroInterno;

    /**
     * @var string
     *
     * @ORM\Column(name="ccoo", type="string", nullable= true)
     */
    private $ccoo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="numero_gde", type="string", nullable= true)
     */
    private $numeroGde;

    /**
     * @var string
     *
     * @ORM\Column(name="extracto", type="text", nullable= true)
     */
    private $extracto;

    /**
     * @var array|null
     *
     * @ORM\Column(name="organismos", type="array", nullable=true)
     */
    private $organismos;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean", nullable= true)
     */
    private $estado;
    
    /** 
     *@ORM\ManyToOne(targetEntity="Siarme\AusentismoBundle\Entity\DepartamentoRm", inversedBy="expediente")
     * @ORM\JoinColumn(name="departamento_rm_id", referencedColumnName="id")
     */
    private $departamentoRm;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Tramite", mappedBy="expediente", cascade={"persist","remove"})
     */
    private $tramite;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Movimiento", mappedBy="expediente", cascade={"persist","remove"}) 
     */
    private $movimiento;

    /** 
     *@ORM\OneToMany(targetEntity="Siarme\GeneralBundle\Entity\ItemAcuerdoMarco", mappedBy="expediente", cascade={"persist","remove"})
    */
    private $itemAcuerdoMarco;

    /** 
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\TipoExpediente",inversedBy="expediente") 
     * @ORM\JoinColumn(name="tipo_expediente_id", referencedColumnName="id")
     */
    private $tipoExpediente;

    /**
     * One Expediente has Many Tramites.
     * @ORM\OneToMany(targetEntity="Siarme\ExpedienteBundle\Entity\Expediente", mappedBy="acuerdo", cascade={"persist","remove"})
     */
    private $prorroga;

    /**
     * Many Tramites have One Expediente.
     * @ORM\ManyToOne(targetEntity="Siarme\ExpedienteBundle\Entity\Expediente", inversedBy="prorroga")
     */
    private $acuerdo;

    /**
     * ToString
     *
     */
    public function __toString()
    {

        $date = new \Datetime('now');
        if (empty($this->getNumeroGde())) {
            $expt = $this->getNumeroInterno();
        } else {
            $expt = $this->getNumeroGde();
        }
        if (empty($this->getCcoo())) {
            $ccoo = 'NO-'.$date->format('Y').'-n/d';
        } else {
            $ccoo = $this->getCcoo();
        }
        
        return (string) $expt.' | '.$ccoo;
         
    }


     public function __construct()
    {
        $this->prorroga = new ArrayCollection();
        $this->tramite = new ArrayCollection();
        $this->movimiento = new ArrayCollection();
        $this->itemAcuerdoMarco = new ArrayCollection();
        $this->fecha = new \Datetime('now');
        $this->fechaDesde = new \Datetime('now');
        $this->fechaHasta = new \Datetime('now');
        $this->estado = null; 
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Expediente
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }
    /**
     * Set fechaDesde.
     *
     * @param \DateTime $fechaDesde
     *
     * @return Expediente
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;
        $this->fecha = $fechaDesde;
        return $this;
    }

    /**
     * Get fechaDesde.
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta.
     *
     * @param \DateTime $fechaHasta
     *
     * @return Expediente
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta.
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }


   /**
     * Set numeroInterno
     *
     * @param string $numeroInterno
     * @return Expediente
     */
    public function setNumeroInterno($numeroInterno)
    {
        $this->numeroInterno = $numeroInterno;

        return $this;
    }

    /**
     * Get numeroInterno
     *
     * @return string 
     */
    public function getNumeroInterno()
    {
        return $this->numeroInterno;
    }

    /**
     * Set ccoo
     *
     * @param string $ccoo
     * @return Expediente
     */
    public function setCcoo($ccoo)
    {
        $this->ccoo = $ccoo;

        return $this;
    }

    /**
     * Get ccoo
     *
     * @return string 
     */
    public function getCcoo()
    {
        return $this->ccoo;
    }

    /**
     * Set numeroGde
     *
     * @param string $numeroGde
     * @return Expediente
     */
    public function setNumeroGde($numeroGde)
    {
        $this->numeroGde = $numeroGde;

        return $this;
    }

    /**
     * Get numeroGde
     *
     * @return string 
     */
    public function getNumeroGde()
    {
        return $this->numeroGde;
    }



    /**
     * Set extracto
     *
     * @param string $extracto
     * @return Expediente
     */
    public function setExtracto($extracto)
    {
        $this->extracto = $extracto;

        return $this;
    }

    /**
     * Get extracto
     *
     * @return string 
     */
    public function getExtracto()
    {
        $breaks = array("<br />","<br>","<br/>");  
        $text = str_ireplace($breaks, "\r\n", $this->extracto);  
        return $text;
    }

    /**
     * Add organismos
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $organismos
     * @return Expediente
     */
    public function addOrganismo($organismo )
    {
        if (!in_array($organismo, $this->organismos, true)) {
            $this->packages[] = $package;
        }

        return $this;
    }

    /**
     * Set organismos.
     *
     * @param array|null $organismos
     *
     * @return Array
     */
    public function setOrganismos($organismos = null)
    {
        $this->organismos = $organismos;

        return $this;
    }

    /**
     * Get organismos.
     *
     * @return array|null
     */
    public function getOrganismos()
    {
        return $this->organismos;
    }
    
    /**
     * Set estado.
     *
     * @param bool $estado
     *
     * @return Expediente
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado.
     *
     * @return bool
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Add tramite
     *
     * @param \Siarme\ExpedienteBundle\Entity\Tramite $tramite
     * @return Expediente
     */
    public function addTramite(\Siarme\ExpedienteBundle\Entity\Tramite $tramite )
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
     * Set departamentoRm
     *
     * @param \Siarme\AusentismoBundle\Entity\DepartamentoRm $departamentoRm
     *
     * @return Expediente
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
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug = "expediente";
    }

    /**
     * Add movimiento
     *
     * @param \Siarme\ExpedienteBundle\Entity\Movimiento $movimiento
     * @return Expediente
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
     * Get presupuestoOficial
     *
     * @return integar  
     */
    public function getPresupuestoOficial()
    {
        $presupuesto= 0;
        $tramites = $this->getTramite();
        foreach ($tramites as $tramite ) {
            $presupuesto = $presupuesto + $tramite->getPresupuestoOficial();
        }

        return  $this->presupuestoOficial= $presupuesto;
    }

    /**
     * Get enviado.
     *
     * @return bool
     */
    public function getEnviado()
    { 
        $movimientos= $this->movimiento;
        $this->enviado= false;
        foreach ($movimientos as $movimiento) {
        if ($movimiento->getUsuario()->getDepartamentoRm() != $movimiento->getDepartamentoRm()) {
             $this->enviado= true;
        }
        }
        return  $this->enviado;
    }

    /**
     * Add itemAcuerdoMarco
     *
     * @param \Siarme\GeneralBundle\Entity\ItemAcuerdoMarco $itemAcuerdoMarco
     *
     * @return Expediente
     */
    public function addItemAcuerdoMarco(\Siarme\GeneralBundle\Entity\ItemAcuerdoMarco $itemAcuerdoMarco)
    {
        $this->itemAcuerdoMarco[] = $itemAcuerdoMarco;

        return $this;
    }

    /**
     * Remove itemAcuerdoMarco
     *
     * @param \Siarme\GeneralBundle\Entity\ItemAcuerdoMarco $itemAcuerdoMarco
     */
    public function removeItemAcuerdoMarco(\Siarme\GeneralBundle\Entity\ItemAcuerdoMarco $itemAcuerdoMarco)
    {
        $this->itemAcuerdoMarco->removeElement($itemAcuerdoMarco);
    }

    /**
     * Get itemAcuerdoMarco
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemAcuerdoMarco()
    {
        return $this->itemAcuerdoMarco;
    }

    /**
     * Set tipoExpediente
     *
     * @param \Siarme\ExpedienteBundle\Entity\TipoExpediente $tipoExpediente
     *
     * @return Expediente
     */
    public function setTipoExpediente(\Siarme\ExpedienteBundle\Entity\TipoExpediente $tipoExpediente = null)
    {
        $this->tipoExpediente = $tipoExpediente;
        return $this;
    }

    /**
     * Get tipoExpediente
     *
     * @return \Siarme\ExpedienteBundle\Entity\TipoExpediente
     */
    public function getTipoExpediente()
    {
        return $this->tipoExpediente;
    }

    /**
     * Get prorroga
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProrroga()
    {
        return $this->prorroga;
    }

    /**
     * Set acuerdo
     *
     * @param \Siarme\ExpedienteBundle\Entity\Expediente $acuerdo
     * @return Expediente
     */
    public function setAcuerdo(\Siarme\ExpedienteBundle\Entity\Expediente $acuerdo = null)
    {
        $this->acuerdo = $acuerdo;

        return $this;
    }

    /**
     * Get acuerdo
     *
     * @return \Siarme\ExpedienteBundle\Entity\Expediente 
     */
    public function getAcuerdo()
    {
        return $this->acuerdo;
    }

}
