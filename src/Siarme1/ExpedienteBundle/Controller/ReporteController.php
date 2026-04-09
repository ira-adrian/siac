<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\AusentismoBundle\Entity\DepartamentoRm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Bandeja controller.
 *
 * @Route("reporte")
 */
class ReporteController extends Controller
{
    /**
     * Lists all bandeja entities.
     *
     * @Route("/{anio}", name="reporte_index")
     * @Method("GET")
     */
    public function indexAction( $anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }

       $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
         //si es false devuelve aquellas que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);
        $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticion($usuario->getDepartamentoRm(), $anio);
        $acuerdos = $em->getRepository('ExpedienteBundle:Expediente')->findAcuerdo($usuario->getDepartamentoRm(), $anio);
       $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario);
         $tramites = $em->getRepository('ExpedienteBundle:Tramite')->findByDepartamentoRm($usuario->getDepartamentoRm(), $anio); 
        return $this->render('ExpedienteBundle:Reporte:reparticion_reporte.html.twig', array(
              'anio'=>$anio,
              'tareasReporte'=>$tareas,
              'acuerdos'=>$acuerdos,
              'recordatorios' => $recordatorios,
              'tramites' => $tramites,
              'expedientes' => $expedientes,
            ));
    }

}
