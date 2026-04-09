<?php

namespace Siarme\DocumentoBundle\Controller;

use Siarme\DocumentoBundle\Entity\Compartir;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Compartir controller.
 *
 * @Route("compartir")
 */
class CompartirController extends Controller
{
    /**
     * Lists all compartir entities.
     *
     * @Route("/comaptidos-index", name="compartidos_index")
     * @Method("GET")
     */
    public function compartidosIndexAction()
    {
         $em = $this->getDoctrine()->getManager();
         $usuario = $this->getUser();

        //devuelve aquellos EXPEDIENTE que pertenecen a la reparticion del usuario y no poseen TRAMITE
        $expedientesPendientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticionPendientes($usuario->getDepartamentoRm());

         //si es false devuelve aquellas que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);
        //Devuelve los TRAMITE que estan sin realizarse.
        $tareasPendientes = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteReparticion($usuario->getDepartamentoRm());
        
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario);

        return $this->render('DocumentoBundle:Compartir:compartido_index.html.twig', array(
            'compartidos' => true,
            'recordatorios' => $recordatorios,
            'tareas' => $tareas,
            'expedientesPendientes'=>$expedientesPendientes,
            'tareasPendientes'=>$tareasPendientes,
        ));
    }

    /**
     * Lists all compartir entities.
     *
     * @Route("/mensajes-index", name="mensajes_index")
     * @Method("GET")
     */
    public function mensajesIndexAction()
    {

        return $this->render('DocumentoBundle:Compartir:mensajes_index.html.twig', array(
            'mensajes' => true,
        ));
    }
    /**
     * Lists all compartir entities.
     *
     * @Route("/{tipoid}/{tipo}/index", name="compartir_index")
     * @Method("GET")
     */
    public function indexAction($tipoid = null, $tipo= null)
    {
        $em = $this->getDoctrine()->getManager();
        //Marco como visto si usuario es el que recibe
        $usuario = $this->getUser();
        $noVistos = $em->getRepository('DocumentoBundle:Compartir')->findBy(['tipoId'=>$tipoid, 'tipo'=>$tipo, 'usuarioRecive'=>$usuario, 'estado'=>false]);
        foreach ($noVistos as $comparte) {
               $comparte->setEstado(true);
               $comparte->setFechaVisto(new \Datetime(Date('d-m-Y')));
        }
        $em->flush();

        $compartidos = $em->getRepository('DocumentoBundle:Compartir')->findBy(['tipoId'=>$tipoid, 'tipo'=>$tipo]);

        return $this->render('DocumentoBundle:Compartir:index.html.twig', array(
            'compartidos' => $compartidos,
        ));
    }
    /**
     * Lists all compartir entities.
     *
     * @Route("/alerta", name="compartir_alerta")
     * @Method("GET")
     */
    public function alertaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $fecha = new \Datetime(Date('d-m-Y'));
    //    $compartiste = $em->getRepository('DocumentoBundle:Compartir')->findBy(['usuarioEnvia'=>$usuario, 'estado' => true, 'fechaVisto'=> $fecha ] );

        
        $repository = $em->getRepository('DocumentoBundle:Compartir');

        $query = $repository->createQueryBuilder('c'); 
        $query->where('c.usuarioRecive = :usuario')
              ->andWhere('c.estado = :estado')
              ->orderBy('c.id', 'DESC')       
              ->setMaxResults(20)
              ->setParameter('usuario',$usuario)
              ->setParameter('estado', false);
        $dql = $query->getQuery();
        $compartieron = $dql->getResult(); 

        return $this->render('DocumentoBundle:Compartir:alerta.html.twig', array(
           // 'compartiste' => $compartiste,
            'compartieron' => $compartieron,
        ));
    }

    /**
     * Lists all compartir entities.
     *
     * @Route("/alert_visto", name="compartir_visto")
     * @Method("GET")
     */
    public function alertaVistoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
      //  $compartiste = $em->getRepository('DocumentoBundle:Compartir')->findBy(['usuarioEnvia'=>$usuario, 'estado' => true] );

        $compartieron = $em->getRepository('DocumentoBundle:Compartir')->findBy(['usuarioRecive'=>$usuario, 'estado'=>false]);

        foreach ($compartieron as $comparte) {
               $comparte->setEstado(true);
               $comparte->setFechaVisto(new \Datetime(Date('d-m-Y')));
        }
        $em->flush();
      return new JsonResponse("Alertas Actualizadas");
    }

    /**
     * Creates a new compartir entity.
     *
     * @Route("/{tipoid}/{tipo}/new", name="compartir_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $tipoid = null, $tipo= null)
    {
        $compartir = new Compartir();
        $compartir->setFecha(new \Datetime(Date('d-m-Y')));
        $compartir->setTipoId($tipoid);
        $compartir->setTipo($tipo);
        $usuario = $this->getUser();
     //   dump($usuario); exit();
        $compartir->setUsuarioEnvia($usuario);
        $form = $this->createForm('Siarme\DocumentoBundle\Form\CompartirType', $compartir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($compartir);
            $em->flush();
            if ($tipo == "MSJ") {
            $url = $this->generateUrl('compartir_eliminar', array('id' =>$compartir->getId()));

             $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'Has enviado el MENSAJE: '.$compartir->getTexto().' a '.$compartir->getUsuarioRecive().'&nbsp;&nbsp;&nbsp;<a href="'.$url.'" role="button"> <span class="glyphicon glyphicon-trash"></span> Cancelar</a>');
            }
          /**  else{ 
             $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'Has COMPARTIDO exitosamente...');
           } **/

            $ant= $request->headers->get('referer');
           // dump($ant);      exit();
            return $this->redirect($ant);
        }

        return $this->render('DocumentoBundle:Compartir:new.html.twig', array(
            'compartir' => $compartir,
            'form' => $form->createView(),
            'tipoid'=>$tipoid,
            'tipo'=>$tipo,
        ));
    }

    /**
     * Finds and displays a compartir entity.
     *
     * @Route("/{id}/show", name="compartir_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Compartir $compartir)
    {
        $em = $this->getDoctrine()->getManager();
        $expediente_repo = $em->getRepository('ExpedienteBundle:Expediente');

        $documento_repo = $em->getRepository('DocumentoBundle:Documento');
        $agente_repo = $em->getRepository('AusentismoBundle:Agente');
                  /** El tipo puede ser ['EXP','DOC','AG','MSJ'] corresponde con las entidades Expediente, Documento, Agente, Mensaje */
        switch ($compartir->getTipo()) {
            case "EXP":
                  $expediente= $expediente_repo->find($compartir->getTipoId());
                 //"Expediente"; 
                  $compartir->setEstado(true);
                  $em->flush();
                  return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));

                break;
            case "DOC":
                  $documento = $documento_repo->find($compartir->getTipoId()); //"Documento";
                  $compartir->setEstado(true);
                  $em->flush();
                  return $this->redirectToRoute('documento_show', array('id' => $documento->getId()));
                break;
            case "AG":
                 $agente = $agente_repo->find($compartir->getTipoId()); //"Agente"; 
                 $compartir =$compartir->setEstado(true);
                  $em->flush();
                  return $this->redirectToRoute('agente_show', array('id' => $agente->getId()));
                break;
             case "MSJ":
                 $compartir =$compartir->setEstado(true);
                 $em->flush();
                 $ant= $request->headers->get('referer');
                // dump($ant);      exit();
                 return $this->redirect($ant);
                break;
             default:
                 $result = "Mensaje";

        }




    }

    /**
     * Displays a form to edit an existing compartir entity.
     *
     * @Route("/{id}/edit", name="compartir_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Compartir $compartir)
    {
        $deleteForm = $this->createDeleteForm($compartir);
        $editForm = $this->createForm('Siarme\DocumentoBundle\Form\CompartirType', $compartir);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compartir_edit', array('id' => $compartir->getId()));
        }

        return $this->render('compartir/edit.html.twig', array(
            'compartir' => $compartir,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a compartir entity.
     *
     * @Route("/{id}", name="compartir_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Compartir $compartir)
    {
        $form = $this->createDeleteForm($compartir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($compartir);
            $em->flush();
        }

        return $this->redirectToRoute('compartir_index');
    }

    /**
     * Creates a form to delete a compartir entity.
     *
     * @param Compartir $compartir The compartir entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Compartir $compartir)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compartir_delete', array('id' => $compartir->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a compartir entity.
     *
     * @Route("/{id}/eliminar", name="compartir_eliminar")
     * @Method("GET")
     */
    public function eliminarAction(Request $request, Compartir $compartir)
    {
            $em = $this->getDoctrine()->getManager();

              $em->remove($compartir);
               $em->flush();
          
            if ($compartir->getTipo() == "MSJ")  {

               $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'Has QUITADO el Mensaje ...');
            } else {


               $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'Has dejado de COMPARTIR ...');
            }

            $ant= $request->headers->get('referer');
           // dump($ant);      exit();
            return $this->redirect($ant);
           //eturn new Response("Se ha dejado de compartir");
    }

}
