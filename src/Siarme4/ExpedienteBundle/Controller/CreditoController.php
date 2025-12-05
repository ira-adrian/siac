<?php

namespace Siarme\ExpedienteBundle\Controller;


use Siarme\ExpedienteBundle\Entity\Tramite;
use Siarme\ExpedienteBundle\Entity\Credito;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Credito controller.
 *
 * @Route("credito")
 */
class CreditoController extends Controller
{
    /**
     * Lists all credito entities.
     *
     * @Route("/", name="credito_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $creditos = $em->getRepository('ExpedienteBundle:Credito')->findAll();

        return $this->render('credito/index.html.twig', array(
            'creditos' => $creditos,
        ));
    }

    /**
     * Creates a new credito entity.
     *
     * @Route("/{id}/new", name="credito_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Tramite $tramite)
    {
        $credito = new Credito();
        $credito->setTramite($tramite);
        $credito->setUsuario($this->getUser());
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\CreditoType', $credito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($credito);
            $em->flush();

            return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        } elseif ($form->isSubmitted()){
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
            $msj= 'No se guardo el registro CREDITO.';         
            $this->get('session')->getFlashBag()->add(
                    'mensaje-danger',
                    $msj);
           return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        }
        return $this->render('ExpedienteBundle:Credito:form.html.twig', array(
            'credito' => $credito,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a credito entity.
     *
     * @Route("/{id}", name="credito_show")
     * @Method("GET")
     */
    public function showAction(Credito $credito)
    {
        $deleteForm = $this->createDeleteForm($credito);

        return $this->render('credito/show.html.twig', array(
            'credito' => $credito,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing credito entity.
     *
     * @Route("/{id}/edit", name="credito_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Credito $credito)
    {
        $deleteForm = $this->createDeleteForm($credito);
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\CreditoType', $credito);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tramite_show', array('id' => $credito->getTramite()->getId()));
        }

        return $this->render('ExpedienteBundle:Credito:form_edit.html.twig', array(
            'credito' => $credito,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a tramite entity.
     *
     * @Route("/{id}/eliminar", name="credito_eliminar")
     * @Method({"GET", "POST"})
     */
    public function eliminarAction(Request $request, Credito $credito)
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($credito);
            $em->flush();
            $msj =  'Has eliminado la Solicitud '.$credito;
          //  $this->historial($id,'ELIMINADO', $msj );
            $this->get('session')->getFlashBag()->add(
            'mensaje-info',$msj );

            return $this->redirectToRoute('tramite_show', array('id' => $credito->getTramite()->getId()));
    }

    /**
     * Deletes a tramite entity.
     *
     * @Route("/{id}/estado", name="credito_estado")
     * @Method({"GET", "POST"})
     */
    public function estadoAction(Request $request, Credito $credito)
    {
            $credito->setEstado(!$credito->getEstado());
            $em = $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tramite_show', array('id' => $credito->getTramite()->getId()));
    }
    /**
     * Deletes a credito entity.
     *
     * @Route("/{id}/delete", name="credito_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Credito $credito)
    {
        $form = $this->createDeleteForm($credito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($credito);
            $em->flush();
        }

        return $this->redirectToRoute('credito_index');
    }

    /**
     * Creates a form to delete a credito entity.
     *
     * @param Credito $credito The credito entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Credito $credito)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('credito_delete', array('id' => $credito->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
