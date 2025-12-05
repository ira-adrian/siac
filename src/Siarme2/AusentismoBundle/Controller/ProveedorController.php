<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Util\Util;
use Siarme\AusentismoBundle\Entity\Proveedor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Proveedor controller.
 *
 * @Route("proveedor")
 */
class ProveedorController extends Controller
{
    /**
     * Lists all proveedor entities.
     *
     * @Route("/", name="proveedor_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $proveedors = $em->getRepository('AusentismoBundle:Proveedor')->findAll();

        return $this->render('AusentismoBundle:Proveedor:index.html.twig', array(
            'proveedors' => $proveedors,
        ));
    }
    
    /**
     * ACTUALIZA LOS SLUG DE LOS PROVEEDORES.
     * BUSCAR OFERTAS QUE CONTENGAN EL NOMBRE DE PROVEEDOR YA  QUE ESTE ULTIMO ES MAS CORTO 
     * @Route("/actualizar-slug", name="proveedor_actualizar_slug")
     * @Method("GET")
     */
    public function acutalizarSlugAction()
    {
        $em = $this->getDoctrine()->getManager();

        $proveedors = $em->getRepository('AusentismoBundle:Proveedor')->findAll();

        foreach ($proveedors as $proveedor){
            //ACTUALIZAR EL SLUG
            $nombreP = trim($proveedor->getProveedor());
            $slug = Util::getSlug($nombreP);
            $proveedor->setSlug($slug);
            $em->flush();

            // BUSCAR OFERTAS QUE CONTENGAN EL NOMBRE DE PROVEEDOR
            $nombreP2 = str_replace('.', '', $nombreP);//quitar los puntos por ej S.R.L. a SRL
            $dql = 'SELECT t 
            FROM ExpedienteBundle:Tramite t 
            JOIN t.tipoTramite tt
            WHERE (t.oferente LIKE :cdn 
            OR t.oferente LIKE :cdn2 )
            AND t.proveedor IS NULL
            AND tt.slug = :slug';
                     
            $query = $em->createQuery($dql);
            $query->setParameter('slug', "tramite_oferta")
                  ->setParameter('cdn', '%'.$nombreP.'%' )
                  ->setParameter('cdn2', '%'.$nombreP2.'%' );

            $ofertas = $query->getResult();

            if (count($ofertas) > 0) {
                echo  $nombreP."<br>";
                foreach ($ofertas as $oferta) {
                    if ($oferta->getCuit() == "-"){
                        echo  "-  -".$oferta->getOferente()."<br>";
                        $oferta->setProveedor($proveedor);
                        $oferta->setCuil($proveedor->getCuit());
                        $em->flush();
                    }
  
                }
            }   
        }

        $this->get('session')->getFlashBag()->add('mensaje-info', 'Se han actualizado los SLUGS y Se relacionaron las OFERTAS');
        return $this->render('AusentismoBundle:Proveedor:index.html.twig', array(
            'proveedors' => $proveedors,
        ));
    }
    
    /**
     * Creates a new proveedor entity.
     *
     * @Route("/new", name="proveedor_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $proveedor = new Proveedor();
        $form = $this->createForm('Siarme\AusentismoBundle\Form\ProveedorNewType', $proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();

            return $this->redirectToRoute('proveedor_show', array('id' => $proveedor->getId()));
        }

        return $this->render('AusentismoBundle:Proveedor:new.html.twig', array(
            'proveedor' => $proveedor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a proveedor entity.
     *
     * @Route("/{id}/{anio}", name="proveedor_show")
     * @Method("GET")
     */
    public function showAction(Proveedor $proveedor, $anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }
        $em = $this->getDoctrine()->getManager();
        $tipoTramites = $em->getRepository('ExpedienteBundle:TipoTramite')->findByProveedor($proveedor, $anio);

        $deleteForm = $this->createDeleteForm($proveedor);
        //si es false devuelve aquellos que no estan con expedientes
        $tareas = $this->getDoctrine()->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($this->getUser());
        return $this->render('AusentismoBundle:Proveedor:show.html.twig', array(
            'proveedor' => $proveedor,
            'anio' => $anio,
            'tipoTramites' => $tipoTramites,
            'tareas'=>$tareas,
            'delete_form' => $deleteForm->createView(),
        ));
    }

      /**
     * Displays a form to edit an existing proveedor entity.
     *
     * @Route("/editar/{id}/edit", name="proveedor_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Proveedor $proveedor)
    {
    
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\ProveedorNewType', $proveedor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proveedor_show', array('id' => $proveedor->getId()));
        }

        return $this->render('AusentismoBundle:Proveedor:edit.html.twig', array(
            'proveedor' => $proveedor,
            'edit_form' => $editForm->createView(),

        ));
    }

    /**
     * Deletes a proveedor entity.
     *
     * @Route("/{id}", name="proveedor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Proveedor $proveedor)
    {
        $form = $this->createDeleteForm($proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($proveedor);
            $em->flush();
            $this->get('session')->getFlashBag()->add('mensaje-info', 'Se ha ELIMINADO el proveedor: '.$proveedor );
        }

        return $this->redirectToRoute('proveedor_index');
    }

    /**
     * Creates a form to delete a proveedor entity.
     *
     * @param Proveedor $proveedor The proveedor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Proveedor $proveedor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proveedor_delete', array('id' => $proveedor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
