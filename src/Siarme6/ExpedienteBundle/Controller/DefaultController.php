<?php
//src/Siarme/ExpedienteBundle/Controller/DefaultController.php

namespace Siarme\ExpedienteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



class DefaultController extends Controller
{

    /**
     * @Route("/")
     * 
     * @Route("/informacion/{pagina}/", 
     * defaults={ "pagina" = "inicio" },
     * requirements={ "pagina"="inicio|contacto" },
     * name="portada_estatica"
     *)
     */
   
    public function estaticaAction(Request $request, $pagina="inicio")
    {
       
    return $this->render('ExpedienteBundle:Portada:inicio.html.twig');

    }


    
    /**
     *
     * @Route("/informe/tramite", name="informe_tramite")
     *  
     */
    public function informeTramiteAction($fechaDesde, $fechaHasta)
    {
        //var_dump($opcion);
        //exit();
        $em = $this->getDoctrine()->getManager();
        
        $clasificaciones = $em->getRepository('ExpedienteBundle:Clasificacion')->findAll();
        $tipoTramites = $em->getRepository('ExpedienteBundle:TipoTramite')->findAll();
        $usuario = $this->getUser();
        $departamento  = $usuario->getDepartamentoRm();
        $informe=array();
        $informe_principal = array();

        foreach ($clasificaciones as $clasificacion) { 

           // $expedientes =  $em->getRepository('ExpedienteBundle:Expediente')->findBy(
             //                  array('departamentoRm' => $departamento,
               //                      'clasificacion' => $clasificacion)); 
      //$fechaDesde = new \DateTime('2016-01-01');
      //$fechaHasta = new \DateTime('2017-01-01');
              $consulta = $em->createQuery(
                        'SELECT e
                        FROM ExpedienteBundle:Expediente e JOIN e.tramite t 
                        JOIN e.departamentoRm d
                        JOIN e.clasificacion c
                        WHERE e.departamentoRm= :id 
                        AND e.clasificacion= :idc
                        AND t.fechaOrigen> :fec1 
                        AND t.fechaOrigen< :fec2 
                        ');
               $consulta->setParameter('id', $departamento);
               $consulta->setParameter('idc', $clasificacion);
               $consulta->setParameter('fec1', $fechaDesde);
               $consulta->setParameter('fec2', $fechaHasta);
               $expedientes = $consulta->getResult(); 
               $informe=array();
               $informe[$clasificacion->getClasificacion()]= count($expedientes);


            foreach ($tipoTramites as $tipoTramite){

                //$tramites = $em->getRepository('ExpedienteBundle:Tramite')
                  //              ->findTramiteClasificacion($departamento, $clasificacion, $tipoTramite, $fechaDesde, $fechaHasta); 
                 $consulta = $em->createQuery(
                'SELECT t
                FROM ExpedienteBundle:Tramite t JOIN t.tipoTramite tt
                JOIN t.expediente e                        
                JOIN e.clasificacion c
                WHERE e.departamentoRm = :id 
                AND e.clasificacion    = :idc 
                AND t.fechaOrigen> :fec1 
                AND t.fechaOrigen< :fec2 
                AND  t.tipoTramite     = :idr');
                $consulta->setParameter('id', $departamento);
                $consulta->setParameter('idc', $clasificacion);
                $consulta->setParameter('idr', $tipoTramite);
                $consulta->setParameter('fec1', $fechaDesde);
                $consulta->setParameter('fec2', $fechaHasta);
                $tramites              = $consulta->getResult(); 

                 $informe[$tipoTramite->getTipoTramite()] = count($tramites);
             }                        

            
            $informe_principal[$clasificacion->getClasificacion()]= $informe;            
        }
     // dump($informe_principal);
     // exit();
        if (!$informe_principal){
           $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'No se han encontrado resultados para la consulta');
            return $this->redirectToRoute('informe_new');          
        }
     return $this->render('ExpedienteBundle:Default:informe_tramite.html.twig', array(
            'tipoTramites' => $tipoTramites,  
            'clasificaciones'=>$clasificaciones,
            'informe' => $informe_principal,
            'fechaDesde'=>$fechaDesde,
            'fechaHasta'=>$fechaHasta
        ));

    }


    public function informeLicenciaAction($fechaDesde, $fechaHasta)
    {
        //var_dump($opcion);
        //exit();
        $em = $this->getDoctrine()->getManager();
        
        $clasificaciones = $em->getRepository('ExpedienteBundle:Clasificacion')->findAll();
        $tipoLicencias = $em->getRepository('AusentismoBundle:Articulo')->findAll();
        $usuario = $this->getUser();
        $departamento  = $usuario->getDepartamentoRm();
        $informe=array();
        $informe_principal = array();

        foreach ($clasificaciones as $clasificacion) { 

           // $expedientes =  $em->getRepository('ExpedienteBundle:Expediente')->findBy(
             //                  array('departamentoRm' => $departamento,
               //                      'clasificacion' => $clasificacion)); 
      //$fechaDesde = new \DateTime('2016-01-01');
      //$fechaHasta = new \DateTime('2017-01-01');
              $consulta = $em->createQuery(
                        'SELECT e
                        FROM ExpedienteBundle:Expediente e 
                        JOIN e.tramite t 
                        JOIN e.departamentoRm d
                        JOIN e.clasificacion c
                        WHERE e.departamentoRm= :id 
                        AND e.clasificacion= :idc
                        AND t.fechaOrigen> :fec1 
                        AND t.fechaOrigen< :fec2 
                        
                        ');
               $consulta->setParameter('id', $departamento);
               $consulta->setParameter('idc', $clasificacion);
               $consulta->setParameter('fec1', $fechaDesde);
               $consulta->setParameter('fec2', $fechaHasta);
               $expedientes = $consulta->getResult(); 

             $informe=array();
            $informe[$clasificacion->getClasificacion()]= count($expedientes);


            foreach ($tipoLicencias as $tipoLicencia){

                //$tramites = $em->getRepository('ExpedienteBundle:Tramite')
                  //              ->findTramiteClasificacion($departamento, $clasificacion, $tipoTramite, $fechaDesde, $fechaHasta); 
                 $consulta = $em->createQuery(
               'SELECT l
                FROM AusentismoBundle:Licencia l 
                JOIN l.articulo a
                JOIN l.documento dm
                JOIN dm.tramite t 
                JOIN t.expediente e                        
                JOIN e.clasificacion c
                WHERE e.departamentoRm = :id 
                AND e.clasificacion    = :idc 
                AND t.fechaOrigen> :fec1 
                AND t.fechaOrigen< :fec2 
                AND  l.articulo     = :idr');
                $consulta->setParameter('id', $departamento);
                $consulta->setParameter('idc', $clasificacion);

                $consulta->setParameter('idr', $tipoLicencia);
                $consulta->setParameter('fec1', $fechaDesde);
                $consulta->setParameter('fec2', $fechaHasta);
                $tramites = $consulta->getResult(); 

                 $informe[$tipoLicencia->getDescripcion()] = count($tramites);
             }                        

            $informe_principal[$clasificacion->getClasificacion()]= $informe;            
        }
       dump($informe_principal);
       exit();
        if (!$informe_principal){
           $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'No se han encontrado resultados para la consulta');
            return $this->redirectToRoute('informe_new');          
        }
     return $this->render('ExpedienteBundle:Default:informe_licencia.html.twig', array(
            'tipoTramites' => $tipoLicencias,  
            'clasificaciones'=>$clasificaciones,
            'informe' => $informe_principal,
            'fechaDesde'=>$fechaDesde,
            'fechaHasta'=>$fechaHasta
        ));

    }

            public function juntasMedicasPorOrganismosAction($fechaDesde, $fechaHasta)
    {
         $em = $this->getDoctrine()->getManager();
     
        /*$dql = 'SELECT dt.estado AS estado, o.organismo AS organismo, COUNT(dt.id) AS cantidad
                FROM ExpedienteBundle:DatoAT dt 
                JOIN dt.expediente e
                JOIN e.agente a
                JOIN a.cargo c
                INNER JOIN c.organismo o
                WHERE dt.fechaAt >= :fec1 
                AND dt.fechaAt <= :fec2
                GROUP BY  o, dt.estado';
     
        $query = $em->createQuery($dql);
        $query->setParameter('fec1', $fechaDesde);
        $query->setParameter('fec2', $fechaHasta);
        dump( $query->getResult());
        exit();
        $informe = $query->getResult();*/

        $dql = 'SELECT dt.estado AS estado, o.organismo AS organismo, COUNT(dt.id) AS cantidad
                FROM AusentismoBundle:Organismo o 
                JOIN o.cargo c
                JOIN c.agente a
                JOIN a.expediente e
                INNER JOIN e.datoAt dt
                WHERE dt.fechaAt >= :fec1 
                AND dt.fechaAt <= :fec2
                GROUP BY  o, dt.estado';
     
        $query = $em->createQuery($dql);
        $query->setParameter('fec1', $fechaDesde);
        $query->setParameter('fec2', $fechaHasta);
       // dump( $query->getResult());
       // exit();
        $informe = $query->getResult();

        /*
            array:5 [▼
              0 => array:3 [▼
                "estado" => "Aceptado"
                "organismo" => "DCCION. DE SERV. DE RECONOC. MEDICO"
                "cantidad" => "1"
              ]
              1 => array:3 [▶]
              2 => array:3 [▶]
              3 => array:3 [▶]
              4 => array:3 [▶]
            ]
        */
          $org= array();
          $dt=array();
          $org = array('organismo' => array(), 'datos' => array());
          foreach ($informe as $key => $fila) {

                    $dt[$fila['organismo']][]= $fila;

            }   
            $informe = $dt;
        // esto devuelbe un array y no array de  objetos  $query->getScalarResult()
        //return $query->getResult();
        return $this->render('ExpedienteBundle:Default:juntas_medicas_por_organismos.html.twig', array(
            'informe' => $informe,
            'fechaDesde'=>$fechaDesde,
            'fechaHasta'=>$fechaHasta
        ));
        
    }
       public function turnosJuntasMedicasAction($fechaDesde, $fechaHasta)
    {
         $em = $this->getDoctrine()->getManager();
     
        $dql = 'SELECT dt As turnos, dt.tipo AS tipo, dt.fechaAt AS fecha, COUNT(dt.id) AS cantidad
                FROM ExpedienteBundle:DatoAT dt 
                WHERE dt.fechaAt >= :fec1 
                AND dt.fechaAt <= :fec2
                GROUP BY  dt.tipo , dt.fechaAt ';
     
        $query = $em->createQuery($dql);
        $query->setParameter('fec1', $fechaDesde);
        $query->setParameter('fec2', $fechaHasta);
       // dump( $query->getResult());
       // exit();
        $informe = $query->getResult();
        // esto devuelbe un array y no array de  objetos  $query->getScalarResult()
        //return $query->getResult();
        return $this->render('ExpedienteBundle:Default:turnos_juntas_medicas.html.twig', array(
            'informe' => $informe,
            'fechaDesde'=>$fechaDesde,
            'fechaHasta'=>$fechaHasta
        ));
        
    }

           public function juntasMedicasPorClasificacionAction($fechaDesde, $fechaHasta)
    {

        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $departamento  = $usuario->getDepartamentoRm();
     
        $dql = 'SELECT e As expedientes, tp.tipoTramite As tipo, cl.clasificacion AS clasificacion, COUNT(e.id) AS cantidad
                FROM ExpedienteBundle:Expediente e 
                JOIN e.tramite t
                JOIN t.documento dc
                JOIN dc.tipoDocumento td
                JOIN e.clasificacion cl
                JOIN e.tipoTramite tp
                WHERE t.fechaOrigen >= :fec1 
                AND t.fechaOrigen <= :fec2
                AND e.departamentoRm = :id
                AND td.slug = :slug
                GROUP BY  cl.clasificacion , tp.tipoTramite ';
     
        $query = $em->createQuery($dql);
        $query->setParameter('fec1', $fechaDesde);
        $query->setParameter('fec2', $fechaHasta);
        $query->setParameter('id', $departamento);
        $query->setParameter('slug', "cese-prestacion");
        dump( $query->getResult());
        exit();

        $informe = $query->getResult();
        // esto devuelbe un array y no array de  objetos  $query->getScalarResult()
        // return $query->getResult();

        return $this->render('ExpedienteBundle:Default:turnos_juntas_medicas.html.twig', array(
            'informe' => $informe,
            'fechaDesde'=>$fechaDesde,
            'fechaHasta'=>$fechaHasta
        ));
    }

    /**
     *
     * @Route("/informe/new", name="informe_new")
     * @param DepartamentoRm $departamento 
     */
    public function informeNewAction(Request $request)
    {

      //  $defaultData = array('message' => 'Crear Informe');
            $form = $this->createFormBuilder()
            ->add('opcion',ChoiceType::class, array(
                    'choices' => array( 'TURNOS para Juntas Medicas' => 0, 'CANTIDAD de Juntas Medicas por ORGANISMO' => 1, 'Juntas Medicas por Clasificacion' => 2),
                       'label'  => 'Seleccione Una Opcion'
                ))
            ->add('fechaDesde', DateType::class, ['widget' => 'single_text'])
            ->add('fechaHasta', DateType::class, ['widget' => 'single_text'])
            ->getForm();
     

        $form->handleRequest($request);
     
        if ($form->isValid()) {
            // data es un array con claves 'name', 'email', y 'message'
            $data = $form->getData();


           $op=$data['opcion'];
             //var_dump($op);
            //exit();
           
           $fd=$data['fechaDesde'];
           $fh=$data['fechaHasta'];
           if ($op == 0) {
                $response = $this->forward('ExpedienteBundle:Default:turnosJuntasMedicas', array(
                'fechaDesde'=>$fd,
                'fechaHasta'=>$fh,
                ));
                return $response;
                }
                elseif ($op == 1) {
                 $response = $this->forward('ExpedienteBundle:Default:juntasMedicasPorOrganismos', array(
                'fechaDesde'=>$fd,
                'fechaHasta'=>$fh, ));
                return $response;
                }
                else{
                 $response = $this->forward('ExpedienteBundle:Default:juntasMedicasPorClasificacion', array(
                'fechaDesde'=>$fd,
                'fechaHasta'=>$fh, ));
                return $response;

                }
            }

        return $this->render('ExpedienteBundle:Default:informe_new.html.twig', array( 
            'form'=>$form->createView()));
    }



     /**
     * 
     * @Route("/buscar", name="buscar" )
     */
   
    public function buscarAction(Request $request, $pagina="inicio")
    {
       
    $cons = $request->query->get('consulta');      
        
         $var1 = is_numeric($cons);
         $var2 = preg_match('/(^[0-9]{1,8})-([0-9]{4,4})/', $cons);


      $em = $this->getDoctrine()->getManager();
      if ($var1) { 
        $num = $cons; 
        $anio = date("Y");

        $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findBy(array(
            'numero' => $num
        ));
       } else{
                if (is_string($cons)) {
                     $consulta = $em->createQuery(
                        'SELECT e, a, t, c, d, 
                         FROM ExpedienteBundle:Expediente e 
                         JOIN e.agente a  
                         JOIN e.tramite t 
                         JOIN e.clasificacion c
                         JOIN e.departamentoRm d
                         WHERE a.apellidoNombre LIKE :key1 OR e.extracto LIKE :key2 
                         ORDER BY a.apellidoNombre ASC'
                        );

                    $consulta->setParameter('key1', '%'.$cons.'%');
                    $consulta->setParameter('key2', '%'.$cons.'%');
                    $expedientes = $consulta->getResult();
             }

       }

      if ($var2) {
             $a = explode( "-" , $cons);
             $num = intval($a[0]);
              $anio = $a[1]; 

        $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findBy(array(
            'numero' => $num,
            'anio' => $anio
        ));
        }

   

      if (!$expedientes) {
          $this->get('session')->getFlashBag()->add(
          'mensaje-info',
          '<strong> Ops... </strong> No se ha encontrado ningun expediente que conicida con la busqueda:'.$cons
          );

        return $this->redirectToRoute('expediente_reparticion_index');
           
      }
        
        $em = $this->getDoctrine()->getManager();

       $tiposdocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findAll();
       $departamento = $em->getRepository('AusentismoBundle:DepartamentoRm')->findAll();

     
     return $this->render('ExpedienteBundle:Intranet:index.html.twig', array(
            'expedientes' => $expedientes, 
            'tiposdocumento'=>$tiposdocumento
        ));
}


    /**
     * @Route("/expediente/iniciado/departamento/{id}", name="expediente_iniciado_departamento")
     *
     */

    public function expedienteIniciadoByDepartamentoAction($id )
    {

       $em = $this->getDoctrine()->getManager();
       $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findExpedienteIniciadoByDepartamento($id);
       //El orden es importante porque la primer consuta se cachea y luego las consultas restantes se hacen desde cache
       // y son mas especificas
       $menu = $em->getRepository('ExpedienteBundle:Expediente')->findMenu();
   
 
       $clasificacion = $em->getRepository('ExpedienteBundle:Clasificacion')->findAll();
       $departamento = $em->getRepository('AusentismoBundle:DepartamentoRm')->findAll();
       $departamentoRm = $em->getRepository('AusentismoBundle:DepartamentoRm')->find($id);
        $this->get('session')->getFlashBag()->add(
                    'mensaje-success',
                    'Expedientes con estado <strong> INICIADO </strong> del departamento:<strong>  '.$departamentoRm->getDepartamentoRm().'</strong> ');
       return $this->render('ExpedienteBundle:Intranet:index.html.twig', array(
            'expedientes' => $expedientes, 
            'departamentos'=>$departamento, 
            'menu'=>$menu,
            'clasificaciones'=>$clasificacion
        ));

    }
  
    // BUSCA EXPEDIENTES POR DEPARTAMENTO Y CLASIFICACION 
    /**
     * @Route("/expediente/{slugd}/clasificacion/{slugc}", name="expediente_departamento_clasificacion")
     *
     */
    public function expedienteDepartamentoClasificacionAction($slugd, $slugc = null )
    {
        $em = $this->getDoctrine()->getManager();

        if (!($this->isGranted('ROLE_USUARIO'))) {
        //findExpediente(id del departamento / null devuelve de todos los dptos  )

        $transito= true;
        } else {
        // el usuario  tiene el role 'ROLE_USUARIO' O 'ROLE_ADMIN'

        $transito= false;

        } 
       if (!empty($slugc)) {       
       $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findByDepartamentoAndClasificacion($slugd, $slugc, $transito);

        $this->get('session')->getFlashBag()->add(
                    'mensaje-success',
                    'Expedientes con <strong> CLASIFICACION </strong>del departamento:<strong>  '."DEPARTAMENTO".'</strong> ');
        } else{
        $departamentoRm = $em->getRepository('AusentismoBundle:DepartamentoRm')->findOneBySlug($slugd);
        $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findExpediente($departamentoRm->getId(), $transito);

        $this->get('session')->getFlashBag()->add(
                    'mensaje-success',
                    'Expedientes con  '."DEPARTAMENTO".'</strong> ');


        }

       $tiposdocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findAll();

       return $this->render('ExpedienteBundle:Intranet:index.html.twig', array(
            'expedientes' => $expedientes, 
             'tiposdocumento'=>$tiposdocumento

        ));

    }

    // BUSCA EXPEDIENTES POR DEPARTAMENTO Y DOCUMENTO 
    /**
     * @Route("/expediente/{slugd}/documento/{slugdc}", name="expediente_departamento_documento")
     *
     */
    public function expedienteDepartamentoDocumentoAction($slugd, $slugdc )
    {
       $em = $this->getDoctrine()->getManager();
       $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findByDepartamentoAndDocumento($slugd, $slugdc );
        $this->get('session')->getFlashBag()->add(
                    'mensaje-success',
                    'Expedientes con <strong> DOCUMENTO </strong>del departamento:<strong>  '."DEPARTAMENTO".'</strong> ');
       $tiposdocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findAll();
     
       return $this->render('ExpedienteBundle:Intranet:index.html.twig', array(
            'expedientes' => $expedientes, 
            'tiposdocumento'=>$tiposdocumento
        ));

    }
  


    # MENU RENDERIZADO EN PLANTILLA 
     /**
     *@Route("/menu", name="menu" )
     *  
     */
    public function findMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clasificaciones = $em->getRepository('ExpedienteBundle:Clasificacion')->findAll();
      
        $menu = array();
        $dpto= array();
        $clas= array();
        $menu_principal = array();


        //findExpediente(id del departamento / null devuelve de todos los dptos  )
        $departamento = $em->getRepository('AusentismoBundle:DepartamentoRm')->findOneById($this->getUser()->getDepartamentoRm()->getId());

        $transito= (!($this->isGranted('ROLE_USUARIO')));


            $dpto['slug']= $departamento->getSlug();
            $dpto['nombre']= $departamento->getDepartamentoRm();
            $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findExpediente($departamento->getId(), $transito);
            $dpto['cantidad']= count($expedientes);

            foreach ($expedientes as $expediente) {

            if(isset($clas[$expediente->getClasificacion()->getSlug()]['slug']))

                {  // si ya existe, le añadimos uno

                    $clas[$expediente->getClasificacion()->getSlug()]['cantidad']+=1;

                }else{

                    // si no existe lo añadimos al array
                   $clas[$expediente->getClasificacion()->getSlug()]['slug']= $expediente->getClasificacion()->getSlug();
                   $clas[$expediente->getClasificacion()->getSlug()]['nombre']= $expediente->getClasificacion();

                    $clas[$expediente->getClasificacion()->getSlug()]['cantidad']=1;;
                }
      
            //foreach ($clasificaciones as $clasificacion) {
                
            //    $expedientes2 = $em->getRepository('ExpedienteBundle:Expediente')->findByDepartamentoAndClasificacion($departamento->getSlug(), $clasificacion->getSlug(), $transito);

            }

                //$clas[$clasificacion->getSlug()]['cantidad']= count($expedientes2);
                $dpto['clasificaciones'] = $clas;
              
                $clas = array();
            $menu[$departamento->getSlug()]=$dpto;            
            
                $doc_menu = $this->getParameter('menu-doc');
                $slug_doc = $doc_menu['slug'];
                //var_dump($opcion);
                //exit();
            $tipoDocumentos = $em->getRepository('DocumentoBundle:Documento')->findAll();
            foreach ($tipoDocumentos as $tipoDoc) {              
        
                if (array_key_exists($tipoDoc->getSlug(), $slug_doc)){
                  $expedientes= $em->getRepository('ExpedienteBundle:Expediente')->findByDepartamentoAndDocumento($departamento->getSlug(), $tipoDoc->getSlug());
                  $docs['slug']= $tipoDoc->getSlug();
                  $docs['nombre']= $tipoDoc->getNombreDocumento();
                  $docs['cantidad']=count($expedientes);
                  $menu[$departamento->getSlug()]['documentos'][$tipoDoc->getSlug()] =$docs; 
                 }
             } 
              

            $menu_principal = $menu;
            $docs = array();
    
         //dump($menu_principal);
        //       exit();
        return $this->render('ExpedienteBundle:Default:menu.html.twig', array( 
            'menu'=>$menu_principal      
        ));
    }


}
