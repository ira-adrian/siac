<?php

namespace Siarme\GeneralBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\GeneralBundle\Entity\ItemSolicitado;
use Siarme\GeneralBundle\Entity\ItemAcuerdoMarco;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Itemsolicitado controller.
 *
 * @Route("itemsolicitado")
 */
class ItemSolicitadoController extends Controller
{
    /**
     * Lists all itemSolicitado entities.
     *
     * @Route("/", name="itemsolicitado_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $itemSolicitados = $em->getRepository('GeneralBundle:ItemSolicitado')->findAll();

        return $this->render('itemsolicitado/index.html.twig', array(
            'itemSolicitados' => $itemSolicitados,
        ));
    }

    /**
     * Creates a new itemSolicitado entity.
     *
     * @Route("/{id}/new", name="itemsolicitado_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Tramite $tramite)
    {
        $itemSolicitado = new Itemsolicitado();
        $itemSolicitado->setTramite($tramite);
        $form = $this->createForm('Siarme\GeneralBundle\Form\ItemSolicitadoType', $itemSolicitado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $itemSolicitado->setCodigo($itemSolicitado->getItemAcuerdoMarco()->getCodigo());
            $itemSolicitado->setItem($itemSolicitado->getItemAcuerdoMarco()->getItem());
            $itemSolicitado->setUnidadMedida($itemSolicitado->getItemAcuerdoMarco()->getUnidadMedida());
            $itemSolicitado->setCantidadAutorizada($itemSolicitado->getCantidad());
            $em->persist($itemSolicitado);

            $em->flush();

            return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        } elseif ($form->isSubmitted()){

            $msj= 'No se guardaron los cambios,';         
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            $errors = $this->get('validator')->validate($itemSolicitado);
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

        return $this->render('GeneralBundle:ItemSolicitado:modal_new.html.twig', array(
            'itemSolicitado' => $itemSolicitado,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a itemSolicitado entity.
     *
     * @Route("/{id}", name="itemsolicitado_show")
     * @Method("GET")
     */
    public function showAction(ItemSolicitado $itemSolicitado)
    {
        $deleteForm = $this->createDeleteForm($itemSolicitado);

        return $this->render('GeneralBundle:ItemSolicitado:show.html.twig', array(
            'itemSolicitado' => $itemSolicitado,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing itemSolicitado entity.
     *
     * @Route("/{id}/edit", name="itemsolicitado_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ItemSolicitado $itemSolicitado)
    {
        $deleteForm = $this->createDeleteForm($itemSolicitado);
        $editForm = $this->createForm('Siarme\GeneralBundle\Form\ItemSolicitadoEditType', $itemSolicitado);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('itemsolicitado_show', array('id' => $itemSolicitado->getId()));
        }

        return $this->render('GeneralBundle:ItemSolicitado:modal_edit.html.twig', array(
            'itemSolicitado' => $itemSolicitado,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/{id}/search", name="itemsolicitado_search")
     * @Method({"GET", "POST"})
     */

    public function ajaxSearchAction(Request $request, Expediente $expediente) {
            $searchString = $request->get('q', null);

            $em = $this->getDoctrine()->getManager();
            if (empty( $searchString)) {
                    $consulta = $em->createQuery(
                    'SELECT i 
                     FROM GeneralBundle:ItemAcuerdoMarco i
                     WHERE i.expediente = :exp 
                     ORDER BY i.item ASC
                      ')
                     ->setParameter('exp',  $expediente);
            } else {
                    $consulta = $em->createQuery(
                    'SELECT i 
                     FROM GeneralBundle:ItemAcuerdoMarco i
                     WHERE i.expediente = :exp 
                     AND ( i.codigo LIKE :searchString
                     OR i.item LIKE :searchString)
                     ORDER BY i.item ASC
                      ')
                     ->setParameter('searchString', '%' . $searchString . '%')
                     ->setParameter('exp',  $expediente);

            }


             $items = $consulta->getResult();
            if (!empty($items)) { 

            $results = array();
                foreach ($items as $item) {
                    $results[] = array('id' => $item->getId(), 'text'=>$item->getCodigo()." - ".$item->getItem());
                }
            } else {
                       $results[] = array('id' => 0, 'text'=>"Sin Resultados");
            }
        
            return new JsonResponse($results);
    }

    /**
     *
     * @Route("/{id}/cantidad-autorizada", name="itemsolicitado_cantidad_autorizada")
     * @Method("POST")
     */
    public function cantidadAutorizadaAction(Request $request, ItemSolicitado $itemSolicitado)
    {
        $form = $this->createForm('Siarme\GeneralBundle\Form\CantidadAutorizadaType', $itemSolicitado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($itemSolicitado);
            $em->flush();

            return $this->redirectToRoute('tramite_show', array('id' => $itemSolicitado->getTramite()->getId()));
        }

        return $this->render('GeneralBundle:ItemSolicitado:modal_cantidad_autorizada.html.twig', array(
            'itemSolicitado' => $itemSolicitado,
            'form' => $form->createView(),
        ));
    
    }

    /**
     * Cambiar de estado a itemSolicitado entity.
     *
     * @Route("/{id}/quitar", name="itemsolicitado_quitar_item")
     * @Method("GET")
     */
    public function quitarItemAction(Request $request, ItemSolicitado $itemSolicitado)
    { 
            
            $itemSolicitado->setItemAcuerdoMarco(null);
            $itemSolicitado->setEstado(false);
            $em = $this->getDoctrine()->getManager()->flush();

         return $this->redirectToRoute('itemsolicitado_show', array('id' => $itemSolicitado->getId()));
    }

    /**
     * Cambiar de estado a itemSolicitado entity.
     *
     * @Route("/{id}/{item_acuerdo_id}/agregar", name="itemsolicitado_agregar_item")
     * @Method("GET")
     */
    public function agregarItemAction(Request $request, ItemSolicitado $itemSolicitado,  $item_acuerdo_id )
    { 
            $em = $this->getDoctrine()->getManager();
            $itemAcuerdo = $em->getRepository('GeneralBundle:ItemAcuerdoMarco')->find($item_acuerdo_id);
            $itemSolicitado->setItemAcuerdoMarco($itemAcuerdo);
            $itemSolicitado->setEstado(true);
            $em->flush();

        return $this->redirectToRoute('itemsolicitado_show', array('id' => $itemSolicitado->getId()));
    }

    /**
     * Cambiar de estado a itemSolicitado entity.
     *
     * @Route("/{id}/estado", name="itemsolicitado_estado")
     * @Method("POST")
     */
    public function estadoAction(Request $request, ItemSolicitado $itemSolicitado)
    {
            $estado= $itemSolicitado->getEstado();
            $itemSolicitado->setEstado(!$estado);
            $em = $this->getDoctrine()->getManager()->flush();

        return new Response("Has cambiado de estado del Item");
    }

    /**
     * Elimina todos itemProceso de un Proceso.
     *
     * @Route("/{id}/delete-all", name="itemsolicitado_delete_all")
     * @Method("GET")
     */
    public function deleteAllAction(Request $request, Tramite $tramite)
    {     
          $em = $this->getDoctrine()->getManager();
          $items = $em->getRepository('GeneralBundle:ItemSolicitado')->findByTramite($tramite);
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
     * @Route("/{id}/eliminar", name="itemsolicitado_eliminar")
     * @Method("POST")
     */
    public function eliminarAction(Request $request, ItemSolicitado $itemSolicitado)
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($itemSolicitado);
            $em->flush();

        return new Response("Has eliminado el Item");
    }
    /**
     * Deletes a itemSolicitado entity.
     *
     * @Route("/{id}", name="itemsolicitado_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ItemSolicitado $itemSolicitado)
    {
        $form = $this->createDeleteForm($itemSolicitado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($itemSolicitado);
            $em->flush();
        }

        return $this->redirectToRoute('tramite_show', array('id' => $itemSolicitado->getTramite()->getId()));
    }

    /**
     * Creates a form to delete a itemSolicitado entity.
     *
     * @param ItemSolicitado $itemSolicitado The itemSolicitado entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ItemSolicitado $itemSolicitado)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itemsolicitado_delete', array('id' => $itemSolicitado->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
