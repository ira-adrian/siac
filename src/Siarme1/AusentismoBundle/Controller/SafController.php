<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\Saf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Saf controller.
 *
 * @Route("saf")
 */
class SafController extends Controller
{
    /**
     * Lists all saf entities.
     *
     * @Route("/", name="saf_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $safs = $em->getRepository('AusentismoBundle:Saf')->findAll();

        return $this->render('saf/index.html.twig', array(
            'safs' => $safs,
        ));
    }

    /**
     * Creates a new saf entity.
     *
     * @Route("/new", name="saf_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $saf = new Saf();
        $form = $this->createForm('Siarme\AusentismoBundle\Form\SafType', $saf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($saf);
            $em->flush();

            return $this->redirectToRoute('saf_show', array('id' => $saf->getId()));
        }

        return $this->render('saf/new.html.twig', array(
            'saf' => $saf,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a saf entity.
     *
     * @Route("/{id}", name="saf_show")
     * @Method("GET")
     */
    public function showAction(Saf $saf)
    {
        $deleteForm = $this->createDeleteForm($saf);

        return $this->render('saf/show.html.twig', array(
            'saf' => $saf,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing saf entity.
     *
     * @Route("/{id}/edit", name="saf_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Saf $saf)
    {
        $deleteForm = $this->createDeleteForm($saf);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\SafType', $saf);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('saf_edit', array('id' => $saf->getId()));
        }

        return $this->render('saf/edit.html.twig', array(
            'saf' => $saf,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a saf entity.
     *
     * @Route("/{id}", name="saf_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Saf $saf)
    {
        $form = $this->createDeleteForm($saf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($saf);
            $em->flush();
        }

        return $this->redirectToRoute('saf_index');
    }

    /**
     * Creates a form to delete a saf entity.
     *
     * @param Saf $saf The saf entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Saf $saf)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('saf_delete', array('id' => $saf->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
