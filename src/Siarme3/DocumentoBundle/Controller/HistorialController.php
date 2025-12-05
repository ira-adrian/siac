<?php

namespace Siarme\DocumentoBundle\Controller;

use Siarme\DocumentoBundle\Entity\Historial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Historial controller.
 *
 * @Route("historial")
 */
class HistorialController extends Controller
{
    /**
     * Lists all compartir entities.
     *
     * @Route("/{id}/eliminar", name="historial_eliminar")
     * @Method("POST")
     */
    public function deleteAction(Request $request, Historial $historial)
    {
         $em = $this->getDoctrine()->getManager();
         $em->remove($historial);
         $em->flush($historial); 
         return new Response("Has eliminado el Historial");
    } 

}
