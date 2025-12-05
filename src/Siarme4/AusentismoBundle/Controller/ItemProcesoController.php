<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\ItemProceso;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Itemproceso controller.
 *
 * @Route("itemproceso")
 */
class ItemProcesoController extends Controller
{
    /**
     * Lists all itemProceso entities.
     *
     * @Route("/", name="itemproceso_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $itemProcesos = $em->getRepository('AusentismoBundle:ItemProceso')->findAll();

        return $this->render('itemproceso/index.html.twig', array(
            'itemProcesos' => $itemProcesos,
        ));
    }

    /**
     * Creates a new itemProceso entity.
     *
     * @Route("/new", name="itemproceso_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $itemProceso = new Itemproceso();
        $form = $this->createForm('Siarme\AusentismoBundle\Form\ItemProcesoType', $itemProceso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($itemProceso);
            $em->flush();

            return $this->redirectToRoute('itemproceso_show', array('id' => $itemProceso->getId()));
        }

        return $this->render('itemproceso/new.html.twig', array(
            'itemProceso' => $itemProceso,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a itemProceso entity.
     *
     * @Route("/{id}/show", name="itemproceso_show")
     * @Method("GET")
     */
    public function showAction(ItemProceso $itemProceso)
    {
        $deleteForm = $this->createDeleteForm($itemProceso);
        
        if ($itemProceso->getItemPedido()->isEmpty() and !$itemProceso->getProceso()->getEstado()) {
               $msje = "<p> No se pudo relacionar el ítem SOLICITADO N° ".$itemProceso->getNumero()." con un ítem PEDIDO por un SAF </p>";        
            $this->get('session')->getFlashBag()->add('mensaje-danger', $msje);
        }
        
        return $this->render('AusentismoBundle:ItemProceso:show.html.twig', array(
            'itemProceso' => $itemProceso,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing itemProceso entity.
     *
     * @Route("/{id}/edit", name="itemproceso_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ItemProceso $itemProceso)
    {
        $deleteForm = $this->createDeleteForm($itemProceso);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\ItemProcesoType', $itemProceso);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('itemproceso_show', array('id' => $itemProceso->getId()));
        }

        return $this->render('AusentismoBundle:ItemProceso:modal_edit.html.twig', array(
            'itemProceso' => $itemProceso,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing itemPedido entity.
     *
     * @Route("/{id}/texto/edit", name="itemproceso_texto_edit")
     * @Method({"GET", "POST"})
     */
    public function editTextoAction(Request $request, ItemProceso $itemProceso)
    {
        $deleteForm = $this->createDeleteForm($itemProceso);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\ItemTextoProcesoType', $itemProceso);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $msj= 'Se han guardado los detalle técnico';
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            return $this->redirectToRoute('itemproceso_show', array('id' => $itemProceso->getId()));
        }

        return $this->render('AusentismoBundle:ItemProceso:modal_texto_edit.html.twig', array(
            'itemProceso' => $itemProceso,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a itemPedido entity.
     *
     * @Route("/{id}/estado", name="item_proceso_estado")
     * @Method("POST")
     */
    public function estadoAction(Request $request, ItemProceso $itemProceso)
    {
            $estado= $itemProceso->getEstado();
            $itemProceso->setEstado(!$estado);
            $em = $this->getDoctrine()->getManager()->flush();

        return new Response("Has cambiado de estado del Item");
    }

    /**
     * Elimina todos itemProceso de un Proceso.
     *
     * @Route("/{id}/delete-all", name="item_proceso_delete_all")
     * @Method("GET")
     */
    public function deleteAllAction(Request $request, Tramite $tramite)
    {     
        $em = $this->getDoctrine()->getManager();
        
        foreach ($tramite->getItemProceso() as $item) {
                $em->remove($item);
        }
        foreach ($tramite->getOferta() as $oferta) {
                $em->remove($oferta);
        }
        $em->flush();
        $msj= "Has eliminado los items";
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * eliminar a itemProceso entity.
     *
     * @Route("/{id}/eliminar", name="item_proceso_eliminar")
     * @Method("POST")
     */
    public function eliminarAction(Request $request, ItemProceso $itemProceso)
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($itemProceso);
            $em->flush();

        return new Response("Has eliminado el Item");
    }

    /**
     * Deletes a itemProceso entity.
     *
     * @Route("/{id}", name="itemproceso_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ItemProceso $itemProceso)
    {
        $form = $this->createDeleteForm($itemProceso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($itemProceso);
            $em->flush();
        }

        return $this->redirectToRoute('itemproceso_index');
    }

    /**
     * Creates a form to delete a itemProceso entity.
     *
     * @param ItemProceso $itemProceso The itemProceso entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ItemProceso $itemProceso)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itemproceso_delete', array('id' => $itemProceso->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
