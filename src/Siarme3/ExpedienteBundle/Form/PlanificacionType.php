<?php

namespace Siarme\ExpedienteBundle\Form;

use Siarme\ExpedienteBundle\Entity\Tramite;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanificacionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('rubro',ChoiceType::class, array('expanded' => false,'multiple' => false,
                    'choices' => array( 
                              "AGRIC,GANADERIA,CAZA,SILVICULT"=>"AGRIC,GANADERIA,CAZA,SILVICULT",
                              "ALIMENTOS"=>"ALIMENTOS",
                              "ALQUILER"=>"ALQUILER",
                              "ARTICULOS DEL HOGAR "=>"ARTICULOS DEL HOGAR",
                              "BANCOS Y SEGUROS "=>"BANCOS Y SEGUROS",
                              "BAZAR Y MENAJE"=>"BAZAR Y MENAJE",
                              "CARPINTERIA"=>"CARPINTERIA",
                              "CEREMONIAL "=>"CEREMONIAL",
                              "CERRAJERIA "=>"CERRAJERIA",
                              "CINE/TELVIS./RADIO/FOTOGRAFIA"=>"CINE/TELVIS./RADIO/FOTOGRAFIA",
                              "COMBUSTIBLES Y LUBRICANTES"=>"COMBUSTIBLES Y LUBRICANTES",
                              "CONCESION"=>"CONCESION",
                              "CONSTRUCCION"=>"CONSTRUCCION",
                              "CULTURA "=>"CULTURA",
                              "ELECTRICIDAD Y TELEFONIA"=>"ELECTRICIDAD Y TELEFONIA",
                              "ELEMENTOS DE LIMPIEZA"=>"ELEMENTOS DE LIMPIEZA",
                              "EQUIPO DE OFICINA Y MUEBLES"=>"EQUIPO DE OFICINA Y MUEBLES",
                              "EQUIPO MILITAR Y DE SEGURIDAD "=>"EQUIPO MILITAR Y DE SEGURIDAD ",
                              "EQUIPOS "=>"EQUIPOS",
                              "FERRETERIA "=>"FERRETERIA",
                              "FINANCIERO "=>"FINANCIERO",
                              "GASES INDUSTRIALES"=>"GASES INDUSTRIALES",
                              "HERRAMIENTAS"=>"HERRAMIENTAS",
                              "HERRERIA"=>"HERRERIA",
                              "IMPRENTA Y EDITORIALES "=>"IMPRENTA Y EDITORIALES",
                              "INDUMENT TEXTIL Y CONFECCIONES"=>"INDUMENT TEXTIL Y CONFECCIONES",
                              "INFORMATICA"=>"INFORMATICA",
                              "INMUEBLES"=>"INMUEBLES",
                              "INSUMO P/ ARMAMENTO "=>"INSUMO P/ ARMAMENTO",
                              "JOYERIA Y ORFEBRERIA"=>"JOYERIA Y ORFEBRERIA",
                              "LIBRERIA,PAP. Y UTILES OFICINA"=>"LIBRERIA,PAP. Y UTILES OFICINA",
                              "MANT. REPARACION Y LIMPIEZA"=>"MANT. REPARACION Y LIMPIEZA",
                              "MATERIALES DE CONSTRUCCION"=>"MATERIALES DE CONSTRUCCION",
                              "METALES "=>"METALES",
                              "METALURGIA "=>"METALURGIA",
                              "NAUTICA "=>"NAUTICA",
                              "PINTURAS"=>"PINTURAS",
                              "PROD. MEDICO/FARMACEUTICOS/LAB"=>"PROD. MEDICO/FARMACEUTICOS/LAB",
                              "PRODUCTOS VETERINARIOS "=>"PRODUCTOS VETERINARIOS",
                              "QUIMICOS"=>"QUIMICOS",
                              "REPUESTOS"=>"REPUESTOS",
                              "SANITARIOS, PLOMERIA Y GAS"=>"SANITARIOS, PLOMERIA Y GAS",
                              "SERV. PROFESIONAL Y COMERCIAL"=>"SERV. PROFESIONAL Y COMERCIAL",
                              "SERVICIO DE NOTICIAS"=>"SERVICIO DE NOTICIAS",
                              "SERVICIOS BASICOS"=>"SERVICIOS BASICOS",
                              "TAPICERIA"=>"TAPICERIA",
                              "TRANSPORTE Y DEPOSITO"=>"TRANSPORTE Y DEPOSITO",
                              "UTILES Y PRODUCTOS DEPORTIVOS"=>"UTILES Y PRODUCTOS DEPORTIVOS",
                              "VIDRIERIA"=>"VIDRIERIA",
                              "VIGILANCIA Y SEGURIDAD "=>"VIGILANCIA Y SEGURIDAD",
                    ),
                       'label'  => 'Trimestre', 'attr' => array('required' => 'required', 'class' => 'form-control') ))

                     ->add('descripcion')
                     ->add('importe', MoneyType::class, array('currency' => 'ARS', 'attr' => array('class' => 'form-control','type' => 'number','placeholder' => 'Sin "." Ej: 21035,45')));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\Planificacion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_expedientebundle_planificacion';
    }


}
