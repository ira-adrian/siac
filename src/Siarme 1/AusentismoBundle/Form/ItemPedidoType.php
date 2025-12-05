<?php

namespace Siarme\AusentismoBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemPedidoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder->add('numero', null, array('label'=>'Numero (ID) : ','attr' => array('class' => 'form-control')));
            if ($options['data']->getSistema() != "BIONEXO") {
                  $builder->add('codigo', null, array('label'=>'Codigo Item : ','attr' => array('class' => 'form-control')));
               }
                $builder->add('item',TextareaType::class, array('label'=>'Descripcion del Item : ','attr' => array('class' => 'form-control')))
                 ->add('rubro',TextType::class, array('label'=>'Rubro: ', 'required' => false,'attr' => array('class' => 'form-control')))
                 ->add('ipp',TextType::class, array('label'=>'I.P.P.: ', 'required' => false,'attr' => array('class' => 'form-control')))
               // ->add('conDetalle')
                ->add('unidadMedida')
                ->add('precio', MoneyType::class, array('currency' => 'ARS', 'attr' => array('class' => 'form-control','type' => 'number','placeholder' => 'Sin "." Ej: 21035,45')))
                ->add('cantidad', null, [ 'label' => 'Cantidad: ','attr' => array('class' => 'form-control') ]);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Siarme\AusentismoBundle\Entity\ItemPedido'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'siarme_ausentismobundle_itempedido';
    }


}
