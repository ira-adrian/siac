<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\ExpedienteBundle\Entity\Movimiento;
use Siarme\DocumentoBundle\Entity\Historial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Movimiento controller.
 *
 * @Route("movimiento")
 */
class MovimientoController extends Controller
{
    /**
     * Lists all movimiento entities.
     *
     * @Route("/", name="movimiento_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $movimientos = $em->getRepository('ExpedienteBundle:Movimiento')->findAll();

        return $this->render('movimiento/index.html.twig', array(
            'movimientos' => $movimientos,
        ));
    }
/**
     * CONFIRMA EL ENVIO DEL PROCESO INICIADO.
     *
     ** @Route("/{id}/modal/new", name="modal_movimiento_new")
     * @Method({"GET", "POST"})
     */
    public function modalMovimientoNewAction(Request $request, Expediente $expediente)
    {
        $em = $this->getDoctrine()->getManager();
        $dpto =  $em->getRepository('AusentismoBundle:DepartamentoRm')->findOneBySlug("dpcbs");
        $movimientoActivo = new Movimiento();

        $movimientoActivo->setExpediente($expediente);
        $movimientoActivo->setDepartamentoRm($dpto);
        $movimientoActivo->setTexto("Para continuidad del trámite");
        $movimientoActivo->setUsuario($this->getUser());
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\MovimientoType', $movimientoActivo);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /**-------------------------------MARCAR COMO REALIZADADA LAS TAREAS ------------------------------------------------
            $usuario = $this->getUser();
            //OBTENGO LA ULTIMA TAREA ACTIVA DEL EXPEDIENTETE Y CAMBIO AL ESTADO REALIZADA
            $tareaE =$em->getRepository('ExpedienteBundle:Tarea')->findOneBy(array('expediente'=>$expediente,'usuario' => $usuario, 'realizada'=>false), array('id'=>'DESC'));
            $tareaE = $tareaE->setRealizada(true);
            $tareaE = $tareaE->setFechaRealizada(new \DateTime('now'));

            //OBTENGO LA ULTIMA TAREA ACTIVA DEL TRAMITE Y CAMBIO AL ESTADO REALIZADA
            $tramites = $expediente->getTramite();
            foreach ($tramites as $tramite) {
                    $tareas =$em->getRepository('ExpedienteBundle:Tarea')->findBy(array('tramite'=>$tramite, 'realizada'=>false), array('id'=>'DESC'));
                    foreach ($tareas as $tarea) {
                        $tarea = $tarea->setRealizada(true);
                        $tarea = $tarea->setFechaRealizada(new \DateTime('now'));
                    }
            }

             /**-------------------------------/ SOLICITUDES DE PROCESOS ------------------------------------------------*/

            $em->persist($movimientoActivo);

            $usuario = $this->getUser();
             /**Obtengo el movimiento que corresponde al Departamento o Sector */
            $movimiento = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array(
                'expediente'=>$expediente, 
                'activo'=>true, 
                'departamentoRm'=>$usuario->getDepartamentoRm()
            ), array(
                'id' => 'ASC') 
            );
           // dump($movimiento);
            //exit();
            if ($movimiento) {
                $movimiento->setActivo(false);
                $em->flush();
            }

            $msj= 'Has enviado el expediente: '.$expediente;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO', 'ENVIADO']*/
            $this->historial($expediente->getId(),'ENVIADO', $msj, $expediente::TIPO_ENTIDAD);
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('ExpedienteBundle:Movimiento:modal_new.html.twig', array(
            'expediente' => $expediente,
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new movimiento entity.
     *
     * @Route("/new", name="movimiento_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $movimiento = new Movimiento();
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\MovimientoType', $movimiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movimiento);
            $em->flush();

            return $this->redirectToRoute('movimiento_show', array('id' => $movimiento->getId()));
        }

        return $this->render('movimiento/new.html.twig', array(
            'movimiento' => $movimiento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a movimiento entity.
     *
     * @Route("/{id}/estado", name="movimiento_cambiar_estado")
     * @Method("GET")
     */
    public function cambiarEstadoAction(Request $request, Expediente $expediente)
    {

        $movimientos=$expediente->getMovimiento();
        $usuario = $this->getUser();
        $ultimoMovimiento = 0;
        foreach ($movimientos as $movimiento) {    
                    if ( $movimiento->getDepartamentoRm() == $usuario->getDepartamentoRm()){
                        $ultimoMovimiento=  $movimiento;
                     }  
        }
        
        if (!empty($ultimoMovimiento)) {
            if ($ultimoMovimiento->getActivo()) {
                $ultimoMovimiento->setActivo(false);
            } else {
                $ultimoMovimiento->setActivo(true);
            } 
           $em = $this->getDoctrine()->getManager()->flush();
        }

        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Finds and displays a movimiento entity.
     *
     * @Route("/{id}", name="movimiento_show")
     * @Method("GET")
     */
    public function showAction(Movimiento $movimiento)
    {
        $deleteForm = $this->createDeleteForm($movimiento);

        return $this->render('movimiento/show.html.twig', array(
            'movimiento' => $movimiento,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing movimiento entity.
     *
     * @Route("/{id}/edit", name="movimiento_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Movimiento $movimiento)
    {
        $deleteForm = $this->createDeleteForm($movimiento);
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\MovimientoType', $movimiento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movimiento_edit', array('id' => $movimiento->getId()));
        }

        return $this->render('movimiento/edit.html.twig', array(
            'movimiento' => $movimiento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a movimiento entity.
     *
     * @Route("/{id}/eliminar", name="movimiento_eliminar")
     * @Method("GET")
     */
    public function eliminarAction(Request $request, Movimiento $movimiento)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movimiento);
            $em->flush();

            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
    }

    /**
     * Deletes a movimiento entity.
     *
     * @Route("/{id}", name="movimiento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Movimiento $movimiento)
    {
        $form = $this->createDeleteForm($movimiento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movimiento);
            $em->flush();
        }

        return $this->redirectToRoute('movimiento_index');
    }

    /**
     * Creates a form to delete a movimiento entity.
     *
     * @param Movimiento $movimiento The movimiento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Movimiento $movimiento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('movimiento_delete', array('id' => $movimiento->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
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
