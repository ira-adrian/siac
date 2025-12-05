<?php

namespace Siarme\DocumentoBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Tramite;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\DocumentoBundle\Entity\Documento;
use Siarme\DocumentoBundle\Entity\Historial;
use Siarme\AusentismoBundle\Entity\Agente;
use Siarme\AusentismoBundle\Entity\ItemPedido;
use Siarme\GeneralBundle\Entity\ItemSolicitado;
use Siarme\GeneralBundle\Entity\ItemAcuerdoMarco;
use Siarme\AusentismoBundle\Entity\ItemProceso;
use Siarme\AusentismoBundle\Entity\ItemOferta;
use Siarme\AusentismoBundle\Entity\Proveedor;
use Siarme\AusentismoBundle\Entity\Licencia;
use Siarme\AusentismoBundle\Entity\ItemCatalogo;
use Siarme\AusentismoBundle\Entity\Rubro;
use Siarme\ExpedienteBundle\Entity\DatoAT;
use Siarme\ExpedienteBundle\Entity\Planificacion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\File\File;
use Siarme\AusentismoBundle\Util\Util;
use Siarme\AusentismoBundle\Util\NumeroALetras;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;



/**
 * Documento controller.
 *
 * @Route("documento")
 */
class DocumentoController extends Controller
{

/**
     * @Route("/media/{tramite_id}/upload", name="documento_upload")
     * 
     */
    public function uploadsAction(Request $request, $tramite_id){

        $documento = new Documento();
   
        $em = $this->getDoctrine()->getManager();

        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);

        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug("archivo");  

        $documento->setTipoDocumento($tipoDocumento);
        $documento->setNombreDocumento($tipoDocumento->getNombreDocumento());
        $fecha= new \DateTime('now');       
        $documento->setFechaDocumento( $fecha);
        $documento->setTramite($tramite);

        $documento->setSlug("archivo");
        $documento->setTexto("upload");
        $documento->setEstado(false);
        $documento->setNumero($tipoDocumento->getNumero());        
        $documento->setAnio($fecha->format('Y'));
        $documento->setUsuario($this->getUser());
        
        $form = $this->createForm('Siarme\DocumentoBundle\Form\DocarchivoType', $documento);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
               
        $file=$request->files->get('file');
          
          
            
            // $file = $documento->getArchivo();
            if (!empty($file) && $file != null) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                //$originalFilename= Util::getSlug($originalFilename);

               // dump($originalFilename);
               // exit();
                // $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // 
                // Util::getSlug($tramite->getExpediente()->getAgente()->getApellidoNombre());
                $fileName = Util::getSlug($tramite->getTipoTramite()."_".$tramite->getNumeroTramite())."".$documento->getSlug()."". $this->generateUniqueFileName().'.'.$file->getClientOriginalExtension();
          
                /** moves the file to the directory where brochures are stored
                $directorio = $this->getParameter('archivos').'/'.Util::getSlug($tramite->getTipoTramite().'-'.$tramite->getNumeroTramite());

                $existe = file_exists( $directorio );

                if ($existe) {
                        echo "El fichero $nombre_fichero existe";
                } else {
                        echo "El fichero $nombre_fichero no existe";
                }
                dump(  );
                exit();*/

                $file->move( $this->getParameter('archivos'),
                    $fileName );

