<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemProcesoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      //  $builder->add('fecha')->add('numero')->add('tipo')->add('codigoEspecial')->add('codigo')->add('item')->add('marca')->add('unidadMedida')->add('cantidad')->add('precioUnitario')->add('pFPrecio')->add('pFCalidad')->add('pFPlazoEntrega')->add('pFAntecedente')->add('cantidadAdjudicada')->add('estado')->add('esTotal')->add('esOferta')->add('texto')->add('sistema')->add('tramite');
      $builder->add('codigo',null, array('label'=>'Codigo Item : ','attr' => array('class' => 'form-control')))
               ->add('item',TextareaType::class, array('label'=>'Descripcion del Item : ', 'required' => false,'attr' => array('class' => 'form-control')))
               // ->add('conDetalle')
                ->add('unidadMedida')
                //->add('precio', MoneyType::class, array('currency' => 'ARS', 'attr' => array('class' => 'form-control','type' => 'number','placeholder' => 'Sin "." Ej: 21035,45')))
                ->add('cantidad');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\AusentismoBundle\Entity\ItemProceso'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_ausentismobundle_itemproceso';
    }


}
