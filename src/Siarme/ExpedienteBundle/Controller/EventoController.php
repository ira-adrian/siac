<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Evento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Evento controller.
 *
 * @Route("evento")
 */
class EventoController extends Controller
{
    /**
     * Lists all evento entities.
     *
     * @Route("/{anio}", name="evento_index")
     * @Method("GET")
     */
    public function indexAction($anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }
        $em = $this->getDoctrine()->getManager();

        $eventos = $em->getRepository('ExpedienteBundle:Evento')->findByAnio($anio);

        return $this->render('ExpedienteBundle:Evento:index.html.twig', array(
            'eventos' => $eventos,
            'anio'=>$anio,
        ));
    }

    /**
     * @Route("/api/eventos/{anio}", name="api_eventos")
     */
    public function obtenerEventosAction($anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }
        $eventos = $this->getDoctrine()->getRepository(Evento::class)->findAll();

        $eventosArray = [];
        foreach ($eventos as $evento) {
        if ($evento->getFechaFin()){
    
            $fecha_actual = $evento->getFechaFin()->format('Y-m-d');

            // 2. Sumar un día usando strtotime()
            $nueva_fecha_timestamp = strtotime($fecha_actual . " + 1 day");

            // 3. Formatear la nueva fecha
            $nueva_fecha = date("Y-m-d", $nueva_fecha_timestamp);
        } else {
            $nueva_fecha = null;
        }


            $eventosArray[] = [
                'title' => $evento->getTitulo(),
                'start' => $evento->getFechaInicio()->format('Y-m-d'),
                'end' => $nueva_fecha,
                //'end' => $evento->getFechaFin() ? $evento->getFechaFin()->format('Y-m-d') : null,
                //'description' => $evento->getDescripcion(),
                // Puedes agregar más campos si es necesario
            ];
        }

        return new JsonResponse($eventosArray);
    }
    /**
     * Creates a new evento entity.
     *
     * @Route("/new", name="evento_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $evento = new Evento();
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\EventoType', $evento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evento);
            $em->flush();
            $msj =  'Has CREADO un nuevo registro en Calendario';  
            $this->get('session')->getFlashBag()->add(
                    'mensaje-success',
                    $msj);
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('ExpedienteBundle:Evento:new.html.twig', array(
            'evento' => $evento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a evento entity.
     *
     * @Route("/{id}", name="evento_show")
     * @Method("GET")
     */
    public function showAction(Evento $evento)
    {
        $deleteForm = $this->createDeleteForm($evento);

        return $this->render('ExpedienteBundle:Evento:show.html.twig', array(
            'evento' => $evento,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing evento entity.
     *
     * @Route("/{id}/edit", name="evento_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Evento $evento)
    {
        $deleteForm = $this->createDeleteForm($evento);
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\EventoType', $evento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $msj =  'Has actualizado  el registro del Calendario';  
            $this->get('session')->getFlashBag()->add(
                    'mensaje-success',
                    $msj);
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('ExpedienteBundle:Evento:edit.html.twig', array(
            'evento' => $evento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Eliminar evento entity.
     *
     * @Route("/{id}/eliminar", name="evento_eliminar")
     * @Method({"GET", "POST"})
     */
    public function eliminarAction(Request $request, Evento $evento)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evento);
            $em->flush();
            $msj =  'Has eliminado el registro del Calendario';  
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);  
    }

    /**
     * Deletes a evento entity.
     *
     * @Route("/{id}", name="evento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Evento $evento)
    {
        $form = $this->createDeleteForm($evento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evento);
            $em->flush();
        }

        return $this->redirectToRoute('evento_index');
    }

    /**
     * Creates a form to delete a evento entity.
     *
     * @param Evento $evento The evento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Evento $evento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('evento_delete', array('id' => $evento->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
