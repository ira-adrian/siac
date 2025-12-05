<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Bandeja;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Bandeja controller.
 *
 * @Route("bandeja")
 */
class BandejaController extends Controller
{
    /**
     * Lists all bandeja entities.
     *
     * @Route("/", name="bandeja_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bandejas = $em->getRepository('ExpedienteBundle:Bandeja')->findAll();

        return $this->render('bandeja/index.html.twig', array(
            'bandejas' => $bandejas,
        ));
    }

    /**
     * Creates a new bandeja entity.
     *
     * @Route("/new", name="bandeja_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $bandeja = new Bandeja();
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\BandejaType', $bandeja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bandeja);
            $em->flush();

            return $this->redirectToRoute('bandeja_show', array('id' => $bandeja->getId()));
        }

        return $this->render('bandeja/new.html.twig', array(
            'bandeja' => $bandeja,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a bandeja entity.
     *
     * @Route("/{id}", name="bandeja_show")
     * @Method("GET")
     */
    public function showAction(Bandeja $bandeja)
    {
        $deleteForm = $this->createDeleteForm($bandeja);

        return $this->render('bandeja/show.html.twig', array(
            'bandeja' => $bandeja,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing bandeja entity.
     *
     * @Route("/{id}/edit", name="bandeja_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Bandeja $bandeja)
    {
        $deleteForm = $this->createDeleteForm($bandeja);
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\BandejaType', $bandeja);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bandeja_edit', array('id' => $bandeja->getId()));
        }

        return $this->render('bandeja/edit.html.twig', array(
            'bandeja' => $bandeja,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a bandeja entity.
     *
     * @Route("/{id}", name="bandeja_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Bandeja $bandeja)
    {
        $form = $this->createDeleteForm($bandeja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bandeja);
            $em->flush();
        }

        return $this->redirectToRoute('bandeja_index');
    }

    /**
     * Creates a form to delete a bandeja entity.
     *
     * @param Bandeja $bandeja The bandeja entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bandeja $bandeja)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bandeja_delete', array('id' => $bandeja->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
