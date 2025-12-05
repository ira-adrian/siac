<?php

namespace Siarme\ExpedienteBundle\Controller;

use Siarme\ExpedienteBundle\Entity\Credito;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Bandeja controller.
 *
 * @Route("externo")
 */
class ExternoController extends Controller
{
    /**
     * Lists all bandeja entities.
     *
     * @Route("/", name="externo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tramites = $em->getRepository('ExpedienteBundle:Tramite')->findByCredito(false);

        return $this->render('ExpedienteBundle:Externo:index.html.twig', array(
            'tramites' => $tramites,
        ));
    }
    
    /**
     * Lista todos los items adjudicados .
     *
     * @Route("/items-adjudicados", name="externo_items_adjudicados")
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
     * Finds and displays a tramite entity.
     *
     * @Route("/{id}", name="credito_tramite_show")
     * @Method("GET")
     */
    public function showAction(Tramite $tramite)
    {
        return $this->render('ExpedienteBundle:Externo:show.html.twig', array(
            'tramite' => $tramite,
        ));
    }
    
    /**
     * Lists all bandeja entities.
     *
     * @Route("/saf/{anio}", name="externo_saf_index")
     * @Method("GET")
     */
    public function safIndexAction($anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }

        return $this->render('ExpedienteBundle:ExternoSaf:index.html.twig', array('anio'=>$anio,));
    }

    /**
     * Lists all bandeja entities.
     *
     * @Route("/ministerio/{anio}", name="externo_ministerio_index")
     * @Method("GET")
     */
    public function ministerioIndexAction($anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }

        return $this->render('ExpedienteBundle:ExternoMinisterio:index.html.twig', array('anio'=>$anio,));
    }

    /**
     * Lists all bandeja entities.
     *
     * @Route("/reporte/saf/{anio}", name="externo_reporte")
     * @Method("GET")
     */
    public function reporteAction($anio = null)
    {
        if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }

        return $this->render('ExpedienteBundle:ExternoSaf:reporte.html.twig', array('anio'=>$anio,));
    }
}
