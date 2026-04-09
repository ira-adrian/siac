<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemOfertaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       // $builder->add('fecha')->add('numero')->add('tipo')->add('codigoEspecial')->add('codigo')->add('item')->add('marca')->add('unidadMedida')->add('cantidad')->add('cantidadSolicitada')->add('precio')->add('pFPrecio')->add('pFCalidad')->add('pFPlazoEntrega')->add('pFAntecedente')->add('cantidadAdjudicada')->add('estado')->add('adjudicado')->add('texto')->add('sistema')->add('proceso')->add('oferta');
          if ($options['data']->getSistema() != "BIONEXO") {
                  $builder->add('codigo',null, array('label'=>'Codigo Item : ','attr' => array('class' => 'form-control')));
          }
          if ($options['data']->getSistema() == "BIONEXO") {
                  $builder->add('marca',null, array('label'=>'Marca : ','attr' => array('class' => 'form-control')));
          }
                $builder->add('item',TextareaType::class, array('label'=>'Descripcion del Item : ', 'required' => false,'attr' => array('class' => 'form-control')))
               // ->add('conDetalle')
                ->add('unidadMedida',null, array('label'=>'Unidad de Medida: ','attr' => array('class' => 'form-control')))
                ->add('precio', MoneyType::class, array('currency' => 'ARS', 'attr' => array('class' => 'form-control','type' => 'number','placeholder' => 'Sin "." Ej: 21035,45')))
                ->add('cantidad', null, array('label'=>'Cantidad Ofertada : ','attr' => array('class' => 'form-control')));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\AusentismoBundle\Entity\ItemOferta'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_ausentismobundle_itemoferta';
    }


}
