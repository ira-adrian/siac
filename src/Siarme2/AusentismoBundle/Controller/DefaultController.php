<?php

namespace Siarme\AusentismoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/ausentismo")
     */
    public function indexAction()
    {
        return $this->render('AusentismoBundle:Default:index.html.twig');
    }
}
