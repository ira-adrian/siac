<?php

namespace Siarme\GeneralBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\GeneralBundle\Entity\ItemAcuerdoMarco;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Itemacuerdomarco controller.
 *
 * @Route("itemacuerdomarco")
 */
class ItemAcuerdoMarcoController extends Controller
{
    /**
     * Lists all itemAcuerdoMarco entities.
     *
     * @Route("/", name="itemacuerdomarco_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $itemAcuerdoMarcos = $em->getRepository('GeneralBundle:ItemAcuerdoMarco')->findAll();

        return $this->render('itemacuerdomarco/index.html.twig', array(
            'itemAcuerdoMarcos' => $itemAcuerdoMarcos,
        ));
    }

    /**
     * Creates a new itemAcuerdoMarco entity.
     *
     * @Route("/{id}/new", name="itemacuerdomarco_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Expediente $expediente)
    {
        $itemAcuerdoMarco = new Itemacuerdomarco();
        $itemAcuerdoMarco->setExpediente($expediente);
        $form = $this->createForm('Siarme\GeneralBundle\Form\ItemAcuerdoMarcoType', $itemAcuerdoMarco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($itemAcuerdoMarco);
            $em->flush();

            return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));
        } elseif ($form->isSubmitted()){

            $msj= 'No se guardaron los cambios,';         
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            $errors = $this->get('validator')->validate($itemAcuerdoMarco);
            // iterate on it
            foreach( $errors as $error ){
                // Do stuff with:
                //   $error->getPropertyPath() : the field that caused the error
                $msj="Verifique el campo  ".$error->getPropertyPath()." ERROR: ".$error->getMessage();
                $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            }
            return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));
        }
        return $this->render('GeneralBundle:ItemAcuerdoMarco:modal_new.html.twig', array(
            'itemAcuerdo' => $itemAcuerdoMarco,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a itemAcuerdoMarco entity.
     *
     * @Route("/{id}", name="itemacuerdomarco_show")
     * @Method("GET")
     */
    public function showAction(ItemAcuerdoMarco $itemAcuerdoMarco)
    {
        $deleteForm = $this->createDeleteForm($itemAcuerdoMarco);

        return $this->render('GeneralBundle:ItemAcuerdoMarco:show.html.twig', array(
            'itemAcuerdo' => $itemAcuerdoMarco,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing itemAcuerdoMarco entity.
     *
     * @Route("/{id}/edit", name="itemacuerdomarco_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ItemAcuerdoMarco $itemAcuerdoMarco)
    {
        $editForm = $this->createForm('Siarme\GeneralBundle\Form\ItemAcuerdoMarcoType', $itemAcuerdoMarco);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('itemacuerdomarco_show', array('id' => $itemAcuerdoMarco->getId()));
        }

        return $this->render('GeneralBundle:ItemAcuerdoMarco:modal_edit.html.twig', array(
            'itemAcuerdo' => $itemAcuerdoMarco,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a itemPedido entity.
     *
     * @Route("/{id}/eliminar", name="itemacuerdomarco_eliminar")
     * @Method("POST")
     */
    public function eliminarAction(Request $request, ItemAcuerdoMarco $itemAcuerdo)
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($itemAcuerdo);
            $em->flush();

        return new Response("Has eliminado el Item");
    }

    /**
     * Elimina todos itemProceso de un Proceso.
     *
     * @Route("/{id}/delete-all", name="itemacuerdomarco_delete_all")
     * @Method("GET")
     */
    public function deleteAllAction(Request $request, Expediente $expediente)
    {     
          $em = $this->getDoctrine()->getManager();
          $items = $expediente->getItemAcuerdoMarco();
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
     * Deletes a itemAcuerdoMarco entity.
     *
     * @Route("/{id}", name="itemacuerdomarco_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ItemAcuerdoMarco $itemAcuerdoMarco)
    {
        $form = $this->createDeleteForm($itemAcuerdoMarco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($itemAcuerdoMarco);
            $em->flush();
        }

       return $this->redirectToRoute('expediente_show', array('id' => $itemAcuerdoMarco->getExpediente()->getId()));
    }

    /**
     * Creates a form to delete a itemAcuerdoMarco entity.
     *
     * @param ItemAcuerdoMarco $itemAcuerdoMarco The itemAcuerdoMarco entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ItemAcuerdoMarco $itemAcuerdoMarco)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itemacuerdomarco_delete', array('id' => $itemAcuerdoMarco->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
