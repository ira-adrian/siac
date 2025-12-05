<?php

/*
 * (c) Ibañez Raul Adrian <ira.adrian@gmail.com>
 *
 * Este archivo pertenece a la aplicación de prueba SIARMe.
 */

namespace Siarme\AusentismoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Siarme\AusentismoBundle\Entity\DepartamentoRm;
use Siarme\AusentismoBundle\Entity\Agente;
use Siarme\AusentismoBundle\Entity\Articulo;
use Siarme\AusentismoBundle\Entity\Cargo;
use Siarme\AusentismoBundle\Entity\Departamento;
use Siarme\AusentismoBundle\Entity\Localidad;
use Siarme\AusentismoBundle\Entity\Organismo;
use Siarme\AusentismoBundle\Entity\Enfermedad;
/**
 * Fixtures de las entidades del Proyecto para
 * la Direccion de Reconocimientos Medicos.
 * Crea las entidades con las informacion de prueba muy realista.
 */
class Ausentismo extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function getOrder()
    {
        return 30;
    }

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {  

        $articulo = $manager->getRepository('AusentismoBundle:Articulo')->find(10);
        $articulo->setCodigoArticulo('5550');         
        $manager->flush();

    }

}