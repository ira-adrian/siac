<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\ItemPedido;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Itempedido controller.
 *
 * @Route("itempedido")
 */
class ItemPedidoController extends Controller
{
    /**
     * Lists all itemPedido entities.
     *
     * @Route("/", name="itempedido_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $itemPedidos = $em->getRepository('AusentismoBundle:ItemPedido')->findAll();

        return $this->render('itemPedido/index.html.twig', array(
            'itemPedidos' => $itemPedidos,
        ));
    }

    /**
     * Creates a new itemPedido entity.
     *
     * @Route("/{id}/new", name="item_pedido_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Tramite $tramite)
    {
        $itemPedido = new ItemPedido();
        $itemPedido->setNumero(count($tramite->getItemPedido()) + 1);
        $itemPedido->setTramite($tramite);
        $itemPedido->setTrimestre($tramite->getTrimestre());
        $itemPedido->setOrganismo($tramite->getOrganismoOrigen());
        $form = $this->createForm('Siarme\AusentismoBundle\Form\ItemPedidoType', $itemPedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($itemPedido);
            $em->flush();
            return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        } elseif ($form->isSubmitted()){

            $msj= 'No se guardaron los cambios,';         
            $this->get('session')->getFlashBag()->add(
                    'mensaje-danger',
                    $msj);
            $errors = $this->get('validator')->validate($form);
            // iterate on it
            foreach( $errors as $error ){
                // Do stuff with:
                //   $error->getPropertyPath() : the field that caused the error
                $msj="Verifique el campo  ".$error->getPropertyPath()." ERROR: ".$error->getMessage();
                $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            }
           return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        }

        return $this->render('AusentismoBundle:ItemPedido:modal_new.html.twig', array(
            'itemPedido' => $itemPedido,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a itemPedido entity.
     *
     * @Route("/{id}/show", name="itempedido_show")
     * @Method("GET")
     */
    public function showAction(ItemPedido $itemPedido)
    {
        $deleteForm = $this->createDeleteForm($itemPedido);

        return $this->render('AusentismoBundle:ItemPedido:show.html.twig', array(
            'itemPedido' => $itemPedido,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing itemPedido entity.
     *
     * @Route("/{id}/edit", name="item_pedido_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ItemPedido $itemPedido)
    {
        $deleteForm = $this->createDeleteForm($itemPedido);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\ItemPedidoType', $itemPedido);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $msj= 'Se han guardado los cambios';
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            return $this->redirectToRoute('itempedido_show', array('id' => $itemPedido->getId()));
        } elseif ($editForm->isSubmitted() && !$editForm->isValid()){

             $msj= 'No se guardaron los cambios, verifique Precio Estimado';
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            return $this->redirectToRoute('itempedido_show', array('id' => $itemPedido->getId()));
        }

        return $this->render('AusentismoBundle:ItemPedido:modal_edit.html.twig', array(
            'itemPedido' => $itemPedido,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/search", name="item_pedido_search")
     * @Method({"GET", "POST"})
     */

    public function ajaxSearchAction(Request $request) {
            $searchString = $request->get('q', null);
            $items = null;
            $em = $this->getDoctrine()->getManager();
            if (!empty( $searchString)) {
                    $consulta = $em->createQuery(
                    'SELECT i 
                     FROM AusentismoBundle:ItemCatalogo i
                     WHERE (i.codigo LIKE :searchString
                     OR i.item LIKE :searchString)
                     ORDER BY i.codigo ASC
                      ')
                     ->setParameter('searchString', '%' . $searchString . '%');
                    $items = $consulta->getResult();
            }

            if (!empty($items)) { 

            $results = array();
                foreach ($items as $item) {
                    $results[] = array('id' => $item->getId(), 'text'=>$item->getCodigo()." -- ".$item->getItem()." -- ".$item->getRubro());
                }
            } else {
                       $results[] = array('id' => 0, 'text'=>"Sin Resultados");
            }
        
            return new JsonResponse($results);
    }
    
    /**
     * Displays a form to edit an existing itemPedido entity.
     *
     * @Route("/{id}/detalle/edit", name="item_detalle_edit")
     * @Method({"GET", "POST"})
     */
    public function editDetalleAction(Request $request, ItemPedido $itemPedido)
    {
        $deleteForm = $this->createDeleteForm($itemPedido);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\ItemDetalleType', $itemPedido);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $msj= 'Se han guardado los detalle técnico';
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            return $this->redirectToRoute('itempedido_show', array('id' => $itemPedido->getId()));
        }

        return $this->render('AusentismoBundle:ItemPedido:modal_detalle_edit.html.twig', array(
            'itemPedido' => $itemPedido,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a itemPedido entity.
     *
     * @Route("/{id}/estado", name="item_pedido_estado")
     * @Method("POST")
     */
    public function estadoAction(Request $request, ItemPedido $itemPedido)
    {
            $estado= $itemPedido->getEstado();
            $itemPedido->setEstado(!$estado);
            $em = $this->getDoctrine()->getManager()->flush();

        return new Response("Has cambiado de estado del Item");
    }

    /**
     * Deletes a itemPedido entity.
     *
     * @Route("/{id}/estado", name="item_pedido_consolida")
     * @Method("GET")
     */
    public function estadoConsolidaAction(Request $request, ItemPedido $itemPedido)
    {
            $estado= $itemPedido->getConsolida();
            $itemPedido->setConsolida(!$estado);
            $em = $this->getDoctrine()->getManager()->flush();
 
           $referer= $request->headers->get('referer');
           return $this->redirect($referer);
    }

    /**
     * Cambiar de estado a ItemPedido entity.
     *
     * @Route("/{id}/quitar", name="item_pedido_quitar_item")
     * @Method("GET")
     */
    public function quitarItemAction(Request $request, ItemPedido $itemPedido)
    { 
            
            $itemPedido->setItemProceso(null);
            $em = $this->getDoctrine()->getManager()->flush();

            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
    }

    /**
     * Cambiar de estado a ItemPedido entity.
     *
     * @Route("/{id}/{item_proceso_id}/agregar", name="item_pedido_agregar_item")
     * @Method("GET")
     */
    public function agregarItemAction(Request $request, ItemPedido $itemPedido,  $item_proceso_id )
    { 
            $em = $this->getDoctrine()->getManager();
            $itemProceso = $em->getRepository('AusentismoBundle:ItemProceso')->find($item_proceso_id);
            $itemPedido->setItemProceso($itemProceso);
            $em->flush();
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
    }
    
    /**
     * Cambiar de estado a ItemPedido entity.
     *
     * @Route("/{id}/{item_proceso_id}/relacionar", name="item_relacionar_item")
     * @Method({"GET", "POST"})
     */
    public function relacioinarItemAction(Request $request, ItemPedido $itemPedido,  $item_proceso_id )
    { 
            $em = $this->getDoctrine()->getManager();

            if ( $itemPedido->getItemProceso() != null ) {
                $itemPedido->setItemProceso(null);
            } else {
                $itemProceso = $em->getRepository('AusentismoBundle:ItemProceso')->find($item_proceso_id);
                $itemPedido->setItemProceso($itemProceso);
            }
            $em->flush();
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
    }

    /**
     * observar a itemPedido entity.
     *
     * @Route("/{id}/detalle", name="item_pedido_detalle")
     * @Method("POST")
     */
    public function detalleItemPedidoAction(Request $request, ItemPedido $itemPedido)
    {
            $texto = $request->get('q', null);

            $em = $this->getDoctrine()->getManager();
            $itemPedido->setDetalle($texto);
            $em->flush();

        return new JsonResponse(true);
    }
    
    /**
     * Deletes a itemPedido entity.
     *
     * @Route("/{id}/eliminar", name="item_pedido_eliminar")
     * @Method("POST")
     */
    public function eliminarAction(Request $request, ItemPedido $itemPedido)
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($itemPedido);
            $em->flush();

        return new Response("Has eliminado el Item");
    }

    /**
     * Deletes a itemPedido entity.
     *
     * @Route("/{id}/delete-all", name="item_pedido_delete_all")
     * @Method("GET")
     */
    public function deleteAllAction(Request $request, Tramite $tramite)
    {     
          $em = $this->getDoctrine()->getManager();
          $items = $em->getRepository('AusentismoBundle:ItemPedido')->findByTramite($tramite);
         foreach ($items as $item) {
                $em->remove($item);
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
     * Deletes a itemPedido entity.
     *
     * @Route("/{id}", name="itempedido_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ItemPedido $itemPedido)
    {
        $form = $this->createDeleteForm($itemPedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($itemPedido);
            $em->flush();
        }
        $msj= "Has eliminado el ítem";
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        return $this->redirectToRoute('tramite_show', array('id' => $itemPedido->getTramite()->getId()));
    }

    /**
     * Creates a form to delete a itemPedido entity.
     *
     * @param ItemPedido $itemPedido The itemPedido entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ItemPedido $itemPedido)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itempedido_delete', array('id' => $itemPedido->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
