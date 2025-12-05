<?php

namespace Siarme\GeneralBundle\Controller;

use Siarme\AusentismoBundle\Util\Util;
use Siarme\AusentismoBundle\Entity\Organismo;
use Siarme\DocumentoBundle\Entity\Documento;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Siarme\GeneralBundle\Entity\ItemAcuerdoMarco;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Expediente controller.
 *
 * @Route("general")
 */
class DefaultController extends Controller
{
    /**
     * Lists all expediente entities.
     *
     * @Route("/index/{anio}", name="general_reparticion_index")
     * @Method("GET")
     */
    public function indexAction($anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
           // dump($anio);
            //exit();
        }

        $em = $this->getDoctrine()->getManager();

        // buscar TRAMITE que se relacionen con USUARIO y  TAREA 
        $usuario = $this->getUser();
        
        //si es false devuelve aquellos expedientes que pertenecen a la reparticion del usuario
        $expedientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticion($usuario->getDepartamentoRm(), $anio);

        //devuelve aquellos EXPEDIENTE que pertenecen a la reparticion del usuario y no poseen TRAMITE
        $expedientesPendientes = $em->getRepository('ExpedienteBundle:Expediente')->findByReparticionPendientes($usuario->getDepartamentoRm());
        $acuerdos = $em->getRepository('ExpedienteBundle:Expediente')->findAcuerdo($usuario->getDepartamentoRm(), $anio);

         //si es false devuelve aquellas que no estan con expedientes
        $tareas = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($usuario);
       
     //   $tramites = $em->getRepository('ExpedienteBundle:Tramite')->findByDepartamentoRm($usuario->getDepartamentoRm(), $anio);
        //Devuelve los TRAMITE que estan sin realizarse.
       // $tareasPendientes = $em->getRepository('ExpedienteBundle:Tarea')->findByTramiteReparticion($usuario->getDepartamentoRm());
        
        // buscar TRAMITE que se relacionen con USUARIO y  RECORDATORIO 
        // o aquellos que son publicos
      
        $recordatorios = $em->getRepository('ExpedienteBundle:Recordatorio')->findByTramiteUsuario($usuario);

