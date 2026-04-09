<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                ->add('fechaInicio', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Inicio', 'attr' => ['class' => 'form-control']])
                ->add('fechaFin', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Fin', 'attr' => ['class' => 'form-control']])
                ->add('tipo', ChoiceType::class, array(
                    'choices' => array( "Inamovible"=>'inamovible',"Puente"=>'puente', "Trasladable"=>'trasladable'),
                       'label'  => 'Tipo'
                ))
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
