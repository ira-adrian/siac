<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class CompartidoExtension extends \Twig_Extension
{

    protected $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('Compartido', [$this, 'CompartidoFilter']),
        ];
    }

    /**
     * 
     * @return string
     */
    public function CompartidoFilter($idTipo, $tipo, $usuarioId = null)
    {
         $compartir_repo = $this->em->getRepository('DocumentoBundle:Compartir');

         $compartidos = $compartir_repo->findByUsuario($idTipo, $tipo, $usuarioId);
         $result= $compartidos;

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'compartido_extension';
    }
}