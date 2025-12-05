<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class CompartirExtension extends \Twig_Extension
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
            new \Twig_SimpleFilter('Comparte', [$this, 'ComparteFilter']),
        ];
    }

    /**
     * 
     * @return string
     */
    public function ComparteFilter($usuarioId, $tipo)
 {
         //**# El tipo puede ser ['EXP','DOC','AG'] corresponde con las entidades Expediente, Documento, Agente #*//

         $compartir_repo = $this->em->getRepository('DocumentoBundle:Compartir');
        
        $query = $compartir_repo ->createQueryBuilder('c');
        $query  ->join('c.usuarioEnvia', 'ue')
                ->addSelect('ue')
                ->join('c.usuarioRecive', 'ur')
                ->addSelect('ur')
                ->where('(ue.id = :id) or (ur.id = :id)')
                ->andWhere('c.tipo = :tipo')
                ->orderBy('c.id', 'DESC')       
                ->setParameter('tipo',$tipo)
                ->setParameter('id', $usuarioId);
         $dql = $query->getQuery();
         $compartirX = $dql->getResult(); 
         //dump($compartir);
         //exit();
         // Extraigo los id de los compartidos
        $compartir_array = array();
        foreach ($compartirX as $compartir) {
            $compartir_array[]= $compartir->getTipoid();
        }

        switch ($tipo) {
            case "EXP"://"Expediente"; 
               $expediente_repo = $this->em->getRepository('ExpedienteBundle:Expediente');

                $query= $expediente_repo->createQueryBuilder('e')
                        ->where('e.id IN (:compartido)')
                        ->setParameter('compartido', $compartir_array);
                $dql = $query->getQuery();
                break;

            case "DOC": //"Documento";
                 $documento_repo = $this->em->getRepository('DocumentoBundle:Documento');
                $query= $documento_repo->createQueryBuilder('d')
                        ->where('d.id IN (:compartido)')
                        ->orderBy('d.fechaDocumento', 'DESC')
                        ->setParameter('compartido', $compartir_array);
                $dql = $query->getQuery();
                break;
            case "TRA":// "TRAMITES"; 
               $expediente_repo = $this->em->getRepository('ExpedienteBundle:Tramite');

                $query= $expediente_repo->createQueryBuilder('t')
                        ->where('t.id IN (:compartido)')
                        ->setParameter('compartido', $compartir_array);
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
        return 'comparte_extension';
    }
}