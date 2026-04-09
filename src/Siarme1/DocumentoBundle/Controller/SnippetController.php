<?php

namespace Siarme\DocumentoBundle\Controller;


use Siarme\DocumentoBundle\Entity\Documento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



/**
 * Documento controller.
 *
 * @Route("snippet")
 */
class SnippetController extends Controller
{

     /**
     * Lists all snippet entities.
     *
     * @Route("/", name="snippet_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        
        $cons = trim($request->get('consulta'));     
   
   /** AQUI QUEDE ESTE CODIGO ESTA BIEN  */
        $repository = $this->getDoctrine()
            ->getRepository('DocumentoBundle:Documento');
         
        $query = $repository->createQueryBuilder('d');
        $query->Where('d.slug = :slug')
              ->andWhere('d.usuario = :usuario')
              ->setParameter('usuario',  $this->getUser()->getId())
              ->setParameter('slug', 'snippet');
        if (!empty($cons)) {     
              $query->andWhere('d.texto like :keyword')
            ->setParameter('keyword', '%'.$cons.'%');
        }
        $query->orderBy('d.fechaDocumento', 'ASC')
              ->getQuery();                                   

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20 /*limit per page*/
                        );
  
      if  ($pagination->getTotalItemCount() == 0){
          $this->get('session')->getFlashBag()->add(
          'mensaje-info',
          'No se ha encontrado ningun REGISTRO que conicida con la busqueda:'.$cons
          ); 

       }
   
        // parameters to template
        return $this->render('DocumentoBundle:Snippet:index.html.twig', array(
            'pagination' => $pagination,
             'find' => $cons));
    }
    
    /**
     * Creates a new documento as snippet entity.
     *
     * @Route("/{slug}/new", name="snippet_new")
     * 
     */
    public function newAction(Request $request, $slug)
    {

        $em = $this->getDoctrine()->getManager();


        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($slug);  
        $documento = new Documento();
        $documento->setTipoDocumento($tipoDocumento);
        $documento->setNombreDocumento($tipoDocumento->getNombreDocumento());
        $fecha= new \DateTime('now');       
        $documento->setFechaDocumento( $fecha);

        $documento->setSlug($slug);
        $documento->setEstado(false);
        $numero = $this->getDoctrine()->getManager();
        $numero = $em->getRepository('DocumentoBundle:TipoDocumento')->getNumeroDoc($slug);
        $documento->setNumero($numero);        
        $documento->setAnio($fecha->format('Y'));
        $documento->setUsuario($this->getUser());
        $tipo = $tipoDocumento ->getRol()->getRoleName();

        $form = $this->createForm('Siarme\DocumentoBundle\Form\Doc'.$tipo.'Type', $documento); 
   
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tipoDocumento->setNumero($documento->getNumero());
            $em->persist($documento);
            $em->flush();
            
             $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'Se ha CREADO el Documento: <strong> '.$documento.' </strong>');

        return $this->redirectToRoute('snippet_index');
        }

        return $this->render('DocumentoBundle:Snippet:documento_new.html.twig', array(
            'documento' => $documento,
            'slug'=>$slug,
            'form' => $form->createView(),

        ));
    }





    /**
     * Displays a form to edit an existing documento entity.
     *
     * @Route("/{id}/edit", name="snippet_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Documento $documento)
    {
        $deleteForm = $this->createDeleteForm($documento);
        $tipo = $documento->getTipoDocumento();
        $editForm = $this->createForm('Siarme\DocumentoBundle\Form\Doc'.$tipo.'Type', $documento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
          
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'Se guardaron los cambios del Registro <strong> '.$documento.' </strong> con EXITO...');

            return $this->redirectToRoute('snippet_index');
        }
        $slug = $documento->getSlug();
        return $this->render('DocumentoBundle:Snippet:documento_edit.html.twig', array(
            'documento' => $documento,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'slug' =>$documento->getSlug(),
        ));
    }

    /**
     * Deletes a documento entity.
     *
     * @Route("/{id}/delete", name="extranet_documento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Documento $documento)
    {
        $form = $this->createDeleteForm($documento);
        $form->handleRequest($request);
        $tramite= $documento->getTramite();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documento);
            $em->flush($documento);
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'El documento <strong> '.$documento.' </strong> fue ELIMINADO...');
        }
        $em = $this->getDoctrine()->getManager();

        
        return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
    }


    /**
     * Creates a form to delete a documento entity.
     *
     * @param Documento $documento The documento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Documento $documento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('extranet_documento_delete', array('id' => $documento->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * 
     * @Route("/{id}/eliminar", name="snippet_eliminar")
     * 
     */
    public function eliminarAction(Documento $documento)
    {
    
      $em = $this->getDoctrine()->getManager();
      $em->remove($documento);
      $em->flush($documento);
      $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'El Registro <strong> '.$documento.' </strong> fue ELIMINADO...');
      return $this->redirectToRoute('snippet_index');
    }
     
}
