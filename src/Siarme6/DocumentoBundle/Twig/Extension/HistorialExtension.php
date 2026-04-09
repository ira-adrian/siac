<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class HistorialExtension extends \Twig_Extension
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
            new \Twig_SimpleFilter('Historial', [$this, 'HistorialFilter']),
        ];
    }

    /**
     * 
     * @return string
     */
    public function HistorialFilter($idTipo, $tipo)
    {
         $historial_repo = $this->em->getRepository('DocumentoBundle:Historial');
        
        $query = $historial_repo ->createQueryBuilder('h');
                $query->innerJoin('h.usuario', 'u')
                    ->addSelect('u')
                    ->andWhere('h.accion != :accion')
                    ->andWhere('h.tipoId = :tipoId')
                    ->andWhere('h.tipo = :tipo')
                    ->setParameter('accion',"VISTO")
                    ->setParameter('tipoId',$idTipo)
                    ->setParameter('tipo',$tipo)
                    ->orderBy('h.fecha', 'DESC')
                    ->setMaxResults(20);
                $dql = $query->getQuery();
              
  /**       $historiales = $historial_repo->findBy( [
                                'tipoId'=>$idTipo, 
                                 'tipo'=>$tipo   
                                ]);*/
         $result= $dql->getResult();

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'historial_extension';
    }
}