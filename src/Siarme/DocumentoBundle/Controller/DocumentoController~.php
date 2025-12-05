<?php

namespace Siarme\DocumentoBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Tramite;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\DocumentoBundle\Entity\Documento;
use Siarme\DocumentoBundle\Entity\Historial;
use Siarme\AusentismoBundle\Entity\Agente;
use Siarme\AusentismoBundle\Entity\ItemPedido;
use Siarme\AusentismoBundle\Entity\Licencia;
use Siarme\AusentismoBundle\Entity\Item;
use Siarme\AusentismoBundle\Entity\Rubro;
use Siarme\ExpedienteBundle\Entity\DatoAT;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\File\File;
use Siarme\AusentismoBundle\Util\Util;
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
                $fileName = Util::getSlug($tramite->getTipoTramite()."_".$tramite->getNumeroTramite())."".$documento->getSlug()."". $this->generateUniqueFileName().'.'.$file->guessExtension();
          
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
     public function actualizarItem($file)           
    {
                    //$file->getActiveSheet()->setCellValue('A1', 'Hello world');

                        //El número de hojas en un libro
                      //  $contar = $file->getSheetCount();
                        //selecciona la hoja actual empezando en 0
                        $hojaActual = $file->getSheet(0);
                        // obtengo el numero filas 
                        $numeroFilas = $file->getHighestRow();
                        // obtengo el numero de columnas
                        $numeroColumnas = $hojaActual->getHighestColumn();
                         ini_set('memory_limit', '2048M');

                        $filas=0;
                        # Iterar filas
                        //$old = ini_set('memory_limit', '512M');
                        $items = array();
                         $em = $this->getDoctrine()->getManager();
                         $batchSize = 500;
                         $j=0;
                        foreach ($hojaActual->getRowIterator() as $fila) {
                                $j= $j+1;
                                $item = new Item();
                                $filas = $filas + 1;
                              //  $s = $j % $batchSize;
                             // echo "Tamaño: $s";
                        if ($filas > 1) {
                                 $i= 0;
                                foreach ($fila->getCellIterator() as $celda) {
                                    $i = $i + 1;
                                         # El valor, así como está en el documento
                                         $valorRaw = trim($celda->getValue());
                                     # Fila, que comienza en 1, luego 2 y así...
                                      $fila = $celda->getRow();
                                        # Columna, que es la A, B, C y así...
                                       $columna = $celda->getColumn();

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
                                         default:
                                          // creates a CSS-response with a 200 status code
                                                echo 'Se producido un error';                          
                                             break;
                                        }
                                         
                                }
                                    $em->persist($item);
                                    if (($j % $batchSize) === 0) {
                                         
                                         $em->flush();
                                         $em->clear(); // Detaches all Car objects from Doctrine!
                                    }
                        }
                        
                     }
                        $em->flush(); 
                        $em->clear();
                        $response = new Response("<html><body>Cantidad de Filas: $filas</body></html>");
                        $response->headers->set('Content-Type', 'text/css');
                        return $response;
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

        $tramite = $em->getRepository('ExpedienteBundle:Tramite')->find($tramite_id);

        $tipoDocumento = $em->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($slug);  

             $acta = null;
  
            //dump($acta);            exit();
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
        $tipo = $tipoDocumento ->getRol()->getRoleName();


        // COMPRUEBO SI ES UN DOCUMENTO PDF
        if ($tipoDocumento->getEsArchivo()){
             $documento->setTexto("Archivo");
             $form = $this->createForm('Siarme\DocumentoBundle\Form\DocarchivoType', $documento);

        } else {
             $form = $this->createForm('Siarme\DocumentoBundle\Form\DocumentoType', $documento);
        }
      
               
        // ### Buscar El tipo de documento y sus valores ##
        //$tipo_docs = $this->getParameter('tipodoc');
        //$tipodoc = $tipo_docs['administrativo'];      
   
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
             $referer = $request->headers->get('referer');
             $file = $documento->getArchivo();

            if ($documento->getTipoDocumento()->getEsArchivo() == true && !empty($file) && $file != null) {
                

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                //$originalFilename= Util::getSlug($originalFilename);

                $fileName = Util::getSlug($tramite->getTipoTramite()."_".$tramite->getNumeroTramite())."".$documento->getSlug()."". $this->generateUniqueFileName().'.'.$file->guessExtension();

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
                    $msj = $this->itemNPT($fileName, $tramite);
                         //$msj = "Cantiad de Hojas: $numeroHojas - Nota Pedido:  Filas: $numeroFilas - Columnas: $numeroColumnas - SAF: $saf";
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
            
            $tramite->addDocumento($documento);
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
            if ($hojaActual->getCell("A".$i)->getCalculatedValue() == "ID"){

                    $escolCodigo = false;
                    $escolRubro = false;
                    $escolIpp = false;
                    $escolDescripcion = false;
                    $escolMedida = false;
                    $escolCantidad = false;
                    $escolPrecio = false;
                    $colId = 1;
                    for ($col=1; $col < 50; $col++) {
                       
                        if ((strpos($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue(), "CODIGO") !== false) or (strpos($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue(), "CÓDIGO") !== false))  {
                             $escolCodigo = true;
                             $colCodigo = $col;
                        }
                        if (strpos($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue(), "RUBRO") !== false) {
                             $escolRubro = true;
                             $colRubro = $col;
                        }                        
                        if ((strpos($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue(), "I.P.P") !== false) or (strpos($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue(), "IPP") !== false)) {
                             $escolIpp = true;
                             $colIpp = $col;
                        }                                
                        if (strpos($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue(), "DESCRIP") !== false) {
                             $escolDescripcion = true;
                             $colDescripcion = $col;
                        }
                        if (strpos($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue(), "UNIDAD") !== false) {
                             $escolMedida = true;
                             $colMedida = $col;
                        }
                        if (strpos($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue(), "CANTIDA") !== false) {
                             $escolCantidad = true;
                             $colCantidad = $col;
                        }
                        if (strpos($hojaActual->getCellByColumnAndRow($col, $i)->getCalculatedValue(), "PRECIO") !== false) {
                             $escolPrecio = true;
                             $colPrecio = $col;
                        }
                    }
    
                
                $npt= ($escolCodigo and $escolRubro and $escolIpp and $escolDescripcion and $escolMedida and $escolCantidad and $escolPrecio);
                $filaItem= $i;
             }
        }

        if (!$npt){
            $msj = "Verifique que sea correcto el formato de la Nota de TRIMESTRAL";
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
            if ($f > $filaItem) {
           
                $esItem = is_numeric($hojaActual->getCell("A".$f)->getValue()) && (!empty($hojaActual->getCell("C".$f)->getValue())); 

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
                             $valorRaw = $celda->getCalculatedValue();

                              switch ($c) {
                                case $colId:                                                
                                      $item->setNumero($valorRaw);
                                     break;
                                case $colCodigo:
                                            if (empty($valorRaw) or $valorRaw == "-"){
                                                $sistema = "BIONEXO";
                                                $item->setSistema($sistema);
                                            } else {
                                                $item->setSistema($sistema);
                                                $item->setCodigo($valorRaw);
                                            }        
                                     break;
                                case $colRubro:
                                       $item->setRubro($valorRaw);
                                     break;
                                case $colIpp:
                                       $item->setIpp($valorRaw);
                                     break;
                                 case $colDescripcion:
                                      $item->setItem($valorRaw);
                                      // $item->setItem(Util::getSlug($valorRaw));
                                      //$item->setCodigo($item->getNumero()." - n/d ");
                                     break;
                                 case $colMedida:
                                      $item->setUnidadMedida($valorRaw);
                                     break;
                                 case $colCantidad:
                                      $item->setCantidad($valorRaw);
                                     break;
                                 case $colPrecio:
                                      $item->setPrecio($valorRaw);
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

        }
            // ASIGNO EL SISTEMA AL TRAMITE 
            $item->getTramite($sistema);
             //Termino de guardar el resto de no ingreso en el batch
             $em->flush();
             $em->clear(ItemPedido::class);
                if (!empty($msjExiste)) {
                    $msjExiste = "<p>Se actualizaron Item existentes: ".$msjExiste."</p>";        
                    //$this->get('session')->getFlashBag()->add('mensaje-info', $msjExiste);
                }

                $texto="<p>JURISDICCI&Oacute;N: $jur <br />SAF: $saf <br /> UNIDAD EJECUTORA:<br /> PERIODO UTILIZACI&Oacute;N: $tri<br /></p> <p> CANTIDAD DE ITEMS: <strong> $totalItems </strong></p> $msjExiste";

                return $texto;

 }

    /**
     * Creates a new licencium entity.
     *
     * @Route("/licencia/{id}/articulo/{articulo}/new", name="documento_licencia_new")
     * @Method({"GET", "POST"})
     * @ParamConverter("agente", class="AusentismoBundle:Agente")
     */
    public function licenciaNewAction(Request $request, Agente $agente, $articulo = 9 )
    {
        
       // dump($agente);
        //exit();

        if  ( (empty($agente->getLocalidad())) or ($agente->getCargo()->isEmpty())) {
           
           $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    'POR FAVOR: Actualizar la <strong> LOCALIDAD </strong> o el <strong> CARGO </strong> del Agente e intente crear la LICENCIA nuevamente');
           return $this->redirectToRoute('agente_show', array('id' => $agente->getId()));

        } 
          $año = (new \DateTime('now'))->format('Y');
          $expediente = $this->findExpedienteLicencia($articulo, $año, $agente);

           return $this->redirectToRoute('documento_new', array('slug' => 'licencia', 'id' => $expediente->getId(), 'articulo'=>$articulo ));

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

         if ($tipo == "Medico"){

                  if ( $documento->getLicencia()->getDias() <= 1 ) {
                    
                        $documento->getLicencia()->setFechaHasta($documento->getLicencia()->getFechaDesde());
                        
                    } else {

                         $fechaDesde = $documento->getLicencia()->getFechaDesde()->format('Y-m-j');
                         $fechaHasta = strtotime ( '+'.($documento->getLicencia()->getDias()-1).' day' , strtotime ( $fechaDesde ) ) ;
                         $fechaHasta = date ( 'Y-m-j' , $fechaHasta );
                         $fechaHasta = new \DateTime($fechaHasta);
                        $documento->getLicencia()->setFechaHasta($fechaHasta);
                        
                    }

                    if ($documento->getTramite()->getExpediente()->getArticulo() != $documento->getLicencia()->getArticulo()){ 

                         $año = ($documento->getLicencia()->getFechaDesde())->format('Y');
                         $expediente = $this->findExpedienteLicencia($documento->getLicencia()->getArticulo(), $año, $documento->getLicencia()->getAgente());

                      
                         $tramite = $em->getRepository('ExpedienteBundle:Tramite')->findUltimo($expediente);
                         $documento->setTramite($tramite);
                        $tramite->addDocumento($documento);
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
            $em->remove($documento);
            $em->flush($documento); 

            $msj = 'Has eliminado el Documento: <strong> '.$documento1.' del tramite '.$documento1->getTramite()->getNumeroTramite.' - Expediente '.$documento1->getTramite().' </strong>';
                /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/    
                $this->historial($documento1->getId(),'ELIMINADO', $msj, $documento1::TIPO_ENTIDAD);
                
                $this->get('session')->getFlashBag()->add(
                'mensaje-info', $msj);

        }
        
        $tramite= $documento->getTramite();
        
        return $this->redirectToRoute('tramite_show', array('id' => $tramite->getId()));
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

   public function limpiar($s) 
    { 
        $s = str_replace('á', 'a', $s); 
        $s = str_replace('Á', 'A', $s); 
        $s = str_replace('é', 'e', $s); 
        $s = str_replace('É', 'E', $s); 
        $s = str_replace('í', 'i', $s); 
        $s = str_replace('Í', 'I', $s); 
        $s = str_replace('ó', 'o', $s); 
        $s = str_replace('Ó', 'O', $s); 
        $s = str_replace('Ú', 'U', $s); 
        $s= str_replace('ú', 'u', $s); 

        //Quitando Caracteres Especiales 
        //$s= str_replace('#', ' ', $s); 
        $s= str_replace('  ', ' ', $s); 
        $s= str_replace('   ', ' ', $s); 
        $s= trim($s); 
        return $s; 
    }
}
