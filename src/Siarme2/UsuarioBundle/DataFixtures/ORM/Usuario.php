<?php

/*
 * (c) Ibañez Raul Adrian <ira.adrian@gmail.com>
 *
 * Este archivo pertenece a la aplicación de prueba SIARMe.
 */

namespace Siarme\UsuarioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Siarme\UsuarioBundle\Entity\Usuario;
use Siarme\AusentismoBundle\Entity\DepartamentoRm;
use Siarme\UsuarioBundle\Entity\Role;

/**
 * Fixtures de las entidades del Usuario Proyecto para
 * la Direccion de Reconocimientos Medicos.
 * Crea 20 usuarios de prueba con información muy realista.
 */
class Usuarios extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function getOrder()
    {
        return 40;
    }

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
         // Crear ROLES para cada Departamento de Rec Medico
        $role = new Role();
        $role->setAreaRm('Area Administrativa');   
        $role->setRol('ROLE_USUARIO');
        $role->setRoleName('Administrativo');           
        $manager->persist($role);

        $role = new Role();
        $role->setAreaRm('Auditoría Médica');
        $role->setRol('ROLE_MEDICO');
        $role->setRoleName('Medico');               
        $manager->persist($role);

        $role = new Role();
        $role->setAreaRm('Area Psiquiatrica');              
        $role->setRol('ROLE_PSIQUIATRA');
        $role->setRoleName('Psiquiatra');
        $manager->persist($role);

        $role = new Role();
        $role->setAreaRm('ADMINISTRADOR');              
        $role->setRol('ROLE_ADMIN');
        $role->setRoleName('admin');
        $manager->persist($role);

        $role = new Role();
        $role->setAreaRm('Area Mesa de Entrada');              
        $role->setRol('ROLE_USUARIO');
        $role->setRoleName('Usuario');
        $manager->persist($role);

        $manager->flush();

        // Obtener todas los departamentos de la Direccion de la base de datos
        $departamentosRm = $manager->getRepository('AusentismoBundle:DepartamentoRm')->findAll();

        $roles=array('Administrativo','Medico','Psiquiatra','Usuario', 'admin');
        $l="a";
        // Crear los 5 usuarios de Prueba de la aplicacion     
       foreach ($departamentosRm as $departamentoRm) {
        // Crear los Roles de Usuario
         
         foreach ($roles as $nombreRol)  {
                    $role = $manager->getRepository('UsuarioBundle:Role')->findOneByRoleName($nombreRol);   
                    $usuario = new Usuario();
                    $usuario->setApellidoNombre($this->getApellidos().' '.$this->getNombre());
                    $usuario->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

                    //$passwordEnClaro = 'usuario'.$i;
                    //$encoder = $this->container->get('security.encoder_factory')->getEncoder($usuario);
                    //$passwordCodificado = $encoder->encodePassword($passwordEnClaro, $usuario->getSalt());
                    //$usuario->setContraseña($passwordCodificado);
                    if (($nombreRol == "admin") and ($l=="a")){
                        $usuario->setUsuario($nombreRol);
                        $usuario->setPassword(1234);
                    }else{
                       $usuario->setUsuario($nombreRol."-".$l);
                       $usuario->setPassword($nombreRol."-".$l); 
                    }                    
                    
                    $usuario->setDepartamentoRm($departamentoRm);
                    $usuario->setDireccion($this->getDireccion());
                    $usuario->setRol($role);

                    $usuario->setFechaCrea(new \DateTime('now - '.rand(51, 150).' days'));   
                    $usuario->setFechaModifica(new \DateTime('now - '.rand(1, 50).' days')); 
                    $usuario->setEsActivo(true);

                    //$usuario->setFechaNacimiento(new \DateTime('now - '.rand(1, 150).' days'));
                    $dni = rand(15000000,32000000);
                    $usuario->setDni($dni);
                    $usuario->setTelefonoMovil($this->getTelefono());
                    $usuario->setTelefono(rand(4400000,4459999));
                    $manager->persist($usuario);
                
                } 
                
                $l="d";
                 $manager->flush();
        }
    }
       

    /**
     * Generador aleatorio de nombres de personas.
     * Aproximadamente genera un 50% de hombres y un 50% de mujeres.
     *
     * @return string Nombre aleatorio generado para el usuario.
     */
    private function getNombre()
    {
        // Los nombres más comunes

        $hombres = array(
            'Antonio', 'José', 'Manuel', 'Francisco', 'Juan', 'David',
            'José Antonio', 'José Luis', 'Jesús', 'Javier', 'Francisco Javier',
            'Carlos', 'Daniel', 'Miguel', 'Rafael', 'Pedro', 'José Manuel',
            'Ángel', 'Alejandro', 'Miguel Ángel', 'José María', 'Fernando',
            'Luis', 'Sergio', 'Pablo', 'Jorge', 'Alberto'
        );
        $mujeres = array(
            'María Carmen', 'María', 'Carmen', 'Josefa', 'Isabel', 'Ana María',
            'María Dolores', 'María Pilar', 'María Teresa', 'Ana', 'Francisca',
            'Laura', 'Antonia', 'Dolores', 'María Angeles', 'Cristina', 'Marta',
            'María José', 'María Isabel', 'Pilar', 'María Luisa', 'Concepción',
            'Lucía', 'Mercedes', 'Manuela', 'Elena', 'Rosa María'
        );

        if (rand() % 2) {
            return $hombres[array_rand($hombres)];
        } else {
            return $mujeres[array_rand($mujeres)];
        }
    }

    /**
     * Generador aleatorio de apellidos de personas.
     *
     * @return string Apellido aleatorio generado para el usuario.
     */
    private function getApellidos()
    {
        // Los apellidos mas comunes

        $apellidos = array(
            'García', 'González', 'Rodríguez', 'Fernández', 'López', 'Martínez',
            'Sánchez', 'Pérez', 'Gómez', 'Martín', 'Jiménez', 'Ruiz',
            'Hernández', 'Díaz', 'Moreno', 'Álvarez', 'Muñoz', 'Romero',
            'Alonso', 'Gutiérrez', 'Navarro', 'Torres', 'Domínguez', 'Vázquez',
            'Ramos', 'Guzman', 'Ramírez', 'Serrano', 'Blanco', 'Suárez', 'Molina',
            'Morales', 'Ortega', 'Delgado', 'Castro', 'Ortíz', 'Rubio', 'Moya',
            'Ibañez', 'Aguero', 'Nuñez', 'Medina', 'Garrido'
        );

        return $apellidos[array_rand($apellidos)].' '.$apellidos[array_rand($apellidos)];
    }

    /**
     * Generador aleatorio de direcciones .
     *
     * @return string         Dirección aleatoria generada para la el Usuario.
     */
    private function getDireccion()
    {
        $prefijos = array('Calle', 'Avenida', 'Plaza');
        $nombres = array(
            'Lorem', 'Ipsum', 'Sitamet', 'Consectetur', 'Adipiscing',
            'Necsapien', 'Tincidunt', 'Facilisis', 'Nulla', 'Scelerisque',
            'Blandit', 'Ligula', 'Eget', 'Hendrerit', 'Malesuada', 'Enimsit'
        );

        return $prefijos[array_rand($prefijos)].' '.$nombres[array_rand($nombres)].', N°'.rand(1, 1000);
    }

    /**
     * Generador aleatorio de Numeros de Telefonos 
     *
     * @return string Código postal aleatorio generado para la el Usuario.
     */
    private function getTelefono()
    {
        return sprintf('%03s%46s', 383, rand(500000,700000));
    }
}   