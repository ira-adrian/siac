<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Siarme\AusentismoBundle\Entity\Organismo;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CargoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('organismo',null, array('label'=>"Lugar de Trabajo: ", 'attr' => array( 'class' => 'form-control')))
                ->add('funcion', TextType::class, array('label'=>"Función: ", 'attr' => array('placeholder' => 'Función del Agente', 'class' => 'form-control')));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\AusentismoBundle\Entity\Cargo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_ausentismobundle_cargo';
    }


}
