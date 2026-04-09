<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\ExpedienteBundle\Entity\DatoAT;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Datoat controller.
 *
 * @Route("datoat")
 */
class DatoATController extends Controller
{
    
    /**
     *
     * @Route("/{id}/{estado}/estado", name="expediente_datoat_estado")
     * 
     */
    public function datoAtEstadoAction(DatoAT $datoAt, $estado = false)
    {
      $em= $this->getDoctrine()->getManager();
       if ($estado == "aceptado" ) {

        $datoAt->setEstado("Aceptado");
        $em->flush($datoAt);
            //Estado del expediente falso es CERRADO
        // return   $this->redirectToRoute('expediente_concluir', array('id' => $datoAt->getExpediente()->getId()));
       } 

       if ($estado == "denegado"){ 
        $datoAt->setEstado("Denegado"); 
        $em->flush($datoAt);
            //Estado del expediente falso es CERRADO
     // return  $this->redirectToRoute('expediente_concluir', array('id' => $datoAt->getExpediente()->getId()));
       }    

     if ($estado == "quitar"){ 
        $datoAt->setEstado("Sin Datos"); 
        $em->flush($datoAt);
            //Estado del expediente falso es CERRADO
     // return  $this->redirectToRoute('expediente_concluir', array('id' => $datoAt->getExpediente()->getId()));
       }    


    if ($estado == "cerrar") {

        $datoAt->setEstado("Cerrado");
        $em->flush($datoAt);
            //Estado del expediente true es ABIERTO
        return  $this->redirectToRoute('expediente_concluir', array('id' => $datoAt->getExpediente()->getId()));
     }

      if ($estado == "abrir") {

        $datoAt->setEstado("Abierto");
        $em->flush($datoAt);
            //Estado del expediente true es ABIERTO
        return     $this->redirectToRoute('expediente_iniciar', array('id' => $datoAt->getExpediente()->getId()));
     }

       return $this->redirectToRoute('expediente_show', array('id' => $datoAt->getExpediente()->getId()));
    }


   
    /**
     *
     * @Route("/{id}/{estado}/altamedica", name="expediente_datoat_altamedica")
     * 
     */
    public function datoAtAltaMedicaction(DatoAT $datoAt, $estado = false)
    {
      $em= $this->getDoctrine()->getManager();
       $datoAt->setAltaMedica( $estado);     
       $em->flush();
       return $this->redirectToRoute('expediente_show', array('id' => $datoAt->getExpediente()->getId()));
    }

    /**
     *
     * @Route("/{id}/{estado}/altalaboral", name="expediente_datoat_altalaboral")
     * 
     */
    public function datoAtAltaLaboralAction(DatoAT $datoAt, $estado = false)
    {
      $em= $this->getDoctrine()->getManager();
       $datoAt->setAltaLaboral( $estado);     
       $em->flush();
       return $this->redirectToRoute('expediente_show', array('id' => $datoAt->getExpediente()->getId()));
    }






