<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\Organismo;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Organismo controller.
 *
 * @Route("organismo")
 */
class OrganismoController extends Controller
{
    /**
     * Lists all organismo entities.
     *
     * @Route("/", name="organismo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }
        $em = $this->getDoctrine()->getManager();
      //si es false devuelve aquellos que no estan con expedientes
        $usuario = $this->getUser();
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);
        $organismos = $em->getRepository('AusentismoBundle:Organismo')->findAll();
        //$safs = $em->getRepository('AusentismoBundle:Saf')->findBy(array(), array('numeroSaf' => 'ASC'));

        $acuerdos = $em->getRepository('ExpedienteBundle:Expediente')->findAcuerdo($usuario->getDepartamentoRm(), $anio);
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($this->getUser());
        return $this->render('AusentismoBundle:Organismo:index.html.twig', array(
           'organismos' => $organismos,
           'tareas' => $tareas,
           'acuerdos' => $acuerdos,
           'recordatorios' => $recordatorios,
          //    'safs' => $safs,
        ));
    }

    /**
     * @Route("/search/{id}", name="organismo_search")
     * @Method({"GET", "POST"})
     */

    public function ajaxSearchAction(Request $request, Expediente $expediente) {
            $searchString = $request->get('q', null);
            /* $em = $this->getDoctrine()->getManager();
           
            $consulta = $em->createQuery(
            'SELECT a 
             FROM AusentismoBundle:Agente a
             WHERE a.apellidoNombre LIKE :searchString
             OR a.dni LIKE :searchString
             ORDER BY a.apellidoNombre ASC
              ')
             ->setParameter('searchString', '%' . $searchString . '%');

             $agentes = $consulta->getResult();
            if (!empty($agentes)) { 

            $results = array();
                foreach ($agentes as $agente) {
                    $results[] = array('id' => $agente->getId(), 'text'=>$agente->getApellidoNombre()." DNI: ".$agente->getDni());
                }
            } else {
                       $results[] = array('id' => 0, 'text'=>"Sin Resultados");
            }*/
            $organismos = $expediente->getOrganismos();
             foreach ($organismos as $organismo) {
                    $results[] = array('id' => $organismo->getId(), 'text'=>$organismo->getOrganismo()." CUIT: ".$organismo->getSaf()->getCuil());
                }
            return new JsonResponse($results);
    }


   /**
     * Lists all organismo entities.
     *
     * @Route("/saf", name="saf_index")
     * @Method("GET")
     */
    public function safIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $safs = $em->getRepository('AusentismoBundle:Saf')->findBy(array(), array('numeroSaf' => 'ASC'));

        return $this->render('AusentismoBundle:Organismo:saf_index.html.twig', array(
            'safs' => $safs,
        ));
    }

     /**
     * Lists all organismo entities.
     *
     * @Route("/lista", name="organismo_lista")
     * @Method("GET")
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $organismos = $em->getRepository('AusentismoBundle:Organismo')->findAll();

        return $this->render('AusentismoBundle:Organismo:lista.html.twig', array(
            'organismos' => $organismos,
        ));
    }

    /**
     * Creates a new organismo entity.
     *
     * @Route("/new", name="organismo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $organismo = new Organismo();

        $em = $this->getDoctrine()->getManager();

        $ministerio = $em->getRepository('AusentismoBundle:Ministerio')->find(8);
        $organismo->setMinisterio($ministerio);


        $secretaria = $em->getRepository('AusentismoBundle:Secretaria')->find(1);
        $organismo->setSecretaria($secretaria); 


        $organismo->setClasificacion("EDUCACION");
        
        $form = $this->createForm('Siarme\AusentismoBundle\Form\OrganismoType', $organismo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($organismo);
            $em->flush();

          $this->get('session')->getFlashBag()->add(
          'mensaje-info',
          'Se ha CREADO el organismo: '.$organismo
          ); 

            $ant= $request->headers->get('referer');
           // dump($ant);      exit();
            return $this->redirect($ant);
        }

        return $this->render('AusentismoBundle:Organismo:form.html.twig', array(
            'organismo' => $organismo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a organismo entity.
     *
     * @Route("/{id}/show/{anio}", name="organismo_show")
     * @Method("GET")
     */
    public function showAction(Organismo $organismo, $anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
           // dump($anio);
            //exit();
        }
        //$deleteForm = $this->createDeleteForm($organismo);
        $em = $this->getDoctrine()->getManager();
        $tipoTramites = $em->getRepository('ExpedienteBundle:TipoTramite')->findByOrganismo($organismo, $anio);
        return $this->render('AusentismoBundle:Organismo:show.html.twig', array(
            'organismo' => $organismo,
            'tipoTramites' => $tipoTramites,
            'anio'=>$anio,
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing organismo entity.
     *
     * @Route("/{id}/edit", name="organismo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Organismo $organismo)
    {

        $em = $this->getDoctrine()->getManager();
        if ( empty($organismo->getMinisterio())) {
                $ministerio = $em->getRepository('AusentismoBundle:Ministerio')->find(8);
                $organismo->setMinisterio($ministerio);
            }
        if ( empty($organismo->getSecretaria())) {
                $secretaria = $em->getRepository('AusentismoBundle:Secretaria')->find(1);
                $organismo->setSecretaria($secretaria); 
             }

        if ( empty($organismo->getClasificacion())) $organismo->setClasificacion("EDUCACION");


       // $departamento = $em->getRepository('AusentismoBundle:Departamento')->find();
        $deleteForm = $this->createDeleteForm($organismo);

        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\OrganismoType', $organismo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
          $this->get('session')->getFlashBag()->add(
          'mensaje-info',
          'Se ha modificado el organismo: '.$organismo
          ); 

            return $this->redirectToRoute('organismo_index');
          
        }
       $html = $this->renderView('AusentismoBundle:Organismo:form_edit.html.twig', array(
            'organismo' => $organismo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(), ) );
        return new Response($html);
       /** return $this->render('AusentismoBundle:Organismo:edit.html.twig', array(
            'organismo' => $organismo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));*/
    }

    /**
     * Finds and displays a tramite entity.
     *
     * @Route("/reporte_items", name="organismo_reporte_items")
     * @Method({"GET", "POST"})
     */
    public function reporteItemsAction(Request $request)
    {
        $q = $request->get('id'); 
        if ( !empty($q) ) {
            foreach ($q as $id) {
                $id = (int) $id;
                 echo "El valor de $id <br>";

            }
        }
        exit();
    }

    /**
     * Deletes a organismo entity.
     *
     * @Route("/{id}", name="organismo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Organismo $organismo)
    {
        $form = $this->createDeleteForm($organismo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($organismo);
            $em->flush();
        }

        return $this->redirectToRoute('organismo_index');
    }

    /**
     * Deletes a organismo entity.
     *
     * @Route("/{id}/eliminar", name="organismo_eliminar")
     * 
     */
    public function eliminarAction(Request $request, Organismo $organismo)
    {
           $temp=$organismo;
            $em = $this->getDoctrine()->getManager();
            $em->remove($organismo);
            $em->flush();
          /** $this->get('session')->getFlashBag()->add(
            'mensaje-info',
           '<strong>Se ha Eliminado el Organismo </strong>'.$temp
          ); **/

           return new Response("Se ha eliminado el Organismo: ".$temp);
    }


    /**
     * Creates a form to delete a organismo entity.
     *
     * @param Organismo $organismo The organismo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Organismo $organismo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('organismo_delete', array('id' => $organismo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
