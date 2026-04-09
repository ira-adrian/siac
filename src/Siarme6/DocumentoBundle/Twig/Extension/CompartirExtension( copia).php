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
    public function ComparteFilter($comparte)
    {




        switch ($comparte->getTipo()) {
            case "EXP":
                $expediente_repo = $this->em->getRepository('ExpedienteBundle:Expediente');
                $expediente= $expediente_repo->find($comparte->getTipoId());
                if (!empty($expediente)) {
                $result='<span class="text-muted glyphicon glyphicon-folder-open "> '.$expediente->__toString().'</span>';
                 //"Expediente"; 
                } else { $result ="Sin expediente"; }
                break;
            case "DOC":
                $documento_repo = $this->em->getRepository('DocumentoBundle:Documento');
                 $documento = $documento_repo->find($comparte->getTipoId()); //"Documento";
                 if (!empty($documento)) {
                 $result='<span class="glyphicon glyphicon-file text-muted"> '.$documento->__toString().' - '.$documento->getTramite()->getExpediente()->getAgente().'</span>';
                } else { $result ="Sin documento"; }
                break;
            case "AG":
                 $agente_repo = $this->em->getRepository('AusentismoBundle:Agente');
                 $agente = $agente_repo->find($comparte->getTipoId());
                 if (!empty($agente)) {
                  $result='<span class="glyphicon glyphicon-user text-muted"> Agente - '.$agente->__toString().'</span>'; // "Agente"; 
              } else { $result ="Sin agente"; }
                break;
             case "MSJ":
                 $result = '<span class="label label-info">MENSAJE:</span>'; // "Mensaje";
                break;
             default:
                 $result = "Ninguno";
        }

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