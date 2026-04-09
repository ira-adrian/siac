<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class DatoATType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fechaAt',  DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Accidente:']);
              //  ->add('numeroDenuncia', NumberType::class)
              /**->add('tipoAccidente', ChoiceType::class, array(
                    'choices' => array( 
                    'ACCIDENTE DE TRABAJO' =>'ACCIDENTE DE TRABAJO',
                    'ACCIDENTE DE TRABAJO IN ITINERE' =>'ACCIDENTE DE TRABAJO IN ITINERE',
                    'ENFERMEDAD PROFESIONAL'=>'ENFERMEDAD PROFESIONAL'),
                    'label'  => 'Tipo de Accidente :', 
                    'data' => 'ACCIDENTE DE TRABAJO'
                ))   **/              
         /**       ->add('estado', ChoiceType::class, array(
                      'choices' => array( 
                      'Sin Datos'=>'Sin Datos',
                      'Aceptado'=>'Aceptado',
                      'Rechazado'=>'Rechazado'
                    ),
                      'label'  => 'Estado del A.T.:', 
                      'data' => 'Sin Datos'
                     ))

                ->add('altaMedica', CheckboxType::class, array(
                      'label'    => 'Alta Medica:',
                      'required' => false,
                     ))
                ->add('altaLaboral', CheckboxType::class, array(
                      'label'    => 'Alta Laboral:',
                      'required' => false,
                     )) */
             /**   ->add('zonaAfectada', ChoiceType::class, array(
                        'choices' => array( 
                        '00-Ninguno'=>'00-Ninguno',
                        'CABEZA'=>'CABEZA',
                        'CUELLO'=>'CUELLO',
                        'TRONCO'=>'TRONCO',
                        'MIEMBRO SUPERIOR'=>'MIEMBRO SUPERIOR',
                        'MIEMBRO INFERIOR'=>'MIEMBRO INFERIOR',
                        'APARATO CARDIOVASCULAR'=>'APARATO CARDIOVASCULAR',
                        'APARATO RESPIRATORIO'=>'APARATO RESPIRATORIO',
                        'APARATO DIGESTIVO'=>'APARATO DIGESTIVO',
                        'APARATO GENITOURINARIO'=>'APARATO GENITOURINARIO',
                        'SISTEMA HEMATOPOYETICO'=>'SISTEMA HEMATOPOYETICO',
                        'PIEL'=>'PIEL',
                        'APARATO PSIQUICO EN GENERAL'=>'APARATO PSIQUICO EN GENERAL',
                        'UBICACIONES MULTIPLES'=>'UBICACIONES MULTIPLES',
                        'OTROS'=>'OTROS'
                        ),
                            'label'  => 'Zona Afectada:', 
                            'data' => '00-Ninguno'
                        ))*/
                       // ->add('descripcionZona', TextareaType::class, array('label' => 'Decripcion de Zona Afectada: '));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\DatoAT'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_expedientebundle_datoat';
    }


}
