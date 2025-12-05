<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Tramite;
use Siarme\ExpedienteBundle\Entity\TipoTramite;
use Siarme\ExpedienteBundle\Entity\Tarea;
use Siarme\ExpedienteBundle\Entity\Credito;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\ExpedienteBundle\Entity\Movimiento;
use Siarme\DocumentoBundle\Entity\Historial;
use Siarme\AusentismoBundle\Util\Util;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tramite controller.
 *
 * @Route("tramite")
 */
class TramiteController extends Controller
{

    /**
     * Lists all tramite entities.
     *
     * @Route("/index/{anio}", name="tramite_reparticion_index")
     * @Method("GET")
     */
    public function indexAction($anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }
        if ($this->isGranted('ROLE_SAF') ) {

                return $this->redirectToRoute('externo_saf_index', array( 'anio'=>$anio, ));
        } 
        
        if ($this->isGranted('ROLE_EXTERNO') ) {

                return $this->redirectToRoute('externo_index');
        } 

        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();



        //si es false devuelve aquellos que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);

        $tramites = $em->getRepository('ExpedienteBundle:Tramite')->findByDepartamentoRm($usuario->getDepartamentoRm(), $anio);
       // dump($tramites );
       // exit();
        //si es false devuelve aquellos que no estan con recordatorios
        
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario);

        //si es false devuelve aquellos que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);


        $acuerdos = $em->getRepository('ExpedienteBundle:Expediente')->findAcuerdo($usuario->getDepartamentoRm(), $anio);
        //devuelve aquellos EXPEDIENTE que pertenecen a la reparticion del usuario y no poseen TRAMITE
        //$expedientesPendientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticionPendientes($usuario->getDepartamentoRm());

        return $this->render('ExpedienteBundle:Tramite:reparticion_index.html.twig', array(
            'tramites' => $tramites,
            'tareas' =>  $tareas,
            'recordatorios' => $recordatorios,
            'anio'=>$anio,
            'acuerdos'=>$acuerdos,
        ));
    }

    /**
     * Lists all tramite entities.
     *
     * @Route("/mis-tramites/{anio}", name="mis_tramites_index")
     * @Method("GET")
     */
    public function misTramitesIndexAction($anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }


        $em = $this->getDoctrine()->getManager();

        // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        $usuario = $this->getUser();
        //si es false devuelve aquellos que no estan con expedientes
       // $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);
        $tramites = $em->getRepository('ExpedienteBundle:Tramite')->findByUsuario($usuario, $anio);
        //si es false devuelve aquellos que no estan con recordatorios
       
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario);
        //VERIFICO SI HAY UN EXPEDIENTE O SOLICITUD INICIADA
       // $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));
        //devuelve aquellos EXPEDIENTE que pertenecen a la reparticion del usuario y no poseen TRAMITE
       // $expedientesPendientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticionPendientes($usuario->getDepartamentoRm());
      // dump($movimientoPendiente);
      //  exit();
                //Devuelve los TRAMITE que estan sin realizarse.
      //  $tareasPendientes = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteReparticion($usuario->getDepartamentoRm());

        return $this->render('ExpedienteBundle:Tramite:mis_tramites_index.html.twig', array(
          //  'tareas' => $tareas,
            'recordatorios' => $recordatorios,
            'tramites' => $tramites,
            'anio' => $anio,
         //   'movimientoPendiente'=>$movimientoPendiente,
         //   'expedientesPendientes'=>$expedientesPendientes,
         //   'tareasPendientes'=>$tareasPendientes,
        ));
    }

    /**
     * Lists all tramite entities.
     *
     * @Route("/realizados/{anio}", name="tramites_realizados_index")
     * @Method("GET")
     */
    public function tramitesRealizadosIndexAction($anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
         //si es false devuelve aquellos que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);

        // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        //si es false devuelve aquellos que no estan con expedientes
        $realizadas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario, true);

        // buscar TRAMITE que se relacionen con USUARIO y  RECORDATORIO 
        // o aquellos que son publicos
        
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario);
        // VERIFICO SI HAY UN EXPEDIENTE O SOLICITUD INICIADA
        // $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));

        // devuelve aquellos EXPEDIENTE que pertenecen a la reparticion del usuario y no poseen TRAMITE
        // $expedientesPendientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticionPendientes($usuario->getDepartamentoRm());
        // dump($movimientoPendiente);
        //  exit();
        // Devuelve los TRAMITE que estan sin realizarse.
        // $tareasPendientes = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteReparticion($usuario->getDepartamentoRm());
        return $this->render('ExpedienteBundle:Tramite:realizados_index.html.twig', array(
            'realizadas' => $realizadas,
            'tareas' => $tareas,
            'recordatorios' => $recordatorios,
            'anio' => $anio,
            //'movimientoPendiente'=>$movimientoPendiente,
           // 'expedientesPendientes'=>$expedientesPendientes,
           // 'tareasPendientes'=>$tareasPendientes,
        ));
    }

