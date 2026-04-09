<?php

/*
 * (c) Ibañez Raul Adrian <ira.adrian@gmail.com>
 *
 * Este archivo pertenece a la aplicación de prueba SIARMe.
 */

namespace Siarme\DocumentoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Siarme\DocumentoBundle\Entity\TipoDocumento;
use Siarme\DocumentoBundle\Entity\Documento;
use Siarme\AusentismoBundle\Entity\Licencia;
use Siarme\AusentismoBundle\Entity\Agente;
use Siarme\AusentismoBundle\Entity\Enfermedad;
use Siarme\AusentismoBundle\Entity\Organismo;
use Siarme\UsuarioBundle\Entity\Usuario;
use Siarme\ExpedienteBundle\Entity\Tramite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * Fixtures de las entidades del Proyecto para
 * la Direccion de Reconocimientos Medicos.
 * Crea las entidades con las informacion de prueba muy realista.
 */
class Documentos extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function getOrder()
    {
        return 60;
    }

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {



                $tipodocumentos = $manager->getRepository('DocumentoBundle:TipoDocumento')->findAll();

                foreach($tipodocumentos as $tipo){
                        $slug = array('licencia', 'expediente', 'in','int','lic','snippet');
                        if ( in_array($tipo->getSlug(), $slug)) {
                            $tipo->setVisible(false);
                        } else {
                             $tipo->setVisible(true);
                        }
                }           

                 $manager->flush();







/*****para borrar

        #Una vez creados los documentos ir a phpmyadmin a la tabla y ver los slug 
      #aniadir en parameters los documentos turno 
           $docsMedico= array('acta-medica'=>'Acta de Junta Medica','informe-medico'=>'Informe Medico');
           $docsAdmin = array('proveido'=>'Proveido','turno'=>'Constancia de Turno','citacion'=>'Citacion', 'constancia'=>'Constancia');
                   
         //$tipo_docs =  $this->container->getParameter('tiposdocs');

          $rolAdmin = $manager->getRepository('UsuarioBundle:Role')->findOneByRoleName("Administrativo"); 

              foreach($docsAdmin as $slug=>$nombre)
            {
                $tipodocumento = new TipoDocumento();
                $tipodocumento->setSlug($slug);
                $tipodocumento->setNombreDocumento($nombre);
                $tipodocumento->setNumero(0);
                $fecha= new \DateTime('now');
                $tipodocumento->setAnio($fecha->format('Y'));
                $tipodocumento->setRol($rolAdmin);
                $manager->persist($tipodocumento); 
            }
             $manager->flush(); 

            $rolMedico = $manager->getRepository('UsuarioBundle:Role')->findOneByRoleName('Medico'); 
            foreach($docsMedico as $slug=>$nombre)
            {
                $tipodocumento = new TipoDocumento();
                $tipodocumento->setSlug($slug);
                $tipodocumento->setNombreDocumento($nombre);
                $tipodocumento->setNumero(0);
                $fecha= new \DateTime('now');
                $tipodocumento->setAnio($fecha->format('Y'));
                $tipodocumento->setRol($rolMedico);
                $manager->persist($tipodocumento);  
            }
             $manager->flush();

          // Crear entre 0 y 3 Documentos de Prueba Para cada Usuario 
           //Recupero los usuarios con rol "administrativo"->ROLE_ADMINISTRATIVO
           $usuarios= $manager->getRepository('UsuarioBundle:Usuario')->finByNombreRol("Administrativo");
           $organismos = $manager->getRepository('AusentismoBundle:Organismo')->findAll();       
           $tramites = $manager->getRepository('ExpedienteBundle:Tramite')->findAll();
           foreach ($tramites as $tramite) {   
                
                for ($i=1; $i <= rand(0,3); $i++) { 
                $slug = array_rand( $docsAdmin, 1 );

                $tipodocumento = $manager->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($slug);
                $organismo = $organismos[array_rand($organismos)];
                $usuario = $usuarios[array_rand($usuarios)];
                
                $numero= $tipodocumento->getNumero() + 1;
                $tipodocumento->setNumero($numero);
                $manager->flush();

                $docAdministrativo = New Documento();
                $fechaDoc =new \DateTime('now - '.rand(1, 365).' days');
                $docAdministrativo->setFechaDocumento($fechaDoc);
                
                $docAdministrativo->setTexto($this->getTexto());
                $docAdministrativo->setUsuario($usuario);                
                $docAdministrativo->setTramite($tramite);
                $docAdministrativo->setTipoDocumento($tipodocumento);
                $docAdministrativo->setNumero($numero);
                $docAdministrativo->setAnio((int)$fechaDoc ->format('Y'));
                $docAdministrativo->setNombreDocumento($tipodocumento->getNombreDocumento());                
                $docAdministrativo->setSlug($slug);

                $manager->persist($docAdministrativo);  
                }
                  $manager->flush();              


             
         // Crear entre 0 y 3 Documentos de Prueba Para cada Usuario 

            $usuarios= $manager->getRepository('UsuarioBundle:Usuario')->finByNombreRol("Medico");
            for ($i=1; $i <= rand(0,3); $i++) { 
                $slug = array_rand( $docsMedico, 1 );
                $tipodocumento = $manager->getRepository('DocumentoBundle:TipoDocumento')->findOneBySlug($slug);
                $organismo = $organismos[array_rand($organismos)];
                $usuario = $usuarios[array_rand($usuarios)];

                $numero= $tipodocumento->getNumero() + 1;
                $tipodocumento->setNumero($numero);
                $manager->flush();

                $docMedico = New Documento();
                $fechaDoc = new \DateTime('now - '.rand(1, 365).' days');
                $docMedico->setFechaDocumento($fechaDoc);

                $docMedico->setTexto($this->getDictamen());
                $docMedico->setUsuario($usuario);
                $docMedico->setTramite($tramite);
                $docMedico->setTipoDocumento($tipodocumento);
                $docMedico->setNombreDocumento($tipodocumento->getNombreDocumento());
                $docMedico->setNumero($numero);
                $docMedico->setAnio((int)$fechaDoc->format('Y'));                
                $docMedico->setSlug($slug);

                $manager->persist($docMedico);
            }
                $manager->flush();
        }

    // Para cada Documento Medico crear  una Licencia

       
       $articulos = $manager->getRepository('AusentismoBundle:Articulo')->findAll();
       $enfermedades = $manager->getRepository('AusentismoBundle:Enfermedad')->findAll();

       foreach ($docsMedico  as $slug=>$nombre){
          $docMedico = $manager->getRepository('DocumentoBundle:Documento')->findBySlug($slug);
    
           foreach ($docMedico as $documento) {

           $expediente = $documento->getTramite()->getExpediente();
           $agente =  $expediente->getAgente();

           $licencia = New Licencia();
           $licencia ->setEnfermedad($enfermedades[array_rand($enfermedades)]);
           $licencia ->setArticulo($articulos[array_rand($articulos)]);

           $licencia ->setAgente($agente);
           $licencia ->setDocumento($documento);

           $fecha = new \DateTime('now - '.rand(0, 180).' days');
           $fechaHasta = new \DateTime('now + '.rand(1, 180).' days');
           $licencia ->setFechaDocumento(new \DateTime('now'));
           $licencia ->setFechaDesde($fecha);
           $licencia ->setFechaHasta($fechaHasta);
           $licencia ->setDiagnostico($enfermedades[array_rand($enfermedades)]->getEnfermedad());
           $manager->persist($licencia);
         
            }
         $manager->flush();
       }
    }


      #--------------------------------------------------------------------------

  
    /**
     * Generador aleatorio de textos de Documentos  
     *
     * @return  string Texto aleatorio generado para los Documentos .
     */
    private function getTexto()
    {

        $frases = array_flip(array(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Mauris ultricies nunc nec sapien tincidunt facilisis.',
            'Nulla scelerisque blandit ligula eget hendrerit.',
            'Sed malesuada, enim sit amet ultricies semper, elit leo lacinia massa, in tempus nisl ipsum quis libero.',
            'Aliquam molestie neque non augue molestie bibendum.',
            'Pellentesque ultricies erat ac lorem pharetra vulputate.',
            'Donec dapibus blandit odio, in auctor turpis commodo ut.',
            'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
            'Nam rhoncus lorem sed libero hendrerit accumsan.',
            'Maecenas non erat eu justo rutrum condimentum.',
            'Suspendisse leo tortor, tempus in lacinia sit amet, varius eu urna.',
            'Phasellus eu leo tellus, et accumsan libero.',
            'Pellentesque fringilla ipsum nec justo tempus elementum.',
            'Aliquam dapibus metus aliquam ante lacinia blandit.',
            'Donec ornare lacus vitae dolor imperdiet vitae ultricies nibh congue.',
        ));

        $numeroFrases = rand(2, 10);

        return implode(' ', array_rand($frases, $numeroFrases));
    }
        




     /**
     * Generador aleatorio de Dictamenes de Documentos Medicos
     *
     * @return  string Dictamen aleatorio generado para los Documentos Medicos.
     */
    private function getDictamen()
    {

        $frases = array_flip(array(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Mauris ultricies nunc nec sapien tincidunt facilisis.',
            'Nulla scelerisque blandit ligula eget hendrerit.',
            'Sed malesuada, enim sit amet ultricies semper, elit leo lacinia massa, in tempus nisl ipsum quis libero.',
            'Aliquam molestie neque non augue molestie bibendum.',
            'Pellentesque ultricies erat ac lorem pharetra vulputate.',
            'Donec dapibus blandit odio, in auctor turpis commodo ut.',
            'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
            'Nam rhoncus lorem sed libero hendrerit accumsan.',
            'Maecenas non erat eu justo rutrum condimentum.',
            'Suspendisse leo tortor, tempus in lacinia sit amet, varius eu urna.',
            'Phasellus eu leo tellus, et accumsan libero.',
            'Pellentesque fringilla ipsum nec justo tempus elementum.',
            'Aliquam dapibus metus aliquam ante lacinia blandit.',
            'Donec ornare lacus vitae dolor imperdiet vitae ultricies nibh congue.',
        ));

        $numeroFrases = rand(2, 10);

        return implode(' ', array_rand($frases, $numeroFrases));
    }





}