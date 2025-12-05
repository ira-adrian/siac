<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\Licencia;
use Siarme\AusentismoBundle\Entity\Agente;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Siarme\DocumentoBundle\Entity\Historial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Licencium controller.
 *
 * @Route("licencia")
 */
class LicenciaController extends Controller
{
    /**
     * Lists all licencium entities.
     *
     * @Route("/{anio}", name="licencia_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, $anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }
   $cons = trim($request->get('consulta'));     
   
   /** AQUI QUEDE ESTE CODIGO ESTA BIEN  */
        $repository = $this->getDoctrine()
            ->getRepository('AusentismoBundle:Agente');
         
        $query = $repository->createQueryBuilder('a');                          
  /**          if (!empty($cons)) {     
             $query->andWhere('a.apellidoNombre like :keyword or a.dni like :keyword')
                  ->setParameter('keyword', '%'.$cons.'%');
             } else {
                $query->join('a.licencia' , 'l')
                      ->join('l.documento' , 'd');
                }
*/
        $query->orderBy('a.apellidoNombre', 'ASC')
              ->getQuery();                                   

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            50 /*limit per page*/
                        );
  
      if  ($pagination->getTotalItemCount() == 0){
          $this->get('session')->getFlashBag()->add(
          'mensaje-info',
          '<strong> Ops... </strong> No se ha encontrado ningun Agente con Licencia:'.$cons
          ); 
       }
        //$em = $this->getDoctrine()->getManager();

       // $licencias = $em->getRepository('AusentismoBundle:Licencia')->findAll();

        return $this->render('AusentismoBundle:Licencia:index.html.twig', array(
            'pagination' => $pagination, 'licencias'=>true, 'anio' => $anio,
        ));
    }


    /**
     * Finds and displays a licencium entity.
     *
     * @Route("/lista/{anio}", name="lista_show")
     * @Method("POST")
     */
    public function listaAction(Request $request, $anio = null)
    {
                if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }

        $fechaDesde = $anio."/01/01";
        $fechaDesde = new \DateTime($fechaDesde);
        $fechaDesde = $fechaDesde->format('Y/m/d');

        $fechaHasta = $anio."/12/31";
        $fechaHasta = new \DateTime($fechaHasta);
        $fechaHasta = $fechaHasta->format('Y/m/d');

        $cons = trim($request->get('agenteId'));


        $repository = $this->getDoctrine()
            ->getRepository('AusentismoBundle:Licencia');
         
        $query = $repository->createQueryBuilder('l');                          
            if (!empty($cons))     
              $query->andWhere('l.agente = :agenteId')
                    ->andWhere('l.fechaDesde >= :fechaDesde')
                    ->andWhere('l.fechaHasta <= :fechaHasta')
                    ->orderBy('l.fechaDesde', 'DESC')
                    
                    ->setParameter('agenteId', $cons)           
                    ->setParameter('fechaDesde', $fechaDesde)           
                    ->setParameter('fechaHasta', $fechaHasta)           
                    ->getQuery();    

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20 /*limit per page*/
        );

        return $this->render('AusentismoBundle:Licencia:lista.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * @Route("/{id}/periodo", name="ajax_licencia_search_periodo")
     * @Method({"GET", "POST"})
     * @ParamConverter("agente", class="AusentismoBundle:Agente")
     */

    public function ajaxLicenciaSearchPeriodoAction(Request $request, Agente $agente) {

        
          //$searchDate = $request->get('fechaDesde', null);

            $fechaDesde = (new \DateTime($request->get('fechaDesde')))->format('Y-m-d');
             $fechaHasta = (new \DateTime($request->get('fechaHasta')))->format('Y-m-d');
           //$searchDate = new \ DateTime('now');
           // dump($fechaHasta);
          // exit();
            $em = $this->getDoctrine()->getManager();
            $licencias = $em->getRepository('AusentismoBundle:Licencia')->findByAgentePeriodo($agente->getId(),   $fechaDesde, $fechaHasta );
           // dump($licencias);
          // exit();
/*
             $agentes = $consulta->getResult();*/


            if (!empty($licencias)) { 

            $results = array();
            foreach ($licencias as $licencia) {
                    $results[] = array('id' => $licencia->getId(), 'text'=>$licencia->getArticulo()->getDescripcion());
                }

            } else {
                       $results[] = array('id' => 0, 'text'=>"Sin Resultados");
            }
    
           return new JsonResponse($results);
    }

/**
     * @Route("/{id}/fecha", name="ajax_licencia_search_fecha")
     * @Method({"GET", "POST"})
     * @ParamConverter("agente", class="AusentismoBundle:Agente")
     */

    public function ajaxLicenciaSearchFechaAction(Request $request, Agente $agente) {

        
          //$searchDate = $request->get('fechaDesde', null);

            $searchDate = (new \DateTime($request->get('fecha')))->format('Y-m-d');
           //$searchDate = new \ DateTime('now');
           // dump($searchDate);
          // exit();
            $em = $this->getDoctrine()->getManager();
            $licencias = $em->getRepository('AusentismoBundle:Licencia')->findByAgenteFecha($agente->getId(),  $searchDate);
           // dump($licencias);
          // exit();
/*
             $agentes = $consulta->getResult();*/


            if (!empty($licencias)) { 

            $results = array();
            foreach ($licencias as $licencia) {
                    $results[] = array('id' => $licencia->getId(), 'text'=>$licencia->getArticulo()->getDescripcion());
                }

            } else {
                       $results[] = array('id' => 0, 'text'=>"Sin Resultados");
            }
    
           return new JsonResponse($results);
    }

  /**
     * @Route("/buscar/{id}/periodo/{fecha_desde}/{fecha_hasta}", name="licencia_search_periodo")
     * @Method({"GET"})
     * @ParamConverter("agente", class="AusentismoBundle:Agente")
     */

    public function licenciaSearchPeriodoAction(Agente $agente, $fecha_desde = null, $fecha_hasta = null ) {

            $fechaDesde = (new \DateTime( $fecha_desde))->format('Y-m-d');
             $fechaHasta = (new \DateTime($fecha_hasta))->format('Y-m-d');

            $em = $this->getDoctrine()->getManager();
            $licencias = $em->getRepository('AusentismoBundle:Licencia')->findByAgentePeriodo($agente->getId(),   $fechaDesde, $fechaHasta );

         return $this->render('AusentismoBundle:Licencia:registro_lista.html.twig', array(
            'pagination' => $licencias,
        ));
    }

  /**
     * @Route("/modal/{id}/new", name="licencia_modal_new")
     * @Method({"GET"})
     * @ParamConverter("agente", class="AusentismoBundle:Agente")
     */

    public function licenciaModalNewAction(Agente $agente) {

         $em = $this->getDoctrine()->getManager();
         $articulos = $em->getRepository('AusentismoBundle:Articulo')->findAll();


         return $this->render('AusentismoBundle:Licencia:licencia_modal_new.html.twig', array(
            'articulos' => $articulos,
            'agente' => $agente,
        ));
    }

  /**
     * @Route("/buscar/{id}/fecha/{fecha}", name="licencia_search_fecha")
     * @Method({"GET"})
     * @ParamConverter("agente", class="AusentismoBundle:Agente")
     */

    public function licenciaSearchFechaAction(Agente $agente, $fecha = null) {

        
           // $searchDate = $request->get('date', null);

           // $searchDate = new \DateTime($request->get('fechaDesde'));
            $searchDate =  (new \DateTime($fecha))->format('Y-m-d');
           //  dump($searchDate);
         // exit();
            $em = $this->getDoctrine()->getManager();
            $licencias = $em->getRepository('AusentismoBundle:Licencia')->findByAgenteFecha($agente->getId(),  $searchDate);
           // dump($licencias);
          // exit();
/*
             $agentes = $consulta->getResult();
            if (!empty($agentes)) { 

            $results = array();
                foreach ($agentes as $agente) {
                    $results[] = array('id' => $agente->getId(), 'text'=>$agente->getApellidoNombre()." DNI: ".$agente->getDni());
                }
            } else {
                       $results[] = array('id' => 0, 'text'=>"Sin Resultados");
            }
        */
           // return new JsonResponse($results);
         return $this->render('AusentismoBundle:Licencia:registro_lista.html.twig', array(
            'pagination' => $licencias,
        ));
    }

     /**
     * @Route("listar/{id}", name="licencia_listar")
     * @Method({"GET"})
     *
     */
    public function listarLicenciaAction($id) {

            $em = $this->getDoctrine()->getManager();
            $licencias =$em->getRepository('AusentismoBundle:Licencia')->findBy(array('id' =>$id));

         return $this->render('AusentismoBundle:Licencia:registro_lista.html.twig', array(
            'pagination' => $licencias,
        ));
    }

    /**
     * Finds and displays a licencia entity.
     *
     * @Route("/{id}", name="licencia_show")
     * @Method("GET")
     */
    public function showAction(Licencia $licencia)
    {
        $deleteForm = $this->createDeleteForm($licencia);

        return $this->render('AusentismoBundle:Licencia:show.html.twig', array(
            'licencia' => $licencia,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing licencium entity.
     *
     * @Route("/{id}/edit", name="licencia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Licencia $licencium)
    {
        $deleteForm = $this->createDeleteForm($licencium);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\LicenciaType', $licencium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

              $this->get('session')->getFlashBag()->add(
              'mensaje-info',
              'Se ha guardado correctamente la licencia <strong>'. $licencium. ' </strong>'
              ); 
              
            return $this->redirectToRoute('licencia_show', array('id' => $licencium->getId()));
        }

        return $this->render('AusentismoBundle:Licencia:edit.html.twig', array(
            'licencia' => $licencium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a licencium entity.
     *
     * @Route("/{id}", name="licencia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Licencia $licencium)
    {
        $form = $this->createDeleteForm($licencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($licencium);
            $em->flush();
        }

        return $this->redirectToRoute('licencia_index');
    }

    /**
     * Creates a form to delete a licencium entity.
     *
     * @param Licencia $licencium The licencium entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Licencia $licencium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('licencia_delete', array('id' => $licencium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     *
     * @Route("/{id}/{estado}/gde", name="licencia_gde")
     * 
     */
    public function gdeAction(Licencia $licencia, $estado = false)
    {
      $em= $this->getDoctrine()->getManager();
       
       $licencia->setEstado($estado);

       $em->flush($licencia);
      return $this->redirectToRoute('licencia_show', array('id' => $licencia->getId()
        ));
    }

     public function historial($expediente, $accion, $msj = null)
    {     
            $em = $this->getDoctrine()->getManager();
            $historial = new Historial();
            $historial->setTipoId($expediente->getId());
            /** El tipo puede ser ['EXP','DOC','AG'] corresponde con las entidades Expediente, Documento, Agente */
            $historial->setTipo('EXP');
            $historial->setUsuario($this->getUser());
            $historial->setAccion($accion);
            $historial->setTexto($msj);
            $em->persist($historial);
            $em->flush();
    }
}
