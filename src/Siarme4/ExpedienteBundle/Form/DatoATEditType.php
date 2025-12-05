<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;


class DatoATEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fechaTurno',  DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Junta Médica: '])
               // ->add('numeroDenuncia', NumberType::class)
              /**->add('tipoAccidente', ChoiceType::class, array(
                    'choices' => array( 
                    'ACCIDENTE DE TRABAJO' =>'ACCIDENTE DE TRABAJO',
                    'ACCIDENTE DE TRABAJO IN ITINERE' =>'ACCIDENTE DE TRABAJO IN ITINERE',
                    'ENFERMEDAD PROFESIONAL'=>'ENFERMEDAD PROFESIONAL'),
                    'label'  => 'Tipo de Accidente :', 
                    'data' => 'ACCIDENTE DE TRABAJO'
                ))                
               ->add('tipo', null , array(
                            'label'=>'Comentario', 'attr' => array('class' => 'form-control'), 
                     ))**/ 
               ->add('hora', TimeType::class, array(
                      'input'  => 'datetime',
                      'widget' => 'choice',
                   'label'=>false) );

             /**    ->add('altaMedica', CheckboxType::class, array(
                      'label'    => 'Alta Medica:',
                      'required' => false,
                     ))
                ->add('altaLaboral', CheckboxType::class, array(
                      'label'    => 'Alta Laboral:',
                      'required' => false,
                     )) 

                ->add('zonaAfectada', ChoiceType::class, array(
                        'choices' => array( 
                        'CABEZA'=>'CABEZA',
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
                        'UBICACIONES MULTIPLES'=>'UBICACIONES MULTIPLES'
                        ),
                            'label'  => 'Zona Afectada:', 
                            'data' => 'MIEMBRO INFERIOR'
                        ))
                        ->add('descripcionZona');**/
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
