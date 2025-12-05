<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\Agente;
use Siarme\AusentismoBundle\Entity\Cargo;
use Siarme\DocumentoBundle\Entity\Historial;
use Siarme\ExpedienteBundle\Form\Type\SelecAgenteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Agente controller.
 *
 * @Route("agente")
 */
class AgenteController extends Controller
{
    const TIPO_ENTIDAD = 'AG';
   
    /**
     * Lists all agente entities.
     *
     * @Route("/", name="agente_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
         
        $repository = $this->getDoctrine()
            ->getRepository('AusentismoBundle:Agente');
         
        $query = $repository->createQueryBuilder('a');
    
        $query  ->join('a.usuario', 'u')
                ->andWhere('u.departamentoRm = :dpto')
                ->setParameter('dpto', $this->getUser()->getDepartamentoRm()->getId());

        $query->getQuery();                       

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20 /*limit per page*/,array('wrap-queries'=>true)
                        );
        //si es false devuelve aquellos que no estan con expedientes
        $tareas = $this->getDoctrine()->getRepository('ExpedienteBundle:Tarea')->findByTramiteUsuario($this->getUser());
        // parameters to template
        return $this->render('AusentismoBundle:Agente:index.html.twig', array('pagination' => $pagination, 'agentes'=>true, 'tareas'=>$tareas,));
    }

    /**
     * @Route("/search", name="agente_search")
     * @Method({"GET", "POST"})
     */

    public function ajaxSearchAction(Request $request) {
            $searchString = $request->get('q', null);
            $em = $this->getDoctrine()->getManager();
            $consulta = $em->createQuery(
            'SELECT a 
             FROM AusentismoBundle:Agente a
             WHERE a.apellidoNombre LIKE :searchString
             OR a.dni LIKE :searchString
             ORDER BY a.apellidoNombre ASC
              ')
             ->setParameter('searchString', '%' . $searchString . '%');

             $agentes = $consulta->getResult();
            if (!empty($agentes)) { 

            $results = array();
                foreach ($agentes as $agente) {
                    $results[] = array('id' => $agente->getId(), 'text'=>$agente->getApellidoNombre()." DNI: ".$agente->getDni());
                }
            } else {
                       $results[] = array('id' => 0, 'text'=>"Sin Resultados");
            }
        
            return new JsonResponse($results);
    }


    /**
     * Creates a new agente entity.
     *
     * @Route("/new", name="agente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $agente = new Agente();
        $cargo = new Cargo();
        $agente->setObraSocial("OSEP Afiliado N°: ");
        $agente->setTelefonoMovil("3834");

        $cargo->setAgente($agente);
        $agente->addCargo($cargo);
        $form = $this->createForm('Siarme\AusentismoBundle\Form\AgenteType', $agente);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $agente->setEscalafon($cargo->getOrganismo()->getClasificacion());
            
            $agente->setApellidoNombre($this->limpiar($agente->getApellidoNombre()));
            $agente->setTelefonoMovil($this->limpiarTelefono($agente->getTelefonoMovil()));
            $agente->setTelefonoFijo($this->limpiarTelefono($agente->getTelefonoFijo()));
            $em->persist($agente);
            $em->persist($cargo);

            $em->flush();
            $msj = 'Has creado el agente '.$agente;
           /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/

            $this->historial($agente,'CREADO', $msj);

            return $this->redirectToRoute('agente_show', array('id' => $agente->getId()));
        }


        return $this->render('AusentismoBundle:Agente:new.html.twig', array(
            'agente' => $agente,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a agente entity.
     *
     * @Route("/{id}/{anio}", name="agente_show")
     * @Method("GET")
     */
    public function showAction(Agente $agente, $anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }

        $deleteForm = $this->createDeleteForm($agente);
        $em = $this->getDoctrine()->getManager();
        $tipoTramites = $em->getRepository('ExpedienteBundle:TipoTramite')->findByAgente($agente, $anio);
        return $this->render('AusentismoBundle:Agente:show.html.twig', array(
            'agente' => $agente,          
            'anio' => $anio,          
            'tipoTramites' => $tipoTramites,          
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing agente entity.
     *
     * @Route("/{id}/edit/modificar", name="agente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Agente $agente)
    {
        if ( empty($agente->getObraSocial())) $agente->setObraSocial("OSEP Afiliado N°: ");  
        if (empty($agente->getTelefonoFijo()))  $agente->setTelefonoFijo("3834");    
        if (empty($agente->getTelefonoMovil()) ) $agente->setTelefonoMovil("3834");   
        if ($agente->getCargo()->isEmpty()){ 
            $cargo = new Cargo();
            $cargo->setAgente($agente);
            $agente->addCargo($cargo);
        }
        $deleteForm = $this->createDeleteForm($agente);
        $editForm = $this->createForm('Siarme\AusentismoBundle\Form\AgenteType', $agente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $agente->setFechaModifica( new \ DateTime('now'));
        if (isset($cargo)){ 
            $agente->setEscalafon($cargo->getOrganismo()->getClasificacion());

            $em->persist($cargo);
        }
            $agente->setApellidoNombre($this->limpiar($agente->getApellidoNombre()));
            $agente->setApellidoNombre($this->limpiar($agente->getApellidoNombre()));
            $agente->setTelefonoMovil($this->limpiarTelefono($agente->getTelefonoMovil()));
            $agente->setTelefonoFijo($this->limpiarTelefono($agente->getTelefonoFijo()));
            $em->flush();

            $msj= 'Has modificado los datos del '.$agente;
          $this->get('session')->getFlashBag()->add(
          'mensaje-info',$msj ); 
                   /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'VISTO' ]*/
          $this->historial($agente,'MODIFICADO', $msj);
            return $this->redirectToRoute('agente_show', array('id' => $agente->getId()));
        }

        return $this->render('AusentismoBundle:Agente:edit.html.twig', array(
            'agente' => $agente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a agente entity.
     *
     * @Route("/{id}", name="agente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Agente $agente)
    {
        $form = $this->createDeleteForm($agente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $msj= 'Has eliminado definitivamente el agente '.$agente;
            $this->get('session')->getFlashBag()->add(
          'mensaje-info',$msj ); 
         /** Las acciones pueden ser ['MODIFICADO','CREADO','ELIMINADO', 'BORRADO', 'VISTO' ]*/
          $this->historial($agente,'ELIMINADO', $msj);

            $em->remove($agente);
            $em->flush();



        }

        return $this->redirectToRoute('agente_index');
    }

    /**
     * Creates a form to delete a agente entity.
     *
     * @param Agente $agente The agente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Agente $agente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agente_delete', array('id' => $agente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


 public function historial($agente, $accion, $msj = null)
    {     

            $em = $this->getDoctrine()->getManager();
            $historial = new Historial();
            $historial->setTipoId($agente->getId());
            /** El tipo puede ser ['EXP','DOC','AG'] corresponde con las entidades Expediente, Documento, Agente */
            $historial->setTipo('AG');
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
        $s= str_replace('"', '', $s); 
        $s= str_replace(':', '', $s); 
        $s= str_replace('.', '', $s); 
        $s= str_replace(',', '', $s); 
        $s= str_replace(';', '', $s); 
        $s= str_replace('#', 'Ñ', $s); 
        return $s; 
    }
    
public function limpiarTelefono($s) 
    { 
        //Quitando Caracteres Especiales 
        $s= str_replace('-', '', $s); 
        $s= str_replace('(', '', $s); 
        $s= str_replace(')', '', $s); 
        return $s; 
    }
}
