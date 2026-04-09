<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\GeneralBundle\Entity\ItemAcuerdoMarco;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Siarme\AusentismoBundle\Entity\Rubro;
use Siarme\ExpedienteBundle\Entity\TipoTramite;
use Siarme\ExpedienteBundle\Entity\Tarea;
use Siarme\ExpedienteBundle\Entity\Movimiento;
use Siarme\DocumentoBundle\Entity\Historial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Expediente controller.
 *
 * @Route("expediente")
 */
class ExpedienteController extends Controller
{
    /**
     * Lists all expediente entities.
     *
     * @Route("/index/{anio}", name="expediente_reparticion_index")
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

        // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        $usuario = $this->getUser();
        
        //si es false devuelve aquellos expedientes que pertenecen a la reparticion del usuario
        $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticion($usuario->getDepartamentoRm(), $anio);

        //devuelve aquellos EXPEDIENTE que pertenecen a la reparticion del usuario y no poseen TRAMITE
       // $expedientesPendientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticionPendientes($usuario->getDepartamentoRm());
        
        $acuerdos = $em->getRepository('ExpedienteBundle:Expediente')->findAcuerdo($usuario->getDepartamentoRm(), $anio);

         //si es false devuelve aquellas que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);
       
     //   $tramites = $em->getRepository('ExpedienteBundle:Tramite')->findByDepartamentoRm($usuario->getDepartamentoRm(), $anio);
        //Devuelve los TRAMITE que estan sin realizarse.
       // $tareasPendientes = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteReparticion($usuario->getDepartamentoRm());
        
        // buscar TRAMITE que se relacionen con USUARIO y  RECORDATORIO 
        // o aquellos que son publicos
        
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario);

        return $this->render('ExpedienteBundle:Expediente:reparticion_index.html.twig', array(
            'expedientes' => $expedientes,
            'recordatorios' => $recordatorios,
           // 'expedientesPendientes' => $expedientesPendientes,
            'tareas' => $tareas,
            'acuerdos' => $acuerdos,
            'anio'=>$anio,
      //      'tramites'=>$tramites,
        ));
    }


    /**
     * Lists all tramite entities.
     *
     * @Route("/realizados", name="expedientes_realizados_index")
     * @Method("GET")
     */
    public function expedientesRealizadosIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        $usuario = $this->getUser();

        // devuelve aquellos expedientes que estan con tareas por defecto (realizada = false)
        $expedientes = $em->getRepository('ExpedienteBundle:Tarea')->findByExpedienteUsuario($usuario, true);

