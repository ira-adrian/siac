<?php

namespace Siarme\ExpedienteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Siarme\AusentismoBundle\Form\ProveedorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class Form_tramite_ofertaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ( !empty($options['data']->getProveedor())) {
            $builder->add('oferente',null, array('label' => 'Razon Social: ','attr' => ['placeholder' => 'Razon Social', 'class' => 'form-control']))
                    ->add('cuit',null, array('label' => 'CUIT: ','attr' => ['placeholder' => 'CUIT', 'class' => 'form-control']))
                    ->add('Proveedor', ProveedorType::class, array('label'=>false));
        } else{
             $builder->add('oferente',null, array('label' => 'Razon Social: ','attr' => ['placeholder' => 'Razon Social', 'class' => 'form-control']))
                     ->add('cuit',null, array('label' => 'CUIT: ','attr' => ['placeholder' => 'CUIT', 'class' => 'form-control']));
        }
       /// 
        

    }

    /**
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
