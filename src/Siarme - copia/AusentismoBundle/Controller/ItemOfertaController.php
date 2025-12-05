<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\ItemOferta;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Itemofertum controller.
 *
 * @Route("itemoferta")
 */
class ItemOfertaController extends Controller
{
    /**
     * Lists all itemOfertum entities.
     *
     * @Route("/", name="itemoferta_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $itemOfertas = $em->getRepository('AusentismoBundle:ItemOferta')->findAll();

        return $this->render('itemoferta/index.html.twig', array(
            'itemOfertas' => $itemOfertas,
        ));
    }

    /**
     * Lista todos los items adjudicados .
     *
     * @Route("/items-adjudicados", name="itemoferta_index_adjudicado")
     * @Method("GET")
     */
    public function indexAdjudicadoAction(Request $request)
    {
        $itemOfertas= null;
        $cons = null;

        $date = new \Datetime();
        $anio = $date->format("Y");

        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');

        $cons = trim($request->get('consulta')); 
            $repository = $this->getDoctrine()->getRepository('AusentismoBundle:ItemOferta');
            $query = $repository->createQueryBuilder('i');
        if (!empty($cons)) {     
              $query->andWhere("concat(i.codigo,' ',i.item) like :keyword")
                    ->andWhere('i.adjudicado = :estado ')
                    ->groupBy('i.codigo')
                    ->addOrderby('i.codigo', 'ASC')
                    ->setParameter('keyword', '%'.$cons.'%')
                    ->setParameter('estado', true);
        } else {
            $query->andWhere('i.adjudicado = :estado ')
                  ->andWhere('i.fecha >= :fechaDesde ')
                  ->andWhere('i.fecha <= :fechaHasta ')
                  ->groupBy('i.codigo')
                  ->addOrderby('i.codigo', 'ASC')
                  ->setParameter('estado', true)
                  ->setParameter('fechaDesde', $fechaDesde)
                  ->setParameter('fechaHasta', $fechaHasta);
        }
        $query->setMaxResults(10000);
        $itemOfertas = $query->getQuery()->getResult();  

        return $this->render('AusentismoBundle:ItemOferta:index_adjudicado.html.twig', array(
            'itemOfertas' => $itemOfertas,
            'find' => $cons,
        ));
    }

    /**
     * Displays a form to edit an existing itemPedido entity.
     *
     * @Route("/{id}/texto/edit", name="itemoferta_texto_edit")
     * @Method({"GET", "POST"})
     */
    public function editTextoAction(Request $request, ItemOferta $itemOferta)
    {
        $deleteForm = $this->createDeleteForm($itemOferta);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\ItemTextoOfertaType', $itemOferta);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $msj= 'Se han guardado los detalle técnico';
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
            return $this->redirectToRoute('itemoferta_show', array('id' => $itemOferta->getId()));
        }

