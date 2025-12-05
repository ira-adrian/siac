<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\Cargo;
use Siarme\AusentismoBundle\Entity\Agente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Cargo controller.
 *
 * @Route("cargo")
 */
class CargoController extends Controller
{
    /**
     * Lists all cargo entities.
     *
     * @Route("/", name="cargo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cargos = $em->getRepository('AusentismoBundle:Cargo')->findAll();

        return $this->render('AusentismoBundle:Cargo:index.html.twig', array(
            'cargos' => $cargos,
        ));
    }

    /**
     * Creates a new cargo entity.
     *
     * @Route("/new/{id}", name="cargo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Agente $agente)
    {
        
        $cargo = new Cargo();
        $cargo->setAgente($agente);
        $form = $this->createForm('Siarme\AusentismoBundle\Form\CargoType', $cargo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cargo);
            $agente->setEscalafon($cargo->getOrganismo()->getClasificacion());
            $em->flush();

            return $this->redirectToRoute('agente_show', array('id' => $agente->getId()));
        }

        return $this->render('AusentismoBundle:Cargo:form.html.twig', array(
            'cargo' => $cargo,
            'agente' => $agente,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cargo entity.
     *
     * @Route("/{id}", name="cargo_show")
     * @Method("GET")
     */
    public function showAction(Cargo $cargo)
    {
        $deleteForm = $this->createDeleteForm($cargo);

        return $this->render('AusentismoBundle:Cargo:show.html.twig', array(
            'cargo' => $cargo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing cargo entity.
     *
     * @Route("/{id}/edit", name="cargo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Cargo $cargo)
    {
        $deleteForm = $this->createDeleteForm($cargo);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\CargoType', $cargo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

             return $this->redirectToRoute('agente_show', array('id' => $cargo->getAgente()->getId()));

        }

        return $this->render('AusentismoBundle:Cargo:form_edit.html.twig', array(
            'cargo' => $cargo,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a organismo entity.
     *
     * @Route("/{id}/eliminar", name="cargo_elimiar")
     * 
     */
    public function eliminarAction(Request $request, Cargo $cargo)
    {
        $temp=$cargo;
        $em = $this->getDoctrine()->getManager();
        $em->remove($cargo);
        $em->flush();
        $this->get('session')->getFlashBag()->add('mensaje-info',
           '<strong>Se ha Eliminado el Cargo </strong>'.$temp
        );

        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Deletes a cargo entity.
     *
     * @Route("/{id}", name="cargo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Cargo $cargo)
    {
        $agenteid= $cargo->getAgente()->getId();
        $form = $this->createDeleteForm($cargo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cargo);
            $em->flush();
        }

        return $this->redirectToRoute('agente_show', array('id' => $agenteid));
    }

    /**
     * Creates a form to delete a cargo entity.
     *
     * @param Cargo $cargo The cargo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cargo $cargo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cargo_delete', array('id' => $cargo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
