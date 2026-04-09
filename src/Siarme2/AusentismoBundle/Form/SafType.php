<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SafType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cuil',TextType::class,  ['attr' => ['placeholder' => 'Ingresar sin "-" ni "."   ejemplo: 20362585423','class' => 'form-control']])
                ->add('telefonoMovil',TelType::class , ['required' => false,'attr' => ['placeholder' => 'Ejemplo: 3834313233', 'class' => 'form-control']])
                ->add('email', EmailType::class, ['required' => false,'attr' => ['class' => 'form-control']])
                ->add('domicilio',TextType::class , ['required' => false,'attr' => ['class' => 'form-control']]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\AusentismoBundle\Entity\Saf'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_ausentismobundle_saf';
    }


}