                // updates the 'brochure' property to store the PDF file name
                // instead of its contents
                $documento->setArchivo($fileName);
                $documento->setNombreArchivo($originalFilename);

            } else {

                 $documento->setArchivo(null);
            }
                $documento->setNumero($tipoDocumento->getNumero());
                $tipoDocumento->setNumero(1);
                $em->persist($documento);
                $em->flush();

                return new JsonResponse(true);
        }

     return $this->render('DocumentoBundle:Documento:documento_new.html.twig', array(
                            'documento' => $documento,
                            'slug'=>"ccoo",
                            'form' => $form->createView(),));
    }

    /**
     * @Route("/media/{tramite_id}/upload", name="documento_upload_modal")
     * 
     */
    public function uploadsModalAction(Request $request, $tramite_id){

        $documento = new Documento();
   
        $em = $this->getDoctrine()->getManager();

        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);

        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug("archivo");  

        $documento->setTipoDocumento($tipoDocumento);
        $documento->setNombreDocumento($tipoDocumento->getNombreDocumento());
        $fecha= new \DateTime('now');       
        $documento->setFechaDocumento( $fecha);
        $documento->setTramite($tramite);

        $documento->setSlug("archivo");
        $documento->setTexto("upload");
        $documento->setEstado(false);
        $documento->setNumero($tipoDocumento->getNumero());        
        $documento->setAnio($fecha->format('Y'));
        $documento->setUsuario($this->getUser());
        
        $form = $this->createForm('Siarme\DocumentoBundle\Form\DocarchivoType', $documento);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
               
        $file=$request->files->get('file');
          
            if (!empty($file) && $file != null) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $fileName = Util::getSlug($tramite->getTipoTramite()."_".$tramite->getNumeroTramite())."".$documento->getSlug()."". $this->generateUniqueFileName().'.'.$file->getClientOriginalExtension();

                $file->move( $this->getParameter('archivos'),
                    $fileName );

                // updates the 'brochure' property to store the PDF file name
                // instead of its contents
                $documento->setArchivo($fileName);
                $documento->setNombreArchivo($originalFilename);

            } else {

                 $documento->setArchivo(null);
            }
                $documento->setNumero($tipoDocumento->getNumero());
                $tipoDocumento->setNumero(1);
                $em->persist($documento);
                $em->flush();

                return new JsonResponse(true);
        }
         return $this->render('DocumentoBundle:Documento:documento_modal_new.html.twig', array(
                            'documento' => $documento,
                            'slug'=>"ccoo",
                            'form' => $form->createView(),));
    }
    
    /**
     * Creates a new documento xls.
     *
     * @Route("/xls", name="documento_xlsx")
     * 
     *
     */

    public function newXlsAction(Request $request)
    {

        $spreadsheet = $this->get('phpoffice.spreadsheet')->createSpreadsheet();
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'Hello world');

        $writerXlsx = $this->get('phpoffice.spreadsheet')->createWriter($spreadsheet, 'Xlsx');
        //dump($this->getParameter('carpeta_xls'));
        //exit();
        $writerXlsx->save($this->getParameter('carpeta_xls').'/destination.xlsx');

        // creates a simple Response with a 200 status code (the default)
        $response = new Response('Hello Loco', Response::HTTP_OK);

        // creates a CSS-response with a 200 status code
        $response = new Response('<style> se ha creado el excel </style>');
        $response->headers->set('Content-Type', 'text/css');
        
        return $response;
    }

    /**
     * Creates a new documento xls.
     *
     * @Route("/actualizar_tabla/{tabla}", name="actualizar_tabla")
     * 
     *
     */
    public function actualizarTablaAction(Request $request, $tabla = null)
    {
        $tablas = array( "catalogo", "rubro", "proveedor");
        $fileName= $this->getParameter('actualizar_tabla').'/'.$tabla.'.xlsx';

        if (in_array($tabla, $tablas) && file_exists($fileName) ){

                $file = IOFactory::load($fileName);
                switch ($tabla) {
                    case "catalogo":
                            // recupero el menasaje de la Funcion
                             $msj = $this->actualizarItem($file);                 
                        break;
                    case "rubro":
                            // recupero el menasaje de la Funcion
                             $msj = $this->actualizarRubro($file); 
                        break;
                    case "proveedor":
                            // recupero el menasaje de la Funcion
                             $msj = $this->actualizarProveedor($file); 
                        break;
                }

                      $response = new Response($msj);
                      $response->headers->set('Content-Type', 'text/css');
                      return $response;
    
        } else {
                 $response = new Response("<html><body>No corresponde a una opcion valida: $tabla o no se encuantra el archivo en $fileName</body></html>");
                        $response->headers->set('Content-Type', 'text/css');
                 return $response;

        }
       
    }

    /**
     * Actualoza la Tabla Rubro
     *
     */
    public function actualizarRubro($file)
    {
    ini_set('memory_limit', '2048M');
        $hojaActual = $file->getSheet(0);
        $f= 0;
        $totalrubros=0;
        $msjExiste = "";
    foreach ($hojaActual->getRowIterator() as $fila) {
        $f = $f + 1;

        $esItem = is_numeric($hojaActual->getCell("A".$f)->getValue());

        if (($f > 1) && ($esItem == true))  {
                $em = $this->getDoctrine()->getManager();
                $rubro = $em->getRepository('AusentismoBundle:Rubro')->findOneByIpp($hojaActual->getCell("B".$f)->getValue());

                if (empty($rubro)) {
                   $rubro = new Rubro();
                } else {
                    $msjExiste = $msjExiste." | ".$hojaActual->getCell("B".$f)->getValue();
                }

                 $c= 0;
                 //Cuento la cantidad de rubros
                 $totalrubros = $totalrubros +1;
                foreach ($fila->getCellIterator() as $celda) {
                    $c = $c + 1;
                    # El valor, así como está en el documento
                    $valorRaw = $celda->getCalculatedValue();
                    switch ($c) {
                        case 2:
                              $rubro->setIpp($valorRaw);    
                             break;
                        case 3:
                              $rubro->setRubro(strtoupper($valorRaw));
                             break;
                        case 4:
                              $rubro->setPeriodo(strtoupper($valorRaw));
                             break;
                        case 5:
                              $grupoRubro = $em->getRepository('AusentismoBundle:GrupoRubro')->find($valorRaw);
                              $rubro->setGrupoRubro($grupoRubro);
                             break;
                    }                 
                }
                $em->persist($rubro);      
                $em->flush(); 
        } else {
            $msjExiste = "Esta actualizada la tabla rubro.xlsx";
        }

    }

    return $msjExiste;   
    }


    /**
     * Actualoza la Tabla Item del sistema COMPRAR
     *
     */
     public function actualizarCatalogo($fileName, $tramite)           
    {
            $file = IOFactory::load($this->getParameter('archivos').'/'.$fileName);
            $em = $this->getDoctrine()->getManager();
            $connection = $em->getConnection();
            $platform   = $connection->getDatabasePlatform();

            $connection->executeUpdate($platform->getTruncateTableSQL('item_catalogo', true /* whether to cascade */));  
            //$file->getActiveSheet()->setCellValue('A1', 'Hello world');

            //selecciona la hoja actual empezando en 0
            $hojaActual = $file->getSheet(0);

            // obtengo el numero de columnas
            $numeroColumnas = $hojaActual->getHighestColumn();
            
           // ini_set('memory_limit', '2048M');

            $filas=0;
            # Iterar filas
            //$old = ini_set('memory_limit', '512M');
            $items = array();
             
            $batchSize = 1000;
            $j=0;          
            foreach ($hojaActual->getRowIterator() as $fila) {
                $j= $j+1;
                $filas = $filas + 1;
                $esItem = !empty($hojaActual->getCell("A".$filas)->getValue());
                if (($filas > 1) and ($esItem)) {
                    $item = new ItemCatalogo();
                    $i= 0;
                    foreach ($fila->getCellIterator() as $celda) {
                         $i = $i + 1;
                        # El valor, así como está en el documento
                        $valorRaw = trim($celda->getValue()); 
                        # Fila, que comienza en 1, luego 2 y así...
                        # $filaCelda = $celda->getRow();
                        # Columna, que es la A, B, C y así...
                        # $columna = $celda->getColumn();
                        switch ($i) {
                            case 1:
                                  $item->setCodigo($valorRaw);
                                  $item->setIpp(substr($valorRaw,0,5));
                                 break;
                            case 2:
                                  $item->setRubro($valorRaw);
                                 break;
                             case 3:
                                  $item->setClase($valorRaw);
                                 break;
                             case 4:
                                  $item->setItem($valorRaw);
                                 break;
                        }   
                    }
                    $em->persist($item);
                }

                if (($j % $batchSize) === 0) {
                    $em->flush();
                    $em->clear(ItemCatalogo::class); // Detaches all Car objects from Doctrine!
                }
            }
            $em->flush(); 
            $em->clear(ItemCatalogo::class);

            $msj = "Cantidad de Filas: $filas";
            $this->get('session')->getFlashBag()->add('mensaje-success', $msj);
            return $msj;
    }

    /**
     * Creates a new documento entity.
     *
     * @Route("/{tramite_id}/{slug}/new", name="documento_new")
     * 
     *
     */
    public function newAction(Request $request, $tramite_id, $slug)
    {
     // $this->get('session')->getFlashBag()->add('mensaje-info', 'El Documento es CREADO cuando presiona el boton GUARDAR de lo contrario los cambios se DESCARTAN.');
     /*** $estado = (($expediente->getDatoAt()->getTurno() == true) or ($expediente->getDatoAt()->getEstado() == "Con Turno") );

        
        if  ( in_array( $slug , ["turno","citacion"])  and  ($estado == false )) {
             $ant= $request->headers->get('referer');
             $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'SE RECOMIENDA: Que vaya a la opcion &nbsp;<span class="glyphicon glyphicon-pushpin ">Dar Turno</span>&nbsp;del Expte. antes de Crear los Documentos <strong>Turno o Citacion</strong> <a href="'.$ant.'" role="button"> <span class="glyphicon glyphicon-folder-open"></span> Volver al Expt.</a>');
         } **/

        $em = $this->getDoctrine()->getManager();
        if ($slug != "licencia"){
                $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);
        }else{
            $tramite = null;
        }
        
        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($slug);  
        $acta = null;
        $documento = new Documento();
        $documento->setTipoDocumento($tipoDocumento);
        $documento->setNombreDocumento($tipoDocumento->getNombreDocumento());
        $fecha= new \DateTime('now');       
        $documento->setFechaDocumento( $fecha);
        $documento->setTramite($tramite);

        $documento->setSlug($slug);
        $documento->setEstado(false);
        //ASIGNO NUMERO
        $documento->setNumero($tipoDocumento->getNumero());      
        
        $documento->setAnio($fecha->format('Y'));
        $documento->setUsuario($this->getUser());
        $tipo = $tipoDocumento->getRol()->getRoleName();
        if ($documento->getSlug() == "planificacion"){
            if (count($documento->getTramite()->getPlanificacion()) > 0) {
                $msj = "Existen RUBROS planificados, si carga nuevamente la planilla, se perderán los PEDIDOS relacionados a los rubros existentes";
                $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            }
        }
        if ($slug == "licencia"){
            $agente = $em->getRepository('AusentismoBundle:Agente')->find($tramite_id);
            $licencia = new Licencia();
            $documento->setLicencia($licencia);
            $licencia->setAgente($agente);
            $licencia->setDocumento($documento);
            $licencia->setEstado(false);
            $licencia->setEnfermedad($em->getRepository('AusentismoBundle:Enfermedad')->find(195));
            $licencia->setArticulo($em->getRepository('AusentismoBundle:Articulo')->find(1));
            $licencia->setFechaDesde(new \DateTime('now'));
            $licencia->setFechaHasta(new \DateTime('now'));
            $documento->setLicencia($licencia);
            $form = $this->createForm('Siarme\DocumentoBundle\Form\DocMedicoType', $documento);

        // COMPRUEBO SI ES UN DOCUMENTO PDF
        } elseif ($tipoDocumento->getEsArchivo()){
             $documento->setTexto("Archivo");
             $form = $this->createForm('Siarme\DocumentoBundle\Form\DocarchivoType', $documento);

        } else {
             $form = $this->createForm('Siarme\DocumentoBundle\Form\DocumentoType', $documento);
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
             $referer = $request->headers->get('referer');
             $file = $documento->getArchivo();

            if ($documento->getTipoDocumento()->getEsArchivo() == true && !empty($file) && $file != null) {
                

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                //$originalFilename= Util::getSlug($originalFilename);

                $fileName = Util::getSlug($tramite->getTipoTramite()."_".$tramite->getNumeroTramite())."".$documento->getSlug()."". $this->generateUniqueFileName().'.'.$file->getClientOriginalExtension();

                // moves the file to the directory where brochures are stored
                $file->move( $this->getParameter('archivos'),
                    $fileName );

                // updates the 'brochure' property to store the PDF file name
                // instead of its contents
                $documento->setArchivo($fileName);
                $documento->setNombreArchivo($originalFilename);

                #---Agrego los Items de la nota al PEDIDO
                if ($documento->getSlug() == "nota-pedido"){
                   // Llamo a la funcion "agregarItem" que devuelve el texto del documento o false en caso de error.
                    //$msj = $this->itemNP($fileName, $tramite);
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                   
                    if ( in_array($ext, ["xlsx", "xls"])) {
                         $msj = $this->itemNPT($fileName, $tramite);
                    } else {
                        $msj = "Debes seleccionar una Archivo Excel, la extencion $ext no corresponde";
                        $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
                        $msj = false;
                    }

                    //$msj = "Cantiad de Hojas: $numeroHojas - Nota Pedido:  Filas: $numeroFilas - Columnas: $numeroColumnas - SAF: $saf";
                    if ($msj == false) {
                        return $this->redirect($referer);
                    } else {
                        $documento->setTexto($msj);
                     }
                    
                }

                #---Agrego los Items  al PROCESO
                if ($documento->getSlug() == "reporte-comprar"){
                   // Llamo a la funcion "agregarItem" que devuelve el texto del documento o false en caso de error.
                    //$msj = $this->itemNP($fileName, $tramite);

                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                   
                    if ( in_array($ext, ["xlsx", "xls"])) {
                         $msj = $this->itemsReporteComprar($fileName, $tramite);
                    } else {
                        $msj = "Debes seleccionar una Archivo Excel, la extencion $ext no corresponde";
                        $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
                        $msj = false;
                    }
                    
                    //$msj = "Cantiad de Hojas: $numeroHojas - Nota Pedido:  Filas: $numeroFilas - Columnas: $numeroColumnas - SAF: $saf";
                    if ($msj == false) {
                        return $this->redirect($referer);
                    } else {
                        $documento->setTexto($msj);
                     }
                    
                }

                #---Agrego los Items  al Adjudicados del Dictamen
                if ($documento->getSlug() == "tabla-dictamen"){
                   // Llamo a la funcion "agregarItem" que devuelve el texto del documento o false en caso de error.
                    //$msj = $this->itemNP($fileName, $tramite);

                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                   
                    if ( in_array($ext, ["xlsx", "xls"])) {
                         $msj = $this->itemsTablaDictamen($fileName, $tramite);
                    } else {
                        $msj = "Debes seleccionar una Archivo Excel, la extencion $ext no corresponde";
                        $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
                        $msj = false;
                    }
                    
                    //$msj = "Cantiad de Hojas: $numeroHojas - Nota Pedido:  Filas: $numeroFilas - Columnas: $numeroColumnas - SAF: $saf";
                    if ($msj == false) {
                        return $this->redirect($referer);
                    } else {
                        $documento->setTexto($msj);
                     }
                    
                }
                #---Agrego los Items  al PROCESO
                if ($documento->getSlug() == "reporte-bionexo"){
                   // Llamo a la funcion "agregarItem" que devuelve el texto del documento o false en caso de error.
                
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                   
                    if ( in_array($ext, ["xlsx", "xls"])) {
                       $msj = $this->itemsReporteBionexo($fileName, $tramite);
                    } else {
                        $msj = "Debes seleccionar una Archivo Excel, la extencion $ext no corresponde";
                        $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
                        $msj = false;
                    }
                    
                    if ($msj == false) {
                        return $this->redirect($referer);
                    } else {
                        $documento->setTexto($msj);
                     }
                    
                }

                #---Actualizo la tabla Proveedores
                if ($documento->getSlug() == "tabla-proveedor"){
                   // Llamo a la funcion "agregarItem" que devuelve el texto del documento o false en caso de error.
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                   
                    if ( in_array($ext, ["xlsx", "xls"])) {
                         $msj = $this->actualizarProveedor($fileName, $tramite);
                    } else {
                        $msj = "Debes seleccionar una Archivo Excel, la extencion $ext no corresponde";
                        $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
                        $msj = false;
                    }
                    
                       
                    if ($msj == false) {
                        return $this->redirect($referer);
                    } else {
                        $documento->setTexto($msj);
                     }
                    
                }

                #---Actualizo la tabla Catalogo de Item COMPRAR
                if ($documento->getSlug() == "tabla-catalogo"){
                   // Llamo a la funcion "agregarItem" que devuelve el texto del documento o false en caso de error.
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                   
                    if ( in_array($ext, ["xlsx", "xls"])) {
                         $msj = $this->actualizarCatalogo($fileName, $tramite);
                    } else {
                        $msj = "Debes seleccionar una Archivo Excel, la extencion $ext no corresponde";
                        $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
                        $msj = false;
                    }
                    
                       
                    if ($msj == false) {
                        return $this->redirect($referer);
                    } else {
                        $documento->setTexto($msj);
                     }
                    
                }
                 #---Agrego los Items de la nota al PEDIDO
                if ($documento->getSlug() == "nota-solicitud"){
                   // Llamo a la funcion "agregarItem" que devuelve el texto del documento o false en caso de error.
                    //$msj = $this->itemNP($fileName, $tramite);
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                   
                    if ( in_array($ext, ["xlsx", "xls"])) {
                         $msj = $this->itemNotaSolicitud($fileName, $tramite);
                    } else {
                        $msj = "Debes seleccionar una Archivo Excel, la extencion $ext no corresponde";
                        $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
                        $msj = false;
                    }

                    //$msj = "Cantiad de Hojas: $numeroHojas - Nota Pedido:  Filas: $numeroFilas - Columnas: $numeroColumnas - SAF: $saf";
                    if ($msj == false) {
                        return $this->redirect($referer);
                    } else {
                        $documento->setTexto($msj);
                     }
                    $tramite->setPresupuestoOficial($tramite->getMontoAutorizado());
                }
                
                #---Agrego los RUBROS PLANIFICADOS DE DPPR
                if ($documento->getSlug() == "planificacion"){
                   // Llamo a la funcion "agregarItem" que devuelve el texto del documento o false en caso de error.
                
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                   
                    if ( in_array($ext, ["xlsx", "xls"])) {
                       $msj = $this->planificacionTrimestral($fileName, $tramite);
                    } else {
                        $msj = "Debes seleccionar una Archivo Excel, la extencion $ext no corresponde";
                        $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
                        $msj = false;
                    }
                    
                    if ($msj == false) {
                        return $this->redirect($referer);
                    } else {
                        $documento->setTexto($msj);
                     }
                    
                }
            } elseif ($documento->getTipoDocumento()->getEsArchivo() == true) {

                $msj = 'Nos has seleccionado ningun archivo';
                $this->get('session')->getFlashBag()->add('mensaje-info', $msj);
                return $this->redirect($referer);
            }

           
            //VERIFICO EL NUMERO Y GUARDO
            $documento->setNumero($tipoDocumento->getNumero()); 
            $tipoDocumento->setNumero(1);
            if ($slug != "licencia"){
            $tramite->addDocumento($documento);
            }
            $em->persist($documento);
            $em->flush();

    
             $msj = 'Has CREADO el Documento: <strong> '.$documento.' para el '.$documento->getTramite().' </strong>';
                       /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
             $this->historial($documento->getId(),'CREADO', $msj, $documento::TIPO_ENTIDAD);        
            $this->get('session')->getFlashBag()->add('mensaje-info', $msj);
             return $this->redirectToRoute('documento_show', array('id' => $documento->getId()));


        }

        return $this->render('DocumentoBundle:Documento:documento_new.html.twig', array(
            'documento' => $documento,
            'slug'=>$slug,
            'form' => $form->createView(),
            'acta' => $acta,

        ));
    }




    /**
     * Agrega Item al Tramite por Nota de Pedido
     *
     */
     public function actualizarProveedor($fileName, $tramite)           
    {
       $documentoXls = IOFactory::load($this->getParameter('archivos').'/'.$fileName);

         //selecciona la hoja actual empezando en 0
        $hojaActual = $documentoXls->getSheet(0);

        
        // obtengo el numero filas 
        $numeroFilas = $hojaActual->getHighestRow();
        // obtengo el numero de columnas
        $numeroColumnas = $hojaActual->getHighestColumn();


        # Cargo los Items del Pedido
        $batchSize = 20;
        $f= 0;
        $totalproveedors=0;
        $msjExiste = "";
       foreach ($hojaActual->getRowIterator() as $fila) {
        $f = $f + 1;

        $esProveedor = !empty($hojaActual->getCell("A".$f)->getValue()); 

        
        if (($f > 1) && ($esProveedor == true )) {

                $em = $this->getDoctrine()->getManager();

                $proveedor = $em->getRepository('AusentismoBundle:Proveedor')->findOneByCuit($hojaActual->getCell("A".$f)->getValue());

               
                if (empty($proveedor)) {
                   $proveedor = new Proveedor();
                } else {
                    $msjExiste = $msjExiste." | ".$hojaActual->getCell("A".$f)->getValue()." - ".$hojaActual->getCell("B".$f)->getValue();
                }
                
                 $c= 0;
                 //Cuento la cantidad de proveedors
                 $totalproveedors = $totalproveedors +1;
                foreach ($fila->getCellIterator() as $celda) {
                    $c = $c + 1;
                    # El valor, así como está en el documento
                     $valorRaw = $celda->getCalculatedValue();

                      switch ($c) {
                        case 1:                                                
                              $proveedor->setCuit($valorRaw);                                              
                             break;
                         case 3:
                              $proveedor->setProveedor(strtoupper($valorRaw));
                             break;
                         case 4:
                              $proveedor->setEstado($valorRaw);
                             break;
                         case 5:
                              /**$dt = new \DateTime();
                              $dt->setTimestamp($valorRaw);
                              $dt->format('d/m/Y');*/
                              $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valorRaw);
                              $proveedor->setFechaInscribe($dt);
                             break;
                         case 6:
                              $proveedor->setDireccion($valorRaw);
                             break;
                         case 7:
                              $proveedor->setTelefono($valorRaw);
                             break;
                         case 8:
                              $proveedor->setClase($valorRaw);
                             break;
                         case 9:
                              $proveedor->setRubro($valorRaw);
                             break;
                        }                 
                }

                    $em->persist($proveedor);

                    if (($f % $batchSize) === 0) {
                         
                         $em->flush();
                         $em->clear(Proveedor::class); // Detaches all Car objects from Doctrine!
                    }
        }
        
     }
     //Termino de guardar el resto de no ingreso en el batch
     $em->flush();
     $em->clear(Proveedor::class);
        if (!empty($msjExiste)) {
            $msjExiste = "<p>Se actualizaron proveedor existentes: ".$msjExiste."</p>";        
            $this->get('session')->getFlashBag()->add('mensaje-info', $msjExiste);
        }

        $texto="REPORTE: $msjExiste";

        return $texto;
 }



    /** 
     * Agrega Item al PROCESO Y OFERTA por Reporte COMPRAR--------------------------------------
     *
     */
     public function itemsReporteComprar($fileName, $tramite)           
    {
       $proceso = $tramite;
       $documentoXls = IOFactory::load($this->getParameter('archivos').'/'.$fileName);
       //El número de hojas en un libro
       $numeroHojas = $documentoXls->getSheetCount();
        if (empty($numeroHojas)) {
            $msj = "Abra el Cuadro Comparativo COMPRAR en Excel, luego haga click en Guardar y vuelva a intentar ";
            $this->get('session')->getFlashBag()->add('mensaje-danger', $msj);
            return false;
        }
         //selecciona la hoja actual empezando en 0
        $hojaActual = $documentoXls->getSheet(0);

        
        // obtengo el numero filas 
        $numeroFilas = $hojaActual->getHighestRow();
        // obtengo el numero de columnas
        $numeroColumnas = $hojaActual->getHighestColumn();


        # Cargo los Items del Pedido
        $batchSize = 20;
        
        $totalofertas=0;
        $msjExiste = "";
        $c = 3;
        $esReporte = ($hojaActual->getCell("A8")->getValue() == "Renglón"); 
        $esNumeroProceso = ($hojaActual->getCell("E2")->getValue() == $proceso->getNumeroComprar()); 
        
        if (!$esReporte) {
            $msj = "Verifique que sea reporte COMPRAR";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        }
        if (!$esNumeroProceso) {
            $msj = "Verifique el númro de proceso de la panilla ".$hojaActual->getCell("E2")->getValue().", no se corresponde con el proceso actual ".$proceso->getNumeroComprar();
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
        }
        
        $fecha = $hojaActual->getCell("E5")->getValue();
        $fecha =  date_create_from_format('d/m/Y', $fecha);
        if ($fecha == false) {
            $fecha = $proceso->getFechaDestino();
        }
        
        $esOferta = true;
        $i = 0;
        //columna de la oferta
        $colOferta = (7 + $i*4);
        //por cada oferente 
        while ($esOferta){
            $f= 0;
            $filas =  $documentoXls->getSheet(0)->getRowIterator();
     
            //foreach cada fila 
            foreach ($filas as $fila) {
                $f = $f + 1;
                if ($f == 8) {
                    $em = $this->getDoctrine()->getManager();
                    //RAZON SOCIAL COMO OFERENTE
                    $oferente = trim(strtoupper($hojaActual->getCellByColumnAndRow($colOferta, 8)->getValue()));
                    //$proveedor = $em->getRepository('AusentismoBundle:Proveedor')->findOneByProveedor($oferente);
                    $slug = Util::getSlug($oferente);
                    $proveedor = $em->getRepository('AusentismoBundle:Proveedor')->findOneBySlug($slug);
                    
                     // REVISAR BUSCAR POR EXPEDIENTE Y OFERENTE ----------------  
                    $oferta = $em->getRepository('ExpedienteBundle:Tramite')->findOneBy(array(
                        'oferente'=>$oferente,
                        'proceso'=>$proceso,
                    ));

                    if (empty($oferta)) {
                       $oferta = new Tramite();
                       $oferta->setOferente($oferente);
                       $oferta->setDepartamentoRm($this->getUser()->getDepartamentoRm());
                       $oferta->setOferente($oferente);
                        if (!empty($proveedor)) {
                            $oferta->setCuit($proveedor->getCuit());
                            $oferta->setProveedor($proveedor);
                            if ($proveedor->getAntecedente() > 0 ) {
                                $oferta->setPFAntecedente($proveedor->getAntecedente());
                            }
                        } else {
                            $oferta->setCuit("-");
                        }

                        // ASIGNO NUMERO DE EXPEDIENTE INTERNO
                        $tipoDocumentoTr = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug("tramite_oferta");
                        $oferta->setNumeroTramite($tipoDocumentoTr->getNumero());
                        // ASIGNO EL ESTADO POR DEFECTO
                       // $tramite->setEstadoTramite($tipoDocumentoTr->getEstadoTramite());
                        // ASIGNO EL TIPO DE TRAMITE OFERTA
                       $tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->findOneBySlug("tramite_oferta");
                        $oferta->setTipoTramite($tipoTramite);
                        $tipoDocumentoTr->setNumero(1);

                        // ASIGNO EL ESTADO 
                        $estado = $em->getRepository('ExpedienteBundle:EstadoTramite')->find(16);
                        $oferta->setEstadoTramite($estado);
                        
                        $oferta->setFechaDestino($fecha);
                        $oferta->setProceso($proceso);
                        $oferta->setExpediente($proceso->getExpediente());
                        $em->persist($oferta);
                        $em->flush();
                    }
                }

                $esItem = (is_numeric( (int) $hojaActual->getCell("A".$f)->getValue()) and ((int) $hojaActual->getCell("A".$f)->getValue() != 0)); 
                $esItemOferta =  $esItem;
                if ($f > 9 && $esItem) {
                        
                    // ver para item proceso si existe solo actualizo
                    //$item = $em->getRepository('AusentismoBundle:ItemProceso')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getValue(), 'codigo'=>$hojaActual->getCell("C".$f)->getValue(), 'proceso'=>$proceso));
                        $itemProceso = $em->getRepository('AusentismoBundle:ItemProceso')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getValue(), 'proceso'=>$proceso));
                        if (empty($itemProceso)) {
                            $itemProceso = new ItemProceso();
                            $itemProceso->setProceso($proceso);
                            $em->persist($itemProceso);
                        }
                        $itemProceso->setFecha($fecha);
                        
                        $itemOferta = $em->getRepository('AusentismoBundle:ItemOferta')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getValue(), 'tipo'=>$hojaActual->getCell("B".$f)->getValue(), 'codigo'=>$hojaActual->getCell("C".$f)->getValue(), 'oferta'=>$oferta));
                        if (empty($itemOferta)) {
                            $itemOferta = new ItemOferta();
                            $itemOferta->setProceso($proceso);
                            $itemOferta->setOferta($oferta);
                        } else {
                            $msjExiste = $msjExiste." | ".$hojaActual->getCell("A".$f)->getValue()." - ".$hojaActual->getCell("C".$f)->getValue();
                        }
                        $itemOferta->setFecha($fecha);
                        
                        $c=0;
                        $colPrecio = (int) $colOferta;
                        $colCantidad = (int) $colOferta + 1;
                        $colItem = (int) $colOferta + 3;
                        foreach ($fila->getCellIterator() as $celda) {
                             $c = $c + 1;
                             # El valor, así como está en el documento
                             $valorRaw = $celda->getValue();
                             switch ($c) {
                                // si es el primer oferente creo los items
                                case 1:                                                
                                      $itemProceso->setNumero($valorRaw);    
                                      $itemOferta->setNumero($valorRaw);    
                                                                            
                                     break;
                                case 2:
                                      $itemOferta->setTipo($valorRaw);
                                     break;
                                case 3:
                                      $itemProceso->setCodigo($valorRaw);
                                      $itemOferta->setCodigo($valorRaw);
                                      break;
                                case 4:
                                      $itemProceso->setItem($valorRaw);
                                     break;                                    
                                case 5:
                                      $itemOferta->setCantidadSolicitada($valorRaw);
                                      $itemProceso->setCantidad($valorRaw);
                                     break;
                                case 6:
                                      $itemProceso->setUnidadMedida($valorRaw);
                                      $itemOferta->setUnidadMedida($valorRaw);
                                     break;                                        
                                // oferta     
                                case $colPrecio:
                                      $itemOferta->setPrecio($valorRaw);
                                     break;
                                case $colCantidad:                                      
                                      $itemOferta->setCantidad($valorRaw);
                                     break;
                               case $colItem:
                                        if (empty($valorRaw)) {
                                            $esItemOferta = false;
                                        }
                                      
                                      $itemOferta->setItem($valorRaw);
                                     break;
                                } 
                        } //foreach celdas

                    if ($esItem == true ) {
                      
                        if ($esItemOferta) {
                            $itemOferta->setItemProceso($itemProceso);
                            $em->persist($itemOferta);
                        }
                                          
                        $em->flush();
                        // Detaches all Car objects from Doctrine!
                        $em->clear(ItemProceso::class); 
                        $em->clear(ItemOferta::class); 
                  

                    }// if item flush

                } // if item

            }//foreach  filas

            $i = $i+ 1;
            $colOferta = (7 + $i*4);
            $esOferta = !empty($hojaActual->getCellByColumnAndRow($colOferta, 8)->getValue());
            
        } //while  oferta

        
       /** PONDERAR  el Factor Precio 
        $msj= $this->itemOfertaPFPrecio($proceso);
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        foreach ($proceso->getOferta() as $oferta) {
            // PONDERAR  el Factor Plazo de Entraga y Antecedente 
            $msj= $this->itemOfertaPFPlazoAntecedente($oferta);
        }
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);*/

       $repository = $em->getRepository('AusentismoBundle:ItemPedido');
        $expediente = $proceso->getExpediente();
        $query = $repository->createQueryBuilder('i'); 
               $query->innerJoin('i.tramite' , 't')
                    ->addSelect('t')
                    ->Where('t.expediente = :exp')
                    ->setParameter('exp', $expediente ); 
                $dql = $query->getQuery();

                $itemsPedidos = $dql->getResult();
        
        foreach ($itemsPedidos as $itemPedido){
            $desierto = false;
            if (!empty($itemPedido->getItemProceso())) {
                $desierto = ((!$itemPedido->getItemProceso()->getAdjudicado()) and ($itemPedido->getItemProceso()->getProceso() != $proceso));
            }
            if (empty($itemPedido->getItemProceso()) or  $desierto) {
                        $items = $this->obtenerItemProceso($itemPedido, $proceso);
                        if (count($items)== 1){
                            foreach ($items as $itemProceso ){
                                //$itemProceso->addItemPedido($itemPedido);
                                $itemPedido->setItemProceso($itemProceso);
                                $em->flush();
                            }
                        }
            }

        }
        
        if (!empty($msjExiste)) {
            $msjExiste = "<p>Se actualizaron item existentes: ".$msjExiste."</p>";        
            $this->get('session')->getFlashBag()->add('mensaje-info', $msjExiste);
        }

        $texto="REPORTE: $msjExiste";

        return $texto;
    }

    /**
     * 
     * @return array
     */
    public function obtenerItemProceso($item, $proceso)
    {
        $em = $this->getDoctrine()->getManager();
       $repository = $em->getRepository('AusentismoBundle:ItemProceso');
        // itemPedido obtengo el codigo y el expediente
        $numero = $item->getNumero();
        $codigo = $item->getCodigo();
        $cantidad = $item->getCantidad();
        $expediente = $item->getTramite()->getExpediente();
        // busco el itemProceso
        $query = $repository->createQueryBuilder('i'); 
               $query->Where('i.codigo = :codigo')
                    ->andWhere('i.proceso = :proceso')
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('proceso', $proceso ); 
                $dql = $query->getQuery();

                $items = $dql->getResult();

        if (count($items) > 1) {
               $query = $repository->createQueryBuilder('i'); 
               $query->Where('i.codigo = :codigo')
                    ->andWhere('i.cantidad = :cant')
                    ->andWhere('i.proceso = :proceso')
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('cant',  $cantidad)
                    ->setParameter('proceso', $proceso ); 
                $dql = $query->getQuery();

                $items = $dql->getResult();
        }
        if (count($items) > 1) {
               $query = $repository->createQueryBuilder('i'); 
               $query->Where('i.codigo = :codigo')
                    ->andWhere('i.cantidad = :cant')
                    ->andWhere('i.numero = :num')
                    ->andWhere('i.proceso = :proceso')
                    ->setParameter('codigo',  $codigo)
                    ->setParameter('cant',  $cantidad)
                    ->setParameter('num',  $numero)
                    ->setParameter('proceso', $proceso ); 
                $dql = $query->getQuery();

                $items = $dql->getResult();
        }

        return $items;
    }

    /**
     * Agrega Item Adjudicados--------------------------------------
     *
     */
     public function itemsTablaDictamen($fileName, $tramite)           
    {
       $proceso = $tramite;
       $documentoXls = IOFactory::load($this->getParameter('archivos').'/'.$fileName);
       //El número de hojas en un libro
       $numeroHojas = $documentoXls->getSheetCount();

         //selecciona la hoja actual empezando en 0
        $hojaActual = $documentoXls->getSheet(0);

        
        // obtengo el numero filas 
        $numeroFilas = $hojaActual->getHighestRow();
        // obtengo el numero de columnas
        $numeroColumnas = $hojaActual->getHighestColumn();
        if ($numeroColumnas =="G") {
           $msj = "Verifique: </br>  1) Sí existe la columna <i>Nombre Oferta</i> debe eliminarla </br> 2) La columna <i>Número de renglón</i> debe estar en la letra A de la Hoja";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        }
        # Cargo los Items del Pedido
        $batchSize = 20;
        
        $totalofertas=0;
        $msjExiste = "";
        $c = 3;
        $esReporte = false;

        if (empty($hojaActual)) {
            $msj = "Verifique, no se  ha encontrado ninguna hoja";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        }
        for ($i=0; $i < 50; $i++) { 
           // (strpos($hojaActual->getCell("A".$i)->getCalculatedValue(), "JURISDIC") !== false)
            $filaValor = $hojaActual->getCell("A".$i)->getValue();
            if (strpos($filaValor, "renglón") !== false)  {
                             $esReporte = true;
                             $filaItem = $i;
            }
        }
        if (!$esReporte) {
            $msj = "No se ha encontrado la columna <i>Número de renglón</i>, debe estar en la letra A de la Hoja";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        }


        $esOferta = true;
        $i = 0;

        $f= 0;
        $filas =  $documentoXls->getSheet(0)->getRowIterator();
 
        //foreach cada fila 
        foreach ($filas as $fila) {
            $f = $f + 1;
        

            if ($f > $filaItem) {  

            //Empiezo desde la primer fila
            // $esItem = (is_numeric( (int) $hojaActual->getCell("A".$f)->getCalculatedValue())); 
            $esItem = (is_numeric( (int) $hojaActual->getCell("A".$f)->getCalculatedValue()) and ((int) $hojaActual->getCell("A".$f)->getCalculatedValue() != 0)); 
             if ($esItem) {   
                $em = $this->getDoctrine()->getManager();
                //RAZON SOCIAL COMO OFERENTE
                $oferente = strtoupper($hojaActual->getCell("C".$f)->getValue());

                $proveedor = $em->getRepository('AusentismoBundle:Proveedor')->findOneByProveedor($oferente);

                 // REVISAR BUSCAR POR EXPEDIENTE Y OFERENTE ----------------  
                $oferta = $em->getRepository('ExpedienteBundle:Tramite')->findOneBy(array(
                    'oferente'=>$oferente,
                    'proceso'=>$proceso,
                ));
        /**
                if (empty($oferta)) {
                   $oferta = new Tramite();
                   $oferta->setOferente($oferente);
                   $oferta->setDepartamentoRm($this->getUser()->getDepartamentoRm());
                   $oferta->setOferente($oferente);
                    if (!empty($proveedor)) {
                        $oferta->setProveedor($proveedor);
                    }

                    // ASIGNO NUMERO DE EXPEDIENTE INTERNO
                    $tipoDocumentoTr = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug("tramite_oferta");
                    $oferta->setNumeroTramite($tipoDocumentoTr->getNumero());
                    // ASIGNO EL TIPO DE TRAMITE OFERTA
                   $tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->findOneBySlug("tramite_oferta");
                    $oferta->setTipoTramite($tipoTramite);
                    $tipoDocumentoTr->setNumero(1);

                    $oferta->setProceso($proceso);
                    $oferta->setExpediente($proceso->getExpediente());
                    $em->persist($oferta);
                    $em->flush();
                }
         */
                if ($hojaActual->getCell("B".$f)->getValue() == "Principal") {
                    $tipo = 1; 
                } else {
                    $tipo = 2;
                }
                // --------Creo los Item Solicitados de la OFERTA -------------------------
                $itemOferta = $em->getRepository('AusentismoBundle:ItemOferta')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getCalculatedValue(), 'tipo'=>$tipo, 'oferta'=>$oferta));
                if (!empty($itemOferta)) {
                    $c=0;
                     $s = $hojaActual->getCell("D".$f)->getCalculatedValue(); 
                     $s= str_replace('.', '', $s); 
                     //$itemOferta->setCantidadAdjudicada($itemOferta->getItemProceso()->getCantidad());
                     $itemOferta->setCantidadAdjudicada(intval($s));
                     $itemOferta->setAdjudicado(true);
                     $em->flush();
                } else {

                    $msjExiste = $msjExiste." | ". $hojaActual->getCell("A".$f)->getValue();
                }            
             } //if es item
            } //if filas de items    
        }//foreach  filas

        if (!empty($msjExiste)) {
            $msjExiste = "<p>No se encontraton los Items: ".$msjExiste."</p>";        
            $this->get('session')->getFlashBag()->add('mensaje-danger', $msjExiste);
        }
         //ENCUENTRO EL ESTADO A CAMBIAR
        $estado = $em->getRepository('ExpedienteBundle:EstadoTramite')->find(7);
        $proceso->setEstadoTramite($estado);
        $proceso->setEstado(true);
        $proceso->setAdjudicado(true);
        $proceso->setMontoAdjudica($proceso->getMontoAdjudicado());
        $em->flush();
        $texto="REPORTE: $msjExiste";

        return $texto;
    }

    /**
     * Agrega Item al PROCESO Y OFERTA por Reporte BIONEXO--------------------------------------
     *
     */
     public function itemsReporteBionexo($fileName, $tramite)           
    {
       $proceso = $tramite;
       $documentoXls = IOFactory::load($this->getParameter('archivos').'/'.$fileName);
       //El número de hojas en un libro
       $numeroHojas = $documentoXls->getSheetCount();

         //selecciona la hoja actual empezando en 0
        $hojaActual = $documentoXls->getSheet(0);

        
        // obtengo el numero filas 
        $numeroFilas = $hojaActual->getHighestRow();
        // obtengo el numero de columnas
        $numeroColumnas = $hojaActual->getHighestColumn();


        # Cargo los Items del Pedido
        $batchSize = 20;
        
        $totalofertas=0;
        $msjExiste = "";
        $c = 3;
        
        $esReporte = ($hojaActual->getCell("A1")->getValue() == "Bionexo.com") and ($hojaActual->getCell("A14")->getValue() == "Precio Unitario"); 

        if (!$esReporte) {
            $msj = "Verifique que sea reporte BIONEXO";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        }

        if (empty($hojaActual)) {
            $msj = "Verifique el reporte BIONEXO, pruebe con abrir el reporte en excel y luego Guardar Como para crear una nueva copia y subirla al sistema";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        }

        $esOferta = true;
        $i = 0;

        $f= 0;
        $filas =  $documentoXls->getSheet(0)->getRowIterator();
 
        //foreach cada fila 
        foreach ($filas as $fila) {
            $f = $f + 1;

            //Empiezo desde la primer fila
            $esItem = (is_numeric( (int) $hojaActual->getCell("A".$f)->getValue()) and ((int) $hojaActual->getCell("A".$f)->getValue() != 0)); 
            if ($f > 13  && $esItem) {
                
                $em = $this->getDoctrine()->getManager();
                //RAZON SOCIAL COMO OFERENTE
                $oferente = strtoupper($hojaActual->getCell("I".$f)->getValue());
                $cuit = strtoupper($hojaActual->getCell("J".$f)->getValue());
                $cuit = str_replace('-', '', $cuit); 

                $proveedor = $em->getRepository('AusentismoBundle:Proveedor')->findOneByCuit($cuit);

                 // REVISAR BUSCAR POR EXPEDIENTE Y OFERENTE ----------------  
                $oferta = $em->getRepository('ExpedienteBundle:Tramite')->findOneBy(array(
                    'oferente'=>$oferente,
                    'proceso'=>$proceso,
                ));

                if (empty($oferta)) {
                   $oferta = new Tramite();
                   $oferta->setOferente($oferente);
                   $oferta->setDepartamentoRm($this->getUser()->getDepartamentoRm());
                   $oferta->setOferente($oferente);
                   $oferta->setCuit($cuit);
                    if (!empty($proveedor)) {
                        $oferta->setProveedor($proveedor);
                    }

                    // ASIGNO NUMERO DE EXPEDIENTE INTERNO
                    $tipoDocumentoTr = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug("tramite_oferta");
                    $oferta->setNumeroTramite($tipoDocumentoTr->getNumero());
                    // ASIGNO EL TIPO DE TRAMITE OFERTA
                   $tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->findOneBySlug("tramite_oferta");
                    $oferta->setTipoTramite($tipoTramite);
                    $tipoDocumentoTr->setNumero(1);

                    $oferta->setProceso($proceso);
                    $oferta->setExpediente($proceso->getExpediente());
                    $em->persist($oferta);
                    $em->flush();
                }
            
                // --------Creo los Item Solicitados del PROCESO -------------------------
                $itemProceso = $em->getRepository('AusentismoBundle:ItemProceso')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getValue(), 'proceso'=>$proceso));
                if (empty($itemProceso)) {
                    $itemProceso = new ItemProceso();
                    $itemProceso->setProceso($proceso);
                    $itemProceso->setSistema("BIONEXO");
                    $em->persist($itemProceso);
                }

                // --------Creo los Item Solicitados de la OFERTA -------------------------
                $itemOferta = $em->getRepository('AusentismoBundle:ItemOferta')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getValue(), 'tipo'=>$hojaActual->getCell("B".$f)->getValue(), 'codigoEspecial'=>$hojaActual->getCell("D".$f)->getValue(), 'oferta'=>$oferta));
                if (empty($itemOferta)) {
                    $itemOferta = new ItemOferta();
                    $itemOferta->setProceso($proceso);
                    $itemOferta->setOferta($oferta);
                    $itemOferta->setSistema("BIONEXO");
                } else {
                    $msjExiste = $msjExiste." | ".$hojaActual->getCell("A".$f)->getValue()." - ".$hojaActual->getCell("C".$f)->getValue();
                }

                $c=0;

                foreach ($fila->getCellIterator() as $celda) {
                     $c = $c + 1;
                     # El valor, así como está en el documento
                     $valorRaw = $celda->getValue();
                     switch ($c) {
                        // si es el primer oferente creo los items
                        case 1:                                                
                              $itemProceso->setNumero($valorRaw);    
                              $itemOferta->setNumero($valorRaw);  
                                                                    
                             break;
                        case 2:
                              $itemOferta->setTipo($valorRaw);
                             break;
                        case 3:
                              $itemProceso->setItem($valorRaw);
                              $itemOferta->setItem($valorRaw);
                              break;
                        case 4:
                              $itemProceso->setCodigoEspecial($valorRaw);
                              $itemOferta->setCodigoEspecial($valorRaw);
                             break;                                    
                        case 5:
                              $itemOferta->setCantidadSolicitada($valorRaw);
                              $itemOferta->setCantidad($valorRaw);
                              $itemProceso->setCantidad($valorRaw);
                             break;          
                        case 11:
                              $itemOferta->setMarca($valorRaw);
                             break; 
                        case 12:
                              $itemProceso->setUnidadMedida($valorRaw);
                              $itemOferta->setUnidadMedida($valorRaw);
                             break;     
                        case 14:
                              $itemOferta->setPrecio($valorRaw);
                             break;                          
                        } 
                } //foreach celdas

                $itemOferta->setItemProceso($itemProceso);
                $em->persist($itemOferta);
                                  
                $em->flush();

            } //filas de items    
        }//foreach  filas

            $i = $i+ 1;
            $colOferta = (7 + $i*4);
            $esOferta = !empty($hojaActual->getCellByColumnAndRow($colOferta, 8)->getValue());
            
        $proceso->setSistema("BIONEXO");
       // PONDERAR  el Factor Precio 
        $msj= $this->itemOfertaPFPrecio($proceso);
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        foreach ($proceso->getOferta() as $oferta) {
            // PONDERAR  el Factor Plazo de Entraga y Antecedente 
            $msj= $this->itemOfertaPFPlazoAntecedente($oferta);
        }

        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        if (!empty($msjExiste)) {
            $msjExiste = "<p>Se actualizaron item existentes: ".$msjExiste."</p>";        
            $this->get('session')->getFlashBag()->add('mensaje-info', $msjExiste);
        }

        $texto="REPORTE: $msjExiste";

        return $texto;
    }

   /**
     * Pondera los Factores Precio Plazo y Antecedente excepto Calidad de  todos itemOferta de un Proceso.
     */
    public function itemOfertaPFPrecio($proceso)
    {     
        $em = $this->getDoctrine()->getManager();

        $items = $em->getRepository('AusentismoBundle:ItemOferta')->findAllItem($proceso, $proceso->getTipoTramite()->getSlug());

            $i= 1;

            $pFPrecio =  $proceso->getPFPrecio();

            $item1 = 0;
            $item2 = 0;

            foreach ($items as $item) {
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
    public function itemOfertaPFPlazoAntecedente( $oferta)
    {     

        $pFPlazoEntrega= $oferta->getPFPlazoEntrega();
        $pFAntecedente= $oferta->getPFAntecedente();
        $pFCalidadBueno= $oferta->getPFCalidadBueno();
        $pFCalidadMuyBueno= $oferta->getPFCalidadMuyBueno();
        $aceptaAlternativa =$oferta->getProceso()->getOfertaAlternativa();  

        if (( $pFPlazoEntrega == 0 ) or ($pFAntecedente == 0))  {
            $msj= "Verifique el Factor Ponderacion es igual a 0";
            $this->get('session')->getFlashBag()->add(
                        'mensaje-warning',
                        $msj);
        }

        $em = $this->getDoctrine()->getManager();
     //   $oferta = $oferta->getProceso();
        $items = $em->getRepository('AusentismoBundle:ItemOferta')->findAllItem($oferta, $oferta->getTipoTramite()->getSlug());

            foreach ($items as $item) {
                if (!$aceptaAlternativa) {
                        if ($item->getTipo() != 1) {
                            $item->setEstado(false);
                        }
                }
                $item->setPFPlazoEntrega($pFPlazoEntrega);
                $item->setPFAntecedente($pFAntecedente);
            }
             
        $em->flush();
        $msj= "Se ha realizado la Ponderacion de Ítems con el Factor Plazo de Entrega = $pFPlazoEntrega % y Antecedente = $pFAntecedente % para cada OFERENTE";

        return $msj;
    }

    /**
     * Agrega Item al Tramite por Nota de Pedido
     *
     */
     public function itemNP($fileName, $tramite)           
    {
       $documentoXls = IOFactory::load($this->getParameter('archivos').'/'.$fileName);

        //El número de hojas en un libro
        $numeroHojas = $documentoXls->getSheetCount();

        switch ($numeroHojas) {
                case 1:
                case 2:
                         //selecciona la hoja actual empezando en 0
                         $hojaActual = $documentoXls->getSheet(0);
                     break;
                 case 3:
                         //selecciona la hoja actual empezando en 0
                         $hojaActual = $documentoXls->getSheet(1);
                     break;
        }
        
        // obtengo el numero filas 
        $numeroFilas = $hojaActual->getHighestRow();
        // obtengo el numero de columnas
        $numeroColumnas = $hojaActual->getHighestColumn();

         if ($hojaActual->getCell("A12")->getValue() <> 1 ){
            $msj = "Verifique que sea correcto el formato de la Nota de Pedido";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $texto);
            
            return false;
         }  
        $nro= $hojaActual->getCell("K1")->getValue();
        $jur= $hojaActual->getCell("D3")->getValue();
        $saf= $hojaActual->getCell("D4")->getValue();
        $uni= $hojaActual->getCell("D5")->getValue();
        $sol= $hojaActual->getCell("D6")->getValue();
        $per= $hojaActual->getCell("D7")->getValue();
        $obj= $hojaActual->getCell("D8")->getValue();
        $nro= $hojaActual->getCell("K1")->getValue();
        $tramite->setNumeroNota($nro);
        $tramite->setTexto($obj);
       if (substr(trim($per), 0,1) != $tramite->getTrimestre()){
            $msj = "Verifique el trimestre del PEDIDO es ".$tramite->getTrimestre()."° Trimestre y no se corresponde con Nota de Pedido: ".substr(trim($per), 0,1)."° Trimestre";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
           $msj = false;
            return $msj;
         }

       /**if (substr(trim($saf), 0,2) != $tramite->getOrganismoOrigen()->getSaf()->getNumeroSaf() || substr(trim($saf), 0,2) != '0'.$tramite->getOrganismoOrigen()->getSaf()->getNumeroSaf() ){
            $msj = "Verifique el número de SAF del PEDIDO es ".$tramite->getOrganismoOrigen()->getSaf()->getNumeroSaf()." y no se corresponde con SAF N°: ".substr(trim($saf), 0,2)." de Nota de Pedido: ".$nro;
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
           $msj = false;
            return $msj;
         } */

        # Cargo los Items del Pedido
        $batchSize = 20;
        $f= 0;
        $totalItems=0;
        $msjExiste = "";
       foreach ($hojaActual->getRowIterator() as $fila) {
        $f = $f + 1;
 
        $esItem = is_numeric($hojaActual->getCell("A".$f)->getValue()) && (!empty($hojaActual->getCell("B".$f)->getValue())); 

        
        if (($f > 11) && ($esItem == true )) {

                $em = $this->getDoctrine()->getManager();
                $item = $em->getRepository('AusentismoBundle:ItemPedido')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getValue(), 'codigo'=>trim($hojaActual->getCell("B".$f)->getValue()), 'tramite'=>$tramite));

               
                if (empty($item)) {
                   $item = new ItemPedido();
                   $item->setTramite($tramite);
                   $item->setTrimestre($tramite->getTrimestre());
                   $item->setOrganismo($tramite->getOrganismoOrigen());

                } else {
                    $msjExiste = $msjExiste." | ".$hojaActual->getCell("A".$f)->getValue()." - ".$hojaActual->getCell("B".$f)->getValue();
                }
                
                 $c= 0;
                 //Cuento la cantidad de Items
                 $totalItems = $totalItems +1;
                foreach ($fila->getCellIterator() as $celda) {
                    $c = $c + 1;
                    # El valor, así como está en el documento
                     $valorRaw = $celda->getCalculatedValue();

                      switch ($c) {
                        case 1:                                                
                              $item->setNumero($valorRaw);                                              
                             break;
                        case 2:
                              $item->setCodigo($valorRaw);
                              $item->setIpp(substr($valorRaw,0,5));       

                             break;
                         case 3:
                              $item->setItem($valorRaw);
                             break;
                         case 8:
                              $item->setConDetalle(strtoupper($valorRaw));
                             break;
                         case 9:
                              $item->setUnidadMedida(strtoupper($valorRaw));
                             break;
                         case 10:
                              $item->setPrecio($valorRaw);
                             break;
                         case 11:
                              $item->setCantidad($valorRaw);
                             break;
                        }                 
                }

                    $em->persist($item);

                    if (($f % $batchSize) === 0) {
                         
                         $em->flush();
                         $em->clear(ItemPedido::class); // Detaches all Car objects from Doctrine!
                    }
        }
        
     }
     //Termino de guardar el resto de no ingreso en el batch
     $em->flush();
     $em->clear(ItemPedido::class);
        if (!empty($msjExiste)) {
            $msjExiste = "<p>Se actualizaron Item existentes: ".$msjExiste."</p>";        
            $this->get('session')->getFlashBag()->add('mensaje-info', $msjExiste);
        }

        $texto="<p>NUMERO DE NOTA DE PEDIDO: <strong> $nro </strong></p><p>JURISDICCI&Oacute;N: $jur<br />SAF: $saf <br /> UNIDAD EJECUTORA: $uni<br /> DEPENDENCIA SOLICITANTE: $sol<br /> PERIODO UTILIZACI&Oacute;N: $per<br /> OBJETO: $obj</p> <p> CANTIDAD DE ITEMS: <strong> $totalItems </strong></p> $msjExiste";

        return $texto;
 }

    /**
     * Agrega Item al Tramite por Nota de Pedido Trimestral
     *
     */
     public function itemNPT($fileName, $tramite)           
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
        $jur = "";
        $saf = "";
        $tri = "";
        $sistema ="COMPRAR";
        $esHoja = false;
        for ($i=0; $i < 15; $i++) { 

            if (strpos($hojaActual->getCell("A".$i)->getCalculatedValue(), "JURISDIC") !== false) {
                $jur = $hojaActual->getCell("C".$i)->getCalculatedValue();
            }
            if (strpos($hojaActual->getCell("A".$i)->getCalculatedValue(), "SAF") !== false) {
                $saf = $hojaActual->getCell("C".$i)->getCalculatedValue();
            }
            if (strpos($hojaActual->getCell("A".$i)->getCalculatedValue(), "TRIMESTRE") !== false) {
                $tri = $hojaActual->getCell("C".$i)->getCalculatedValue();
            }
            if (trim($hojaActual->getCell("A".$i)->getCalculatedValue()) == "ID"){

                    $escolCodigo = false;
                    $escolRubro = false;
                    $escolIpp = false;
                    $escolDescripcion = false;
                    $escolMedida = false;
                    $escolCantidad = false;
                    $escolPrecio = false;
                    $colETenica = 0;
                    $colId = 1;
                    $esHoja = true;
                    for ($col=1; $col < 50; $col++) {

                        $columnaValor = strtoupper($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue());
                       
                        if ((strpos($columnaValor, "CODIGO") !== false) or (strpos($columnaValor, "CÓDIGO") !== false))  {
                             $escolCodigo = true;
                             $colCodigo = $col;
                        }
                        if (strpos($columnaValor, "RUBRO") !== false) {
                             $escolRubro = true;
                             $colRubro = $col;
                        }                        
                        if ((strpos($columnaValor, "I.P.P") !== false) or (strpos($columnaValor, "IPP") !== false)) {
                             $escolIpp = true;
                             $colIpp = $col;
                        }                                
                        if (strpos($columnaValor, "DESCRIP") !== false) {
                             $escolDescripcion = true;
                             $colDescripcion = $col;
                        }
                        if (strpos($columnaValor, "UNIDAD") !== false) {
                             $escolMedida = true;
                             $colMedida = $col;
                        }
                        if (strpos($columnaValor, "CANTIDA") !== false) {
                             $escolCantidad = true;
                             $colCantidad = $col;
                        }
                       // if (strpos($columnaValor, "PRECIO") !== false) 
                       if (strpos($columnaValor, "UNITARIO") !== false) {
                             $escolPrecio = true;
                             $colPrecio = $col;
                        }
                        if ((strpos($columnaValor, "TECNIC") !== false) or (strpos($columnaValor, "TÉCNIC") !== false) or (strpos($columnaValor, "ESPECIFI") !== false)) {
                             $colETenica = $col;
                        }
                    }
    
                
                $npt= ($escolCodigo and $escolRubro and $escolIpp and $escolDescripcion and $escolMedida and $escolCantidad and $escolPrecio);
                $filaItem= $i;
             }
        }

        if (!$esHoja){
            $msj = "Se encontraron  $numeroHojas hojas , Verifique que sea la 1° hoja la Nota TRIMESTRAL (no se encontró la columna ID)";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        } 

        if (!$npt){
            $msj = "Verifique el Pedido de Necesidad Trimestral, no se encontró la COLUMNA: <br/>";
            if (!$escolCodigo) {
                $msj = $msj . "- CÓDIGO <br/>";
            }
            if (!$escolRubro) {
                $msj = $msj . "- RUBRO DE COMPRA <br/>";
            }
            if (!$escolIpp) {
                $msj = $msj . "- I.P.P. <br/>";
            }
            if (!$escolDescripcion) {
                $msj = $msj . "- DESCRIPCION <br/>";
            }
            if (!$escolMedida) {
                $msj = $msj . "- UNIDAD DE MEDIDA <br/>";
            }
            if (!$escolCantidad) {
                $msj = $msj . "- CANTIDAD SOLICITADA <br/>";
            }
            if (!$escolPrecio) {
                $msj = $msj . "- PRECIO UNITARIO<br/>";
            }
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            
            return false;
         }  

        # Cargo los Items del Pedido
        $batchSize = 20;
        $f=0;
        $totalItems=0;
        $msjExiste = "";
       foreach ($hojaActual->getRowIterator() as $fila) {
        $f = $f + 1;
            // Agrego item a partir de la primera "fila"
            // if verifica que sea item por ID
            if ($f > $filaItem) {
    
                // $esItem = is_numeric($hojaActual->getCell("A".$f)->getValue());
                $esItem = is_numeric($hojaActual->getCell("A".$f)->getCalculatedValue());
                
                //if verifica que sea una fila de item 
                if ($esItem == true ) {
                        $em = $this->getDoctrine()->getManager();
                        $item = $em->getRepository('AusentismoBundle:ItemPedido')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getValue(), 'tramite'=>$tramite));

                       
                        if (empty($item)) {
                           $item = new ItemPedido();
                           $item->setTramite($tramite);
                           $item->setTrimestre($tramite->getTrimestre());
                           $item->setOrganismo($tramite->getOrganismoOrigen());

                        } else {
                            $msjExiste = $msjExiste." | <strong>".$hojaActual->getCell("A".$f)->getValue()."</strong> - ".$hojaActual->getCell("B".$f)->getValue();
                        }
                        
                         $c= 0;
                         //Cuento la cantidad de Items
                         $totalItems = $totalItems +1;
                        foreach ($fila->getCellIterator() as $celda) {
                            $c = $c + 1;
                            # El valor, así como está en el documento
                             $valorRaw = trim($celda->getCalculatedValue());

                              switch ($c) {
                                case $colId:  
                                     $itemNumero = $valorRaw;                                           
                                      $item->setNumero($valorRaw);
                                     break;
                                case $colCodigo:
                                            
                                            if (empty($valorRaw) or $valorRaw == "-"){
                                                $sistema = "BIONEXO";
                                                $item->setSistema($sistema);
                                            } else {
                                                $escolCodigo = (empty($valorRaw)) ? false : true;
                                                $item->setSistema($sistema);
                                                $item->setCodigo($valorRaw);
                                            }        
                                     break;
                                case $colRubro:
                                       $item->setRubro($valorRaw);
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
                                 case $colPrecio:
                                      $escolPrecio = (empty($valorRaw)) ? false : true;
                                      $item->setPrecio($valorRaw);
                                     break;
                                 case $colETenica:
                                      $item->setDetalle($valorRaw);
                                     break;
                                } //switch
                        } // foreach columna

                        $esItem= ($escolCodigo and $escolIpp and $escolDescripcion and $escolMedida and $escolCantidad and $escolPrecio);

                        if ($esItem == true ) {
                            $em->persist($item);
                            if (($f % $batchSize) === 0) {
                                $item->getTramite()->setSistema($sistema);
                                 $em->flush();

                                 // Detaches all Car objects from Doctrine!
                                 $em->clear(ItemPedido::class); 
                            }
                        } else {

                            $msj = "El Item N° <strong> $itemNumero </strong> tiene celda vacía en la COLUMNA: <br/> ";
                            if (!$escolCodigo) {
                                $msj = $msj . "- CÓDIGO ";
                            }
                            if (!$escolRubro) {
                                $msj = $msj . "- RUBRO DE COMPRA ";
                            }
                            if (!$escolIpp) {
                                $msj = $msj . "- I.P.P. ";
                            }
                            if (!$escolDescripcion) {
                                $msj = $msj . "- DESCRIPCION ";
                            }
                            if (!$escolMedida) {
                                $msj = $msj . "- UNIDAD DE MEDIDA ";
                            }
                            if (!$escolCantidad) {
                                $msj = $msj . "- CANTIDAD SOLICITADA ";
                            }
                            if (!$escolPrecio) {
                                $msj = $msj . "- PRECIO UNITARIO ";
                            }
            
                            $this->get('session')->getFlashBag()->add('mensaje-danger', $msj);
                        }

                }// if verifica que sea item por ID
        
            } //if verifica que sea una fila de item   

        } // foreach fila

        if ($esItem == true ) {
            // ASIGNO EL SISTEMA AL TRAMITE 
            $item->getTramite()->setSistema($sistema);
            //Termino de guardar el resto de no ingreso en el batch
            $em->flush();

            // Detaches all Car objects from Doctrine!
            $em->clear(ItemPedido::class);
        }
                if (!empty($msjExiste)) {
                    $msjExiste = "<p>Se actualizaron Item existentes: ".$msjExiste."</p>";        
                    $this->get('session')->getFlashBag()->add('mensaje-info', $msjExiste);
                }

                $texto="<p>JURISDICCI&Oacute;N: $jur <br />SAF: $saf <br /> UNIDAD EJECUTORA: <br /> PERIODO UTILIZACI&Oacute;N: $tri<br /></p> <p> CANTIDAD DE ITEMS: <strong> $totalItems </strong></p> $msjExiste";

                return $texto;
    }

    /**
     *
     * Agrega Item al Tramite por Nota de Pedido Trimestral
     *
     */
     public function planificacionTrimestral($fileName, $tramite)           
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
        for ($i=0; $i < 10; $i++) { 
            $columnaValor= trim($hojaActual->getCell("A".$i)->getCalculatedValue());
            if (strpos($columnaValor, "RUBRO") !== false) {
                    $escolRubro = false;
                    $escolDescripcion = false;
                    $escolImporte = false;
                    $escolConcentra = false;
                    $colId = 1;
                    $esHoja = true;
                    for ($col=1; $col < 10; $col++) {

                        $columnaValor = strtoupper($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue());
                       
                        if (strpos($columnaValor, "RUBRO") !== false) {
                             $escolRubro = true;
                             $colRubro = $col;
                        }                        
                             
                        if (strpos($columnaValor, "DESCRIP") !== false) {
                             $escolDescripcion = true;
                             $colDescripcion = $col;
                        }
                        if (strpos($columnaValor, "IMPORTE") !== false) {
                             $escolImporte = true;
                             $colImporte = $col;
                        }

                
                $npt= ($escolRubro and  $escolDescripcion and $escolImporte );
                $filaItem= $i;
             }
        }
     }

        if (!$esHoja){
            $msj = "Se encontraron  $numeroHojas hojas , Verifique que sea la 1° hoja la Nota TRIMESTRAL (no se encontró la columna ID)";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        } 

        if (!$npt){
            $msj = "Verifique el Pedido de Necesidad Trimestral, no se encontró la COLUMNA: <br/>";
            if (!$escolRubro) {
                $msj = $msj . "- RUBRO COMPRAR <br/>";
            }
            if (!$escolDescripcion) {
                $msj = $msj . "- DESCRIPCION <br/>";
            }
            if (!$escolImporte) {
                $msj = $msj . "- IMPORTE <br/>";
            }

            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            
            return false;
         }  

        $em = $this->getDoctrine()->getManager();
        # Cargo los Items del Pedido
        $batchSize = 20;
        $f=0;
        $totalItems=0;
        $planificaciones = $tramite->getPlanificacion();
        foreach ( $planificaciones as $rubro) {
            $em->remove($rubro);
             $em->flush();
        }
       foreach ($hojaActual->getRowIterator() as $fila) {
        $f = $f + 1;
            // Agrego item a partir de la primera "fila"
            // if verifica que sea item por ID
            if ($f > $filaItem) {
    
                //if verifica que sea una fila de item 
                if ( !(empty($hojaActual->getCell("B".$f)->getCalculatedValue())) ) {
                        

                           $item = new Planificacion();
                           $item->setTramite($tramite);
                           $item->setTrimestre($tramite->getTrimestre());
                        
                         $c= 0;
                         //Cuento la cantidad de Items
                         $totalItems = $totalItems +1;
                        foreach ($fila->getCellIterator() as $celda) {
                            $c = $c + 1;
                            # El valor, así como está en el documento
                             $valorRaw = trim($celda->getCalculatedValue());

                              switch ($c) {
                                case $colRubro:
                                       $item->setRubro($valorRaw);
                                     break;
                                 case $colDescripcion:
                                      $escolDescripcion = (empty($valorRaw)) ? false : true;
                                      $item->setDescripcion($valorRaw);
                                      // $item->setItem(Util::getSlug($valorRaw));
                                      //$item->setCodigo($item->getNumero()." - n/d ");
                                     break;
                                 case $colImporte:
                                      $escolImporte = (empty($valorRaw)) ? false : true;
                                      $item->setImporte($valorRaw);
                                     break;

                                } //switch
                        } // foreach columna

                        $esItem= ($escolRubro and  $escolDescripcion and $escolImporte);

                        if ($esItem == true ) {
                            $em->persist($item);
                            if (($f % $batchSize) === 0) {
                                $item->getTramite()->setSistema($sistema);
                                 $em->flush();

                                 // Detaches all Car objects from Doctrine!
                                 $em->clear(ItemPedido::class); 
                            }
                        } else {
                            if (!$escolRubro) {
                                $msj = $msj . "- RUBRO COMPRAR ";
                            }
                            if (!$escolDescripcion) {
                                $msj = $msj . "- DESCRIPCION ";
                            }
                            if (!$escolImporte) {
                                $msj = $msj . "- IMPORTE ESTIMADO ";
                            }
            
                            $this->get('session')->getFlashBag()->add('mensaje-danger', $msj);
                        }

                }// if verifica que sea item por ID
        
            } //if verifica que sea una fila de item   

        } // foreach fila

        if ($esItem == true ) {
            //Termino de guardar el resto de no ingreso en el batch
            $em->flush();
            // Detaches all Car objects from Doctrine!
            $em->clear(Planificacion::class);
        }

            $texto="<p> CANTIDAD DE ITEMS: <strong> $totalItems </strong></p>";

                return $texto;
    } 

    /**
     * Agrega Item al Tramite por Nota de Pedido Trimestral
     *
     */
     public function itemNotaSolicitud($fileName, $tramite)           
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
        //dump(strtoupper($hojaActual->getCell("A10")->getCalculatedValue()));
        //exit();
        for ($i=0; $i < 15; $i++) { 
            $idValor = trim($hojaActual->getCell("A".$i)->getCalculatedValue());
            if ((strtoupper($idValor) == "ID") or (strtoupper($idValor) == "N°") or (strtoupper($idValor) == "ÍTEM")  or (strtoupper($idValor) == "ORDEN")){
                    $escolCodigo = false;
                    $escolRubro = false;
                    $escolPrecio = false;
                    $escolDescripcion = false;
                    $escolMedida = false;
                    $escolCantidad = false;
                    $colId = 1;
                    $esHoja = true;
                    for ($col=1; $col < 20; $col++) {
                       $valorCelda = strtoupper(Util::limpiarCadena($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue()));
                           
                        if ((strpos($valorCelda, "CODIGO") !== false) or (strpos($valorCelda, "CÓDIGO") !== false))  {
                             $escolCodigo = true;
                             $colCodigo = $col;
                        }
                        if (strpos($valorCelda, "DESCRIP") !== false) {
                             $escolDescripcion = true;
                             $colDescripcion = $col;
                        }
                        if (strpos($valorCelda, "UNIDAD") !== false) {
                             $escolMedida = true;
                             $colMedida = $col;
                        }
                        if (strpos($valorCelda, "CANTIDAD") !== false) {
                             $escolCantidad = true;
                             $colCantidad = $col;
                        }
                    }
                
                $npt= ($escolCodigo and $escolDescripcion and $escolMedida and $escolCantidad);
                $filaItem= $i;
             }
        }

        if (!$esHoja){
            $msj = "Se encontraron  $numeroHojas hojas. <br/> Verifique que los Items esten en la 1° hoja. <br/> No se encontró la columna (ID) o (N°) de ítem";
            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            return false;
        } 

        if (!$npt){
            $msj = "Verifique que sea correcto el formato de la Tabla  <br /> No se encontró la COLUMNA: <br />";
            if (!$escolCodigo) {
                $msj = $msj . "CÓDIGO <br/>";
            }
            if (!$escolDescripcion) {
                $msj = $msj . "DESCRIPCION <br/>";
            }
            if (!$escolMedida) {
                $msj = $msj . "UNIDAD DE MEDIDA <br/>";
            }
            if (!$escolCantidad) {
                $msj = $msj . "CANTIDAD SOLICITADA <br/>";
            }

            $this->get('session')->getFlashBag()->add('mensaje-warning', $msj);
            
            return false;
         }  

        # Cargo los Items del Pedido
        $batchSize = 20;
        $f=0;
        $totalItems=0;
        $msjExiste = "";
        $em = $this->getDoctrine()->getManager();
  

        foreach ($hojaActual->getRowIterator() as $fila) {
        $f = $f + 1;
            // Agrego item a partir de la primera "fila"
            // if verifica que sea item por ID
            if ($f > $filaItem) {
    
                // $esItem = is_numeric($hojaActual->getCell("A".$f)->getValue());
                $esItem = is_numeric($hojaActual->getCell("A".$f)->getCalculatedValue());
                
                //if verifica que sea una fila de item 
                if ($esItem == true ) {
                        $item = $em->getRepository('GeneralBundle:ItemSolicitado')->findOneBy(array('numero'=>$hojaActual->getCell("A".$f)->getCalculatedValue(), 'tramite'=>$tramite));

                       
                        if (empty($item)) {
                           $item = new ItemSolicitado();
                           $item->setTramite($tramite);
                           //$item->setTrimestre($tramite->getTrimestre());
                      //     $item->setOrganismo($tramite->getOrganismoOrigen());

                        } else {
                            $msjExiste = $msjExiste."ID <strong>".$hojaActual->getCell("A".$f)->getCalculatedValue()."</strong> fila ".$f."; ";
                        }
                        
                         $c= 0;
                         //Cuento la cantidad de Items
                         $totalItems = $totalItems +1;
                        $variosItem = false;
                        foreach ($fila->getCellIterator() as $celda) {
                            $c = $c + 1;
                            # El valor, así como está en el documento
                              $valorRaw = trim($celda->getCalculatedValue());
                              switch ($c) {
                                case $colId:  
                                     $itemNumero = $valorRaw;                                           
                                      $item->setNumero($valorRaw);
                                     break;
                                case $colCodigo:
                                        $valorRaw = preg_replace("/[\r\n|\n|\r]+/", "", $valorRaw);
                                        $valorRaw= str_replace('  ', '', $valorRaw); 
                                        $valorRaw= str_replace(' ', '', $valorRaw); 
                                        $escolCodigo = (empty($valorRaw)) ? false : true;
                                        $item->setCodigo($valorRaw);
                                        $item->setIpp(substr($valorRaw,0,5)); 
                                        $itemsAcuerdo = $em->getRepository('GeneralBundle:ItemAcuerdoMarco')->findBy(array('codigo'=>$valorRaw, 'expediente'=>$tramite->getExpediente()));

                                        if (count($itemsAcuerdo) == 1 ) {
                                            foreach ($itemsAcuerdo as $itemAcuerdo) {
                                                $item->setItemAcuerdoMarco($itemAcuerdo);
                                                $item->setEstado(true);
                                                $item->setPrecio($itemAcuerdo->getPrecio());
                                            }
                                        } else {

                                            $item->setEstado(false);
                                             $variosItem = true;
                                        }

                                     break;
                                 case $colDescripcion:
                                      $escolDescripcion = (empty($valorRaw)) ? false : true;
                                      $item->setItem($valorRaw);
                                       if ($variosItem) {

                                          $itemsAcuerdo= $em->getRepository('GeneralBundle:ItemAcuerdoMarco')->findBy(array('item'=>strtoupper(Util::limpiarItem($valorRaw)), 'expediente'=>$tramite->getExpediente()));
                                        //  dump($itemAcuerdo);
                                         // exit();
                                          if (count($itemsAcuerdo) == 1 ) {
                                                    foreach ($itemsAcuerdo as $itemAcuerdo) {
                                                        $item->setItemAcuerdoMarco($itemAcuerdo);
                                                        $item->setEstado(true);
                                                        $item->setPrecio($itemAcuerdo->getPrecio());
                                                    }
                                            }
                                          }   

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
                                      $item->setCantidadAutorizada($valorRaw);
                                     break;
                                } //switch
                        } // foreach columna

                        $esItem= ($escolCodigo and $escolDescripcion and $escolMedida and $escolCantidad);

                        if ($esItem == true ) {
                            $em->persist($item);
                            $em->flush();

                        } else {

                            $msj= "<p>No se cargo El Item N° <strong> $itemNumero </strong> tiene celdas vacias en la COLUMNA:</p>";
                                if (!$escolCodigo) {
                                    $msj = $msj . "| CÓDIGO ";
                                }
                                if (!$escolDescripcion) {
                                    $msj = $msj . "| DESCRIPCION ";
                                }
                                if (!$escolMedida) {
                                    $msj = $msj . "| UNIDAD DE MEDIDA ";
                                }
                                if (!$escolCantidad) {
                                    $msj = $msj . "|CANTIDAD SOLICITADA ";
                                }

                            $this->get('session')->getFlashBag()->add('mensaje-danger', $msj);
                        }

                }// if verifica que sea item por ID
        
            } //if verifica que sea una fila de item   

        } // foreach fila

        if ($esItem == true ) {
            //Termino de guardar el resto de no ingreso en el batch
            $em->flush();
        }
        if (!empty($msjExiste)) {
                $msjExiste = "<p> Se encontraron ID que estan repetidos y se sobreescribieron</strong>: <br/>".$msjExiste."</p>";
                $this->get('session')->getFlashBag()->add('mensaje-warning', $msjExiste);        
        }

        $texto="<p> CANTIDAD DE ITEMS: <strong> $totalItems </strong></p>";
        return $texto;
    }
 
          /**
     * Creates a new licencium entity.
     *
     * @Route("/expediente/pedido/{id}/{slug}/new", name="documento_expediente_pnt_new")
     * @Method({"GET", "POST"})
     */
    public function expedientePntNewAction(Request $request, Expediente $expediente, $slug )
    {
        $em = $this->getDoctrine()->getManager();
        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->findOneBy(array('tipoTramite' => 8, 'expediente' =>$expediente->getId() ));
     
        if (empty($tramite)) {
               $tramite = new Tramite();
               $tramite->setOrganismoOrigen($this->getUser()->getDepartamentoRm()->getOrganismo());
               // ASIGNO NUMERO DE EXPEDIENTE INTERNO
                $tipoDocTramite = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug("tramite_doc");
                $tramite->setNumeroTramite($tipoDocTramite->getNumero());
                $tipoDocTramite->setNumero(1);

                // ASIGNO EL TIPO DE TRAMITE 
                $tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->findOneBySlug("tramite_doc");
                $tramite->setTipoTramite($tipoTramite);

                // ASIGNO EL ESTADO 
                $estado = $em->getRepository('ExpedienteBundle:EstadoTramite')->find(31);
                $tramite->setEstadoTramite($estado);
                $tramite->setExpediente($expediente);
                $em->persist($tramite);
                $em->flush();
                $msjExiste = "<p> Has CREADO  </strong>:".$tramite."</p>";
                $this->get('session')->getFlashBag()->add('mensaje-info', $msjExiste);  
        }

    if ($slug != "expediente-pnt") {
        return $this->redirectToRoute('documento_new', array('tramite_id' => $tramite->getId(), 'slug' => $slug));
     } else {
        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug("expediente-pnt");  
        $documento = new Documento();
        $documento->setTipoDocumento($tipoDocumento);
        $documento->setNombreDocumento($tipoDocumento->getNombreDocumento());
        $fecha= new \DateTime('now');       
        $documento->setFechaDocumento( $fecha);
        $documento->setTramite($tramite);
        $documento->setSlug("expediente-pnt");
        $documento->setEstado(false);
        $documento->setTexto("Pedido de Necesidades Trimestrales");
        //ASIGNO NUMERO  
        $documento->setAnio($fecha->format('Y'));
        $documento->setUsuario($this->getUser());
        //VERIFICO EL NUMERO
        $documento->setNumero($tipoDocumento->getNumero()); 
        $tipoDocumento->setNumero(1);

         /**Obtengo los items**/
            $expediente = $tramite->getExpediente();

            /** busco el itemPedido
            $repository = $this->em->getRepository('AusentismoBundle:ItemPedido');
            $query = $repository->createQueryBuilder('i'); 
                    $query->Join('i.tramite' , 't')
                          ->addSelect('t')
                          ->Where('t.expediente = :exp')
                          ->groupBy('i.codigo','i.consolida' )
                          ->setParameter('exp', $expediente ); 
                    $dql = $query->getQuery();

                    $items1 = $dql->getResult();*/

            $dql = 'SELECT  i AS item, SUM(i.cantidad) AS cantidad, MIN(i.precio) AS precio
                    FROM AusentismoBundle:ItemPedido i 
                    JOIN  i.tramite t
                    WHERE t.expediente = :exp 
                    GROUP BY i.codigo, i.detalle
                    ORDER BY i.precio ASC';
            $query = $em->createQuery($dql);
            $query->setParameter('exp', $expediente);
            $items = $query->getResult();

        $tramite->addDocumento($documento);
        $fileName = Util::getSlug($tramite->getTipoTramite()."_".$tramite->getNumeroTramite())."".$documento->getSlug()."". $this->generateUniqueFileName().'.xlsx';

        $spreadsheet = IOFactory::load($this->getParameter('plantillas').'/plantilla_pnt.xls');
        //$spreadsheet = $this->get('phpoffice.spreadsheet')->createSpreadsheet();
        $organismo= $this->getUser()->getDepartamentoRm()->getOrganismo();
        $saf = $organismo->getSaf();
        $ministerio = $organismo->getMinisterio();
        $fecha = new \DateTime('now');
        $anio = $fecha->format('Y');
        $mes=date('m');
        //  $mes = is_null($mes) ? date('m') : $mes;
        $trim=floor(($mes-1) / 3)+1;

        //CREO PEDIDO DE NECESIDADES TRIMESTRALES ------------------------------
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setCellValue('C4', $ministerio->getCodigoJuridiccion()." - ". $ministerio->getMinisterio());
        $spreadsheet->getActiveSheet()->setCellValue('C5', $saf->getNumeroSaf()." - ".$saf->getSaf());
        $spreadsheet->getActiveSheet()->setCellValue('C6', $anio);
        $spreadsheet->getActiveSheet()->setCellValue('C7', $trim."° TRIMESTRE");

        $fila = 10;
        $uf = 11; 
        $i =0;
        foreach ($items as $item) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore($uf);
            $i++;
            $uf++;
            $itemPedido = $item['item'];
            $cantidad = $item['cantidad'];
            $precio = $item['precio'];
            $ipp = $itemPedido->getIpp();
            $ipp = str_split($ipp);
            $ipp = implode(".", $ipp);
            $codigo = $itemPedido->getCodigo();
            $item = $itemPedido->getItem();
            $rubro = $itemPedido->getRubro();
            $itemCatalogo = $em->getRepository('AusentismoBundle:ItemCatalogo')->findOneByCodigo($codigo);
            if (!empty($itemCatalogo)) {
                $codigo = $itemCatalogo->getCodigo();
                $item = $itemCatalogo->getItem();
                $rubro = $itemCatalogo->getRubro();
            }
              $spreadsheet->getActiveSheet()->setCellValue('A'.$fila, $i);
              $spreadsheet->getActiveSheet()->setCellValue('B'.$fila, $codigo);
              $spreadsheet->getActiveSheet()->setCellValue('C'.$fila, $rubro);
              $spreadsheet->getActiveSheet()->setCellValue('D'.$fila, $ipp);
              $spreadsheet->getActiveSheet()->setCellValue('E'.$fila, $item);
              $spreadsheet->getActiveSheet()->setCellValue('F'.$fila, $itemPedido->getUnidadMedida());
              $spreadsheet->getActiveSheet()->setCellValue('G'.$fila, $cantidad);
              $spreadsheet->getActiveSheet()->setCellValue('H'.$fila, $precio);
              $spreadsheet->getActiveSheet()->setCellValue('I'.$fila, ($cantidad * $precio));
              $detalle = strip_tags($itemPedido->getDetalle());
              $detalle = preg_replace("/[\r\n|\n|\r]+/", " - ", $detalle);
              $spreadsheet->getActiveSheet()->setCellValue('J'.$fila, $detalle);
            $fila++;
        }
        // MONTO TOTAL
        $formatter = new NumeroALetras();
        $monto = $spreadsheet->getActiveSheet()->getCell('I'.($uf+1))->getCalculatedValue();
        $letra = $formatter->toInvoice($monto, 2,'pesos');
        $spreadsheet->getActiveSheet()->setCellValue('C'.($uf+1), $letra);

        // CREAR NOTA DE PEDIDO -----------------------------------------
        $tipoTramite = $em->getRepository('ExpedienteBundle:TipoTramite')->findOneBySlug("tramite_pedido");
        $pedidos = $em->getRepository('ExpedienteBundle:Tramite')->findBy(array('tipoTramite' => $tipoTramite->getId(), 'expediente' =>$expediente->getId()));
        foreach ($pedidos as $tramite) {
            $codigoSaf = $tramite->getOrganismoOrigen()->getCodigoGde();
            $numeroPedido = $tramite->getNumeroTramite()." / ".$anio;
            $clonedWorksheet = clone $spreadsheet->getSheetByName('NotaPedido');
            $clonedWorksheet->setTitle('Nota Pedido '.$codigoSaf);
            $clonedWorksheet->setCellValue('G2', $numeroPedido );
            $clonedWorksheet->setCellValue('C4', $ministerio->getCodigoJuridiccion()." - ". $ministerio->getMinisterio());
            $clonedWorksheet->setCellValue('C5', $saf->getNumeroSaf()." - ".$saf->getSaf());
            $clonedWorksheet->setCellValue('C6', $tramite->getOrganismoOrigen());
            $clonedWorksheet->setCellValue('C7', $tramite->getOrganismoOrigen());
            $clonedWorksheet->setCellValue('C8', $tramite->getTrimestre()."° TRIMESTRE - ".$anio);
            $clonedWorksheet->setCellValue('C9', $tramite->getTexto());
            $fila = 13;
            $uf = 14; 
            $i =0;
            $total=0;
            foreach ($tramite->getItemPedido() as $itemPedido) {
                $clonedWorksheet->insertNewRowBefore($uf);
                $i++;
                $uf++;
                $ipp = $itemPedido->getIpp();
                $ipp = str_split($ipp);
                $ipp = implode(".", $ipp);
                $cantidad = $itemPedido->getCantidad();
                $precio = $itemPedido->getPrecio();
                $codigo = $itemPedido->getCodigo();
                $item = $itemPedido->getItem();
                $itemCatalogo = $em->getRepository('AusentismoBundle:ItemCatalogo')->findOneByCodigo($codigo);
                if (!empty($itemCatalogo)) {
                    $codigo = $itemCatalogo->getCodigo();
                    $item = $itemCatalogo->getItem();
                }
                  $clonedWorksheet->setCellValue('A'.$fila, $itemPedido->getNumero());
                  $clonedWorksheet->setCellValue('B'.$fila, $codigo );
                  $clonedWorksheet->setCellValue('C'.$fila, $item);
                    if (empty($itemPedido->getDetalle())) {
                        $detalle = "NO";
                    } else {
                        $detalle = "SÍ";
                    }
                  $clonedWorksheet->setCellValue('D'.$fila, $detalle);
                  $clonedWorksheet->setCellValue('E'.$fila, $itemPedido->getUnidadMedida());
                  $clonedWorksheet->setCellValue('F'.$fila, $precio);
                  $clonedWorksheet->setCellValue('G'.$fila, $cantidad);
                  $total = $total + ($precio*$cantidad);
                 $fila++;
            }
            // MONTO TOTAL
            $formatter = new NumeroALetras();
            $letra = $formatter->toInvoice($total, 2,'pesos');
            $clonedWorksheet->setCellValue('C'.($uf+1), $letra);

            // DETALLE TECNICO 
            $fila = $uf + 7;
            $uf = $uf + 8; 
            foreach ($tramite->getItemPedido() as $itemPedido) {
                if (!empty($itemPedido->getDetalle())) {
                    $clonedWorksheet->insertNewRowBefore($uf);
                    $uf++;
                    $clonedWorksheet->setCellValue('A'.$fila, "ITEM ".$itemPedido->getNumero());
                    $detalle = strip_tags($itemPedido->getDetalle());
                    $detalle = preg_replace("/[\r\n|\n|\r]+/", " - ", $detalle);
                    $clonedWorksheet->setCellValue('B'.$fila, $detalle);
                    $fila++;
                }
            }
          //AGREGO LA HOJA
            $spreadsheet->addSheet($clonedWorksheet);

          //CREAR APG ---------------------------------------------------------------------
            $clonedWorksheet = clone $spreadsheet->getSheetByName('APG');
            $clonedWorksheet->setTitle('APG '.$codigoSaf);
            $clonedWorksheet->setCellValue('D1', $anio);
            $clonedWorksheet->setCellValue('G4', $ministerio->getCodigoJuridiccion()." - ". $ministerio->getMinisterio());
            $clonedWorksheet->setCellValue('G5', $saf->getNumeroSaf()." - ".$saf->getSaf());
            $clonedWorksheet->setCellValue('G6', $tramite->getOrganismoOrigen());
            $clonedWorksheet->setCellValue('G7', $tramite->getOrganismoOrigen());
            $clonedWorksheet->setCellValue('C8', $tramite->getTrimestre()."° TRIMESTRE - ".$anio);
            $clonedWorksheet->setCellValue('A10'," Autorizo la realización de los  trámites correspondientes, a fin de proveer a  este Organismo los bienes y/o servicios que se detallan en Nota  de Pedido N° ".$numeroPedido.",  afectando la erogación resultante a los créditos presupuestarios aprobados a favor de esta Unidad Ejecutora, conforme al siguiente detalle:");

            $dql = 'SELECT  i AS item, i.ipp AS ipp, SUM(i.precio*i.cantidad) AS monto
                    FROM AusentismoBundle:ItemPedido i 
                    JOIN  i.tramite t
                    WHERE i.tramite = :id 
                    GROUP BY i.ipp
                    ORDER BY i.ipp ASC';
            $query = $em->createQuery($dql);
            $query->setParameter('id', $tramite);
            $items = $query->getResult();
            $fila = 15;
            $uf = 16; 
            $total =0;
            foreach ($items as $item) {
                $ippArrAy = str_split($item['ipp']);
                $total = $total + $item['monto'];
                $clonedWorksheet->insertNewRowBefore($uf);
                $uf++;
                $sb=0;
                $py=0;
                $ug=4901;
                $subP=0;
                $m=1;
                $ff=11;
                $rubro = $em->getRepository('AusentismoBundle:Rubro')->findOneByIpp($item['ipp']);
                $clonedWorksheet->setCellValue('B'.$fila, $sb);
                $clonedWorksheet->setCellValue('C'.$fila, $py);
                $clonedWorksheet->setCellValue('F'.$fila, $ug);
                $clonedWorksheet->setCellValue('L'.$fila, $subP);
                $clonedWorksheet->setCellValue('M'.$fila, $m);
                $clonedWorksheet->setCellValue('N'.$fila, $ff);
                $clonedWorksheet->setCellValue('I'.$fila, $ippArrAy[0]);
                $clonedWorksheet->setCellValue('J'.$fila, $ippArrAy[1]);
                $clonedWorksheet->setCellValue('K'.$fila, $ippArrAy[2]);
                $clonedWorksheet->setCellValue('O'.$fila, $rubro->getRubro());
                $clonedWorksheet->setCellValue('Q'.$fila, $item['monto']);
                $fila++;
            }
            
            // MONTO TOTAL
            $formatter = new NumeroALetras();
            $letra = $formatter->toInvoice($total, 2,'pesos');
            $clonedWorksheet->setCellValue('C'.($uf+1), $letra);

            $clonedWorksheet->setCellValue('A'.($uf+4), $tramite->getTexto());

            //AGREGO LA HOJA
            $spreadsheet->addSheet($clonedWorksheet);
        }

        //ELIMINO LAS HOJAS DE PLANTILLAS
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('NotaPedido'));
        $spreadsheet->removeSheetByIndex($sheetIndex);
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('APG'));
        $spreadsheet->removeSheetByIndex($sheetIndex);

        //GUARDO EL ARCHIVO EXCEL CREADO
        $writerXlsx = $this->get('phpoffice.spreadsheet')->createWriter($spreadsheet, 'Xlsx');
        $writerXlsx->save($this->getParameter('archivos').'/'.$fileName);


        $documento->setArchivo($fileName);

        //GUARDO EL REGISTRO DECUMENTO
        $em->persist($documento);
        $em->flush();
        
    }
        $msjExiste = "<p> Has CREADO el Documento </strong>:".$documento."</p>";
        $this->get('session')->getFlashBag()->add('mensaje-info', $msjExiste);  

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);

    }
    
    
    /**
     * @return array
     */
 public function findExpedienteLicencia($articulo, $año, $agente)
    {     
        $em = $this->getDoctrine()->getManager();

        $expediente = $em->getRepository('ExpedienteBundle:Expediente')->findOneBy(array('articulo'=>$articulo, 'anio'=>$año, 'agente'=>$agente ));

        if  (!is_object($expediente)) {
            $expediente = new Expediente();
            $tramite= new Tramite();
            $datoAt= new DatoAT();
            $expediente->setEstado(true);
            $tramite->setEstado(true);      
            $tramite->setFechaOrigen(new \DateTime('now')); 
            $expediente->setAnio((new \DateTime('now'))->format('Y'));
            $expediente->setAnioGde((new \DateTime('now'))->format('Y')); 
            $articulo=$em->getRepository('AusentismoBundle:Articulo')->find($articulo);
            $expediente->setArticulo($articulo); 
            $clas = $em->getRepository('ExpedienteBundle:Clasificacion')->getByClasificacion($agente->getEscalafon());
            $expediente->setClasificacion($clas);
            $usuario = $this->getUser();
            $departamento  = $usuario->getDepartamentoRm();
            $tipo = $departamento->getId();
            $expediente->setExtracto('LICENCIA: '.$articulo);   
             $tt = $em->getRepository('ExpedienteBundle:TipoTramite')->find(17);
            $expediente->setTipoTramite($tt);
            //  $cl = $em->getRepository('ExpedienteBundle:Clasificacion')->find(1);
            // $expediente->setClasificacion($cl); 
            $org = $em->getRepository('AusentismoBundle:Organismo')->find(227);
            $tramite->setOrganismoOrigen($org);
      
            $expediente->setLetra("LIC");
            // ASIGNO NUMERO DE EXPEDIENTE INTERNO
            $numero = $em->getRepository('DocumentoBundle:TipoDocumento')->getNumeroDoc('lic');
            $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug('lic');

            $expediente->setNumero($numero);
            $expediente->setAgente($agente);
            $tramite->setExpediente($expediente);
            $expediente->addTramite($tramite);
            $expediente->setDatoAt($datoAt);
            $datoAt->setExpediente($expediente);
            $expediente->setDepartamentoRm($departamento );
            $tipoDocumento->setNumero($expediente->getNumero());
            $em->persist($expediente);
            $em->persist($tramite); 
            $em->persist($datoAt);
            $em->persist($tipoDocumento);
            $em->flush();

            $msj= 'Se ha creado el expediente: '.$expediente;
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);

            $this->historial($expediente->getId(),'CREADO', $msj, $expediente::TIPO_ENTIDAD); 
         // $this->historial($expediente,'CREADO', $msj);
      }

           return $expediente;
}


    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
       // return md5(uniqid());
        return substr(md5(uniqid(rand(1,6))), 0, 8);
    }


     /**
     * Finds and displays a documento entity.
     *
     * @Route("/{id}/show", name="documento_show")
     * @Method("GET")
     */
    public function showAction(Documento $documento)
    {
        $deleteForm = $this->createDeleteForm($documento);

        $slug = $documento->getSlug();

       $msj = 'Has VISTO el Documento: <strong> '.$documento.' del Expediente:'.$documento->getTramite().'</strong>';


         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
       //$this->historial($documento->getid(),'VISTO', $msj, $documento::TIPO_ENTIDAD);
        return $this->render('DocumentoBundle:Documento:documento_show.html.twig', array(
            'documento' => $documento,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing documento entity.
     *
     * @Route("/{id}/edit", name="extranet_documento_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Documento $documento)
    {
        $deleteForm = $this->createDeleteForm($documento);

        $tipo = $documento->getTipoDocumento();
        
        if ($documento->getArchivo() != null) {
        $documento->setArchivo(  
            new File ( $this->getParameter('archivos').'/'.$documento->getArchivo()));
        }
        
        if ($documento->getSlug() == "archivo"){

            $editForm = $this->createForm('Siarme\DocumentoBundle\Form\Doc'.$documento->getSlug().'Type', $documento);

        } elseif ($documento->getSlug() == "licencia"){

            $editForm = $this->createForm('Siarme\DocumentoBundle\Form\DocMedicoType', $documento);

        } else {

            $editForm = $this->createForm('Siarme\DocumentoBundle\Form\DocumentoType', $documento);

        }

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $documento->getArchivo();
            if (!empty($file) && $file != null) {

                // $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                // 
                // Util::getSlug($tramite->getExpediente()->getAgente()->getApellidoNombre());
               // $fileName = Util::getSlug($tramite->getExpediente()->getAgente()->getApellidoNombre())."_".$tramite->getExpediente()->getAgente()->getDni()."_". $this->generateUniqueFileName().'.'.$file->guessExtension();
                //dump( $fileName );
                //exit();
                // moves the file to the directory where brochures are stored
                $file->move( $this->getParameter('archivos'),
                    $fileName );

                // updates the 'brochure' property to store the PDF file name
                // instead of its contents
                $documento->setArchivo($fileName);
            } else {

                 $documento->setArchivo(null);
            }
       
       $em = $this->getDoctrine()->getManager();

       if ($documento->getSlug() == "licencia"){

                  if ( $documento->getLicencia()->getDias() <= 1 ) {
                    
                        $documento->getLicencia()->setFechaHasta($documento->getLicencia()->getFechaDesde());
                        
                    } else {

                        $fechaDesde = $documento->getLicencia()->getFechaDesde()->format('Y-m-j');
                        $fechaHasta = strtotime ( '+'.($documento->getLicencia()->getDias()-1).' day' , strtotime ( $fechaDesde ) ) ;
                        $fechaHasta = date ( 'Y-m-j' , $fechaHasta );
                        $fechaHasta = new \DateTime($fechaHasta);
                        $documento->getLicencia()->setFechaHasta($fechaHasta);
                    }
            }

            $em->flush();
             $msj = 'Has guardado los cambios en el documento '.$documento;
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info', $msj);

         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
        $this->historial($documento->getId(),'MODIFICADO', $msj, $documento::TIPO_ENTIDAD);

        return $this->redirectToRoute('documento_show', array('id' => $documento->getId(), 'tipodocumento' => $documento->getId()));

        }

        $slug = $documento->getSlug();
        return $this->render('DocumentoBundle:Documento:documento_edit.html.twig', array(
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

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $documento1= $documento;
            $id = $documento->getId();
            $em->remove($documento);
            $em->flush($documento); 

            $msj = 'Has eliminado el Documento: <strong> '.$documento1.' del tramite '.$documento1->getTramite()->getNumeroTramite().' - Expediente '.$documento1->getTramite().' </strong>';
                /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
                $this->historial($id,'ELIMINADO', $msj, $documento1::TIPO_ENTIDAD);
                
                $this->get('session')->getFlashBag()->add(
                'mensaje-info', $msj);

        }
        
        $tramite= $documento->getTramite();
        
        return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
    }

    /**
     * Deletes a documento entity.
     *
     * @Route("/{id}/eliminar", name="documento_eliminar")
     * @Method("GET")
     */
    public function eliminarAction(Request $request, Documento $documento)
    {
            $documento1= $documento;
            $id = $documento->getId();
            $em = $this->getDoctrine()->getManager();
            if (!empty($documento->getArchivo())) {
                $filePath = $this->getParameter('archivos').'/'.$documento->getArchivo();
                $existe = file_exists($filePath);
                if ($existe) {
                            unlink($filePath);
                }
            }
            
            $em->remove($documento);
            $em->flush($documento);   
           if ($documento1->getSlug()=="licencia") {
                $msj = 'Has eliminado el Documento: <strong> '.$documento1;
            } else {
                $msj = 'Has eliminado el Documento: <strong> '.$documento1.' del tramite '.$documento1->getTramite()->getNumeroTramite().' - Expediente '.$documento1->getTramite().' </strong>';
            }

         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
         $this->historial($id,'ELIMINADO', $msj, $documento1::TIPO_ENTIDAD);
       $referer = $request->headers->get('referer');
                return $this->redirect($referer);
    }

    /**
     * Deletes a documento entity.
     *
     * @Route("/{id}/papelera", name="documento_papelera")
     * @Method("GET")
     */
    public function papeleraAction(Request $request, Documento $documento)
    {
            $em = $this->getDoctrine()->getManager();
            $documento->setPapelera(true);
            $em->flush($documento);   

       $msj = 'Has enviado a la papelera el documento '.$documento;

            $this->get('session')->getFlashBag()->add(
                    'mensaje-info', $msj);

         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
      $this->historial($documento->getId(),'PAPELERA', $msj, $documento::TIPO_ENTIDAD);
       $referer = $request->headers->get('referer');
                return $this->redirect($referer);
    }

    /**
     * Deletes a documento entity.
     *
     * @Route("/{id}/restaurar", name="documento_restaurar")
     * @Method("GET")
     */
    public function restaurarAction(Request $request, Documento $documento)
    {
            $em = $this->getDoctrine()->getManager();
            $documento->setPapelera(false);
            $em->flush($documento);   

       $msj = 'Has restaurado de la papelera el documento '.$documento;

            $this->get('session')->getFlashBag()->add(
                    'mensaje-info', $msj);

         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
      $this->historial($documento->getId(),'RESTAURADO', $msj, $documento::TIPO_ENTIDAD);
               $referer = $request->headers->get('referer');
                return $this->redirect($referer);
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
     * @Route("/{id}/{estado}/gde", name="extranet_documento_gde")
     * 
     */
    public function gdeAction(Documento $documento, $estado = false)
    {
      $em= $this->getDoctrine()->getManager();
       
       $documento->setEstado($estado);
       if (!empty($documento->getLicencia()))
       $documento->getLicencia()->setEstado($estado);

       $em->flush();
       $msj = 'Has enviado a GDE el Documento '.$documento;
         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
      $this->historial($documento->getId(),'MODIFICADO', $msj, $documento::TIPO_ENTIDAD);

      return $this->redirectToRoute('documento_show', array('id' => $documento->getId(), 'tipodocumento' => $documento->getId()));
    }

    // 
    /**
     * CODIGO PARA REDIRIGIR A UNA PAGINA ANTERIOR !!
     * @Route("/doc/cancelar", name="extranet_documento_cancelar")
     * 
     */
    public function cancelarAction(Request $request)
    {
    
       $request->getSession()
                    ->getFlashBag()
                    ->add('notice', 'success');
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
    }
     
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
