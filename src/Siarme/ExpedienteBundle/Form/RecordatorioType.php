<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class RecordatorioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('recordatorio',ChoiceType::class, 
            array(
                    'choices' => array(
                        'Llamado'=>'llamado', 
                        'Notificación OC'=>'notificacion_oc',
                        'Muestras'=>'muestras',
                        'Vencimiento OC'=>'vencimiento_oc'),

                        'label'  => 'Agendar')
            )
        ->add('fecha', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha'])
        ->add('hora', TimeType::class, array(
                      'input'  => 'datetime',
                      'widget' => 'choice',
                   'label'=>"Hora y minutos"))
      //  ->add('publico')
        ->add('texto', TextareaType::class, array('label'=>'Observaciones : ', 'required' => false,'attr' => array('class' => 'form-control','autocomplete' => 'off')));
        //->add('tarea')->add('tramite')->add('usuario')
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\Recordatorio'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_expedientebundle_recordatorio';
    }


}