         //si es false devuelve aquellos que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);
        // buscar TRAMITE que se relacionen con USUARIO y  RECORDATORIO 
        // o aquellos que son publicos
        
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario);
        //VERIFICO SI HAY UN EXPEDIENTE O SOLICITUD INICIADA
        $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));

        //devuelve aquellos EXPEDIENTE que pertenecen a la reparticion del usuario y no poseen TRAMITE
        $expedientesPendientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticionPendientes($usuario->getDepartamentoRm());
        return $this->render('ExpedienteBundle:Expediente:realizados_index.html.twig', array(
            'expedientes' => $expedientes,
            'recordatorios' => $recordatorios,
            'tareas' => $tareas,
            'movimientoPendiente'=>$movimientoPendiente,
            'expedientesPendientes'=>$expedientesPendientes,
        ));
    }

    /** FALTA REALIZAR ASOCIACION CON RECORDATORIO
     * Lists all tramite entities.
     *
     * @Route("/recordatorios", name="expedientes_recordatorios_index")
     * @Method("GET")
     */
    public function recordatoriosIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // buscar TRAMITE que se relacionen con USUARIO y  RECORDATORIO 
        // o aquellos que son publicos
        $id = $this->getUser()->getId();
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByExpedienteUsuario($id);

         // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        $usuario = $this->getUser();
        //VERIFICO SI HAY UN EXPEDIENTE O SOLICITUD INICIADA
        $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));
        
        return $this->render('ExpedienteBundle:Expediente:recordatorios_index.html.twig', array(
            'recordatorios' => $recordatorios,
            'movimientoPendiente' => $movimientoPendiente,
        ));
    }

    /**
     * Creates a new expediente entity.
     *
     * @Route("/{slug}/new", name="expediente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $slug = null)
    {

        // EXPEDIENTE
        $expediente = new Expediente();        

        $movimiento = new Movimiento();
        $movimiento->setDepartamentoRm($this->getUser()->getDepartamentoRm()); 
        //$expediente->addMovimiento($movimiento); 
        $movimiento->setExpediente($expediente);
        $movimiento->setUsuario($this->getUser());

        // ASIGNO NUMERO DE EXPEDIENTE INTERNO
        $em = $this->getDoctrine()->getManager();
        $tipo = $em->getRepository('ExpedienteBundle:TipoExpediente')->findOneBySlug($slug);
        $tipoDocumentoEx = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($tipo->getSlug());
       
        $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());
        $expediente->setTipoExpediente($tipo);
        if ($tipo->getSlug() == "exp_acuerdo") {
           // $organismos = $em->getRepository('AusentismoBundle:Organismo')->findByMinisterio(17);
         //  $expediente->setOrganismos($organismos);
           $expediente->setExtracto("ACUERDO MARCO ");

           // dump($expediente);
           // exit();
        }

        $form = $this->createForm('Siarme\ExpedienteBundle\Form\ExpedienteType', $expediente, ['accion' => $slug]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // VERIFICO Y ASIGNO NUMERO
            $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());

            // se asigna automaticamente en entity el numero + 1
            $tipoDocumentoEx->setNumero(1);
            
            $em->persist($movimiento);
            $em->persist($expediente);
            $em->flush();
            // MENSAJE EXPEDIENTE
            $msj= 'Se ha creado el expediente: '.$expediente;
            /**$this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
          $this->historial($expediente->getId(),'CREADO', $msj, $expediente::TIPO_ENTIDAD);


        return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));

        }
        $referer= $request->headers->get('referer');
        
        return $this->render('ExpedienteBundle:Expediente:new.html.twig', array(
            'expediente' => $expediente,
            'form' => $form->createView(),
            'referer' => $referer,
        ));
    }

    /**
     * Creates a new expediente entity.
     *
     * @Route("/prorroga/{id}/new", name="expediente_porroga_new")
     * @Method({"GET", "POST"})
     */
    public function prorrogaNewAction(Request $request, Expediente $exp)
    {
        // EXPEDIENTE
        $expediente = new Expediente();  
        $acuerdo = $exp;
        if (empty($exp->getAcuerdo()) and $exp->getProrroga()->isEmpty()) {
            $expediente->setAcuerdo($acuerdo);
           // dump($acuerdo->getId()); exit();
        } else {
            foreach ($exp->getProrroga() as $prorroga) {
                $acuerdo = $prorroga;
            }
            $expediente->setAcuerdo($acuerdo->getAcuerdo());
           // dump($acuerdo->getAcuerdo()->getId()); exit();
        }


        //obtengo la cantidad de días
        $date1 = $acuerdo->getFechaDesde();
        $date2 = $acuerdo->getFechaHasta();
        $diff = $date1->diff($date2);

         // calcular la nueva fechas desde 
         $fechaDesde = date_modify($acuerdo->getFechaHasta(), '+1 day');
         $fechaDesde = $fechaDesde->format('Y-m-d');
        // calcular la nueva fechas desde 
         $fechaHasta = strtotime ( '+'.($diff->days).' day' , strtotime ( $fechaDesde ) ) ;
         $fechaHasta = date ( 'Y-m-d' , $fechaHasta );
         $fechaHasta = new \DateTime($fechaHasta);
         $fechaDesde = new \DateTime($fechaDesde);

       /** dump($diff->days);
        $diff = $fechaDesde->diff($fechaHasta);
        dump($diff->days);
        dump($fechaDesde);
        dump($fechaHasta);
        dump(count($expediente->getItemAcuerdoMarco()));
        dump(count($expediente->getTramite()));
        exit();*/
        $expediente->setFechaDesde($fechaDesde);
        $expediente->setFechaHasta($fechaHasta);
        $expediente->setCcoo(null);
        $expediente->setNumeroGde(null);
        $expediente->setExtracto("PRORROGA DE ". $acuerdo->getExtracto());
        $expediente->setOrganismos($acuerdo->getOrganismos());


        $movimiento = new Movimiento();
        $movimiento->setDepartamentoRm($this->getUser()->getDepartamentoRm()); 
        //$expediente->addMovimiento($movimiento); 
        $movimiento->setExpediente($expediente);
        $movimiento->setUsuario($this->getUser());

        $slug = $acuerdo->getTipoExpediente()->getSlug();
        // ASIGNO NUMERO DE EXPEDIENTE INTERNO
        $em = $this->getDoctrine()->getManager();
        $tipo = $em->getRepository('ExpedienteBundle:TipoExpediente')->findOneBySlug($slug);
        $tipoDocumentoEx =  $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($tipo->getSlug());
    
        $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());
        $expediente->setTipoExpediente($tipo);

        $form = $this->createForm('Siarme\ExpedienteBundle\Form\ExpedienteType', $expediente, ['accion' => $slug]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // VERIFICO Y ASIGNO NUMERO
            $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());

            // se asigna automaticamente en entity el numero + 1
            $tipoDocumentoEx->setNumero(1);
            foreach ($acuerdo->getItemAcuerdoMarco() as  $item) {
                 $itemAc = new ItemAcuerdoMarco();
                 $itemAc = clone $item;
                 $itemAc->setExpediente($expediente);
                 $em->persist($itemAc);
             }
            $em->persist($movimiento);
            $em->persist($expediente);
            $em->flush();
            // MENSAJE EXPEDIENTE
            $msj= 'Se ha creado el expediente: '.$expediente;
            /**$this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
          $this->historial($expediente->getId(),'CREADO', $msj, $expediente::TIPO_ENTIDAD);


        return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));

        }
        $referer= $request->headers->get('referer');
        $msj= "Se ha calculado el nuevo periodo <br/> Fecha de Inicio: <strong>".$fechaDesde->format('d-m-Y')."</strong>  y la Fecha de Finalizacion: <strong>".$fechaHasta->format('d-m-Y')."</strong>, con un total de <strong>$diff->days días</strong>" ;
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);

        return $this->render('ExpedienteBundle:Expediente:new.html.twig', array(
            'expediente' => $expediente,
            'form' => $form->createView(),
            'referer' => $referer,
        ));
    }

    /**
     * Creates a new expediente entity.
     *
     * @Route("/prorroga/pago/{id}/new", name="expediente_porroga_pago_new")
     * @Method({"GET", "POST"})
     */
    public function prorrogaPagoNewAction(Request $request, Expediente $exp)
    {
        // EXPEDIENTE
        $expediente = new Expediente();  
        $acuerdo = $exp;
        if (empty($exp->getAcuerdo()) and $exp->getProrroga()->isEmpty()) {
            $expediente->setAcuerdo($acuerdo);
           // dump($acuerdo->getId()); exit();
        } else {
            foreach ($exp->getProrroga() as $prorroga) {
                $acuerdo = $prorroga;
            }
            $expediente->setAcuerdo($acuerdo->getAcuerdo());
           // dump($acuerdo->getAcuerdo()->getId()); exit();
        }
        $date = new \Datetime();
        $expediente->setCcoo("RS-".$date->format("Y")."-00-CAT-SCA#MEC");
        $expediente->setNumeroGde("(Prorroga) ".$acuerdo->getNumeroGde());
        $expediente->setExtracto("PRORROGA DE ". $acuerdo->getExtracto());
        $expediente->setOrganismos($acuerdo->getOrganismos());


        $movimiento = new Movimiento();
        $movimiento->setDepartamentoRm($this->getUser()->getDepartamentoRm()); 
        //$expediente->addMovimiento($movimiento); 
        $movimiento->setExpediente($expediente);
        $movimiento->setUsuario($this->getUser());

        $slug = $acuerdo->getTipoExpediente()->getSlug();
        // ASIGNO NUMERO DE EXPEDIENTE INTERNO
        $em = $this->getDoctrine()->getManager();
        $tipo = $em->getRepository('ExpedienteBundle:TipoExpediente')->findOneBySlug($slug);
        $tipoDocumentoEx =  $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($tipo->getSlug());
    
        $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());
        $expediente->setTipoExpediente($tipo);
        // se asigna automaticamente en entity el numero + 1
        $tipoDocumentoEx->setNumero(1);
        $em->persist($movimiento);
        $em->persist($expediente);
        $em->flush();
        // MENSAJE EXPEDIENTE
        $msj= 'Se ha creado el expediente: '.$expediente;
        /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
        $this->historial($expediente->getId(),'CREADO', $msj, $expediente::TIPO_ENTIDAD);
        return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));
    }
    
    /**
     * Creates a new expediente entity.
     *
     * @Route("/pedido/new", name="expediente_pedido_new")
     * @Method({"GET", "POST"})
     */
    public function pedidoNewAction(Request $request)
    {

        // EXPEDIENTE
        $expediente = new Expediente();        
       // $tarea = new Tarea();
        //$tarea->setUsuario($this->getUser());
       // $tarea->setExpediente($expediente);

        $em = $this->getDoctrine()->getManager();
        $movimiento = new Movimiento();
        $movimiento->setDepartamentoRm($this->getUser()->getDepartamentoRm()); 
        //$expediente->addMovimiento($movimiento); 
        $movimiento->setExpediente($expediente);
        $movimiento->setUsuario($this->getUser());

         // ASIGNO NUMERO DE EXPEDIENTE INTERNO
        $tipoDocumentoEx = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($expediente->getSlug());
        $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());

        //TRAMITE

        $tramite = new Tramite();
        $tarea = new Tarea();
        $tarea->setUsuario($this->getUser());
        $tarea->setTramite($tramite);

        $tramite->setDepartamentoRm( $this->getUser()->getDepartamentoRm());

        //Busco el tramite PEDIDO
        $tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->find(1);
        $tramite->setTipoTramite($tipoTramite);

        // ASIGNO NUMERO DE TRAMITE INTERNO
        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($tipoTramite->getSlug());
        $tramite->setEstadoTramite( $tipoDocumento->getEstadoTramite());
        $tramite->setNumeroTramite($tipoDocumento->getNumero());
        
        $tramite->setExpediente($expediente);
        $expediente->addTramite($tramite);
   /**     $rubro = new Rubro();
        $form_rubro = $this->createForm('Siarme\AusentismoBundle\Form\RubroType', $rubro);
        $form_rubro->handleRequest($request);
        if ($form_rubro->isSubmitted() && $form_rubro->isValid()) {
           $em->persist($rubro);
           $em->flush();
           $tramite->setRubro($rubro);
            // MENSAJE EXPEDIENTE
            $msj= 'Se ha creado un nuevo rubro';
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        }*/

        $form = $this->createForm('Siarme\ExpedienteBundle\Form\ExpedientePedidoType', $expediente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // VERIFICO Y ASIGNO NUMERO
            $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());
            $tramite->setNumeroTramite($tipoDocumento->getNumero());
            // se asigna automaticamente en entity el numero + 1
            $tipoDocumentoEx->setNumero(1);
            $tipoDocumento->setNumero(1);
            
            $tramite->setTexto($tramite->getRubro());
            $expediente->setExtracto($tramite->getRubro()."<br />".$tramite->getOrganismoOrigen()." - $ ".number_format($tramite->getPresupuestoOficial(),2, ',', '.'));
            $em->persist($movimiento);
            $em->persist($expediente);
            $em->persist($tramite);
            $em->persist($tarea);
            $em->flush();
            // MENSAJE EXPEDIENTE
            $msj= 'Se ha creado el expediente: '.$expediente;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
          $this->historial($expediente->getId(),'CREADO', $msj, $expediente::TIPO_ENTIDAD);


          // MENSAJE TRAMITE
         $msj= 'Has creado el : '.$tramite->getTipoTramite()."-".$tramite->getNumeroTramite();
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
         $this->historial($tramite->getId(),'CREADO', $msj, $tramite::TIPO_ENTIDAD);

        return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));

        }
        $referer= $request->headers->get('referer');
        
        return $this->render('ExpedienteBundle:Expediente:new.html.twig', array(
            'expediente' => $expediente,
            'form' => $form->createView(),
          //  'form_rubro' => $form_rubro->createView(),
            'referer' => $referer,
        ));
    }

    /**
     * Creates a new tramite entity.
     *
     * @Route("/{id}/tipo/{tipo_id}/new", name="tramite_expediente_new")
     * @Method({"GET", "POST"})
     */
    public function tramiteExpedienteNewAction(Request $request, Expediente $expediente, $tipo_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $tramite = new Tramite();
        $tramite->setExpediente($expediente);
        $tarea = new Tarea();
        $tarea->setUsuario($this->getUser());
        $tarea->setTramite($tramite);

        $tramite->setDepartamentoRm( $this->getUser()->getDepartamentoRm());
        //$tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->find(1);
        $tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->find($tipo_id);
        $tramite->setTipoTramite($tipoTramite);

        // ASIGNO NUMERO DE TRAMITE INTERNO
        $tipoDocumentoTr = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($tipoTramite->getSlug());
        $tramite->setNumeroTramite($tipoDocumentoTr->getNumero());

        $tramite->setEstadoTramite( $tipoDocumentoTr->getEstadoTramite());
        $tramite->setEstado($tipoDocumentoTr->getEstadoTramite()->getEstado());

        if ($tipoTramite->getSlug() == "tramite_solicitud") {
              $tramite->setRubro($em->getRepository('AusentismoBundle:Rubro')->find(180));
              $tramite->setTexto($expediente->getExtracto());
              $tramite->setPresupuestoOficial(0);
        }
        if ($tramite->getTipoTramite()->getSlug() == "tramite_pago" )  {
                $codigo = substr(md5(uniqid(rand(1,6))), 0, 6);
                $tramite->setCcoo("P_".$codigo );
        } 
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\Form_'.$tramite->getTipoTramite()->getSlug().'Type', $tramite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tramite->setNumeroTramite($tipoDocumentoTr->getNumero());
            $tipoDocumentoTr->setNumero(1);
            $tramite->setTexto($tramite->getRubro());

            if ($tipoTramite->getSlug() == "tramite_pedido") {
              $expediente->setExtracto($expediente->getExtracto()."<br />".$tramite->getOrganismoOrigen()." - $ ".number_format($tramite->getPresupuestoOficial(),2, ',', '.'));
            }
            if ( in_array($tramite->getTipoTramite()->getSlug(), ["tramite_despacho", "tramite_solicitud", "tramite_pago"])) {
                $usuario = $this->getUser();
                $movimiento = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>$usuario->getDepartamentoRm(),'expediente' => $tramite->getExpediente()));
                if (empty($movimiento)) {
                        $movimiento = new Movimiento();
                        $movimiento->setExpediente($tramite->getExpediente());
                        $movimiento->setDepartamentoRm($usuario->getDepartamentoRm());
                        $movimiento->setTexto("Continuidad del Trámite");
                        $movimiento->setUsuario($this->getUser());
                        $em->persist($movimiento);
                }
            }

            $em->persist($tramite);
            $em->persist($tarea);
            $em->flush();

            if ($tramite->getTipoTramite()->getSlug() == "tramite_pedido") {
                 $msj= "Has creado el : ".$tramite->getTipoTramite()." ".$tramite->getNumeroTramite()." | ".$tramite->getCcoo()." | Fecha: ".$tramite->getFechaDestino()->format('d/m/Y')." - $ ".number_format($tramite->getPresupuestoOficial(),2, ',', '.')." | Rubro: ".$tramite->getRubro().' | <span class="'.$tramite->getEstadoTramite()->getClass().'">'.$tramite->getEstadoTramite()."</span>";
            } else {

                $msj= 'Has creado el : '.$tramite->getTipoTramite()." ".$tramite->getNumeroTramite().' | <span class="'.$tramite->getEstadoTramite()->getClass().'">'.$tramite->getEstadoTramite()."</span>";
            }
            
          /**  $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
          $this->historial($tramite->getId(),'CREADO', $msj, $tramite::TIPO_ENTIDAD);
             return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
        //    return $this->redirectToRoute('expediente_show', array('id' => $tramite->getExpediente()->getId()));
        }
        $referer= $request->headers->get('referer');
        return $this->render('ExpedienteBundle:Tramite:new.html.twig', array(
            'tramite' => $tramite,
            'form' => $form->createView(),
            'referer' => $referer,
        ));
    }
    
    /**
     * LISTA LOS EXPEDIENTES DE LOS QUE TENGO TAREAS.
     *
     * @Route("/mis_expedientes", name="mis_expedientes_index")
     * @Method("GET")
     */
    public function misExpedientesIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        $usuario = $this->getUser();
        //si es false devuelve aquellos que no estan con expedientes
        $expedientes = $em->getRepository('ExpedienteBundle:Tarea')->findByExpedienteUsuario($usuario);
         //si es false devuelve aquellos que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);
        // buscar TRAMITE que se relacionen con USUARIO y  RECORDATORIO 
        // o aquellos que son publicos
       
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario);
        //VERIFICO SI HAY UN EXPEDIENTE O SOLICITUD INICIADA
        $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));
        
        //devuelve aquellos EXPEDIENTE que pertenecen a la reparticion del usuario y no poseen TRAMITE
        $expedientesPendientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticionPendientes($usuario->getDepartamentoRm());
      // dump($movimientoPendiente);
      //  exit();

        return $this->render('ExpedienteBundle:Expediente:mis_expedientes_index.html.twig', array(
            'expedientes' => $expedientes,
            'recordatorios' => $recordatorios,
            'tareas' => $tareas,
            'movimientoPendiente'=>$movimientoPendiente,
            'expedientesPendientes'=>$expedientesPendientes,
        ));
    }


    /**
     * Creates a new expediente entity.
     *
     ** @Route("/{tramite_id}/modal/new", name="modal_expediente_new")
     * @Method({"GET", "POST"})
     */
    public function modalExpedienteNewAction(Request $request, $tramite_id = null)
    {
        $expediente = new Expediente();
        $movimiento = new Movimiento();

        $movimiento->setDepartamentoRm($this->getUser()->getDepartamentoRm()); 
        //$expediente->addMovimiento($movimiento); 
        $movimiento->setExpediente($expediente);
        $movimiento->setUsuario($this->getUser());

        $em = $this->getDoctrine()->getManager();

  


        // Asigno el expediente al tramite.
        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);
        
        /** PARA CUANDO SE ENVIE EL EXPEDIENTE
        $movimientoActivo = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('expediente' =>  $tramite->getExpediente(), 'departamentoRm'=>$this->getUser()->getDepartamentoRm(),'activo' => true));*/

       // dump($movimiento);
       // exit();
       // $expediente->addTramite($tramite);
        $tramite->setExpediente($expediente);

        // ASIGNO NUMERO DE EXPEDIENTE INTERNO
        $tipoDocumentoEx = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($expediente->getSlug());
        $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());
        
        $form = $this->createForm('Siarme\ExpedienteBundle\Form\MovimientoType', $movimiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $expediente->setNumeroInterno($tipoDocumentoEx->getNumero());
            $tipoDocumentoEx->setNumero(1);
            $em->persist($expediente);
            $em->persist($movimiento);
            $em->persist($tramite);

            $em->flush();
            $msj= 'Has creado el expediente: '.$expediente;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            $msj= 'Has creado el tramite: '.$tramite;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($expediente->getId(),'CREADO', $msj, $expediente::TIPO_ENTIDAD);
            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('ExpedienteBundle:Expediente:modal_pendiente_send.html.twig', array(
            'expediente' => $expediente,
            'tramite' => $tramite,
            'form' => $form->createView(),
        ));
    }

     /**
     * MUESTRA EL EXPEDIENTE. 
     *
     * @Route("/expediente/{id}/show", name="expediente_show")
     * @Method({"GET", "POST"})
     */
    public function tramiteExpedienteShowrAction(Request $request, Expediente $expediente)
    {      
       $em = $this->getDoctrine()->getManager();
         // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        $usuario = $this->getUser();
        //VERIFICO SI HAY UN EXPEDIENTE O SOLICITUD INICIADA
        $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));
        $tipoTramites = $em->getRepository('ExpedienteBundle:TipoTramite')->findByExpediente($expediente);
                   // devuelve aquellos TRAMITE que no estan asociados con EXPEDIENTE
        $organismos = $em->getRepository('AusentismoBundle:Organismo')->findAll();

        if ($expediente->getTipoExpediente()->getSlug() == "exp_acuerdo") {
            return $this->render('GeneralBundle:Expediente:show.html.twig', array(
            'expediente' => $expediente,
            'organismos' => $organismos,
            'movimientoPendiente'=>$movimientoPendiente,
            'tipoTramites'=>$tipoTramites,
        ));

        }
          $tramitesActivos = $em->getRepository('ExpedienteBundle:Tramite')->findByDapartamentoRmActivo($usuario->getDepartamentoRm());
        return $this->render('ExpedienteBundle:Expediente:show.html.twig', array(
            'expediente' => $expediente,
            'tramitesActivos' => $tramitesActivos,
            'movimientoPendiente'=>$movimientoPendiente,
            'tipoTramites'=>$tipoTramites,
        ));


    }


     /**
     * Displays a form to edit an existing tramite entity.
     *
     * @Route("/expediente/{id}/estado/{estado_id}/cambiar", name="expediente_estado_cambiar")
     * @Method({"GET", "POST"})
     */
    public function estadoCambiarAction(Request $request, Expediente $expediente, $estado_id)
    {
        $em = $this->getDoctrine()->getManager();
    
        //ENCUENTRO EL ESTADO A CAMBIAR
        if ($estado_id == 0 ) {
            $estado = false;
        }
        if ($estado_id == 1 ) {
            $estado = true;
        }
        if ($estado_id == 2 ) {
            $estado = null;
        }
        $expediente->setEstado($estado);
        $em->flush();
       //recupero las pagina anterior 
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }
    
     /**
     * MUESTRA EL EXPEDIENTE. 
     *
     * @Route("/expediente/{id}", name="expediente_detalle_show")
     * @Method({"GET", "POST"})
     */
    public function expedienteDetalleAction(Request $request, Expediente $expediente, $id = null)
    {      
       $em = $this->getDoctrine()->getManager();
         // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        $usuario = $this->getUser();
        $tipoTramites = $em->getRepository('ExpedienteBundle:TipoTramite')->findByExpediente($expediente);

        return $this->render('ExpedienteBundle:Expediente:modal_show.html.twig', array(
            'expediente' => $expediente,
            'tipoTramites'=>$tipoTramites,
        ));


    }

    /**
     * Displays a form to edit an existing expediente entity.
     *
     * @Route("/{id}/edit", name="expediente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Expediente $expediente)
    {
        
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\ExpedienteEditType', $expediente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
             $msj= 'Has modificado el expediente: '.$expediente;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($expediente->getId(),'MODIFICADO', $msj, $expediente::TIPO_ENTIDAD);

            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        }  elseif ($editForm->isSubmitted()){

            $msj= 'No se guardaron los cambios,';         
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            $errors = $this->get('validator')->validate($expediente);
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
            $referer= $request->headers->get('referer');

        return $this->render('ExpedienteBundle:Expediente:modal_edit.html.twig', array(
            'expediente' => $expediente,
            'referer' => $referer,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing expediente entity.
     *
     * @Route("/{id}/gde/edit", name="expediente_gde_edit")
     * @Method({"GET", "POST"})
     */
    public function gdeEditAction(Request $request, Expediente $expediente)
    {
        
        $editForm = $this->createForm('Siarme\ExpedienteBundle\Form\ExpedienteGdeEditType', $expediente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
             $msj= 'Has modificado el expediente: '.$expediente;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($expediente->getId(),'MODIFICADO', $msj, $expediente::TIPO_ENTIDAD);

            $referer= $request->headers->get('referer');
            return $this->redirect($referer);
        } elseif ($editForm->isSubmitted()){

            $msj= 'No se guardaron los cambios,';         
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
            $errors = $this->get('validator')->validate($expediente);
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



        $referer= $request->headers->get('referer');
        return $this->render('ExpedienteBundle:Expediente:modal_gde_edit.html.twig', array(
            'expediente' => $expediente,
            'referer' => $referer,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a expediente entity.
     *
     * @Route("/eliminar/{id}", name="expediente_eliminar")
    * @Method({"GET", "POST"})
     */
    public function eliminarAction(Request $request, Expediente $expediente)
    {
        if  ($expediente->getTramite()->isEmpty() || count($expediente->getTramite())==1)  {
            $entidad = $expediente->getId();
            $em = $this->getDoctrine()->getManager();
            $em->remove($expediente);
            $em->flush();
             /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
            $msj= 'Has eliminado el Expediente '.$entidad;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            if ($expediente->getTipoExpediente()->getSlug() == "exp_acuerdo") {
                return $this->redirectToRoute('general_reparticion_index');
            } else {


                return $this->redirectToRoute('expediente_reparticion_index');
            }

        } else {
            $msj= 'No puede eliminar porque el Expediente contiene tramites'.$expediente;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-warning',
                    $msj);
         return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));
        }

    }


    /**
     * Deletes a expediente entity.
     *
     * @Route("/cancelar/enviar", name="expediente_cancelar_enviar")
    * @Method({"GET", "POST"})
     */
    public function cancelarEnviarAction()
    {     
           $em = $this->getDoctrine()->getManager();
          // buscar movimiento que se relacionen con USUARIO
          $usuario = $this->getUser();

            // ECUENTRO EL MOVIMIENTO PENDIENTE
           $movimientoPendiente = $em->getRepository('ExpedienteBundle:Movimiento')->findOneBy(array('departamentoRm'=>null,'usuario' => $usuario));
           $expediente = $movimientoPendiente->getExpediente();
            $em->remove($movimientoPendiente);
            $em->flush();

            $msj= 'Has guardado del expediente '.$expediente;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
           //$this->historial($expediente->getId(),'ELIMINADO', $msj, $expediente::TIPO_ENTIDAD);
          //  $referer= $request->headers->get('referer');
           // return $this->redirect($referer);

        return $this->redirectToRoute('mis_expedientes_index');
    }
    
    /**
     * cambiar estado de EXPEDIENTE entity.
     *
     * @Route("/{id}/estado", name="expediente_estado")
     * @Method("POST")
     */
    public function estadoAction(Request $request, Expediente $expediente)
    {

            $em = $this->getDoctrine()->getManager();

            
            if ($expediente->getEstado()) {
                $expediente->setEstado(false);
                $msj = "false";
            } else {
                $expediente->setEstado(true);
                $msj = "true";
            }
            
            $em->flush();

        return new Response($msj);
    }
    
    /**
     * Deletes a expediente entity.
     *
     * @Route("/{id}", name="expediente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Expediente $expediente)
    {
        $form = $this->createDeleteForm($expediente);
        $form->handleRequest($request);
        $id= $expediente->getId();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($expediente);
            $em->flush();

             $msj= 'Se ha eliminado el expediente: '.$expediente;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
               $this->historial($id,'ELIMINADO', $msj, $expediente::TIPO_ENTIDAD);
        }

        if ($expediente->getTipoExpediente()->getSlug() == "exp_acuerdo") {
            return $this->redirectToRoute('general_reparticion_index');
        } else {


            return $this->redirectToRoute('expediente_reparticion_index');
        }

    }

    /**
     * Creates a form to delete a expediente entity.
     *
     * @param Expediente $expediente The expediente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Expediente $expediente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('expediente_delete', array('id' => $expediente->getId())))
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

public function limpiarMoneda($s) 
    { 
        //Quitando Caracteres Especiales 
        $s= str_replace('$', '', $s); 
        $s= str_replace(',', '', $s);  
        return $s; 
    }
}
