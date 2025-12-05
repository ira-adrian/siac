<?php

/*
 * (c) Ibañez Raul Adrian <ira.adrian@gmail.com>
 *
 * Este archivo pertenece a la aplicación de prueba SIARMe.
 */

namespace Siarme\ExpedienteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Siarme\ExpedienteBundle\Entity\Clasificacion;
use Siarme\ExpedienteBundle\Entity\Expediente;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Siarme\ExpedienteBundle\Entity\TipoTramite;

/**
 * Fixtures de las entidades del Proyecto para
 * la Direccion de Reconocimientos Medicos.
 * Crea las entidades con las informacion de prueba muy realista.
 */
class Expte extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function getOrder()
    {
        return 50;
    }

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
    
        // Crear los tipos de Tramites que pueden realizarse por expedientes
        foreach (array('LICENCIA INJUSTIFICADA', 'LICENCIA PENDIENTE' ,'CAMBIO DE FUNCIONES - Art. 32° C.F. Dcto. 1875/94',
                    'RECTIFICAR O RATIFICAR DICTAMEN MEDICO - Art. 32° C.F. Dcto. 1875/94',
                    'RECONSIDERARACION DE - Art. 32° C.F. Dcto. 1875/94',
                    'INGRESO DE ACCIDENTE DE TRABAJO - Art. 28° Dcto. 1875/94', 
                    'RECHAZO DE ACCIDENTE DE TRABAJO - Art. 28° Dcto. 1875/94',
                    'REINTEGO DE GASTOS - Art. 28° A.T. Dcto. 1875/94',
                    'RECTIFICAR O RATIFICAR DICTAMEN MEDICO - Art. 28° A.T. Dcto. 1875/94',
                    'COMISION DE SERVICIO DOCENTE - RESOLUCION 36',
                    'EXCEPCION LARGO TRATAMIENTO - Art. 25° L.T. Dcto. 1875/94',
                    'DCTO. 201 ATENCIÓN PERSONA DISFUNCIONADA -  (ART 38 A.F. Dcto. 1875/94)',
                    'TOMA DE CONOCIMIENTO',
                    'CAMBIO DE Art. 23° C.T. POR 25° L.T. - Dcto. 1875/84',
                ) as $t) {
            $tipoTramite = new TipoTramite();
            $tipoTramite->setTipoTramite($t);
           
            $manager->persist($tipoTramite);
        }

        $manager->flush();

       // Crear las Clasificaciones de los Expedientes
        foreach (array('SALUD','VARIOS','EDUCACION','POLICIA', 'MUNICIPALIDAD') as $c) {
            $clasificacion = new Clasificacion();
            $clasificacion->setClasificacion($c);
           
            $manager->persist($clasificacion);
        }

        $manager->flush();


         // Crear 50 Expedientes de Prueba 

            $agentes = $manager->getRepository('AusentismoBundle:Agente')->findAll();
            $clasificaciones = $manager->getRepository('ExpedienteBundle:Clasificacion')->findAll();
            $departamentosRm = $manager->getRepository('AusentismoBundle:DepartamentoRm')->findAll();
        for ($i=1; $i <= 50; $i++) { 
            $d = $departamentosRm[array_rand($departamentosRm)];
            $a= $agentes[array_rand($agentes)];
            $c= $clasificaciones[array_rand($clasificaciones)];
            
            $expediente = New Expediente();
            $expediente->setLetra($this->getLetra());
            $expediente->setNumero(rand(1,5000));
            $expediente->setAnio(rand(2012,date('Y')));
            $expediente->setExtracto($this->getExtracto());
            $expediente -> setDepartamentoRm($d);
           // $expediente->setObservacion(
             //           "Lorem ipsum dolor sit amet, consectetur adipisicing elit,"
               //         ."sed do eiusmod tempor incididunt ut labore et dolore magna"
                 //       ."aliqua. Ut enim ad minim veniam, quis nostrud exercitation."
                 //   );
         //   $expediente->setDepartamentoRm($d);
            $expediente->setAgente($a);
            $expediente->setClasificacion($c);

        //Estado true-Iniciado / false-Concluido
            $expediente->setEstado(rand(0,1));
            $manager->persist($expediente);
        }
        $manager->flush();

        
        // Crear 3 Tramites de Prueba para cada Expediente  

        $tipoTramites = $manager->getRepository('ExpedienteBundle:TipoTramite')->findAll();
        $expedientes = $manager->getRepository('ExpedienteBundle:Expediente')->findAll();
        $organismos = $manager->getRepository('AusentismoBundle:Organismo')->findAll();
        
        foreach ($expedientes as $e) {            

                $d = $departamentosRm[array_rand($departamentosRm)];
                $o = $organismos[array_rand($organismos)];
                //$e = $expedientes[array_rand($expedientes)];
                $t = $tipoTramites[array_rand($tipoTramites)];
                $random= rand(0,4);
                $estado=1;
            for ($i=0; $i < $random; $i++) { 
                $tramite = New Tramite();
                $tramite -> setOrganismoOrigen($o);
                $o = $organismos[array_rand($organismos)];
                $tramite -> setOrganismoDestino($o);
                $tramite -> setTipoTramite($t);
                $tramite -> setFechaOrigen(new \DateTime('now - '.rand(20, 1800).' days'));
                $tramite -> setFechaDestino(new \DateTime('now - '.rand(1, 365).' days'));
                // estados 1-Transito ,0-Concluido
                $c = $i+1;
                if (($c == $random) AND ($e->getEstado() == true )) {
                $tramite -> setEstado(true);
                }  else {
                $tramite -> setEstado(false);
                }                
                $tramite -> setDepartamentoRm($d);
                $tramite -> setExpediente($e);
                $manager -> persist($tramite);             
                
            }
        }
        $manager -> flush();
 }  



   /**
     * Generador aleatorio de Letras de Expedientes.
     *
     * @return string Letra
     */

    private function getLetra()
    {
        $palabras = array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
            'J', 'L', 'M', 'N', 'O', 'P','Q', 'R', 'S', 'T', 'U', 'V',
            'AG', 'ACT');

        return $palabras[array_rand($palabras)];
    }




    /**
     * Generador aleatorio de Extractos de Expedientes.
     *
     * @return string Extracto
     */

    private function getExtracto()
    {
        $palabras = array_flip(array(
            'Lorem', 'Ipsum', 'Sitamet', 'Et', 'At', 'Sed', 'Aut', 'Vel', 'Ut',
            'Dum', 'Tincidunt', 'Facilisis', 'Nulla', 'Scelerisque', 'Blandit',
            'Ligula', 'Eget', 'Drerit', 'Malesuada', 'Enimsit', 'Libero',
            'Penatibus', 'Imperdiet', 'Pendisse', 'Vulputae', 'Natoque',
            'Aliquam', 'Dapibus', 'Lacinia'
        ));

        $numeroPalabras = rand(4, 8);

        return implode(' ', array_rand($palabras, $numeroPalabras));
    }

}