/**-------------------------------ACCIONES PARA EXPEDINTES ------------------------------------------------*/

   /**
     * HABILITA UN EXPEDIENTE PARA AGREGAR TRAMITES Y ENVIAR. 
     *
     * @Route("/expediente_movimiento/{expediente_id}/habilitar", name="movimiento_habilitar_expediente")
     * @Method({"GET", "POST"})
     */
    public function movimientoHabilitarAction(Request $request, $expediente_id = null)
    {      
       $em = $this->getDoctrine()->getManager();
         // Asigno el expediente al tramite.
        $expediente = $em->getRepository('ExpedienteBundle:Expediente')->find($expediente_id);

       /** $tarea = new Tarea();
        $tarea->setUsuario($this->getUser());
        $tarea->setExpediente($expediente);*/

        $movimientoActivo = new Movimiento();
        $movimientoActivo->setDepartamentoRm(); 
        //$expediente->addMovimiento($movimiento); 
        $movimientoActivo->setExpediente($expediente);
        $movimientoActivo->setUsuario($this->getUser());

        
            $em->persist($movimientoActivo);
            
            $em->flush();

           return $this->redirectToRoute('mis_tramites_index');
    }

 /**
     * Agraga un TRAMITE a un EXPEDIENTE existente. 
     *
     * @Route("/{id}/expediente/{expediente_id}/new", name="tramite_expediente_add")
     * @Method({"GET", "POST"})
     */
    public function tramiteExpedienteAddAction(Request $request, Tramite $tramite, $expediente_id= null)
    {
        $em = $this->getDoctrine()->getManager();
        // Asigno el expediente al tramite.
        $expediente = $em->getRepository('ExpedienteBundle:Expediente')->find($expediente_id);
        $tramite->setExpediente($expediente);
        $expediente->setExtracto( $expediente->getExtracto()."<br />".$tramite->getOrganismoOrigen()." - $ ".number_format($tramite->getPresupuestoOficial(),2, ',', '.'));
        $em->flush();

        $msj= 'Has agregado el : '.$tramite->getTipoTramite()."-".$tramite->getNumeroTramite()."al expediente".$expediente;
        $this->get('session')->getFlashBag()->add(
                    'modal-mensaje-info',
                    $msj);
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

   /**
     * INICIA UN PROCESO PARA LUEGO ENVIAR. 
     *
     * @Route("/expediente_movimiento/{tramite_id}/new", name="movimiento_expediente_new")
     * @Method({"GET", "POST"})
     */
    public function movimientoExpedienteNewAction(Request $request, $tramite_id = null)
    {
        
        $expediente = new Expediente();


        $movimiento = new Movimiento();
        //$tarea = new Tarea();
       // $tarea->setUsuario($this->getUser());
        //$tarea->setExpediente($expediente);

        $movimiento = new Movimiento();
        $movimiento->setDepartamentoRm($this->getUser()->getDepartamentoRm()); 
        //$expediente->addMovimiento($movimiento); 
        $movimiento->setExpediente($expediente);
        $movimiento->setUsuario($this->getUser());


        $movimientoActivo = new Movimiento();
        $movimientoActivo->setDepartamentoRm(); 
        //$expediente->addMovimiento($movimiento); 
        $movimientoActivo->setExpediente($expediente);
        $movimientoActivo->setUsuario($this->getUser());

        $em = $this->getDoctrine()->getManager();
        // Asigno el expediente al tramite.
        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);
        
        // PARA CUANDO SE ENVIE EL EXPEDIENTE
       //    $movimientoActivo = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('expediente' =>  $tramite->getExpediente(), 'departamentoRm'=>$this->getUser()->getDepartamentoRm(),'activo' => true));

       // dump($movimiento);
       // exit();
       // $expediente->addTramite($tramite);
        $tramite->setExpediente($expediente);


        // ASIGNO NUMERO DE EXPEDIENTE INTERNO
        $tipoDocumentoEx = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($expediente->getSlug());
        $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());
        $tipoDocumentoEx->setNumero(1);
        
            $em->persist($expediente);
            $em->persist($movimiento);
            $em->persist($movimientoActivo);
            $em->persist($tramite);
            //$em->persist($tarea);
            $em->flush();
           
           return $this->redirectToRoute('mis_tramites_index');
    }


   /**
     * Creates a new movimiento entity.CUANDO HAY UNA SOLICITUD ACTIVA SE AGREGA EL TRAMITE A DICHA SOLICITUD
     *
     * @Route("/{tramite_id}/expediente/agregar", name="tramite_expediente_agregar")
     * @Method({"GET", "POST"})
     */
    public function tramiteExpedienteAgregarAction(Request $request, $tramite_id = null)
    {
        $expediente = new Expediente();
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        // ECUENTRO EL MOVIMIENTO PENDIENTE
        $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));
         // ECUENTRO EL EXPEDEINTE
        $expediente = $movimientoPendiente->getExpediente();


        // Asigno el expediente al tramite.
        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);
        
        $tramite->setExpediente($expediente);

            $em->persist($expediente);
            $em->persist($tramite);
            $em->flush();
           
           return $this->redirectToRoute('mis_tramites_index');
    }

   /**
     * Creates a new movimiento entity.CUANDO HAY UNA SOLICITUD ACTIVA SE AGREGA EL TRAMITE A DICHA SOLICITUD
     *
     * @Route("/expediente/{tramite_id}/quitar", name="tramite_expediente_quitar")
     * @Method({"GET", "POST"})
     */
    public function tramiteExpedienteQuitarAction(Request $request, $tramite_id = null)
    {
        
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        // ECUENTRO EL MOVIMIENTO PENDIENTE
      //  $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));
         // ECUENTRO EL EXPEDEINTE
      //  $expediente = $movimientoPendiente->getExpediente();

        // Libero el tramite.
        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);
        $tramite->setExpediente(null);
        $tareas =$em->getRepository('ExpedienteBundle:Tarea')->findBy(array('tramite'=>$tramite,'usuario' => $usuario, 'realizada'=>false));
        if (empty($tareas)) {
                //Asigno nueva tarea
                $tarea = new Tarea();
                $tarea->setUsuario($this->getUser());
                $tarea->setTramite($tramite);
                $em->persist($tarea);
        } 
                $em->persist($tramite);
                $em->flush();
           
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
    }



     /**
     * CONFIRMA EL ENVIO DEL PROCESO INICIADO.
     *
     ** @Route("/expediente_modal/send", name="modal_expediente_send")
     * @Method({"GET", "POST"})
     */
    public function modalExpedienteSendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        // ECUENTRO EL MOVIMIENTO PENDIENTE
        $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));

         // ECUENTRO EL EXPEDEINTE
        $expediente = $movimientoPendiente->getExpediente();

        $form = $this->createForm('Siarme\ExpedienteBundle\Form\MovimientoType', $movimientoPendiente);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /**-------------------------------MARCAR COMO REALIZADADA LAS TAREAS ------------------------------------------------
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
           // $em->persist($expediente);
           // $em->persist($movimientoPendiente);
           // $em->persist($tareaE);
           $usuario = $this->getUser();
               /**Obtengo el movimiento que corresponde al Departamento o Sector */
            $movimiento = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('expediente'=>$expediente, 'departamentoRm'=>$usuario->getDepartamentoRm()));
            $movimiento->setActivo(false);
        
            $em->flush();
            $msj= 'Has enviado el expediente: '.$expediente;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($expediente->getId(),'ENVIADO', $msj, $expediente::TIPO_ENTIDAD);
           return $this->redirectToRoute('mis_expedientes_index');
        }

        return $this->render('ExpedienteBundle:Tramite:modal_expediente_send.html.twig', array(
            'expediente' => $expediente,
            'tramite' => $movimientoPendiente,
            'form' => $form->createView(),
        ));
    }


