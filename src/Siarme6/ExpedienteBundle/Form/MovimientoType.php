<?php

namespace Siarme\ExpedienteBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Siarme\ExpedienteBundle\Form\ExpedienteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovimientoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
        ->add('departamentoRm', null , array('label'=>'Reparticion: ',  'attr' => array('class' => 'form-control')))
        ->add('texto', TextareaType::class , array('label'=>'Motivo: ',  'attr' => array('class' => 'form-control')));
              
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\Movimiento'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_expedientebundle_movimiento';
    }


}
