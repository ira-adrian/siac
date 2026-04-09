<?php

namespace Siarme\AusentismoBundle\Controller;

use Siarme\AusentismoBundle\Entity\GrupoRubro;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Rubro controller.
 *
 * @Route("grupo_rubro")
 */
class GrupoRubroController extends Controller
{
  
    /**
     * Finds and displays a rubro entity.
     *
     * @Route("/{id}/show/{anio}", name="grupo_rubro_show")
     * @Method("GET")
     */
    public function showAction(GrupoRubro $grupoRubro, $anio = null)
    {
     
         if (empty($anio)) {
            $date = new \Datetime();
            $anio = $date->format("Y");
        }
        return $this->render('AusentismoBundle:GrupoRubro:show.html.twig', array(
            'grupoRubro' => $grupoRubro,
            'anio' => $anio
        ));
    }

}
