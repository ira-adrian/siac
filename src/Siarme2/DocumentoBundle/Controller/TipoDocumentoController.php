<?php

namespace Siarme\DocumentoBundle\Controller;

use Siarme\DocumentoBundle\Entity\TipoDocumento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Tipodocumento controller.
 *
 * @Route("tipodocumento")
 */
class TipoDocumentoController extends Controller
{
    /**
     * Lists all tipoDocumento entities.
     *
     * @Route("/", name="tipodocumento_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tipoDocumentos = $em->getRepository('DocumentoBundle:TipoDocumento')->findAll();

        return $this->render('DocumentoBundle:TipoDocumento:index.html.twig', array(
            'tipoDocumentos' => $tipoDocumentos,
        ));
    }

    /**
     * Creates a new tipoDocumento entity.
     *
     * @Route("/new", name="tipodocumento_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tipoDocumento = new Tipodocumento();
        $form = $this->createForm('Siarme\DocumentoBundle\Form\TipoDocumentoType', $tipoDocumento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tipoDocumento);
            $em->flush();

            return $this->redirectToRoute('tipodocumento_show', array('id' => $tipoDocumento->getId()));
        }

        return $this->render('DocumentoBundle:TipoDocumento:new.html.twig', array(
            'tipoDocumento' => $tipoDocumento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tipoDocumento entity.
     *
     * @Route("/{id}", name="tipodocumento_show")
     * @Method("GET")
     */
    public function showAction(TipoDocumento $tipoDocumento)
    {
        $deleteForm = $this->createDeleteForm($tipoDocumento);

        return $this->render('DocumentoBundle:TipoDocumento:show.html.twig', array(
            'tipoDocumento' => $tipoDocumento,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tipoDocumento entity.
     *
     * @Route("/{id}/edit", name="tipodocumento_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TipoDocumento $tipoDocumento)
    {
        $deleteForm = $this->createDeleteForm($tipoDocumento);
        $editForm = $this->createForm('Siarme\DocumentoBundle\Form\TipoDocumentoType', $tipoDocumento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipodocumento_edit', array('id' => $tipoDocumento->getId()));
        }

        return $this->render('DocumentoBundle:TipoDocumento:edit.html.twig', array(
            'tipoDocumento' => $tipoDocumento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tipoDocumento entity.
     *
     * @Route("/{id}", name="tipodocumento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TipoDocumento $tipoDocumento)
    {
        $form = $this->createDeleteForm($tipoDocumento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tipoDocumento);
            $em->flush();
        }

        return $this->redirectToRoute('tipodocumento_index');
    }

    /**
     * Creates a form to delete a tipoDocumento entity.
     *
     * @param TipoDocumento $tipoDocumento The tipoDocumento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TipoDocumento $tipoDocumento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipodocumento_delete', array('id' => $tipoDocumento->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
