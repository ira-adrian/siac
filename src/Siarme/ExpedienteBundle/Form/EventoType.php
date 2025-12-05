<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('titulo', TextType::class, [
                    'label' => 'Título del Evento',
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('fechaInicio', DateTimeType::class, [
                    'label' => 'Fecha y Hora de Inicio',
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('fechaFin', DateTimeType::class, [
                    'label' => 'Fecha y Hora de Fin (Opcional)',
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'required' => false,
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('tipo', TextareaType::class, [
                    'label' => 'Descripción (Opcional)',
                    'required' => false,
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('guardar', SubmitType::class, [
                    'label' => 'Guardar Evento',
                    'attr' => ['class' => 'btn btn-primary'],
                ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\Evento'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_expedientebundle_evento';
    }


}
