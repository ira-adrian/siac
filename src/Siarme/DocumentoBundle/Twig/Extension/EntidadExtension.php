<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class EntidadExtension extends \Twig_Extension
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
            new \Twig_SimpleFilter('Entidad', [$this, 'EntidadFilter']),
        ];
    }

    /**
     * 
     * @return string
     */
    public function EntidadFilter($tipoId, $tipo)
    {

        switch ($tipo) {
            case "EXP":
                $expediente_repo = $this->em->getRepository('ExpedienteBundle:Expediente');
                $expediente= $expediente_repo->find($tipoId);

                 if (!empty($expediente)) {
                $result='<span class="text-muted glyphicon glyphicon-folder-open "> '.$expediente.'</span>';
                 //"Expediente"; 
                 } else { $result ="Sin expediente"; }
                break;
            case "DOC":
                $documento_repo = $this->em->getRepository('DocumentoBundle:Documento');
                 $documento = $documento_repo->find($tipoId); //"Documento";
                 if (!empty($documento)) {
                      $result='<span class="glyphicon glyphicon-file text-muted"> '.$documento.' - '.$documento->getTramite()->getExpediente().'</span>';
                 } else { $result ="Sin documento"; }
                
                break;
            case "TRA":
                 $tramite_repo = $this->em->getRepository('ExpedienteBundle:Tramite');
                 $tramite = $tramite_repo->find($tipoId);

                 if (!empty($tramite)) {
                  $result='<span class="glyphicon glyphicon-user text-muted"> Tramite - '.$tramite.'</span>'; // "Tramite"; 
                 } else { $result ="Sin tramite"; }
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