    /**
     * Lists all datoAT entities.
     *
     * @Route("/", name="datoat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $datoATs = $em->getRepository('ExpedienteBundle:DatoAT')->findAll();

        return $this->render('ExpedienteBundle:DatoAT:turnos_index.html.twig', array(
            'datoATs' => $datoATs,
        ));
    }




    /**
     * Creates a new datoAT entity.
     *
     * @Route("/{id}/new", name="datoat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Expediente $expediente)
    {
        $datoAT = new Datoat();
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\DatoATType', $datoAT);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $datoAT->setexpediente($expediente);
            $em->persist($datoAT);            
            $em->flush();
           $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'Los datos de Denuncia A. T. del expediente: <strong> '.$expediente.' </strong> Fueron CREADOS...');
            return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));
        }

        return $this->render('ExpedienteBundle:DatoAt:new.html.twig', array(
            'datoAt' => $datoAT,
            'form' => $form->createView(),
            'expediente' => $expediente,
        ));
    }

    /**
     * Finds and displays a datoAT entity.
     *
     * @Route("/{id}/show", name="datoat_show")
     * @Method("GET")
     */
    public function showAction(DatoAT $datoAT)
    {
        $deleteForm = $this->createDeleteForm($datoAT);

        return $this->render('datoat/show.html.twig', array(
            'datoAT' => $datoAT,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing datoAT entity.
     *
     * @Route("/{id}/turno_new", name="datoat_turno_new")
     * @Method({"GET", "POST"})
     * 
     */
    public function turnoNewAction(Request $request, DatoAT $datoAT)
    {
        $deleteForm = $this->createDeleteForm($datoAT);
        $expediente = $datoAT->getExpediente();

        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\DatoATEditType', $datoAT);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
             $fechaDesde= $datoAT->getFechaTurno();
              $date = new \DateTime(date('d-m-Y'));

         if ( $fechaDesde >= $date ) {
             $datoAT->setTurno(true);
             $em->flush();
             
            
             $usuario = $this->getUser();

            $departamento  = $usuario->getDepartamentoRm();
         
            $dql = 'SELECT e 
                    FROM ExpedienteBundle:Expediente e 
                    JOIN e.datoAt dt
                    WHERE dt.fechaTurno = :fec1 AND e.departamentoRm= :id
                    ORDER BY dt.hora ASC';
         
            $query = $em->createQuery($dql);
            $query->setParameter('fec1', $fechaDesde);
            $query->setParameter('id', $departamento);
          
            $result = $query->getResult();
            $cantidad = count($result);

               //dump( $result );      exit();
            switch ($cantidad) {
                case 1:
                     $msj ='<strong class="text-primary">1° PRIMER</strong> TURNO para la Fecha: <strong>'.$fechaDesde->format('d-m-Y').'</strong><br> ';
                     break;
                case 2:
                      $msj ='<strong class="text-primary">2° SEGUNDO</strong> TURNO para la Fecha: <strong>'.$fechaDesde->format('d-m-Y').'</strong><br> ';
                      break;
                case 3:
                      $msj ='<strong class="text-primary">3° TERCER</strong> TURNO para la Fecha: <strong>'.$fechaDesde->format('d-m-Y').'</strong><br> ';
                      break;
                case 4:
                      $msj ='<strong class="text-primary">4° CUARTO</strong> TURNO para la Fecha: <strong>'.$fechaDesde->format('d-m-Y').'</strong><br> ';
                      break;
                case 5:
                      $msj ='<strong class="text-primary">5° QUINTO</strong> TURNO para la Fecha: <strong>'.$fechaDesde->format('d-m-Y').'</strong><br> ';
                      break;
                default:
                        $msj = 'Se Otorgaron: <strong class="text-primary"> '.$cantidad.' TURNOS </strong> para la Fecha: <strong>'.$fechaDesde->format('d-m-Y').'</strong><br> ';
                        break;
            }
                

            if ($cantidad > 1) {
                $i = 1;
                 foreach ($result as $expediente ) {
                            //dump( $expediente );      exit();
                           $url = $this->generateUrl('expediente_show', array('id' =>$expediente->getId()));

                            $msj= $msj.'<strong>'.$i.'-</strong> '.$expediente->getAgente().'- hora: <strong>'.$expediente->getDatoAt()->getHora()->format('H:i').'</strong> <a href="'.$url.'" role="button"><span class="glyphicon glyphicon-folder-open"></span> Ir al Expediente</a><br>';
                            $i++;
                        }
            } 


         $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);

            return $this->redirectToRoute('expediente_show', array('id' => $datoAT->getExpediente()->getId()));

         } else {
             $msj = 'La Fecha: <strong>'.$fechaDesde->format('d-m-Y').'</strong> debe ser mayor a la actual<br> ';
                  $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
                    return $this->redirectToRoute('expediente_show', array('id' => $datoAT->getExpediente()->getId()));
         }
         
          
        }

        return $this->render('ExpedienteBundle:DatoAt:turno_new.html.twig', array(
            'datoAT' => $datoAT,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a datoAT entity.
     *
     * @Route("/{id}", name="datoat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DatoAT $datoAT)
    {
        $form = $this->createDeleteForm($datoAT);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($datoAT);
            $em->flush();
        }

        return $this->redirectToRoute('datoat_index');
    }

    /**
     * Creates a form to delete a datoAT entity.
     *
     * @param DatoAT $datoAT The datoAT entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DatoAT $datoAT)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('datoat_delete', array('id' => $datoAT->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
