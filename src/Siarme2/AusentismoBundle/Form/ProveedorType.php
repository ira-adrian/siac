<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProveedorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
               // ->add('fechaInscribe', DateType::class, ['widget' => 'single_text', 'label'=> 'Fecha de Inscripción','attr' => array('class' => 'form-control')])
                ->add('direccion', TextareaType::class ,array('label' => 'Direccion: ', 'attr' => ['placeholder' => 'Direccion del Oferente', 'class' => 'form-control']))
                ->add('telefono', null, array('label' => 'Telefono: ', 'attr' => ['placeholder' => '3834365892', 'class' => 'form-control']))
                ->add('email',null,  array('label' => 'Email: ', 'attr' => ['placeholder' => 'proveedor@gmail.com', 'class' => 'form-control']));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\AusentismoBundle\Entity\Proveedor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_ausentismobundle_proveedor';
    }


}
