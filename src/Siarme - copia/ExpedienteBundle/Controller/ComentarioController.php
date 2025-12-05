<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Comentario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Comentario controller.
 *
 * @Route("comentario")
 */
class ComentarioController extends Controller
{
    /**
     * Lists all comentario entities.
     *
     * @Route("/{tipoid}/{tipo}/index", name="comentario_index")
     * @Method("GET")
     */
    public function indexAction($tipoid = null, $tipo= null)
    {
        $em = $this->getDoctrine()->getManager();

        $comentarios = $em->getRepository('ExpedienteBundle:Comentario')->findBy(['tipoId'=>$tipoid, 'tipo'=>$tipo]);
//dump($comentarios); exit();
        return $this->render('ExpedienteBundle:Comentario:index.html.twig', array(
            'comentarios' => $comentarios,
        ));
    }

    /**
     * Creates a new comentario entity.
     *
     * @Route("/{tipoid}/{tipo}/new", name="comentario_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $tipoid = null, $tipo= null)
    {
        $comentario = new Comentario();
        $comentario->setFecha(new \Datetime(Date('d-m-Y')));
        $comentario->setTipoId($tipoid);
        $comentario->setTipo($tipo);
        $user = $this->getUser();
     //   dump($usuario); exit();
        $comentario->setUsuario($user);
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\ComentarioType', $comentario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comentario);

            $em->flush();

          
            $ant= $request->headers->get('referer');
           // dump($ant);      exit();
            return $this->redirect($ant);
        }

        return $this->render('ExpedienteBundle:Comentario:new.html.twig', array(
            'comentario' => $comentario,
            'form' => $form->createView(),
            'tipoid'=>$tipoid,
            'tipo'=>$tipo,
        ));
    }

    /**
     * Finds and displays a comentario entity.
     *
     * @Route("/{id}", name="comentario_show")
     * @Method("GET")
     */
    public function showAction(Comentario $comentario)
    {
        $deleteForm = $this->createDeleteForm($comentario);

        return $this->render('comentario/show.html.twig', array(
            'comentario' => $comentario,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing comentario entity.
     *
     * @Route("/{id}/edit", name="comentario_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Comentario $comentario)
    {
        $deleteForm = $this->createDeleteForm($comentario);
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\ComentarioType', $comentario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comentario_edit', array('id' => $comentario->getId()));
        }

        return $this->render('comentario/edit.html.twig', array(
            'comentario' => $comentario,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

      /**
     * Deletes a comentario entity.
     *
     * @Route("/{id}/eliminar", name="comentario_eliminar")
     * @Method("GET")
     */
    public function eliminarAction(Request $request, Comentario $comentario)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comentario);
            $em->flush();
           return new Response("Se ha eliminado el Comentario");
    }

    /**
     * Deletes a comentario entity.
     *
     * @Route("/{id}", name="comentario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Comentario $comentario)
    {
        $form = $this->createDeleteForm($comentario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comentario);
            $em->flush();
        }

        return $this->redirectToRoute('comentario_index');
    }

    /**
     * Creates a form to delete a comentario entity.
     *
     * @param Comentario $comentario The comentario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comentario $comentario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comentario_delete', array('id' => $comentario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
