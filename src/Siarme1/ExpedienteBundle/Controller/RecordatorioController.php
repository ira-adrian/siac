<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Recordatorio;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Recordatorio controller.
 *
 * @Route("recordatorio")
 */
class RecordatorioController extends Controller
{
    /**
     * Lists all recordatorio entities.
     *
     * @Route("/", name="recordatorio_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findAll();

        return $this->render('recordatorio/index.html.twig', array(
            'recordatorios' => $recordatorios,
        ));
    }

    /**
     * Creates a new recordatorio entity.
     *
     * @Route("/new", name="recordatorio_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $recordatorio = new Recordatorio();
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\RecordatorioType', $recordatorio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recordatorio);
            $em->flush();

            return $this->redirectToRoute('recordatorio_show', array('id' => $recordatorio->getId()));
        }

        return $this->render('recordatorio/new.html.twig', array(
            'recordatorio' => $recordatorio,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new recordatorio entity.
     *
     * @Route("/tramite/{id}/new", name="recordatorio_tramite_new")
     * @Method({"GET", "POST"})
     */
    public function recordatorioTramiteNewAction(Request $request, Tramite $tramite)
    {
        $recordatorio = new Recordatorio();
        $recordatorio->setTramite($tramite);
        $usuario= $this->getUser();
        $recordatorio->setUsuario($usuario);
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\RecordatorioType', $recordatorio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fecha= $recordatorio->getFecha();
            $fecha->setTime($recordatorio->getHora()->format('H'),$recordatorio->getHora()->format('i'));
            $recordatorio->setFecha($fecha);
            $em = $this->getDoctrine()->getManager();
            $em->persist($recordatorio);
            $em->flush();
            if (($tramite->getTipoTramite()->getSlug() == "tramite_proceso") and ($recordatorio->getRecordatorio()== "notificacion_oc"))  {
                $estado= $em->getRepository('ExpedienteBundle:EstadoTramite')->find(52);
                $tramite->setEstadoTramite($estado);
                $em->flush();
                $slug = "informe-plazosoferta";
                return $this->redirectToRoute('documento_new', array('tramite_id' => $tramite->getId(), 'slug' => $slug));
            }
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('ExpedienteBundle:Recordatorio:new.html.twig', array(
            'recordatorio' => $recordatorio,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a recordatorio entity.
     *
     * @Route("/{id}", name="recordatorio_show")
     * @Method("GET")
     */
    public function showAction(Recordatorio $recordatorio)
    {
        $deleteForm = $this->createDeleteForm($recordatorio);

        return $this->render('recordatorio/show.html.twig', array(
            'recordatorio' => $recordatorio,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing recordatorio entity.
     *
     * @Route("/{id}/edit", name="recordatorio_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Recordatorio $recordatorio)
    {
        $deleteForm = $this->createDeleteForm($recordatorio);
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\RecordatorioType', $recordatorio);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recordatorio_edit', array('id' => $recordatorio->getId()));
        }

        return $this->render('recordatorio/edit.html.twig', array(
            'recordatorio' => $recordatorio,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tarea entity.
     *
     * @Route("/{id}/eliminar", name="recordatorio_eliminar")
     * @Method({"GET", "POST"})
     */
    public function eliminarAction(Request $request, Recordatorio $recordatorio)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recordatorio);
            $em->flush();
       //recupero las pagina anterior 
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }
    
    /**
     * Deletes a recordatorio entity.
     *
     * @Route("/{id}", name="recordatorio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Recordatorio $recordatorio)
    {
        $form = $this->createDeleteForm($recordatorio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recordatorio);
            $em->flush();
        }

        return $this->redirectToRoute('recordatorio_index');
    }

    /**
     * Creates a form to delete a recordatorio entity.
     *
     * @param Recordatorio $recordatorio The recordatorio entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Recordatorio $recordatorio)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('recordatorio_delete', array('id' => $recordatorio->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
