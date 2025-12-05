<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Planificacion;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Planificacion controller.
 *
 * @Route("planificacion")
 */
class PlanificacionController extends Controller
{
    /**
     * Lists all planificacion entities.
     *
     * @Route("/", name="planificacion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $planificacions = $em->getRepository('ExpedienteBundle:Planificacion')->findAll();

        return $this->render('ExpedienteBundle:Planificacion:index.html.twig', array(
            'planificacions' => $planificacions,
        ));
    }

    /**
     * Creates a new planificacion entity.
     *
     * @Route("/new/{id}", name="planificacion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Tramite $tramite)
    {
        $planificacion = new Planificacion();
        $planificacion->setTramite($tramite);
        $planificacion->setTrimestre($tramite->getTrimestre());
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\PlanificacionType', $planificacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planificacion);
            $em->flush();

            return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        }

        return $this->render('ExpedienteBundle:Planificacion:modal_new.html.twig', array(
            'planificacion' => $planificacion,
            'tramite' => $tramite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new planificacion entity.
     *
     * @Route("/new/sin/{id_pedido}/{id_tramite}", name="sin_planificacion_new")
     * @Method({"GET", "POST"})
     */
    public function sinNewAction(Request $request, $id_pedido= null, $id_tramite = null)
    {
         $em = $this->getDoctrine()->getManager();
        $pedido = $em->getRepository('ExpedienteBundle:Tramite')->find($id_pedido);
        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($id_tramite);
        $planificacion = new Planificacion();
        $planificacion->setTramite($tramite);
        $planificacion->setPedido($pedido);
        $planificacion->setRubro($pedido->getRubro()->getRubro());
        $planificacion->setDescripcion("SIN PLANIFICAR");
        $planificacion->setImporte(null);
        $planificacion->setEstado(1);
        $planificacion->setTrimestre($tramite->getTrimestre());
       
        $em->persist($planificacion);
        $em->flush();

        return $this->redirectToRoute('tramite_show', array('id' => $pedido->getId()));

    }
    
    /**
     * Finds and displays a planificacion entity.
     *
     * @Route("/{id}", name="planificacion_show")
     * @Method("GET")
     */
    public function showAction(Planificacion $planificacion)
    {
        $deleteForm = $this->createDeleteForm($planificacion);

        return $this->render('ExpedienteBundle:Planificacion:show.html.twig', array(
            'planificacion' => $planificacion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing planificacion entity.
     *
     * @Route("/{id}/edit", name="planificacion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Planificacion $planificacion)
    {
        $deleteForm = $this->createDeleteForm($planificacion);
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\PlanificacionType', $planificacion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('planificacion_show', array('id' => $planificacion->getId()));
        }

        return $this->render('ExpedienteBundle:Planificacion:edit.html.twig', array(
            'planificacion' => $planificacion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
   /**
     * Finds and displays a planificacion entity.
     *
     * @Route("/{id}/{id2}", name="planificacion_seleccionar")
     * @Method("GET")
     */
    public function seleccionarAction(Planificacion $planificacion, $id2, Request $request)
    {   $em = $this->getDoctrine()->getManager();
        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($id2);
        $planificacion->setPedido($tramite);
        $em = $this->getDoctrine()->getManager()->flush();
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Finds and displays a planificacion entity.
     *
     * @Route("/{id}/quitar", name="planificacion_quitar")
     * @Method("GET")
     */
    public function quitarAction(Planificacion $planificacion, Request $request)
    {   
        $planificacion->setPedido(null);
        $this->getDoctrine()->getManager()->flush();
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Cambia de estado de planificacion entity.
     *
     * @Route("/{id}/{estado}/concentra", name="planificacion_concentra")
     * @Method("POST")
     */
    public function concentraAction(Request $request, Planificacion $planificacion, $estado=0)
    { 
        $msj = new JsonResponse("cambio de estado");

            $planificacion->setEstado($estado);

            $this->getDoctrine()->getManager()->flush();

       return $msj;
    }
    /**
     * Deletes a planificacion entity.
     *
     * @Route("/{id}", name="planificacion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Planificacion $planificacion)
    {
        $form = $this->createDeleteForm($planificacion);
        $form->handleRequest($request);
        $tramite_id= $planificacion->getTramite()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($planificacion);
            $em->flush();
        }
        return $this->redirectToRoute('tramite_show', array('id' => $tramite_id));

    }

    /**
     * Creates a form to delete a planificacion entity.
     *
     * @param Planificacion $planificacion The planificacion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Planificacion $planificacion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('planificacion_delete', array('id' => $planificacion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
