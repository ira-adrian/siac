<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class HistorialEntidadExtension extends \Twig_Extension
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
            new \Twig_SimpleFilter('Historial_Entidad', [$this, 'HistorialEntidadFilter']),
        ];
    }

    /**
     * 
     * @return string
     */
    public function HistorialEntidadFilter($usuarioId, $tipo )
    {
         //**# El tipo puede ser ['EX','DOC','AG'] corresponde con las entidades Expediente, Documento, Agente #*//

         $historial_repo = $this->em->getRepository('DocumentoBundle:Historial');
        
        $query = $historial_repo ->createQueryBuilder('h');
        $query  ->innerJoin('h.usuario', 'u')
                ->addSelect('u')
                ->Where('h.accion = :accion')
                ->andWhere('h.usuario = :usuario')
                ->andWhere('h.tipo = :tipo')
                ->groupBy('h.fecha')
                ->addGroupBy('h.tipo')
                ->orderBy('h.id', 'DESC')       
                ->setMaxResults(35)
                ->setParameter('accion',"VISTO")
                ->setParameter('usuario',$usuarioId)
                ->setParameter('tipo',$tipo);
         $dql = $query->getQuery();
         $historial = $dql->getResult(); 
         // Extraigo los id de los ultimos expediente vistos
        $historial_array = array();
        foreach ($historial as $historia) {
            $historial_array[]= $historia->getTipoid();
        }

        switch ($tipo) {
            case "EXP"://"Expediente"; 
               $expediente_repo = $this->em->getRepository('ExpedienteBundle:Expediente');

                $query= $expediente_repo->createQueryBuilder('e')
                        ->innerJoin('e.tramite', 't')
                        ->addSelect('t')
                        ->where('e.id IN (:visto)')
                        ->orderBy('t.id', 'DESC')
                        ->setParameter('visto', $historial_array);
                $dql = $query->getQuery();
                break;

            case "DOC": //"Documento";
                 $documento_repo = $this->em->getRepository('DocumentoBundle:Documento');
                $query= $documento_repo->createQueryBuilder('d')
                        ->where('d.id IN (:visto)')
                        ->orderBy('d.fechaDocumento', 'DESC')
                        ->setParameter('visto', $historial_array);
                $dql = $query->getQuery();
                break;
            case "AG":// "Agente"; 
                $agente_repo = $this->em->getRepository('AusentismoBundle:Agente');
                $query= $agente_repo->createQueryBuilder('a')
                        ->where('a.id IN (:visto)')
                        ->orderBy('a.fechaModifica', 'DESC')
                        ->setParameter('visto', $historial_array);
                $dql = $query->getQuery();
                break;
       //      default:
             //    $result = "Ninguno";

        }




         $result= $dql->getResult();


        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'historial_entidad_extension';
    }
}