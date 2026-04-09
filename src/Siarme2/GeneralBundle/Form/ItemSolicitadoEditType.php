<?php

namespace Siarme\GeneralBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemSolicitadoEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numero',NumberType::class, array('label'=>'Numero de Item (ID) : ','attr' => array('class' => 'form-control')))
                ->add('codigo', null, array('label'=>'Código:','attr' => array( 'placeholder' => "2.4.4-6818.687", 'class' => 'form-control')))
                ->add('ipp')
                ->add('item',TextareaType::class, array('label'=>'Descripcion del Item : ','attr' => array('class' => 'form-control')))
                ->add('unidadMedida')
                ->add('precio')
                ->add('cantidad',NumberType::class, array('label'=>'Cantidad Pedida: ','attr' => array('class' => 'form-control')));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\GeneralBundle\Entity\ItemSolicitado'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_generalbundle_itemsolicitado';
    }


}