        return $this->render('GeneralBundle:Expediente:reparticion_index.html.twig', array(
       //     'expedientes' => $expedientes,
            'recordatorios' => $recordatorios,
//            'expedientesPendientes' => $expedientesPendientes,
            'tareas' => $tareas,
            'acuerdos' => $acuerdos,
            'anio'=>$anio,
      //      'tramites'=>$tramites,
        ));
    }



     /**
     * Creates a new documento entity.
     *
     * @Route("/{id}/cargar-items", name="acuerdo_cargar_items")
     * 
     *
     */
    public function cargarItemsAction(Request $request, Tramite $proceso)
    {
                $expediente = $proceso->getExpediente();
                $em = $this->getDoctrine()->getManager();

                $itemsAcuerdo = $em->getRepository('GeneralBundle:ItemAcuerdoMarco')->findByExpediente($expediente);
                $itemsProceso = $proceso->getItemProceso();
                if (empty($itemsAcuerdo) and !empty($itemsProceso)) {
                     
                    foreach ($itemsProceso as $itemProceso) {
                        $precio = 0;
                        $cantidadAdjudicada = 0;
                        $itemsOferta = $itemProceso->getItemOferta();
                        foreach ( $itemsOferta as $itemOferta) {
                            if ($itemOferta->getAdjudicado()) {
                                $precio =  $itemOferta->getPrecio();
                                $cantidadAdjudicada = $cantidadAdjudicada + $itemOferta->getCantidadAdjudicada();
                            }
                        } 
                        if ($cantidadAdjudicada > 0 ) {
                            $itemAcuerdo = new ItemAcuerdoMarco();
                            $itemAcuerdo->setExpediente($expediente);
                            $itemAcuerdo->setNumero($itemProceso->getNumero());
                            $itemAcuerdo->setCodigo($itemProceso->getCodigo());
                            $itemAcuerdo->setIpp(substr($itemProceso->getCodigo(),0,5) );
                            $itemAcuerdo->setItem($itemProceso->getItem());
                            $itemAcuerdo->setUnidadMedida($itemProceso->getUnidadMedida());
                            $itemAcuerdo->setPrecio($precio);
                            $itemAcuerdo->setCantidad($cantidadAdjudicada);
                            $itemAcuerdo->setItemProceso($itemProceso);
                            $em->persist($itemAcuerdo);
                        }
                        
                    }
                    $em->flush();   
                } elseif (empty($itemsProceso)) {
                        $msj = "Debe existir un PROCESO con estado <Ajudicado>";
                        $this->get('session')->getFlashBag()->add('mensaje-info', $msj);
                } else {
                        foreach ($itemsAcuerdo as $itemAcuerdo) {
                            $em = $this->getDoctrine()->getManager();
                            $consulta = $em->createQuery(
                            'SELECT i 
                             FROM AusentismoBundle:ItemProceso i
                             WHERE i.proceso = :tramite 
                             AND i.numero = :numero
                              ')                    
                             ->setParameter('tramite', $proceso)
                             ->setParameter('numero', $itemAcuerdo->getNumero());
                            $itemProceso = $consulta->getSingleResult();
                            $itemAcuerdo->setItemProceso($itemProceso);
                            $em->flush();   
                        }
                        $msj = "Ya fueron cargados los Ítems";
                        $this->get('session')->getFlashBag()->add('mensaje-info', $msj);
                }
                
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
    }
    
    /**
     * Creates a new documento entity.
     *
     * @Route("/{id}/{slug}/new", name="acuerdo_subir_item")
     * 
     *
     */

    public function newAction(Request $request, Expediente $expediente, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($slug);  
        $documento = new Documento();
        $documento->setTipoDocumento($tipoDocumento);
        $form = $this->createForm('Siarme\DocumentoBundle\Form\DocarchivoType', $documento);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
             $referer = $request->headers->get('referer');

             $file = $documento->getArchivo();

            if ($documento->getTipoDocumento()->getEsArchivo() == true && !empty($file) && $file != null) {
                

                        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                        $fileName = $documento->getSlug()."-".$documento->getId().'.'.$file->getClientOriginalExtension();

                        // moves the file to the directory 
                        $file->move( $this->getParameter('archivos'), $fileName );

                        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                           
                        if ( in_array($ext, ["xlsx", "xls"])) {

                                     $msj = $this->itemAcuerdo($fileName, $expediente);

                        } else {
                                    $msj = "Debes seleccionar una Archivo Excel, la extencion $ext no corresponde";
                                    $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
                                    $msj = false;
                                    return $this->redirect($referer);
                        }

            } elseif ($documento->getTipoDocumento()->getEsArchivo() == true) {

                        $msj = 'Nos has seleccionado ningun archivo';
                        $this->get('session')->getFlashBag()->add('mensaje-info', $msj);
                        return $this->redirect($referer);
            }
    
            // $msj = 'Has CREADO el Documento: <strong> '.$documento.' para el '.$documento->getTramite().' </strong>';
             /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
           //  $this->historial($documento->getId(),'CREADO', $msj, $documento::TIPO_ENTIDAD);
           // $this->get('session')->getFlashBag()->add('mensaje-info', $msj);
              
              $this->get('session')->getFlashBag()->add('mensaje-info', $msj);
            return $this->redirectToRoute('expediente_show', array('id' => $expediente->getId()));

           
        }

        return $this->render('GeneralBundle:Documento:documento_new.html.twig', array(
            'documento' => $documento,
            'slug'=>$slug,
            'form' => $form->createView(),
        ));
    }


    /**
     * Agrega Item al Tramite por Nota de Pedido Trimestral
     *
     */
     public function itemAcuerdo($fileName, $expediente)           
    {
       $documentoXls = IOFactory::load($this->getParameter('archivos').'/'.$fileName);

        //El número de hojas en un libro
        $numeroHojas = $documentoXls->getSheetCount();
        //selecciona la hoja actual empezando en 0
        $hojaActual = $documentoXls->getSheet(0);

        // obtengo el numero filas 
        $numeroFilas = $hojaActual->getHighestRow();

        //verifica que sea nota de pedido 
        $npt=false;
        $filaItem= 0;
        $sistema ="COMPRAR";
        $esHoja = false;
        for ($i=0; $i < 15; $i++) { 


            if ($hojaActual->getCell("A".$i)->getCalculatedValue() == "ID"){

                    $escolCodigo = false;
                    $escolRubro = false;
                    $escolIpp = false;
                    $escolDescripcion = false;
                    $escolMedida = false;
                    $escolCantidad = false;
                    $colId = 1;
                    $esHoja = true;
                    for ($col=1; $col < 50; $col++) {
                       $valorCelda = strtoupper($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue());
                       $valorCelda = Util::limpiarCadena($valorCelda);
                        if ((strpos($valorCelda, "CODIGO") !== false) or (strpos($valorCelda, "CÓDIGO") !== false))  {
                             $escolCodigo = true;
                             $colCodigo = $col;
                        }
                           
                        if ((strpos($valorCelda, "I.P.P") !== false) or (strpos($valorCelda, "IPP") !== false)) {
                             $escolIpp = true;
                             $colIpp = $col;
                        }                                
                        if (strpos($valorCelda, "DESCRIP") !== false) {
                             $escolDescripcion = true;
                             $colDescripcion = $col;
                        }
                        if (strpos($valorCelda, "UNIDAD") !== false) {
                             $escolMedida = true;
                             $colMedida = $col;
                        }
                        if (strpos($valorCelda, "CANTIDAD TOTAL") !== false) {
                             $escolCantidad = true;
                             $colCantidad = $col;
                        }
                    }
    
                
                $npt= ($escolCodigo and $escolIpp and $escolDescripcion and $escolMedida and $escolCantidad);
                $filaItem= $i;
             }
        }

        if (!$esHoja){
            $msj = "Se encontraron  $numeroHojas hojas , Verifique que los Items esten en la 1° hoja  (no se encontró la columna ID)";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        } 

        if (!$npt){
            $msj = "Verifique que sea correcto el formato de la Tabla  <br /> Los nombres de columas con 1 fueron encontrados  <br /> CODIGO ITEM:------------ $escolCodigo <br />  I.P.P:------------ $escolIpp <br /> DESCRIPCION:------------ $escolDescripcion <br /> UNIDAD DE MEDIDA:------------ $escolMedida <br /> CANTIDAD TOTAL:------------ $escolCantidad <br /> ";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            
            return false;
         }  

        # Cargo los Items del Pedido
        $batchSize = 20;
        $f=0;
        $totalItems=0;
        $msjExiste = "";
        $em = $this->getDoctrine()->getManager();
        $expediente_id = $expediente->getId();
       foreach ($hojaActual->getRowIterator() as $fila) {
        $f = $f + 1;
            // Agrego item a partir de la primera "fila"
            // if verifica que sea item por ID
            if ($f > $filaItem) {
    
                // $esItem = is_numeric($hojaActual->getCell("A".$f)->getValue());
                $esItem = is_numeric($hojaActual->getCell("A".$f)->getCalculatedValue());
                
                //if verifica que sea una fila de item 
                if ($esItem == true ) {
                       
                        $item = $em->getRepository('GeneralBundle:ItemAcuerdoMarco')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getCalculatedValue(), 'expediente'=>$expediente_id));

                        if (empty($item)) {
                           $item = new ItemAcuerdoMarco();
                           $item->setExpediente($expediente);

                        } else {
                            $msjExiste = $msjExiste." <strong>".$hojaActual->getCell("A".$f)->getCalculatedValue()."</strong> |";
                        }
                        
                         $c= 0;
                         //Cuento la cantidad de Items
                         $totalItems = $totalItems +1;
                        foreach ($fila->getCellIterator() as $celda) {
                            $c = $c + 1;
                            # El valor, así como está en el documento
                             $valorRaw = $celda->getCalculatedValue();

                              switch ($c) {
                                case $colId:  
                                     $itemNumero = $valorRaw;                                           
                                      $item->setNumero($valorRaw);
                                     break;
                                case $colCodigo:    
                                        $escolCodigo = (empty($valorRaw)) ? false : true;
                                        $item->setCodigo($valorRaw);
                                     break;
                                case $colIpp:
                                       $escolIpp = (empty($valorRaw)) ? false : true;
                                       $item->setIpp($valorRaw);
                                     break;
                                 case $colDescripcion:
                                      $escolDescripcion = (empty($valorRaw)) ? false : true;
                                      $item->setItem($valorRaw);
                                      // $item->setItem(Util::getSlug($valorRaw));
                                      //$item->setCodigo($item->getNumero()." - n/d ");
                                     break;
                                 case $colMedida:
                                      $escolMedida = (empty($valorRaw)) ? false : true;
                                      $item->setUnidadMedida($valorRaw);
                                     break;
                                 case $colCantidad:
                                      $escolCantidad = (empty($valorRaw)) ? false : true;
                                      $item->setCantidad($valorRaw);
                                     break;
                                } //switch
                        } // foreach columna

                        $esItem= ($escolCodigo and $escolIpp and $escolDescripcion and $escolMedida and $escolCantidad);

                        if ($esItem == true ) {
                            $em->persist($item);
                                 $em->flush();
                                 // Detaches all Car objects from Doctrine!
                                 $em->clear(ItemAcuerdoMarco::class); 
                        } else {

                          $msj= "<p>El Item N° <strong> $itemNumero </strong> tiene celdas vacias </p><p>Las columanas con 1 fueron encontradas | CODIGO ITEM:---- $escolCodigo | I.P.P.:--- $escolIpp | DESCRIPCION:--- $escolDescripcion | UNIDAD DE MEDIDA:---$escolMedida | CANTIDAD:--- $escolCantidad</p> ";
                            $this->get('session')->getFlashBag()->add('mensaje-danger', $msj);
                        }

                }// if verifica que sea item por ID
        
            } //if verifica que sea una fila de item   

        } // foreach fila

        if ($esItem == true ) {
            //Termino de guardar el resto de no ingreso en el batch
            $em->flush();
            // Detaches all Car objects from Doctrine!
            $em->clear(ItemAcuerdoMarco::class);
        }
        if (!empty($msjExiste)) {
                    $msjExiste = "<p>Se actualizaron Item existentes: ".$msjExiste."</p>";        
                    //$this->get('session')->getFlashBag()->add('mensaje-info', $msjExiste);
        }

                $texto="<p> CANTIDAD DE ITEMS: <strong> $totalItems </strong></p> $msjExiste";

                return $texto;
 }


    /**
     * Agrega un Oganismo al Acuerdo Marco.
     *
     * @Route("/{id}/{expediente_id}/quitar", name="organismo_quitar")
     * @Method({"GET", "POST"})
     */
    public function quitarOrganismoAction(Request $request, Organismo $organismo, $expediente_id)
    {
        $clave= false;
   
        $em = $this->getDoctrine()->getManager();
        $expediente = $em->getRepository('ExpedienteBundle:Expediente')->find($expediente_id);  
        $array_organismos = $expediente->getOrganismos(); 
        foreach ($array_organismos as $key => $organismo2) {
    
            if ($organismo->getId() == $organismo2->getId()) {
                unset($array_organismos[$key]);
                $clave = true;
                 $expediente->setOrganismos($array_organismos);
                 $em->flush();
            }
        }

        $msj= "No existe el organismo";
        if ($clave) {
            $msj= "Eliminaste el organismo";
        }
    
          return new Response($msj);
    }
    /**
     * Quieta un Oganismo del Acuerdo Marco.
     *
     * @Route("/{id}/{expediente_id}/agregar", name="organismo_agregar")
     * @Method({"GET", "POST"})
     */
    public function agregarOrganismoAction(Request $request, Organismo $organismo, $expediente_id)
    {
        $em = $this->getDoctrine()->getManager();
        $expediente = $em->getRepository('ExpedienteBundle:Expediente')->find($expediente_id);  
        if (empty($expediente->getOrganismos())) {
           $array_organismos = array();
        } else {
           $array_organismos = $expediente->getOrganismos();  
        }

        array_push ( $array_organismos , $organismo );
        $expediente->setOrganismos($array_organismos);
        $em->flush();
        $msj= "Agregaste el organismo";
        return new Response($msj);
    }
}