/**-------------------------------</ ACCIONES PARA EXPEDIENTES------------------------------------------------*/

    /**
     * Lists all tramite entities.
     *
     * @Route("/recordatorios", name="recordatorios_index")
     * @Method("GET")
     */
    public function recordatoriosIndexAction(Request $request)
    {
        $fecha = $request->get('fecha'); 
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        // buscar TRAMITE que se relacionen con USUARIO y  RECORDATORIO 
        // o aquellos que son publicos
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario,$fecha);
        // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
       
        //si es false devuelve aquellos que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);

         // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        //VERIFICO SI HAY UN EXPEDIENTE O SOLICITUD INICIADA
     //   $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));
                //devuelve aquellos EXPEDIENTE que pertenecen a la reparticion del usuario y no poseen TRAMITE
     //   $expedientesPendientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticionPendientes($usuario->getDepartamentoRm());
         //Devuelve los TRAMITE que estan sin realizarse.
      //  $tareasPendientes = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteReparticion($usuario->getDepartamentoRm());  
        return $this->render('ExpedienteBundle:Tramite:recordatorios_index.html.twig', array(
            'recordatorios' => $recordatorios,
            'tareas' => $tareas,
     //       'movimientoPendiente' => $movimientoPendiente,
     //       'expedientesPendientes' => $expedientesPendientes,
     //       'tareasPendientes' => $tareasPendientes,
        ));
    }

    /**
     * Creates a new tramite entity.
     *
     * @Route("/{tipo_tramite_id}/new", name="tramite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $tipo_tramite_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $tramite = new Tramite();
        $tarea = new Tarea();
        $tarea->setUsuario($this->getUser());
        $tarea->setTramite($tramite);

        $tramite->setDepartamentoRm( $this->getUser()->getDepartamentoRm());
        //$tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->find(1);
        $tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->find($tipo_tramite_id);
        $tramite->setTipoTramite($tipoTramite);

        if ($tipoTramite->getDepartamentoRm() != $this->getUser()->getDepartamentoRm()) {
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }

        // ASIGNO NUMERO DE TRAMITE INTERNO
        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($tipoTramite->getSlug());
        $tramite->setNumeroTramite($tipoDocumento->getNumero());
               
        $tramite->setEstadoTramite($tipoDocumento->getEstadoTramite());
        $tramite->setEstado($tipoDocumento->getEstadoTramite()->getEstado());
        //ASIGNO UN CODIGO UNICO NECESARIO TEMPORALMENTE EN PAGOS
        if ($tramite->getTipoTramite()->getSlug() == "tramite_pago" )  {
                $codigo = substr(md5(uniqid(rand(1,6))), 0, 6);
                $tramite->setCcoo("P_".$codigo );
        }         
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\Form_'.$tramite->getTipoTramite()->getSlug().'Type', $tramite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //VERIFICO NUMERO Y GUARDO
            $tramite->setNumeroTramite($tipoDocumento->getNumero());
            $tipoDocumento->setNumero(1);

            if ( in_array($tramite->getTipoTramite()->getSlug(), ["tramite_despacho", "tramite_solicitud", "tramite_pago","tramite_proceso"])) {
                $usuario = $this->getUser();
                $movimiento = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>$usuario->getDepartamentoRm(),'expediente' => $tramite->getExpediente()));
                if (empty($movimiento)) {
                        $movimiento = new Movimiento();
                        $movimiento->setExpediente($tramite->getExpediente());
                        $movimiento->setDepartamentoRm($usuario->getDepartamentoRm());
                        $movimiento->setTexto("Crear Proceso de Compra");
                        $movimiento->setUsuario($this->getUser());
                        $em->persist($movimiento);
                }
            }
            if ($tramite->getTipoTramite()->getSlug() == "tramite_pedido" )  {
                $tramite->setTexto($tramite->getRubro());
            }
            $em->persist($tramite);
            $em->persist($tarea);
            $em->flush();

            if ($tramite->getTipoTramite()->getSlug() == "tramite_pedido" || $tramite->getTipoTramite()->getSlug() == "tramite_solicitud" ) {
                 $msj= "Has creado el : ".$tramite->getTipoTramite()." ".$tramite->getNumeroTramite()." | ".$tramite->getCcoo()." | Fecha: ".$tramite->getFechaDestino()->format('d/m/Y')." - $ ".number_format($tramite->getPresupuestoOficial(),2, ',', '.')." | Rubro: ".$tramite->getRubro().' | <span class="'.$tramite->getEstadoTramite()->getClass().'">'.$tramite->getEstadoTramite()."</span>";
            } else {

                $msj= 'Has creado el : '.$tramite->getTipoTramite()." ".$tramite->getNumeroTramite().' | <span class="'.$tramite->getEstadoTramite()->getClass().'">'.$tramite->getEstadoTramite()."</span>";
            }
                    

            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($tramite->getId(),'CREADO', $msj, $tramite::TIPO_ENTIDAD);

            return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        }

        $referer= $request->headers->get('referer');
        return $this->render('ExpedienteBundle:Tramite:new.html.twig', array(
            'tramite' => $tramite,
            'form' => $form->createView(),
            'referer' => $referer,
        ));
    }

  /**
     * Creates a new tramite entity.
     *
     * @Route("/{id}/oferta/new", name="tramite_oferta_new")
     * @Method({"GET", "POST"})
     */
    public function ofertaNewAction(Request $request, Tramite $proceso)
    {
        $em = $this->getDoctrine()->getManager();
        $tramite = new Tramite();

        $tramite->setDepartamentoRm( $this->getUser()->getDepartamentoRm());
        //$tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->find(1);
        $tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->findOneBySlug("tramite_oferta");
        $tramite->setTipoTramite($tipoTramite);
        $tramite->setProceso($proceso);
        $tramite->setExpediente($proceso->getExpediente());

        // ASIGNO NUMERO DE TRAMITE INTERNO
        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($tipoTramite->getSlug());
        $tramite->setNumeroTramite($tipoDocumento->getNumero());
               
        $tramite->setEstadoTramite( $tipoDocumento->getEstadoTramite());
        $tramite->setEstado($tipoDocumento->getEstadoTramite()->getEstado());
        
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\Form_'.$tramite->getTipoTramite()->getSlug().'Type', $tramite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //VERIFICO NUMERO Y GUARDO
            $tramite->setNumeroTramite($tipoDocumento->getNumero());
            $tipoDocumento->setNumero(1);
            $tramite->setOferente($tramite->getProveedor()->getProveedor());
            $tramite->setCuit($tramite->getProveedor()->getCuit());

            $em->persist($tramite);
            $em->flush();
            
            $msj= 'Has creado el : '.$tramite->getTipoTramite()."-".$tramite->getNumeroTramite();   

            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($tramite->getId(),'CREADO', $msj, $tramite::TIPO_ENTIDAD);

            return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        }
        $referer= $request->headers->get('referer');
        return $this->render('ExpedienteBundle:Tramite:modal_new.html.twig', array(
            'tramite' => $tramite,
            'form' => $form->createView(),
            'referer' => $referer,
        ));
    }

    /**
     * Finds and displays a tramite entity.
     *
     * @Route("/{id}", name="tramite_show")
     * @Method("GET")
     */
    public function showAction(Tramite $tramite)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario= $this->getUser();
        //VERIFICO SI HAY UN EXPEDIENTE O SOLICITUD INICIADA
        $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));
        $items = "";
        if (($tramite->getTipoTramite()->getSlug() == "tramite_proceso") and !$tramite->getEstado()){
            foreach ($tramite->getItemProceso() as $itemProceso){
                if ($itemProceso->getItemPedido()->isEmpty()) {
                    $items = $items." ".$itemProceso->getNumero().",";
                }
            }
            if (!empty($items)) {
                $msje = "<p class='text-info'> En <span class='glyphicon glyphicon-plus'></span> <span class='glyphicon glyphicon-shopping-cart'></span> ITEM SOLICITADO - Existen ítem que no se relacionaron con un ítem PEDIDO por un SAF. <br> Los ítems son: <strong>". $items."</strong> </p>";        
                $this->get('session')->getFlashBag()->add('mensaje-danger', $msje);
            }
        }
        
        return $this->render('ExpedienteBundle:'.$tramite->getTipoTramite()->getSlug().':show.html.twig', array(
            'tramite' => $tramite,
             'movimientoPendiente'=>$movimientoPendiente,
        ));
    }

    /**
     * Displays a form to edit an existing tramite entity.
     *
     * @Route("/{id}/edit", name="tramite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Tramite $tramite)
    {
    
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\Form_'.$tramite->getTipoTramite()->getSlug().'Type', $tramite);

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted()) {
          $presupuesto = $this->limpiarMoneda($tramite->getPresupuestoOficial());
          $tramite->setPresupuestoOficial($presupuesto);
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        /**
            if ($tramite->getTipoTramite()->getSlug() == "tramite_proceso") {
                    // PONDERAR  el Factor Precio 
                    $msj= $this->procesoItemPFPrecio($tramite);
                    $this->get('session')->getFlashBag()->add(
                                'mensaje-info',
                                $msj);
            } 

            if ($tramite->getTipoTramite()->getSlug() == "tramite_oferta") {
   
                    
                    // PONDERAR  el Factor Plazo de Entraga y Antecedente 
                    $msj= $this->ofertaItemPFPlazoAntecedente($tramite);
                
                    $this->get('session')->getFlashBag()->add(
                                'mensaje-info',
                                $msj);
            } 
        */

            if ($tramite->getTipoTramite()->getSlug() == "tramite_pedido" || $tramite->getTipoTramite()->getSlug() == "tramite_solicitud") {
                 $msj= "Has modificado el : ".$tramite->getTipoTramite()." ".$tramite->getNumeroTramite()." | ".$tramite->getCcoo()." | Fecha: ".$tramite->getFechaDestino()->format('d/m/Y')." - $ ".number_format($tramite->getPresupuestoOficial(),2, ',', '.')." | Rubro: ".$tramite->getRubro()." | ".$tramite->getNumeroNota().' - <span class="'.$tramite->getEstadoTramite()->getClass().'">'.$tramite->getEstadoTramite()."</span>";
            } else {
                $msj= 'Has modificado el : '.$tramite->getTipoTramite()." ".$tramite->getNumeroTramite()." | Fecha: ".$tramite->getFechaDestino()->format('d/m/Y')." | ".$tramite->getTexto();
            }


            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($tramite->getId(),'MODIFICADO', $msj, $tramite::TIPO_ENTIDAD);

           // return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
                        //recupero las pagina anterior             
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);

        } elseif ($editForm->isSubmitted()){

            $msj= 'No se guardaron los cambios,';         
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            $errors = $this->get('validator')->validate($tramite);
            // iterate on it
            foreach( $errors as $error ){
                // Do stuff with:
                //   $error->getPropertyPath() : the field that caused the error
                $msj="Verifique el campo  ".$error->getPropertyPath()." ERROR: ".$error->getMessage();
                $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            }

            //recupero las pagina anterior             
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }

                //recupero las pagina anterior 
        //$referer= $request->headers->get('referer');
        return $this->render('ExpedienteBundle:Tramite:modal_edit.html.twig', array(
            'tramite' => $tramite,
            'edit_form' => $editForm->createView(),
         //   'referer' => $referer,
        ));
    }


   /**
     * Displays a form to edit an existing tramite entity.
     *
     * @Route("/{id}/despacho/pase", name="tramite_despacho_pase")
     * @Method({"GET", "POST"})
     */
    public function tramiteDespachoPaseAction(Request $request, Tramite $tramite)
    {
    
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\Form_'.$tramite->getTipoTramite()->getSlug().'_paseType', $tramite);

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted()) {
          $presupuesto = $this->limpiarMoneda($tramite->getPresupuestoOficial());
          $tramite->setPresupuestoOficial($presupuesto);
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $msj= 'Has modificado el : '.$tramite->getTipoTramite()."-".$tramite->getNumeroTramite();
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($tramite->getId(),'MODIFICADO', $msj, $tramite::TIPO_ENTIDAD);
          //  return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        } elseif ($editForm->isSubmitted()){
            dump($tramite);
            exit();
            $msj= 'No se guardaron los cambios,';         
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            $errors = $this->get('validator')->validate($tramite);
            // iterate on it
            foreach( $errors as $error ){
                // Do stuff with:
                //   $error->getPropertyPath() : the field that caused the error
                $msj="Verifique el campo  ".$error->getPropertyPath()." ERROR: ".$error->getMessage();
                $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            }
            //recupero las pagina anterior             
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }

        //recupero las pagina anterior 
        //$referer= $request->headers->get('referer');
        return $this->render('ExpedienteBundle:Tramite:modal_despacho_pase.html.twig', array(
            'tramite' => $tramite,
            'edit_form' => $editForm->createView(),
         //   'referer' => $referer,
        ));
    }

    /**
     * Displays a form to edit an existing tramite entity.
     *
     * @Route("/{id}/proceso/ponderacion/edit", name="proceso_ponderacion_edit")
     * @Method({"GET", "POST"})
     */
    public function procesoEditPonderacionAction(Request $request, Tramite $tramite)
    {
    
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\Form_proceso_ponderacionType', $tramite);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($tramite->getTipoTramite()->getSlug() == "tramite_proceso") {
                    // PONDERAR  el Factor Precio 
                    $msj= $this->procesoItemPFPrecio($tramite);
                    $msj= $this->procesoItemPFCalidad($tramite);
                    $this->get('session')->getFlashBag()->add(
                                'mensaje-info',
                                $msj);
                  $tramite->setAdjudicado(false);
            } 

            $msj= 'Has actualizado los factores de ponderacion : '.$tramite->getTipoTramite()."-".$tramite->getNumeroTramite();

            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($tramite->getId(),'PONDERADO', $msj, $tramite::TIPO_ENTIDAD);
            return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));

        }

        return $this->render('ExpedienteBundle:'.$tramite->getTipoTramite()->getSlug().':modal_ponderacion_edit.html.twig', array(
            'tramite' => $tramite,
            'edit_form' => $editForm->createView(),
         //   'referer' => $referer,
        ));
    }

    /**
     * Displays a form to edit an existing tramite entity.
     *
     * @Route("/{id}/oferta/ponderacion/edit", name="oferta_ponderacion_edit")
     * @Method({"GET", "POST"})
     */
    public function ofertaEditPonderacionAction(Request $request, Tramite $tramite)
    {
    
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\Form_oferta_ponderacionType', $tramite);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($tramite->getTipoTramite()->getSlug() == "tramite_oferta") {
   
                    $pFPlazoEntrega= $tramite->getPFPlazoEntrega();
                    $pFAntecedente= $tramite->getPFAntecedente();
                    if ( $pFPlazoEntrega == 0 )   {
                        $msj= "Advertencia: El Plazo de Entrega es igual a 0 de ".$tramite->getOferente();
                        $this->get('session')->getFlashBag()->add(
                                    'mensaje-warning',
                                    $msj);
                    }
                    if  ($pFAntecedente == 0)  {
                        $msj= "Advertencia: El Factor Antecedente es igual a 0 de ".$tramite->getOferente();
                        $this->get('session')->getFlashBag()->add(
                                    'mensaje-warning',
                                    $msj);
                    }
                    // PONDERAR  el Factor Plazo de Entraga y Antecedente 
                    $msj= $this->ofertaItemPFPlazoAntecedente($tramite);
                    if (!empty($tramite->getProveedor())) {
                        $tramite->getProveedor()->setAntecedente($tramite->getPFAntecedente());
                    }
                    $this->get('session')->getFlashBag()->add(
                                'mensaje-info',
                                $msj);
                    $tramite->getProceso()->setAdjudicado(false);
            }  

            $msj= 'Has actualizado los factores de ponderacion : '.$tramite->getTipoTramite()."-".$tramite->getNumeroTramite();

            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($tramite->getId(),'PONDERADO', $msj, $tramite::TIPO_ENTIDAD);
           // return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
                      //recupero las pagina anterior 
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
        }

        return $this->render('ExpedienteBundle:'.$tramite->getTipoTramite()->getSlug().':modal_ponderacion_edit.html.twig', array(
            'tramite' => $tramite,
            'edit_form' => $editForm->createView(),
         //   'referer' => $referer,
        ));
    }


  /**
     * Pondera el Factores Calidad de todos itemOferta de un Proceso.
     */
    public function procesoItemPFCalidad($proceso)
    {
        $ofertas = $proceso->getOferta();
        foreach ($ofertas as $oferta) {
            $msj= $this->ofertaItemPFPlazoAntecedente($oferta);
            $items = $oferta->getItemOferta();
            foreach ($items as $itemOferta) {
                $itemOferta->setAdjudicado(false);
                $itemOferta->setCantidadAdjudicada(0);
                $calidad = $itemOferta->getCalidad();
                if ($calidad == 0 or $calidad == 1) {
                     $itemOferta->setPFCalidad(0);
                }

                if ($calidad == 2) {
                     $itemOferta->setPFCalidad( $itemOferta->getProceso()->getPFCalidadBueno());
                }

                if ($calidad == 3) {
                     $itemOferta->setPFCalidad( $itemOferta->getProceso()->getPFCalidadMuyBueno());
                }
            }

        }
        $bueno = $proceso->getPFCalidadBueno();
        $muyBueno = $proceso->getPFCalidadMuyBueno();
        $em = $this->getDoctrine()->getManager()->flush();
       $msj= "Ponderacion de Ítems con Calidad: Bueno = $bueno y muy bueno = $muyBueno ";
       return $msj;
    }

   /**
     * Pondera los Factores Precio Plazo y Antecedente excepto Calidad de  todos itemOferta de un Proceso.
     */
    public function procesoItemPFPrecio($proceso)
    {     
        $em = $this->getDoctrine()->getManager();
        //Obtengo los items ordenados por precio asc.
        $items = $em->getRepository('AusentismoBundle:ItemOferta')->findAllItem($proceso, $proceso->getTipoTramite()->getSlug());

            $i= 1;

            $pFPrecio =  $proceso->getPFPrecio();

            $item1 = 0;
            $item2 = 0;

            foreach ($items as $item) {
                $item->setAdjudicado(false);
                $item->setCantidadAdjudicada(0);
                $precio = $item->getPrecio();
                if ($i == 1) {
                    $menorPrecio =  $item->getPrecio();
                    $item->setPFPrecio($pFPrecio);
                } elseif ($item1->getNumero() == $item->getNumero()) {
                    $pFP =($menorPrecio*$pFPrecio) / $precio;
                    $item->setPFPrecio($pFP);
                    
                } else {
                    $menorPrecio =  $item->getPrecio();
                    $item->setPFPrecio($pFPrecio);                  
                }

                 $item1 = $item;
                 $i = $i + 1;
            }

        $em->flush();
        $msj= "Se ha realizado la Ponderacion de Ítems con el Factor Precio = $pFPrecio %";

        return $msj;
    }

    /**
     * Pondera todos itemOferta de un Proceso.
     */
    public function ofertaItemPFPlazoAntecedente( $oferta)
    {     

        $pFPlazoEntrega= $oferta->getPFPlazoEntrega();
        $pFAntecedente= $oferta->getPFAntecedente();

     //   $oferta = $oferta->getProceso();
        $items = $oferta->getItemOferta();

        foreach ($items as $item) {
                $item->setAdjudicado(false);
                $item->setCantidadAdjudicada(0);
                $item->setPFPlazoEntrega($pFPlazoEntrega);
                $item->setPFAntecedente($pFAntecedente);
            }
             
        $this->getDoctrine()->getManager()->flush();

        $msj= "Se realizar la Ponderacion de Ítems con el Factor Plazo de Entrega = $pFPlazoEntrega % y Antecedente = $pFAntecedente %";

        return $msj;
    }

    /**
     * Adjudita los itemOferta por mayor puntaje.
     *
     * @Route("/{id}/adjudicar", name="proceso_adjudicar")
     * @Method({"GET", "POST"})
     */
    public function adjudicarAction(Request $request, Tramite $proceso)
    {
        
        $em = $this->getDoctrine()->getManager();
        $msj= $this->procesoItemPFPrecio($proceso);
        $msj= $this->procesoItemPFCalidad($proceso);
        $ofertas = $proceso->getOferta();

        // Restablezco todos los items adjudicados 
        foreach ($ofertas as $oferta) {
            $msj= $this->ofertaItemPFPlazoAntecedente($oferta);
            $items = $oferta->getItemOferta();
            foreach ($items as $item) {
                $item->setAdjudicado(false);
                $item->setCantidad(0);
            }
        }
        $em->flush();
        
        // Adjudico los items
        $items = $em->getRepository('AusentismoBundle:itemOferta')->findAllByMayorPuntaje($proceso);
    
            $i= 1;

            $itemIguales = true;
            foreach ($items as $item) {

                $cantidadSolicitada = $item->getItemProceso()->getCantidad();
                $cantidadOfertada = $item->getCantidad();

                if ($i ==1) {

                    $cantidadFaltante = $cantidadSolicitada - $cantidadOfertada;
                    if ($cantidadFaltante  <= 0  ) {
                            $item->setCantidadAdjudicada($cantidadSolicitada);
                            $item->setAdjudicado(true);
                            $cantidadFaltante = 0;
                    } else {
                            $item->setCantidadAdjudicada($cantidadOfertada);
                            $item->setAdjudicado(true);
                    }

                } else {

                    $itemIguales = ($itemAnterior->getNumero() == $item->getNumero());

                    if ( $itemIguales and ($cantidadFaltante > 0 )) {

                        if (($cantidadFaltante - $cantidadOfertada) <= 0) {
                                $item->setCantidadAdjudicada($cantidadFaltante);
                                $item->setAdjudicado(true);
                                $cantidadFaltante = 0;
                        } else {
                                $item->setCantidadAdjudicada($cantidadOfertada);
                                $cantidadFaltante = $cantidadFaltante - $cantidadOfertada;
                                $item->setAdjudicado(true);
                        }
    
        
                    } elseif  (!$itemIguales) {

                            $cantidadFaltante = $cantidadSolicitada - $cantidadOfertada;
                            if ($cantidadFaltante  <= 0  ) {
                            $item->setCantidadAdjudicada($cantidadSolicitada);
                            $item->setAdjudicado(true);
                            $cantidadFaltante = 0;
                            } else {
                                    $item->setCantidadAdjudicada($cantidadOfertada);
                                    $item->setAdjudicado(true);
                            }
   
                    } else {
                        $item->setCantidadAdjudicada(0);
                        $item->setAdjudicado(false);
                    }
                   
                }
              

                $itemAnterior = $item;
                 $em->flush();
                 $i = $i + 1;
            }
        //ENCUENTRO EL ESTADO A CAMBIAR
        $estado = $em->getRepository('ExpedienteBundle:EstadoTramite')->find(7);
        $proceso->setEstadoTramite($estado);
        $proceso->setEstado(true);
        $proceso->setAdjudicado(true);
        $proceso->setMontoAdjudica($proceso->getMontoAdjudicado());
        $em->flush();

        $msj= "Se ha realizado la Adjudicacion de los Items %";
       //recupero las pagina anterior 
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Displays a form to edit an existing tramite entity.
     *
     * @Route("/{id}/estado/{estado_id}/cambiar", name="tramite_estado_cambiar")
     * @Method({"GET", "POST"})
     */
    public function estadoCambiarAction(Request $request, Tramite $tramite, $estado_id)
    {
        $em = $this->getDoctrine()->getManager();
    
        //ENCUENTRO EL ESTADO A CAMBIAR
        $estado = $em->getRepository('ExpedienteBundle:EstadoTramite')->find($estado_id);
        $date = new \Datetime();
        if ($estado->getSlug() == "cgp") {
           $tramite->setFechaDestino($date);
           $organismo = $em->getRepository('AusentismoBundle:Organismo')->find(10);
           $tramite->setOrganismoDestino($organismo );
        }
        if ($estado->getSlug() == "tgp") {
            $tramite->setFechaDestino($date);
            $organismo = $em->getRepository('AusentismoBundle:Organismo')->find(70);
            $tramite->setOrganismoDestino($organismo );
        }
        if ($estado->getEstado()) {
            foreach ($tramite->getTarea() as $tarea) {
                $tarea->setRealizada(true);
            }
        }
        if ($tramite->getTipoTramite()->getSlug() == "tramite_solicitud") {
            $tramite->setPresupuestoOficial($tramite->getMontoAutorizado());
        }
      
        $tramite->setEstadoTramite($estado);
        $tramite->setEstado($estado->getEstado());
        $em->persist($estado);
        $em->flush();
        if ($tramite->getTipoTramite()->getSlug() == "tramite_pedido" || $tramite->getTipoTramite()->getSlug() == "tramite_solicitud") {
            $msj= "Has cambiado el ESTADO de: ".$tramite->getTipoTramite()." ".$tramite->getNumeroTramite()." | ".$tramite->getCcoo().' | <span class="'.$tramite->getEstadoTramite()->getClass().'">'.$tramite->getEstadoTramite()."</span>";
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO', 'ESTADO' ]*/
            $this->historial($tramite->getId(),'ESTADO', $msj, $tramite::TIPO_ENTIDAD);
        } 
        if ( in_array($tramite->getTipoTramite()->getSlug(), ["tramite_despacho", "tramite_pago", "tramite_legal"])) {
            $msj= "Has cambiado el ESTADO de: ".$tramite->getTipoTramite()." ".$tramite->getNumeroTramite()." | ".$tramite->getTexto().' | <span class="'.$tramite->getEstadoTramite()->getClass().'">'.$tramite->getEstadoTramite()."</span>";
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO', 'ESTADO' ]*/
            $this->historial($tramite->getId(),'ESTADO', $msj, $tramite::TIPO_ENTIDAD);
        }  
        if ($tramite->getTipoTramite()->getSlug() == "tramite_proceso") {
            if ($estado->getEstado()) {
               $tramite->setAdjudicado(true);
            } else {
               $tramite->setAdjudicado(false);
                if ($tramite->getEstadoTramite()->getSlug() == "desierto" or $tramite->getEstadoTramite()->getSlug() == "fracasado") {
                       $ofertas = $tramite->getOferta();
                       foreach ($ofertas as $oferta) {
                             $items = $oferta->getItemOferta();
                             foreach ($items as $item) {
                                  $item->setAdjudicado(false);
                                  $item->setCantidadAdjudicada(0);
                             }
                       }
                       $em->flush();
                }
            }
            $tramite->setMontoAdjudica($tramite->getMontoAdjudicado());
            $msj= "Has cambiado el ESTADO de: ".$tramite->getTipoTramite()." ".$tramite->getNumeroTramite()." | ".$tramite->getNumeroComprar().' | <span class="'.$tramite->getEstadoTramite()->getClass().'">'.$tramite->getEstadoTramite()."</span>";
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO', 'ESTADO' ]*/
            $this->historial($tramite->getId(),'ESTADO', $msj, $tramite::TIPO_ENTIDAD);
        } 
       //recupero las pagina anterior 
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Deletes a itemPedido entity.
     *
     * @Route("/{id}/estado", name="oferta_estado")
     * @Method("GET")
     */
    public function estadoAction(Request $request, Tramite $tramite)
    {
            $estado= $tramite->getEstado();
            $tramite->setEstado(!$estado);
            $items = $tramite->getItemOferta();
            foreach ($items as $item) {
                $item->setEstado(!$estado);
                $item->setAdjudicado(false);
                $item->setCantidadAdjudicada(0);
            }
            $tramite->getProceso()->setAdjudicado(false);
            $em = $this->getDoctrine()->getManager()->flush();
       //recupero las pagina anterior 
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
       // return new Response("Has cambiado de estado del Item");
    }

    /**
     * Deletes a tramite entity.
     *
     * @Route("/{id}/eliminar", name="tramite_eliminar")
     * @Method({"GET", "POST"})
     */
    public function eliminarAction(Request $request, Tramite $tramite)
    {
         if ($tramite->getDocumento()->isEmpty()){ 
            $tramite1 = $tramite->getTipoTramite()."-".$tramite->getNumeroTramite();
            $id= $tramite->getId();
            $em = $this->getDoctrine()->getManager();
            $em->remove($tramite);
            $em->flush();
            $msj =  'Has eliminado el '.$tramite1;
          //  $this->historial($id,'ELIMINADO', $msj );
            $this->get('session')->getFlashBag()->add(
            'mensaje-info',$msj );
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($id,'ELIMINADO', $msj, $tramite::TIPO_ENTIDAD);
               
            if (empty($tramite->getProceso())) {
                    
                    if (empty($tramite->getExpediente())) {
                        //recupero las pagina anterior 
                        return $this->redirectToRoute('tramite_reparticion_index');
                    } else {
                        return $this->redirectToRoute('expediente_show', array('id' => $tramite->getExpediente()->getId()));
                    }   
               } else {
                    
                    return $this->redirectToRoute('tramite_show', array('id' => $tramite->getProceso()->getId()));
               }   

        } else {

            $msj =  'No puedes eliminar porque contiene documentos el '.$tramite;
             //  $this->historial($id,'ELIMINADO', $msj );
            $this->get('session')->getFlashBag()->add(
            'mensaje-warning',$msj );

            return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        }
    }

    /**
     * Deletes a tramite entity.
     *
     * @Route("/{id}", name="tramite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Tramite $tramite)
    {
        $form = $this->createDeleteForm($tramite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tramite);
            $em->flush();
        }

        return $this->redirectToRoute('tramite_reparticion_index');
    }

    /**
     * Creates a form to delete a tramite entity.
     *
     * @param Tramite $tramite The tramite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tramite $tramite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tramite_delete', array('id' => $tramite->getId())))
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


    /**
     * Creates a form to delete a tramite entity.
     *
     */    
function toCamelCase($string, $capitalizeFirstCharacter = false) 
    {

        $str = str_replace('-', '', ucwords($string, '-'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }
    
public function limpiarMoneda($s) 
    { 
        //Quitando Caracteres Especiales 
        $s= str_replace('$', '', $s); 
        $s= str_replace(',', '', $s);  
        return $s; 
    }
}