        return $this->render('AusentismoBundle:ItemOferta:modal_texto_edit.html.twig', array(
            'itemOferta' => $itemOferta,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a new itemOfertum entity.
     *
     * @Route("/new", name="itemoferta_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $itemOfertum = new Itemofertum();
        $form = $this->createForm('Siarme\AusentismoBundle\Form\ItemOfertaType', $itemOfertum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($itemOfertum);
            $em->flush();

            return $this->redirectToRoute('itemoferta_show', array('id' => $itemOfertum->getId()));
        }

        return $this->render('itemoferta/new.html.twig', array(
            'itemOfertum' => $itemOfertum,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a itemOferta entity.
     *
     * @Route("/{id}", name="itemoferta_show")
     * @Method("GET")
     */
    public function showAction(ItemOferta $itemOferta)
    {
        $deleteForm = $this->createDeleteForm($itemOferta);

        return $this->render('AusentismoBundle:ItemOferta:show.html.twig', array(
            'itemOferta' => $itemOferta,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing itemOferta entity.
     *
     * @Route("/{id}/edit", name="itemoferta_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ItemOferta $itemOferta)
    {
        $deleteForm = $this->createDeleteForm($itemOferta);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\ItemOfertaType', $itemOferta);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $msj= 'Se han guardado los cambios';
            $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);

            return $this->redirectToRoute('itemoferta_show', array('id' => $itemOferta->getId()));
        }

        return $this->render('AusentismoBundle:ItemOferta:modal_edit.html.twig', array(
            'itemOferta' => $itemOferta,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Cambia de estado de itemOferta entity.
     *
     * @Route("/{id}/estado", name="item_oferta_estado")
     * @Method("POST")
     */
    public function estadoAction(Request $request, ItemOferta $itemOferta)
    {
            $estado= $itemOferta->getEstado();
            $itemOferta->setEstado(!$estado);
            $proceso = $itemOferta->getProceso();
            $proceso->setAdjudicado(false);
            $em = $this->getDoctrine()->getManager()->flush();

        return new Response("Has cambiado de estado del Item");
    }

    /**
     * devuelve true si todos los items tiene calidad ponderada.
     *
     * @Route("/{id}/calidad-ponderada", name="item_oferta_calidad_ponderada")
     * @Method("POST")
     */
    public function calidadPonderadaAction(Tramite $tramite)
    {   
        
       $em = $this->getDoctrine()->getManager();
        if ($tramite->getTipoTramite()->getSlug() == "tramite_proceso") {
            $itemSinPonderar = $em->getRepository('AusentismoBundle:ItemOferta')->findBy(array('proceso'=>$tramite, 'calidad'=> 0));
        }

        if ($tramite->getTipoTramite()->getSlug() == "tramite_oferta") {
           $itemSinPonderar = $em->getRepository('AusentismoBundle:ItemOferta')->findBy(array('oferta'=>$tramite, 'calidad'=>0));
        }

        $cantidad = count($itemSinPonderar);
        // Si la respuesta es true todos los item fueron ponderados 
        if ($cantidad <= 0) {
            return new JsonResponse(true);
        } else{
            return new JsonResponse(false);
        }
    }

    /**
     * Cambia de estado de TODOS los itemOferta de un PROCESO u OFERTA.
     *
     * @Route("/{id}/{calidad}/calidad/todo", name="item_oferta_calidad_todo")
     * @Method("GET")
     */
    public function calidadTodoAction(Request $request, Tramite $tramite, $calidad = 0)
    { 
        if ($tramite->getTipoTramite()->getSlug() == "tramite_proceso") {
            $pFPCalidadBueno =  $tramite->getPFCalidadBueno();
            $pFPCalidadMuyBueno =  $tramite->getPFCalidadMuyBueno();
            $items = $tramite->getItemOfertas();
            foreach ($items as $itemOferta) {
                $itemOferta->setAdjudicado(false);
                $itemOferta->setCantidadAdjudicada(0);
                $itemOferta->setCalidad($calidad);
                if ($calidad == 0 or $calidad == 1) {
                     $itemOferta->setPFCalidad(0);
                }
                if ($calidad == 2) {
                     $itemOferta->setPFCalidad($pFPCalidadBueno);
                }
                if ($calidad == 3) {
                     $itemOferta->setPFCalidad($pFPCalidadMuyBueno);
                }
            }
            $tramite->setAdjudicado(false);
        }

        if ($tramite->getTipoTramite()->getSlug() == "tramite_oferta") {
            $pFPCalidadBueno =  $tramite->getProceso()->getPFCalidadBueno();
            $pFPCalidadMuyBueno =  $tramite->getProceso()->getPFCalidadMuyBueno();
            $items = $tramite->getItemOferta();

            foreach ($items as $itemOferta) {
                $itemOferta->setAdjudicado(false);
                $itemOferta->setCantidadAdjudicada(0);
                $itemOferta->setCalidad($calidad);
                if ($calidad == 0 or $calidad == 1) {
                     $itemOferta->setPFCalidad(0);
                }
                if ($calidad == 2) {
                     $itemOferta->setPFCalidad($pFPCalidadBueno);
                }
                if ($calidad == 3) {
                     $itemOferta->setPFCalidad($pFPCalidadMuyBueno);
                }
            }

            $tramite->getProceso()->setAdjudicado(false);
        }
            $em = $this->getDoctrine()->getManager()->flush();

        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Cambia de estado de itemOferta entity.
     *
     * @Route("/{id}/{calidad}/calidad", name="item_oferta_calidad")
     * @Method("POST")
     */
    public function calidadAction(Request $request, ItemOferta $itemOferta, $calidad = 0)
    { 
        $msj = new JsonResponse("ninguno");

        if ($itemOferta->getCalidad() != $calidad) {
            $itemOferta->setAdjudicado(false);
            $itemOferta->setCantidadAdjudicada(0);
            $itemOferta->setCalidad($calidad);

            if ($calidad == 0 or $calidad == 1) {
                 $itemOferta->setPFCalidad(0);
            }
            if ($calidad == 2) {
                 $itemOferta->setPFCalidad( $itemOferta->getProceso()->getPFCalidadBueno());
            }
            if ($calidad == 3) {
                 $itemOferta->setPFCalidad( $itemOferta->getProceso()->getPFCalidadMuyBueno());
            }
            $em = $this->getDoctrine()->getManager();
            $proceso = $itemOferta->getProceso();
            $proceso->setAdjudicado(false);
            $em->flush();

            $itemSinPonderar = $em->getRepository('AusentismoBundle:ItemOferta')->findBy(array('proceso'=>$proceso, 'calidad'=>0));
            $cantidad = count($itemSinPonderar);
            // Si la respuesta es true todos los item fueron ponderados 
            if ($cantidad <= 0) {
                    $msj = new JsonResponse(true);
            } else{
                    $msj = new JsonResponse(false);
            }
        }

       return $msj;
    }

    /**
     * Pondera todos itemOferta de un Proceso.
     * slug puede ser igual a "proceso" u "oferta"
     * @Route("/{id}/proceso/ponderar", name="itemoferta_proceso_ponderar")
     * @Method("GET")
     */
    public function ponderarProcesoAction(Request $request, Tramite $tramite)
    {     

        $em = $this->getDoctrine()->getManager();

        $items = $em->getRepository('AusentismoBundle:ItemOferta')->findAllItem($tramite, $tramite->getTipoTramite()->getSlug());

            $i= 1;

            $pFPrecio =  $tramite->getPFPrecio();

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
        $msj= "Has realizado la ponderacion de los Items";
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Pondera todos itemOferta de un Proceso.
     * slug puede ser igual a "proceso" u "oferta"
     * @Route("/{id}/oferta/ponderar", name="itemoferta_oferta_ponderar")
     * @Method("GET")
     */
    public function ponderarOfertaAction(Request $request, Tramite $tramite)
    {     

        $pFPlazoEntrega= $tramite->getPFPlazoEntrega();
        $pFAntecedente= $tramite->getPFAntecedente();
        $pFCalidadBueno= $tramite->getPFCalidadBueno();
        $pFCalidadMuyBueno= $tramite->getPFCalidadMuyBueno();

        $em = $this->getDoctrine()->getManager();
     //   $tramite = $tramite->getProceso();
        $items = $em->getRepository('AusentismoBundle:ItemOferta')->findAllItem($tramite, $tramite->getTipoTramite()->getSlug());

        foreach ($items as $item) {
                $item->setPFPlazoEntrega($pFPlazoEntrega);
                $item->setPFAntecedente($pFAntecedente);
            }
             
        $em->flush();
        $msj= "Has realizado la ponderacion de los Items";
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * Elimina todos itemOferta de un Proceso.
     *
     * @Route("/{id}/oferta/delete-all", name="item_oferta_delete_all")
     * @Method("GET")
     */
    public function deleteAllAction(Request $request, Tramite $tramite)
    {     
        $em = $this->getDoctrine()->getManager();

        foreach ($tramite->getItemOferta() as $item) {
                $em->remove($item);
        }
         $em->flush();
        
        $msj= "Has eliminado los items";
        $this->get('session')->getFlashBag()->add(
                    'mensaje-info',
                    $msj);
        $referer= $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * observar a itemOferta entity.
     *
     * @Route("/{id}/observacion", name="item_oferta_observacion")
     * @Method("POST")
     */
    public function observacionAction(Request $request, ItemOferta $itemOferta)
    {
            $texto = $request->get('q', null);

            $em = $this->getDoctrine()->getManager();
            $itemOferta->setTexto($texto);
            $em->flush();

        return new JsonResponse(true);
    }
    
    /**
     * eliminar a itemOferta entity.
     *
     * @Route("/{id}/eliminar", name="item_oferta_eliminar")
     * @Method("POST")
     */
    public function eliminarAction(Request $request, ItemOferta $itemOferta)
    {

            $em = $this->getDoctrine()->getManager();
            $em->remove($itemOferta);
            $em->flush();

        return new Response("Has eliminado el Item");
    }

    /**
     * adjudicar a itemOferta entity.
     *
     * @Route("/{id}/adjudicar", name="item_oferta_adjudicar")
     * @Method("POST")
     */
    public function adjudicarAction(Request $request, ItemOferta $item)
    {

            $em = $this->getDoctrine()->getManager();

                $cantidadSolicitada = $item->getItemProceso()->getCantidad();
                $cantidadOfertada = $item->getCantidad();
                $adjudicado = !$item->getAdjudicado();
                $msj = "ok";
                if ($adjudicado) {
                    $cantidadFaltante = $cantidadSolicitada - $cantidadOfertada;
                    if ($cantidadFaltante  <= 0  ) {
                            $item->setCantidadAdjudicada($cantidadSolicitada);
                            $cantidadFaltante = 0;
                    } else {
                            $item->setCantidadAdjudicada($cantidadOfertada);
                            $msj = "error";
                    }

                } else {
                    $item->setCantidadAdjudicada(0);
                }

            $item->setAdjudicado($adjudicado);
            $em->flush();

        return new Response($msj);
    }
    /**
     * Deletes a itemOfertum entity.
     *
     * @Route("/{id}", name="itemoferta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ItemOferta $itemOfertum)
    {
        $form = $this->createDeleteForm($itemOfertum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($itemOfertum);
            $em->flush();
        }

        return $this->redirectToRoute('itemoferta_index');
    }

    /**
     * Creates a form to delete a itemOfertum entity.
     *
     * @param ItemOferta $itemOfertum The itemOfertum entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ItemOferta $itemOfertum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('itemoferta_delete', array('id' => $itemOfertum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}