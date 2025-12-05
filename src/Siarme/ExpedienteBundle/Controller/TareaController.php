<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\DocumentoBundle\Entity\Historial;
use Siarme\AusentismoBundle\Util\Util;
use Siarme\ExpedienteBundle\Entity\Tarea;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Twilio\Rest\Client;

/**
 * Tarea controller.
 *
 * @Route("tarea")
 */
class TareaController extends Controller
{
    /**
     * Lists all tarea entities.
     *
     * @Route("/", name="tarea_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findAll();

        return $this->render('ExpedienteBundle:Tarea:index.html.twig', array(
            'tareas' => $tareas,
        ));
    }

    /**
     * Creates a new tarea entity.
     *
     * @Route("/new", name="tarea_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tarea = new Tarea();
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\TareaType', $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tarea);
            $em->flush();

            return $this->redirectToRoute('tarea_show', array('id' => $tarea->getId()));
        }

        return $this->render('ExpedienteBundle:Tarea:new.html.twig', array(
            'tarea' => $tarea,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new tarea entity. Se agreaga usuario COLABORADOR 
     *
     * @Route("/{id}/adquirir/new", name="adquirir_tarea_new")
     * @Method({"GET", "POST"})
     */
    public function adquirirTareaNewAction(Request $request, Tramite $tramite)
    {
        $tarea = new Tarea();

        $tarea->setUsuario($this->getUser());
        //Asigno como colaborador
        $tarea->setEsColaborador(true);
        $tarea->setFecha(new \DateTime(date('d-m-Y')));
        
        $em = $this->getDoctrine()->getManager();
       // $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);
        $tarea->setTramite($tramite);
        $em->persist($tarea);
        $em->flush();

        $msj= "Has adquirido el : ".$tramite->getTipoTramite().' N°: '.$tramite->getNumeroTramite(); 
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);

        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Creates a new tarea entity. Se agreaga usuario COLABORADOR 
     *
     * @Route("/{tramite_id}/modal/new", name="modal_tarea_new")
     * @Method({"GET", "POST"})
     */
    public function modalTareaNewAction(Request $request, $tramite_id = null)
    {
        $tarea = new Tarea();


        $tarea->setUsuario($this->getUser());
        //Asigno como colaborador
        $tarea->setEsColaborador(true);
        $tarea->setFecha(new \DateTime(date('d-m-Y')));
        
        $em = $this->getDoctrine()->getManager();
        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);
        $tarea->setTramite($tramite);
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\TareaType', $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $em->persist($tarea);
            $em->flush();

