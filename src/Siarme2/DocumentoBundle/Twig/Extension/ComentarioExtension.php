<?php

namespace Siarme\DocumentoBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;


class ComentarioExtension extends \Twig_Extension
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
            new \Twig_SimpleFilter('Comentario', [$this, 'ComentarioFilter']),
        ];
    }

    /**
     * 
     * @return string
     */
    public function ComentarioFilter($idTipo, $tipo)
    {

         $comentario_repo = $this->em->getRepository('ExpedienteBundle:Comentario');


                 $comentarios = $comentario_repo->findBy( [
                                'tipoId'=>$idTipo, 
                                 'tipo'=>$tipo   
                                ]);

                 

                                 //"Documento";
                /**$html='<ul class="list-group"><li class="list-group-item">'
                 foreach ($comentarios as $comentario) {
                $html.= '<span class="glyphicon glyphicon-user text-muted">'.$comentario->getUsuario().' el {{'.$comentario->getFecha.'|long_time }}' {% endif %}</span></small> <br> {{ comentario.texto }}
                }

                 if ($idTipo > 0) {
                      $result='<span class="glyphicon glyphicon-file text-muted"> '.$comentario->getTexto().'</span>';
                 }*/
                $result= $comentarios;


        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'comentario_extension';
    }
}