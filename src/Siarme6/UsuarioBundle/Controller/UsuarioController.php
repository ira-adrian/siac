<?php

namespace Siarme\UsuarioBundle\Controller;

use Siarme\UsuarioBundle\Entity\Usuario;
use Siarme\AusentismoBundle\Entity\Agente;
use Siarme\AusentismoBundle\Entity\DepartamentoRm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * Datoat controller.
 *
 * @Route("usuario")
 */
class UsuarioController extends Controller
{
  /**
    * @Route("/index", name="usuario_index")
    * @Method({"GET", "POST"})
    */
    public function indexAction(Request $request)
    {
       

   /** AQUI QUEDE ESTE CODIGO ESTA BIEN  */
        $repository = $this->getDoctrine()
            ->getRepository('AusentismoBundle:Agente');
        $query = $repository->createQueryBuilder('a');    
       /** 
        if (!$this->isGranted('ROLE_ADMIN')) {
            $query->join('a.usuario', 'u')
                  ->andWhere('u.departamentoRm = :dpto')
                  ->setParameter('dpto', $this->getUser()->getDepartamentoRm()->getId());
        }
        */
        $usuario = $this->getUser();
        if ($usuario->getDepartamentoRm()->getSlug() != "info" ) {
            $query->join('a.usuario', 'u')
                  ->andWhere('u.esActivo = :st')
                  ->setParameter('st', true);
        }

        $query->getQuery();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            100 /*limit per page*/
                        );

       $em = $this->getDoctrine()->getManager();
       $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($this->getUser());
    
        if  ($pagination->getTotalItemCount() == 0){
          $this->get('session')->getFlashBag()->add(
          'mensaje-info',
          '<strong> Ops... </strong> No se han encontrado usuarios'
          ); 

        }
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }
        $acuerdos = $em->getRepository('ExpedienteBundle:Expediente')->findAcuerdo($this->getUser()->getDepartamentoRm(), $anio);
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($this->getUser());
        return $this->render('UsuarioBundle:usuario:index.html.twig', array(
            'usuarios' => $pagination,
            'tareas' => $tareas,
            'acuerdos' => $acuerdos,
            'recordatorios' => $recordatorios,
        ));
    }

    /**
    * @Route("/{id}/new", name="usuario_new")
    * @Method({"GET", "POST"})
    * @ParamConverter("agente", class="AusentismoBundle:Agente")
    */
    public function newAction(Request $request , Agente $agente)
    {
        $usuario = new Usuario();
        $usuario->setAgente($agente);
        $usuario->setSalt("salt");

        $usuario->setDepartamentoRm($this->getUser()->getDepartamentoRm());

        $form = $this->createForm('Siarme\UsuarioBundle\Form\UsuarioType', $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush($usuario);

            return $this->redirectToRoute('usuario_show', array('id' => $usuario->getId()));
        }

        return $this->render('UsuarioBundle:usuario:new.html.twig', array(
            'usuario' => $usuario,
            'form' => $form->createView(),
            'agente' => $agente,
        ));
    }

    /**
    * @Route("/{id}/sector/new", name="usuario_sector_new")
    * @Method({"GET", "POST"})
    *
    */
    public function sectorNewAction(Request $request , Usuario $usuario)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPERVISOR', null, 'No estas autorizado para acceder a esta Página !');
        $Form = $this->createForm('Siarme\UsuarioBundle\Form\SectorType', $usuario);
        $Form->handleRequest($request);

        if ($Form->isSubmitted() && $Form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'Los NUEVOS datos fueron guardados...');
            return $this->redirectToRoute('usuario_show', array('id' => $usuario->getId()));
        }

        return $this->render('UsuarioBundle:usuario:sector_modal_new.html.twig', array(
            'usuario' => $usuario,
            'form' => $Form->createView(),
        ));
    }

   /**
    * @Route("/{id}/sector/edit", name="usuario_cambiar_sector")
    * @Method("GET")
    * 
    */
    public function cambiarSectorAction(Request $request, DepartamentoRm $dpto)
    {
        /**
        $usuario = $this->getUser();
        $usuario->setDepartamentoRm($dpto);
        $this->getDoctrine()->getManager()->flush($usuario);
        */
        $usuario = $this->getUser();
        if (in_array($dpto, $usuario->getSector()->toArray())) {
            $usuario->setDepartamentoRm($dpto);
            $this->getDoctrine()->getManager()->flush($usuario);
          
        } else {
           $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    'El usuario no tiene asignado el Sector Seleccionado');
        }
        //return $this->redirectToRoute('tramite_reparticion_index');
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }
    
   /**
    * @Route("/show/{id}", name="usuario_show")
    * @Method("GET")
    * 
    */
    public function showAction(Usuario $usuario)
    {
        $deleteForm = $this->createDeleteForm($usuario);

        return $this->render('UsuarioBundle:usuario:show.html.twig', array(
            'usuario' => $usuario,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/show-perfil/{id}", name="usuario_show_perfil")
     * @Method("GET")
     * 
     */
    public function showPerfilAction(Usuario $usuario)
    {
      if ($usuario->getId() == $this->getUser()->getId() ){
       
        return $this->render('UsuarioBundle:usuario:perfil_show.html.twig', array(
            'usuario' => $usuario,
             ));
      }else{
      
              throw $this->createAccessDeniedException();

      }
    }


    /**
     * @Route("/{id}/edit", name="usuario_edit")
     * @Method({"GET", "POST"})
    */
    public function editAction(Request $request, Usuario $usuario)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPERVISOR', null, 'No estas autorizado para acceder a esta Página !');
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('Siarme\UsuarioBundle\Form\UsuarioType', $usuario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'Los NUEVOS datos fueron guardados...');
            return $this->redirectToRoute('usuario_show', array('id' => $usuario->getId()));
        }

        return $this->render('UsuarioBundle:usuario:edit.html.twig', array(
            'usuario' => $usuario,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


     /**
    * @Route("/edit-perfil/{id}", name="usuario_edit_perfil")
    * @Method({"GET", "POST"})
    */
    public function editPerfilAction(Request $request, Usuario $usuario)
    {
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('Siarme\UsuarioBundle\Form\PerfilUsuarioType', $usuario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if (null !== $usuario->getPasswordEnClaro()) {
                $encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
                $passwordCodificado = $encoder->encodePassword( $usuario->getPasswordEnClaro(), null );
                $usuario->setPassword($passwordCodificado);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('usuario_show', array('id' => $usuario->getId()));
        }

        return $this->render('UsuarioBundle:usuario:perfil_edit.html.twig', array(
            'usuario' => $usuario,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

   /**
    * @Route("/{id}/delete", name="usuario_delete")
    * @Method("DELETE")
    */
    public function deleteAction(Request $request, Usuario $usuario)
    {
        $form = $this->createDeleteForm($usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($usuario);
            $em->flush($usuario);
        }

        return $this->redirectToRoute('usuario_index');
    }

     /**
     * 
     * @param Usuario $usuario The usuario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Usuario $usuario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuario_delete', array('id' => $usuario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
