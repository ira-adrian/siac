<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class Form_proceso_ponderacionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pFPrecio', NumberType::class, array('label' => 'Puntaje Factor Precio (%)','attr' => array('type' => 'number','placeholder' => '83', 'required' => 'required', 'class' => 'form-control')))
                ->add('pFCalidadBueno', NumberType::class, array('label' => 'Puntaje Factor Calidad Bueno (%)','attr' => array('type' => 'number','placeholder' => '83', 'required' => 'required', 'class' => 'form-control')))
                ->add('pFCalidadMuyBueno', NumberType::class, array('label' => 'Puntaje Factor Calidad Muy Bueno (%)','attr' => array('type' => 'number','placeholder' => '83', 'required' => 'required', 'class' => 'form-control')))
                ->add('ofertaAlternativa', CheckboxType::class, array('label' => 'Se Aceptan Ofertas Alternativas', 'required' => false));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\ExpedienteBundle\Entity\Tramite'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_expedientebundle_tramite';
    }


}