            if ($tramite->getTipoTramite()->getSlug() == "tramite_proceso")  {
                $msj= "Se ha enviado un mensaje de texto SMS a: ".$tarea->getUsuario()->getAgente()->getApellidoNombre();
                $tipo = 'mensaje-info';
                $respuesta = $this->sendSms($tarea);
                if (!$respuesta) {
                    $msj = "No se envio el SMS a ".$tarea->getUsuario()->getAgente()->getApellidoNombre();
                     $tipo = 'mensaje-warning';
                }
                $this->get('session')->getFlashBag()->add($tipo,$msj);
                /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO', 'SMS', 'EMAIL' ]*/
                $this->historial($tramite->getId(),'SMS', $msj, $tramite::TIPO_ENTIDAD);
            }
                    
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('ExpedienteBundle:Tarea:modal_new.html.twig', array(
            'tarea' => $tarea,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tarea entity.
     *
     * @Route("/{id}", name="tarea_show")
     * @Method("GET")
     */
    public function showAction(Tarea $tarea)
    {
        $deleteForm = $this->createDeleteForm($tarea);

        return $this->render('ExpedienteBundle:Tarea:show.html.twig', array(
            'tarea' => $tarea,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tarea entity.
     *
     * @Route("/{id}/edit", name="tarea_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tarea $tarea)
    {
        $deleteForm = $this->createDeleteForm($tarea);
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\TareaType', $tarea);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tarea_edit', array('id' => $tarea->getId()));
        }

        return $this->render('ExpedienteBundle:Tarea:edit.html.twig', array(
            'tarea' => $tarea,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tarea entity.
     *
     * @Route("/{id}/estado", name="tarea_cambiar_estado")
     * @Method({"GET", "POST"})
     */
    public function cambiarEstadoAction(Request $request, Tarea $tarea)
    {
        $em = $this->getDoctrine()->getManager();

        $tarea->setRealizada(!$tarea->getRealizada());
        $tarea->setFechaRealizada(new \DateTime(date('d-m-Y')));
        $em->persist($tarea);
        $em->flush();
       //recupero las pagina anterior 
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);

        $msj =  'Has cabiado el estado';
        //  $this->historial($id,'ELIMINADO', $msj );
        $this->get('session')->getFlashBag()->add(
                                'mensaje-info',$msj );

    }

    /**
     * Displays a form to edit an existing tarea entity.
     *
     * @Route("/realizar/all", name="tarea_realizar_all")
     * @Method({"GET", "POST"})
     */
    public function realizarAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByUsuario($this->getUser());
        foreach ($tareas as $tarea) {
           $tarea->setRealizada(true);
           $tarea->setFechaRealizada(new \DateTime(date('d-m-Y')));
        }

        $em->flush();
       //recupero las pagina anterior 
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);

        $msj =  'Has realizado todas las tareas';
        //  $this->historial($id,'ELIMINADO', $msj );
        $this->get('session')->getFlashBag()->add(
                                'mensaje-info',$msj );

    }

    /**
     * Deletes a tarea entity.
     *
     * @Route("/{id}/eliminar", name="tarea_eliminar")
     * @Method({"GET", "POST"})
     */
    public function eliminarAction(Request $request, Tarea $tarea)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tarea);
            $em->flush();
       //recupero las pagina anterior 
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Deletes a tarea entity.
     *
     * @Route("/{id}", name="tarea_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Tarea $tarea)
    {
        $form = $this->createDeleteForm($tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tarea);
            $em->flush();
        }

        return $this->redirectToRoute('tarea_index');
    }

    /**
     * Creates a form to delete a tarea entity.
     *
     * @param Tarea $tarea The tarea entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tarea $tarea)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tarea_delete', array('id' => $tarea->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
     public function sendSms(Tarea $tarea)
    {
      
        // Tu Account SID y Auth Token de Twilio
        $sid    = $this->container->getParameter('twilio_account_sid');
        $token  = $this->container->getParameter('twilio_auth_token');
        $twilio = new Client($sid, $token);

        // Número de Twilio con capacidad para SMS
        // $from = $this->container->getParameter('twilio_phone_sms');
          $from = $this->container->getParameter('twilio_phone_sms');
        
        // Número de destino del SMS
        $to = "+549".$tarea->getUsuario()->getAgente()->getTelefonoMovil(); // Reemplaza con el número de teléfono del destinatario

        if (!empty($tarea->getTramite()->getExpediente())) {
            // Contenido del mensaje
            $messageBody = "SE LE ASIGNO ".$tarea->getTramite()->getExpediente()->getCcoo()." EN compras-sca.online";
        } else {
            // Contenido del mensaje
            $messageBody = "Se te asignó: ".$tarea->getTramite()->getCcoo()." en compras-sca.online";
        }
        $messageBody = Util::limpiarParaGSM7($messageBody);
        try {
            $message = $twilio->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => $messageBody,
                ]
            );

            return true;
            // new Response('Mensaje SMS enviado correctamente. SID: ' . $message->sid);
        } catch (\Exception $e) {
            return  false;
            //new Response('Error al enviar el SMS: ' . $e->getMessage());
        }
    }
        
    /**
     * Historial.
     * 
     */
     public function historial($entidad, $accion, $msj, $tipo)
        {     
                $em = $this->getDoctrine()->getManager();
                $historial = new Historial();
                $historial->setTipoId($entidad);
                /** El tipo puede ser ['EXP','DOC','AG'] corresponde con las entidades Expediente, Documento, Agente */
                $historial->setTipo($tipo);
                $historial->setUsuario($this->getUser());
                $historial->setAccion($accion);
                $historial->setTexto($msj);
                $em->persist($historial);
                $em->flush();
        }

}
